<?php
namespace App\Actions\Auth\Admin;

use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Http\Resources\AdminResource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Implementations\AdminImplementation;

class AdminRegisterAction
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
        $data['verified'] = 0;
        if (array_key_exists('file', $data)) {
            $data['photo'] = UploadImageAction::run(['photo'=>$data['file'],'folder'=> 'Admins']);
        }
        $admin = $this->admin->Create($data);
        return new AdminResource($admin);
    }
    public function rules()
    {
        return [
            'username' => ['required', 'unique:admins,username'],
            'email' => ['required', 'unique:admins,email'],
            'password' => ['required', 'confirmed','min:8'],
            'active' => ['required'],
            'role_id' => ['required'],

        ];
    }
    // public function withValidator(Validator $validator, ActionRequest $request)
    // {
    //     $validator->after(function (Validator $validator) use ($request) {
           
    //     });
    // }

    public function asController(Request $request)
    {
    // dd(1);
        $admin = $this->handle($request->all());

        return $this->sendResponse($admin, '');
    }
}