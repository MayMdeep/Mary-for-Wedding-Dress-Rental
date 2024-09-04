<?php
namespace App\Actions\Balances;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\BalanceTypeImplementation;
use App\Http\Resources\BalanceTypeResource;
use Hash;
class GetBalanceTypesListAction
{
    use AsAction;
    use Response;
    private $balanceType;
    
    function __construct(BalanceTypeImplementation $BalanceTypeImplementation)
    {
        $this->balanceType = $BalanceTypeImplementation;
    }

    public function handle(array $data = [], int $perPage = 10)
    {
        if (!is_numeric($perPage))
            $perPage = 10;
        
        return BalanceTypeResource::collection($this->balanceType->getPaginatedList($data, $perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.get'))
            return $this->sendError('Forbidden',[],403);
        $list = $this->handle($request->all(),  $request->input('results', 10));
        
        return $this->sendPaginatedResponse($list,'');
    }
}