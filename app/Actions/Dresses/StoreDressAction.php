<?php
namespace App\Actions\Dresses;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Dress;
use App\Implementations\DressImplementation;
use App\Http\Resources\DressResource;

use Hash;
class StoreDressAction
{
    use AsAction;
    use Response;
    private $dress;
    
    function __construct(DressImplementation $DressImplementation)
    {
        
        $this->dress = $DressImplementation;
    }

    public function handle(array $data)
    {
        if (array_key_exists('file', $data)) {
            $image = $data['file'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/photos', $imageName);
            $data["image"] = 'photos/' . $imageName;
        }

        $dress = $this->dress->Create($data);
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
            'options' => ['required','exists:specification_options,id'],
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