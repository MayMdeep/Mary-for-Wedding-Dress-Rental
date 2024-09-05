<?php
namespace App\Actions\Auth\User;

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
        {

            if (Auth::attempt(['name' => $data['name'], 'password' => $data['password']])) {
                $user = Auth::user();
                $user = $this->user->update(['login_date' => Carbon::now()], $user->id);
                $success = $user;
                $success['token'] = $user->createToken('Mary')->plainTextToken;
                return [ true,$success];

            } 
        }

        return [false,'Wrong Password Or UserName/Phone' ];
    }
    public function rules()
    {
        return [
            'name' => ['required'],
            'password' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
     
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
