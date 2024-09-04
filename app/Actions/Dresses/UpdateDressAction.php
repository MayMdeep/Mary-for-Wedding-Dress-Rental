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
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class UpdateDressAction
{
    use AsAction;
    use Response;
    private $dress;
    
    function __construct(DressImplementation $DressImplementation)
    {
        $this->dress = $DressImplementation;
    }

    public function handle(array $data, int $id)
    {
        if (array_key_exists('languages', $data)) {
            foreach ($data['languages'] as $key => $lang) {
                foreach ($lang as $translationKey => $translation) {
                    UpdateTranslationAction::run([
                        'text_type' => $translationKey,
                        'value' => $translation,
                    ], (int) $key);
                }

            }
        }

        $dress = $this->dress->Update($data, $id);
        return new DressResource($dress);
    }
    public function rules(Request $request)
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'from_date' => ['date'],
            'to_date' => ['date'],
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