<?php
namespace App\Actions\Users;
use App\Implementations\UserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;

class SetFcmTokenAction
{
    use AsAction;
    use Response;
    private $user;

    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data)
    {
        return $this->user->update(['fcm_token'=>$data['fcm_token']],auth('sanctum')->user()->id);
    }
    public function rules()
    {
        return [
            'fcm_token' => ['required']
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {}

    public function asController(Request $request)
    {
        $code = $this->handle($request->all());

        return $this->sendResponse($code,'');
    }
}
