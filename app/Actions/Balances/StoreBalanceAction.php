<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Balance;
use App\Implementations\BalanceImplementation;
use App\Implementations\UserImplementation;
use App\Http\Resources\BalanceResource;

use Hash;
class StoreBalanceAction
{
    use AsAction;
    use Response;
    private $balance;
    private $user;
    
    function __construct(BalanceImplementation $BalanceImplementation,UserImplementation $userImplementation)
    {
        $this->balance = $BalanceImplementation;
        $this->user=$userImplementation;
    }

    public function handle(array $data)
    {
        $amount=$data['amount'];
        $user=$this->user->getOne($data['user_id']);
        $move_type=$data['move_type'];
        if($move_type==1){
            $data['balance']=(float)($user->balance())+$amount;
        }
        else if($move_type==2){
            $data['balance']=(float)($user->balance())-$amount;
        }
        $balance = $this->balance->Create($data);
        return new BalanceResource($balance);
    }
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'to_user_id' => ['exists:users,id'],
            'move_type' => ['required'],
            'balance_type' => ['required', 'exists:balance_types,id'],
            'active' => ['required'],
            'amount' => ['required', 'numeric'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.add'))
            return $this->sendError('Forbidden',[],403);

        $balance = $this->handle($request->all());

        return $this->sendResponse($balance,'');
    }
}