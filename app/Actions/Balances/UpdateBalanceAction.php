<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Balance;
use App\Implementations\BalanceImplementation;
use App\Http\Resources\BalanceResource;
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class UpdateBalanceAction
{
    use AsAction;
    use Response;
    private $balance;
    
    function __construct(BalanceImplementation $BalanceImplementation)
    {
        $this->balance = $BalanceImplementation;
    }

    public function handle(array $data, int $id)
    {

        $balance = $this->balance->Update($data, $id);
        return new BalanceResource($balance);
    }
    public function rules(Request $request)
    {
        return [
            'user_id' => ['exists:users,id'],
            'to_user_id' => ['exists:users,id'],
            'balance_type' => ['exists:balance_types,id'],
            'amount' => ['numeric'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.delete'))
            return $this->sendError('Forbidden',[],403);
        $balance = $this->handle($request->all(), $id);

        return $this->sendResponse($balance,'');
    }
}