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
use App\Actions\Translations\StoreTranslationAction;
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
        $dress = $this->dress->Create($data);

        foreach($data['languages'] as $key => $lang)
        {
            foreach($lang as $translationKey => $translation)
            {
                StoreTranslationAction::run([
                    'language_id' => $key,
                    'type' => Dress::class,
                    'type_id' => $dress->id,
                    'text_type' => $translationKey,
                    'value' => $translation,
                ]);
            }

        }
        return new DressResource($dress);
    }
    public function rules()
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
            'languages' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('dress.add'))
            return $this->sendError('Forbidden',[],403);

        $dress = $this->handle($request->all());

        return $this->sendResponse($dress,'');
    }
}