<?php
namespace App\Actions\Permissions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Permission;
use App\Implementations\PermissionImplementation;
use App\Implementations\RoleImplementation;
use App\Http\Resources\PermissionResource;

use Hash;
class AssignOnePermissionToRoleAction
{
    use AsAction;
    use Response;
    private $permission;
    private $role;
    function __construct(PermissionImplementation $permissionImplementation , RoleImplementation $roleImplementation )
    {
        $this->permission = $permissionImplementation;
        $this->role=$roleImplementation;
    }

    public function handle(array $data)
    {
       // $this->role->getOne($data['id'])->permissions()->detach();

        $this->role->getOne($data['id'])->permissions()->attach($data['ids']);

        return [true,'Pemissions added successfully'];
        
    }
    public function rules()
    {
        return [
            'ids' => ['required','exists:permissions,id'],
           // 'id'=>['required','exists:roles,id']
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('permission.edit'))
            return $this->sendError('Forbidden',[],403);

        $result = $this->handle($request->all());

        return $this->sendResponse($result[0],$result[1]);
    }
}