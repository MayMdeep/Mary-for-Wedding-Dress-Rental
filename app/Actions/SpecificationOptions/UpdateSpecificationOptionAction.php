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
use App\Actions\Translations\UpdateTranslationAction;
use App\Actions\Uploads\UploadImageAction;
use Hash;
class UpdateSpecificationOptionAction
{
    use AsAction;
    use Response;
    private $specificationOption;
    
    function __construct(SpecificationOptionImplementation $specificationOptionImplementation)
    {
        $this->specificationOption = $specificationOptionImplementation;
    }

    public function handle(array $data, int $id)
    {
        $specificationOption = $this->specificationOption->Update($data, $id);
        return new SpecificationOptionResource($specificationOption);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:specification_options,name,'.$request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('specificationOption.edit'))
            return $this->sendError('Forbidden',[],403);

        $specificationOption = $this->handle($request->all(), $id);

        return $this->sendResponse($specificationOption,'Updated Successfly');
    }
}