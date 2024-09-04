<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\BalanceImplementation;
use Hash;
class DeleteBalanceAction
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
        return $this->balance->delete($id);
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(int $id)
    {
        try{
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.delete'))
            return $this->sendError('Forbidden',[],403);
            $this->handle($id);
            return $this->sendResponse(['Success'], 'Balance Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}