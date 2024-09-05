<?php
namespace App\Actions\Roles;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\RoleImplementation;
use App\Http\Resources\RoleResource;
use Hash;
class GetRoleAction
{
    use AsAction;
    use Response;
    private $role;
    
    function __construct(RoleImplementation $RoleImplementation)
    {
        $this->role = $RoleImplementation;
    }

    public function handle(int $id)
    {
        return new RoleResource($this->role->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('role.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}