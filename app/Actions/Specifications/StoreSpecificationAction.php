<?php
namespace App\Actions\Specifications;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Specification;
use App\Implementations\SpecificationImplementation;
use App\Http\Resources\SpecificationResource;

use Hash;
class StoreSpecificationAction
{
    use AsAction;
    use Response;
    private $specification;
    
    function __construct(SpecificationImplementation $specificationImplementation)
    {
        $this->specification = $specificationImplementation;
    }

    public function handle(array $data)
    {

        $specification = $this->specification->Create($data);

        return new specificationResource($specification);
    }
    public function rules()
    {
        return [
            'name' => ['required','unique:specifications,name'],

        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specification.add'))
            return $this->sendError('Forbidden',[],403);

        $specification = $this->handle($request->all());

        return $this->sendResponse($specification,' Specification Added Successfully');
    }
}