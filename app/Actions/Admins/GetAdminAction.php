<?php
namespace App\Actions\Admins;
use App\Implementations\BlockedAdminImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\AdminImplementation;
use App\Http\Resources\AdminResource;
use Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAdminAction
{
    use AsAction;
    use Response;
    private $admin;

    function __construct(AdminImplementation $AdminImplementation)
    {
        $this->admin = $AdminImplementation;
    }

    public function handle(int $id)
    {
        return new AdminResource($this->admin->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('admin.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}
