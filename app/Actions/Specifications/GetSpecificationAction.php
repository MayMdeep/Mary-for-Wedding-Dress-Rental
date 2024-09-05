<?php
namespace App\Actions\Specifications;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\SpecificationImplementation;
use App\Http\Resources\SpecificationResource;
use Hash;
class GetSpecificationAction
{
    use AsAction;
    use Response;
    private $specification;
    
    function __construct(SpecificationImplementation $SpecificationImplementation)
    {
        $this->specification = $SpecificationImplementation;
    }

    public function handle(int $id)
    {
        return new SpecificationResource($this->specification->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specification.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}