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
class GetBalanceListAction
{
    use AsAction;
    use Response;
    private $balance;
    
    function __construct(BalanceImplementation $BalanceImplementation)
    {
        $this->balance = $BalanceImplementation;
    }

    public function handle(array $data = [], int $perBalance = 10)
    {
        if (!is_numeric($perBalance))
            $perBalance = 10;
        
        return BalanceResource::collection($this->balance->getPaginatedList($data, $perBalance));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.get-all'))
            return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));

        return $this->sendPaginatedResponse($list,'');
    }
}