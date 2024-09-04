<?php
namespace App\Actions\Users;
use App\Implementations\BlockedUserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\UserImplementation;
use App\Http\Resources\UserResource;
use Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetUserAction
{
    use AsAction;
    use Response;
    private $user;
    private $blockedUser;

    function __construct(UserImplementation $UserImplementation, BlockedUserImplementation $blockedUserImplementation)
    {
        $this->user = $UserImplementation;
        $this->blockedUser = $blockedUserImplementation;
    }

    public function handle(int $id)
    {
        if (auth('sanctum')->check()) {
            $blockedExists = $this->blockedUser->getList(['blocked_exists' => [auth('sanctum')->user()->id, $id]])->first();
            if (!is_null($blockedExists)) return throw new NotFoundHttpException('not_found');
        }

        return new UserResource($this->user->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('user.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}
