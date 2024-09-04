<?php
namespace App\Actions\Balances;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\BalanceImplementation;
use App\Http\Resources\BalanceResource;
use Hash;
class GetBalanceAction
{
    use AsAction;
    use Response;
    private $balance;
    
    function __construct(BalanceImplementation $BalanceImplementation)
    {

        $this->balance = $BalanceImplementation;
    }

    public function handle(int $id)
    {
        return new BalanceResource($this->balance->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.get'))
            return $this->sendError('Forbidden',[],403);
    
        $record = $this->handle($id);
        return $this->sendResponse($record,'');
    }
}