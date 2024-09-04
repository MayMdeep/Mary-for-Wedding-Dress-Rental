<?php
namespace App\Actions\Users;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\UserImplementation;
use App\Http\Resources\UserResource;
use Hash;
class GetUserListAction
{
    use AsAction;
    use Response;
    private $user;

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data, int $perPage)
    {
        if (!is_numeric($perPage))
            $perPage = 10;

        // Get all users that not blocked
        if (auth('sanctum')->check()) {
            $data['without_blocked_users'] = auth('sanctum')->id();
        }
        return UserResource::collection($this->user->getPaginatedList($data, $perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('user.get-all'))
            return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));

        return $this->sendPaginatedResponse($list,'');
    }
}
