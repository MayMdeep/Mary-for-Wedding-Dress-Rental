<?php
namespace App\Actions\SpecificationOptions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\SpecificationOption;
use App\Implementations\SpecificationOptionImplementation;
use App\Http\Resources\SpecificationOptionResource;

use Hash;
class StoreSpecificationOptionAction
{
    use AsAction;
    use Response;
    private $specificationOption;
    
    function __construct(SpecificationOptionImplementation $specificationOptionImplementation)
    {
        $this->specificationOption = $specificationOptionImplementation;
    }

    public function handle(array $data)
    {

        $specificationOption = $this->specificationOption->Create($data);

        return new specificationOptionResource($specificationOption);
    }
    public function rules()
    {
        return [
            'name' => ['required','unique:specification_options,name'],
            'specification_id' => ['required', 'exists:specifications,id'],

        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specificationOption.add'))
            return $this->sendError('Forbidden',[],403);

        $specificationOption = $this->handle($request->all());

        return $this->sendResponse($specificationOption,' SpecificationOption Added Successfully');
    }
}