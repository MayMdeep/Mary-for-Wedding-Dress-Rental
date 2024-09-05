<?php

namespace App\Actions\Auth\Admin;

use App\Implementations\AdminImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class ChangeAdminPasswordAction
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

        if (!Hash::check($data['current_password'], auth('sanctum')->admin()->password)) {
            return [false,'password_incorrect'];
        }
        else {
            
        return [true,$this->admin->update(['password' => $data['password']], auth('sanctum')->admin()->id)];
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
