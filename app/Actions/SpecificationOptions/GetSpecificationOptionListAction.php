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
class GetSpecificationOptionListAction
{
    use AsAction;
    use Response;
    private $specificationOption;
    
    function __construct(SpecificationOptionImplementation $specificationOptionImplementation)
    {
        $this->specificationOption = $specificationOptionImplementation;
    }

    public function handle(array $data = [], int $perPage = 10)
    {
        if (!is_numeric($perPage))
            $perPage = 10;
        
        return SpecificationOptionResource::collection($this->specificationOption->getPaginatedList($data, $perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
       if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specificationOption.get'))
           return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));
        
        return $this->sendPaginatedResponse($list,'');
    }
}