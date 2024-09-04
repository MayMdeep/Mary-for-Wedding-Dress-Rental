<?php
namespace App\Actions\Admins;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\AdminImplementation;
use App\Http\Resources\AdminResource;
use Hash;
class GetAdminListAction
{
    use AsAction;
    use Response;
    private $admin;

    function __construct(AdminImplementation $AdminImplementation)
    {
        $this->admin = $AdminImplementation;
    }

    public function handle(array $data, int $perPage)
    {
        if (!is_numeric($perPage))
            $perPage = 10;
        return AdminResource::collection($this->admin->getPaginatedList($data, $perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('admin.get'))
            return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));

        return $this->sendPaginatedResponse($list,'');
    }
}
