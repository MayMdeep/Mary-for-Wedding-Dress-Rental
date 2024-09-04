<?php
namespace App\Actions\Auth;

use App\Implementations\UserImplementation;
use App\Traits\Response;
use Carbon\Carbon;
use App\Enum\ErrorCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserLoginAction
{
    use AsAction;
    use Response;
    private $user;

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data)
    {
       // dd('handle');
        $isFromSocial = isset($data['register_method']) && $data['register_method'] > 0 ? true : false;
        if ($isFromSocial) {
            if (Auth::attempt(['firebase_uid' => $data['firebase_uid'], 'password' => $data['password']])) {
                $user = Auth::user();
                $user = $this->user->update(['login_date' => Carbon::now()], $user->id);
                $success = $user;

                $success['token'] = $user->createToken('Shake')->plainTextToken;
                return $success;
            }
        } else 
        {
     //   dd( Auth::attempt(['phone' => $data['username'], 'password' => $data['password']]));

            if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {


                $user = Auth::user();
                $user = $this->user->update(['login_date' => Carbon::now()], $user->id);
                if ($user->verified == 0) {
                    return [ false, 'Your account has not been verified, please wait for management approval'];
                }
                $success = $user;
                $success['token'] = $user->createToken('Shake')->plainTextToken;
                return [ true,$success];

            } elseif (Auth::attempt(['phone' => $data['username'], 'password' => $data['password']])) {

                $user = Auth::user();
                $user = $this->user->update(['login_date' => Carbon::now()], $user->id);
                if ($user->verified == 0) {
                    return [ false, 'Your account has not been verified, please wait for management approval'];
                }
                $success = $user;
                $success['token'] = $user->createToken('Shake')->plainTextToken;
                return [true, $success];

            }
        }

        return [false,'Wrong Password Or UserName/Phone' ];
    }
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            $users = $this->user->getList(['username' => $request->get('username')]);
            if ($users->isEmpty()) {
                $phone = $request->input('username');
                if (strpos($phone, '0') === 0) {
                    $phone = ltrim($phone, '0');
                }

                $request->merge(['username' => $phone]);

                $users = $this->user->getList(['phone' => $request->get('username')]);
            }
            if ($users->isNotEmpty()) {
                $user = $users->first();
                if ($user->active != 1) {
                    $validator->errors()->add('active', 'User is not active');
                }

                if ($user->role->website != 1) {
                    $validator->errors()->add('website', 'Unauthorised');
                }

            }

        });
    }

    public function asController(Request $request)
    {
        $result = $this->handle($request->all());
        if ($result[0])
            return $this->sendResponse($result[1], 'User login successfully.');
        else
            return $this->sendError($result[1], '');
    }
}
