<?php
namespace App\Actions\SpecificationOptions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\SpecificationOptionImplementation;
use Hash;
class DeleteSpecificationOptionAction
{
    use AsAction;
    use Response;
    private $specificationOption;
    
    function __construct(SpecificationOptionImplementation $specificationOptionImplementation)
    {
        $this->specificationOption = $specificationOptionImplementation;
    }

    public function handle(int $id)
    {
        return $this->specificationOption->delete($id);
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
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specificationOption.delete'))
            return $this->sendError('Forbidden',[],403);
            $this->handle($id);
            return $this->sendResponse(['Success'], 'specificationOption Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}

