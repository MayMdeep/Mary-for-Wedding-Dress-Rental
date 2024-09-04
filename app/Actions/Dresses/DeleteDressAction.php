<?php
namespace App\Actions\Dresses;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\DressImplementation;
use Hash;
class DeleteDressAction
{
    use AsAction;
    use Response;
    private $dress;
    
    function __construct(DressImplementation $DressImplementation)
    {
        $this->dress = $DressImplementation;
    }

    public function handle(int $id)
    {
        return $this->dress->delete($id);
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
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('dress.delete'))
            return $this->sendError('Forbidden',[],403);

            $this->handle($id);
            return $this->sendResponse(['Success'], 'Dress Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}