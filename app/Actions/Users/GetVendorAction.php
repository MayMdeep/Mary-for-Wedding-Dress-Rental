<?php
namespace App\Actions\Users;
use App\Implementations\BlockedUserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use App\Implementations\ProductImplementation;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\UserImplementation;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductListResource;
use Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetVendorAction
{
    use AsAction;
    use Response;
    private $user;
    private $product;
    private $blockedUser;

    function __construct(UserImplementation $UserImplementation, ProductImplementation $ProductImplementation, BlockedUserImplementation $blockedUserImplementation)
    {
        $this->product = $ProductImplementation;
        $this->user = $UserImplementation;
        $this->blockedUser = $blockedUserImplementation;
    }

    public function handle(int $id, array $data = [], int $perPage = 10)
    {
        if (!is_numeric($perPage))
        {
            $perPage = 10;
        }
        if (auth('sanctum')->check()) {
            $blockedExists = $this->blockedUser->getList(['blocked_exists' => [auth('sanctum')->user()->id, $id]]);
            if ($blockedExists->isNotEmpty()) return throw new NotFoundHttpException('not_found');
        }

        $data['user_id'] = $id;
        $products = ProductListResource::collection($this->product->getPaginatedList($data, $perPage));
        $user = new UserResource($this->user->getOne($id));

        return [$products, $user];
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request, int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('user.get'))
            return $this->sendError('Forbidden',[],403);

        $response = $this->handle($id,$request->all(),  $request->input('results', 10));

        return $this->sendPaginatedResponse($response[0],'',[],$response[1]);
    }
}
