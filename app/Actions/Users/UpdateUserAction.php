<?php
namespace App\Actions\Users;

use App\Helpers\ImageDimensions;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Implementations\UserImplementation;
use App\Http\Resources\UserResource;
use App\Actions\Auth\CheckIfPasswordRepeatedAction;
use App\Actions\UserPasswords\StoreUserPasswordAction;

class UpdateUserAction
{
    use AsAction;
    use Response;
    private $user;
    
    private $photos = [
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-5512105-1712569942.png',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-449293-1712569974.png',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-4774743-1712570002.png'
        ];

    private $covers = [
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-7323036-1712569007.webp',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-1098213-1712569045.webp',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-2944588-1712569079.webp',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-4727227-1712569126.webp',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-1821166-1712569666.webp',
        'https://shackshack.s3.ca-central-1.amazonaws.com/images/img-6822681-1712569898.webp'
    ];

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data, int $id)
    {
        
      //dd($this->user->getOne(auth('sanctum')->user()->id)->phone);
        if (array_key_exists('password', $data)) {
            if (CheckIfPasswordRepeatedAction::run($data['password'])) {
                return [false, 'You Can\'t use an old password'];
            }
            StoreUserPasswordAction::run(['user_id' => $id, 'password' => $data['password']]);
        }

        if (array_key_exists('photo', $data) && $data['photo'] == '') {
            $data['photo'] = $this->photos[rand(0, 2)];
        }

        if (array_key_exists('file', $data)) {
            $data['photo'] = UploadImageAction::run($data['file'], ImageDimensions::USER_PHOTO);
        }

        if (array_key_exists('cover', $data) && $data['cover'] == '') {
            $data['cover'] = $this->covers[rand(0, 5)];
        }

        if (array_key_exists('coverFile', $data)) {
            $data['cover'] = UploadImageAction::run($data['coverFile'], ImageDimensions::USER_COVER);
        }
        if (array_key_exists('phone', $data)&& ($this->user->getOne(auth('sanctum')->user()->id)->phone !== "") && !is_null($this->user->getOne(auth('sanctum')->user()->id)->phone) ){
            unset($data['phone']);
        }

        if (array_key_exists('username', $data)) {
            unset($data['username']);
        }

        return new UserResource($this->user->update($data, $id));
    }
    public function rules(Request $request)
    {
        return [
            'username' => ['unique:users,username,' . $request->route('id')],
            'file' => ['file'],
            'phone' => ['unique:users,phone,' . $request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {}

    public function asController(Request $request, int $id)
    {
        if (auth('sanctum')->check() && ( !auth('sanctum')->user()->has_permission('user.edit') && ( auth('sanctum')->user()->id != $id ) ) ) {
            return $this->sendError('Forbidden', [], 403);
        }

        $user = $this->handle($request->all(), $id);

        return $this->sendResponse($user, '');
    }
}
