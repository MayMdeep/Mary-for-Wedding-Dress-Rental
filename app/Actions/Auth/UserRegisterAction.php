<?php
namespace App\Actions\Auth;

use App\Actions\Uploads\UploadImageAction;
use App\Actions\UserPasswords\StoreUserPasswordAction;
use App\Helpers\ImageDimensions;
use App\Implementations\UserImplementation;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserRegisterAction
{
    use AsAction;
    use Response;
    private $user;
    private $photo = 'https://shackshack.s3.ca-central-1.amazonaws.com/vendors/User-Avatar-Profile-Transparent-Isolated-Background+(1).png';
    private $covers = [
        'https://shackshack.s3.ca-central-1.amazonaws.com/vendors/pexels-george-dolgikh-1303081.jpg',
        'https://shackshack.s3.ca-central-1.amazonaws.com/vendors/getty_624893634_2000133320009280325_333356.jpg',
        'https://shackshack.s3.ca-central-1.amazonaws.com/vendors/PixoLabo-Optimizing-E-Commerce-Site-Holidays-2023.jpg',
    ];

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data)
    {
        $isFromSocial = isset($data['register_method']) && $data['register_method'] > 0 ? true : false;

        
       $data['user_id']=4;
        if (array_key_exists('file', $data)) {
            $data['photo'] = UploadImageAction::run($data['file'], ImageDimensions::USER_PHOTO);
        } else {
            $data['photo'] = $this->photo;
        }

        if (array_key_exists('coverFile', $data)) {
            $data['cover'] = UploadImageAction::run($data['coverFile'], ImageDimensions::USER_COVER);
        } else {
            $data['cover'] = $this->covers[rand(0, 2)];
        }

        if (!array_key_exists('badge_id', $data)) {
            $data['badge_id'] = 1;
        }
        if (array_key_exists('first_name', $data) && array_key_exists('second_name', $data)) {
            $data['name'] = $data['first_name'].' ' . $data['second_name'];
        }

        if (array_key_exists('verified', $data)) {
            unset($data['verified']);
        }
        $data['verified'] = 0;
        // if (array_key_exists('role_id', $data)) {
        //     unset($data['role_id']);
        // }
        if (!$isFromSocial) {
            //check for stale users that was added with phone on a previous invitation
            $users = $this->user->getList(['is_stale' => $data['phone']]);
            if (strpos($data['phone'], '0') === 0) {
                $data['phone'] = ltrim($data['phone'], '0');
            }

            if ($users->isEmpty()) {
                $user = $this->user->Create($data);
                StoreUserPasswordAction::run(['user_id' => $user->id, 'password' => $data['password']]);
                return $user;
            } else {
                return $this->user->Update($data, $users->first()->id);
            }
        } else {
            $user = $this->user->Create($data);
            StoreUserPasswordAction::run(['user_id' => $user->id, 'password' => $data['password']]);
            return $user;
        }

    }
    public function rules()
    {
        return [
            'username' => ['required', 'unique:users,username'],
            'first_name' => ['required'],
            'second_name' => ['required'],
            'phone' => ['required','numeric'],
            'phone_code'=>['required'],
            'badge_id' => ['exists:badges,id'],
            'file' => ['file'],
            'coverFile' => ['file'],
            'password' => ['required','min:8'],
            'role_id' => ['required'],
            'active' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            if ($request->input('phone')) {

                $phone = $request->input('phone');
                if (strpos($phone, '0') === 0) {
                    $phone = ltrim($phone, '0');
                }

                $request->merge(['phone' => $phone]);

                $users = $this->user->getList(['not_stale' => $request->input('phone'),'phone_code' =>$request->input('phone_code')]);

                if ($users->isNotEmpty()) {
                    $validator->errors()->add('phone', 'Phone already Used');
                }

            }
        });
    }

    public function asController(Request $request)
    {
        $user = $this->handle($request->all());

        return $this->sendResponse($user, '');
    }
}
