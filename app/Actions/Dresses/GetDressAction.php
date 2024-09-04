<?php
namespace App\Actions\Dresses;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\DressImplementation;
use App\Http\Resources\DressResource;
use Hash;
class GetDressAction
{
    use AsAction;
    use Response;
    private $dress;
    
    function __construct(DressImplementation $DressImplementation)
    {
        $this->dress = $DressImplementation;
    }

    public function handle(int $id)
    {
        return new DressResource($this->dress->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('dress.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}