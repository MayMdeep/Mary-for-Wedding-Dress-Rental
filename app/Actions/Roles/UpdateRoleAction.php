<?php
namespace App\Actions\Roles;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Role;
use App\Implementations\RoleImplementation;
use App\Http\Resources\RoleResource;
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class UpdateRoleAction
{
    use AsAction;
    use Response;
    private $role;
    
    function __construct(RoleImplementation $roleImplementation)
    {
        $this->role = $roleImplementation;
    }

    public function handle(array $data, int $id)
    {
        $role = $this->role->Update($data, $id);
        return new RoleResource($role);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:roles,name,'.$request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('role.edit'))
            return $this->sendError('Forbidden',[],403);

        $role = $this->handle($request->all(), $id);

        return $this->sendResponse($role,'Updated Successfly');
    }
}