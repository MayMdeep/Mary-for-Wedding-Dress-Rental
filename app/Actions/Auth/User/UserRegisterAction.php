<?php
namespace App\Actions\Auth\User;

use App\Actions\Uploads\UploadImageAction;
use App\Actions\UserPasswords\StoreUserPasswordAction;
use App\Helpers\ImageDimensions;
use App\Implementations\UserImplementation;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserRegisterAction
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
        
       $data['user_id']=4;
       $data['profile_image']='ss';
       $user = $this->user->Create($data);
        return $user;
    }
    public function rules()
    {
        return [
            'name' => ['required', 'unique:users,name'],
            'password' => ['required','min:8'],
            'role_id' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        $user = $this->handle($request->all());

        return $this->sendResponse($user, '');
    }
}
