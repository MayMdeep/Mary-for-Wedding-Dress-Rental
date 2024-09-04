<?php

namespace App\Actions\Auth;

use App\Implementations\UserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

use App\Actions\UserPasswords\StoreUserPasswordAction;

class ChangeUserPasswordAction
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

        if (!Hash::check($data['current_password'], auth('sanctum')->user()->password)) {
            return [false,'password_incorrect'];
        }
       else if(CheckIfPasswordRepeatedAction::run($data['password'])){
            return[false,'old_password'];
        }
        else {
            StoreUserPasswordAction::run(['user_id'=>auth('sanctum')->user()->id,'password'=> $data['password']]);
            
        return [true,$this->user->update(['password' => $data['password']], auth('sanctum')->user()->id)];
        }
    }
    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function asController(Request $request)
    {

        $result = $this->handle($request->all());

        if($result[0])
            return $this->sendResponse($result[1],'Password changed successfully');
        else
            return $this->sendError($result[1],'');
    }
}
