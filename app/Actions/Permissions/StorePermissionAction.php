<?php
namespace App\Actions\Permissions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Permission;
use App\Implementations\PermissionImplementation;
use App\Http\Resources\PermissionResource;

class StorePermissionAction
{
    use AsAction;
    use Response;
    private $permission;

    function __construct(PermissionImplementation $permissionImplementation)
    {
        $this->permission = $permissionImplementation;
    }

    public function handle(array $data)
    {

        $permission = $this->permission->Create($data);

        return new PermissionResource($permission);
    }
    public function rules()
    {
        return [
           'name'=>['required', 'unique:permissions,name'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('permission.add'))
            return $this->sendError('Forbidden',[],403);

        $permission = $this->handle($request->all());

        return $this->sendResponse($permission,'');
    }
}
