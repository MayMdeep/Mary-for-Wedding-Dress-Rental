<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Traits\Response;
use Hash;
class BulkDeleteBalanceAction
{
    use AsAction;
    use Response;
    private $balance;
    
    function __construct(Balance $Balance)
    {
        $this->balance = $Balance;
    }

    public function handle(array $data)
    {
        return $this->balance->destroy($data['ids']);
    }
    public function rules()
    {
        return [
            //'ids' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        try{
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.delete'))
            return $this->sendError('Forbidden',[],403);
    
            $this->handle($request->all());
            return $this->sendResponse(['Success'], 'Balance Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}