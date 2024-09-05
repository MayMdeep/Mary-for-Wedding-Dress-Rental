<?php
namespace App\Actions\Permissions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Permission;
use App\Models\Role;
use App\Implementations\PermissionImplementation;
use App\Implementations\RoleImplementation ;
use App\Http\Resources\PermissionResource;
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class GetPermissionsByRoleAction
{
    use AsAction;
    use Response;
    private $permission;
    private $role;
    
    function __construct(PermissionImplementation $permissionImplementation,RoleImplementation $roleImplementation)
    {
        $this->permission = $permissionImplementation;
        $this->role=$roleImplementation;
    }

    public function handle(array $data, int $id)
    {
        $permissions = $this->permission->getList($data);

        return PermissionResource::collection($permissions);
    }

    public function rules(Request $request)
    {
        return [
            'name' => ['unique:blogs,name,'.$request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('permission.get'))
            return $this->sendError('Forbidden',[],403);

        $permission = $this->handle($request->all(), $id);

        return $this->sendResponse($permission,'');
    }
}