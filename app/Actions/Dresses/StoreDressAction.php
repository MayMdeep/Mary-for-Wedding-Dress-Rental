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

use App\Actions\Uploads\UploadImageAction;
use App\Implementations\DressImplementation;
use App\Implementations\SpecificationImplementation;
use App\Implementations\SpecificationOptionImplementation;

class StoreDressAction
{
    use AsAction;
    use Response;
    private $dress;
    private $specification_option;
    private $specification;

    
    function __construct(DressImplementation $DressImplementation,SpecificationOptionImplementation $SpecificationOptionImplementation, SpecificationImplementation $SpecificationImplementation)
    {
        $this->specification_option= $SpecificationOptionImplementation;
        $this->dress = $DressImplementation;
        $this->specification= $SpecificationImplementation;
    }

    public function handle(array $data)
    {
        if (array_key_exists('file', $data)) {
            $data["image"]= UploadImageAction::run($data);
        }
        

        $dress = $this->dress->Create($data);
        $options = [];
        if (array_key_exists('options', $data)) {
            $options = $data['options'];
            unset($data['options']);
        }

        if (!empty($options)) {
            foreach ($options as $specName => $specOptionId) {
                $specification = $this->specification->getList(['name', $specName])->first();
                $dress->specifications()->attach($specification->id, ['option_id' => $specOptionId]);
            }
        }
        return new DressResource($dress);
    }
    public function rules()
    {
        return [
            'name' => ['required'],
            'rental_price' => ['required'],
            'description' => ['required'],
            'file' => ['required'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'options' => ['required'],
            'options.*' => ['required','exists:specification_options,id'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        // if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('dress.add'))
        //     return $this->sendError('Forbidden',[],403);

        $dress = $this->handle($request->all());

        return $this->sendResponse($dress,'');
    }
}