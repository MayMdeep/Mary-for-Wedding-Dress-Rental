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
class GetUserBalanceAction
{
    use AsAction;
    use Response;
    private $user;

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle()
    {
        return [ 'balance' => auth('sanctum')->user()->balance() ];
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController()
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('user.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle();

        return $this->sendResponse($record,'');
    }
}
