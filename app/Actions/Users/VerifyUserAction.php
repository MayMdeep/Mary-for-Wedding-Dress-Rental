<?php
namespace App\Actions\Users;

use App\Helpers\ImageDimensions;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\Uploads\UploadImageAction;
use App\Implementations\UserImplementation;
use App\Http\Resources\UserResource;
use App\Actions\Auth\CheckIfPasswordRepeatedAction;
use App\Actions\UserPasswords\StoreUserPasswordAction;

class VerifyUserAction
{
    use AsAction;
    use Response;
    private $user;
   
    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(int $id)
    {
        return new UserResource($this->user->update(['verified'=> 1], $id));
    }
    public function rules(Request $request)
    {
        return [
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {}

    public function asController(int $id)
    {
        if (auth('sanctum')->check() && ( !auth('sanctum')->user()->has_permission('user.edit') && ( auth('sanctum')->user()->id != $id ) ) ) {
            return $this->sendError('Forbidden', [], 403);
        }

        $user = $this->handle($id);

        return $this->sendResponse($user, '');
    }
}
