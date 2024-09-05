<?php
namespace App\Actions\Auth\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Implementations\UserImplementation;
use App\Actions\UserPasswords\StoreUserPasswordAction;

class SetForgettenPasswordAction
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
        if(CheckIfPasswordRepeatedAction::run($data['password'], $data['user_id'])){
            return[false,'old_password'];
        }
        StoreUserPasswordAction::run(['user_id'=>$data['user_id'],'password'=> $data['password']]);

        return [true,$this->user->update(['password'=>$data['password']],$data['user_id'])];
    }
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'password' => ['required', 'confirmed','min:8']
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {}

        public function asController(Request $request)
    {
        $result = $this->handle($request->all());

        if($result[0])
            return $this->sendResponse($result[1],'');
        else
            return $this->sendError($result[1],'');
    }
}