<?php
namespace App\Actions\Auth\Admin;

use App\Http\Resources\UserResource;
use App\Implementations\AdminImplementation;
use App\Traits\Response;
use Carbon\Carbon;
use App\Enum\ErrorCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Enums\ApiResponse;
use Hash;

class AdminLoginAction
{
    use AsAction;
    use Response;
    private $admin;

    function __construct(AdminImplementation $AdminImplementation)
    {
        $this->admin = $AdminImplementation;
    }

    public function handle(array $data)
    {
        
        $admin = $this->admin->getList(['username' => $data['username']])->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return [false, ApiResponse::WRONG_CREDENTIALS];
        }

        $result=$admin;
        $result['token'] = $admin->createToken('laimonah')->plainTextToken;
        $admin['permissions'] = $admin->permissions();
     //   unset($admin->role);
        unset($admin->password);
        return [true, $admin];
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
            $admin = $this->admin->getList(['username' => $request->get('username')])->first();
            if ($admin && $admin->active != 1)
                $validator->errors()->add('active', ApiResponse::NOT_ACTIVE);
        });
    }

    public function asController(Request $request)
    {
        $result = $this->handle($request->all());
        if ($result[0])
            return $this->sendResponse($result[1], ApiResponse::AUTHINTICATED);
        else
            return $this->sendError($result[1], '');
    }
}
