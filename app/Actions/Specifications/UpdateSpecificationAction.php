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
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class UpdateSpecificationAction
{
    use AsAction;
    use Response;
    private $specification;
    
    function __construct(SpecificationImplementation $specificationImplementation)
    {
        $this->specification = $specificationImplementation;
    }

    public function handle(array $data, int $id)
    {
        $specification = $this->specification->Update($data, $id);
        return new SpecificationResource($specification);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:specifications,name,'.$request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specification.edit'))
            return $this->sendError('Forbidden',[],403);

        $specification = $this->handle($request->all(), $id);

        return $this->sendResponse($specification,'Updated Successfly');
    }
}