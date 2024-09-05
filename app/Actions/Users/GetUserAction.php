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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetUserAction
{
    use AsAction;
    use Response;
    private $user;
    private $blockedUser;

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(int $id)
    {
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
