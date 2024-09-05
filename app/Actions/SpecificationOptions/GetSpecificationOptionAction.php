<?php
namespace App\Actions\SpecificationOptions;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\SpecificationOptionImplementation;
use App\Http\Resources\SpecificationOptionResource;
use Hash;
class GetSpecificationOptionAction
{
    use AsAction;
    use Response;
    private $specificationOption;
    
    function __construct(SpecificationOptionImplementation $SpecificationOptionImplementation)
    {
        $this->specificationOption = $SpecificationOptionImplementation;
    }

    public function handle(int $id)
    {
        return new SpecificationOptionResource($this->specificationOption->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specificationOption.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}