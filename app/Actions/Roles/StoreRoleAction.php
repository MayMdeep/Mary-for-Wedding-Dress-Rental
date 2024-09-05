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

use Hash;
class StoreRoleAction
{
    use AsAction;
    use Response;
    private $role;
    
    function __construct(RoleImplementation $roleImplementation)
    {
        $this->role = $roleImplementation;
    }

    public function handle(array $data)
    {

        $role = $this->role->Create($data);

        return new roleResource($role);
    }
    public function rules()
    {
        return [
            'name' => ['required','unique:roles,name'],
            'website' => ['required','numeric']
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('role.add'))
            return $this->sendError('Forbidden',[],403);

        $role = $this->handle($request->all());

        return $this->sendResponse($role,' Role Added Successfully');
    }
}