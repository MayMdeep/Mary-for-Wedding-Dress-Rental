<?php
namespace App\Actions\Dresses;

use Hash;
use App\Models\Dress;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Http\Resources\DressResource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Implementations\DressImplementation;
use App\Actions\Translations\UpdateTranslationAction;
use App\Implementations\SpecificationOptionImplementation;

class UpdateDressAction
{
    use AsAction;
    use Response;
    private $dress;
    private $specification_option;
    
    function __construct(DressImplementation $DressImplementation, SpecificationOptionImplementation $SpecificationOptionImplementation)
    {
        $this->specification_option= $SpecificationOptionImplementation;
        $this->dress = $DressImplementation;
    }

    public function handle(array $data, int $id)
    { 
        $dress= $this->dress->getOne($id);
        $options = [];
        if (array_key_exists('options', $data)) {
            $options = $data['options'];
            unset($data['options']);
        }

        if (!empty($options)) {
            // remove previous specifications
            $dress->specifications()->detach();
            // attach new options
            foreach ($options as $optionId) {
                $specificationOption = $this->specification_option->getOne($optionId);
                $dress->specifications()->attach($specificationOption->specification_id, ['option_id' => $optionId]);
            }
        }
        $dress = $this->dress->Update($data, $id);
        return new DressResource($dress);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:dresses,name,' . $request->route('id')],
            'options.*' => ['exists:specification_options,id'],
          
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('dress.edit'))
            return $this->sendError('Forbidden',[],403);

        $dress = $this->handle($request->all(), $id);

        return $this->sendResponse($dress,'');
    }
}