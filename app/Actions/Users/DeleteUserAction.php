<?php
namespace App\Actions\Users;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\UserImplementation;
use Hash;
class DeleteUserAction
{
    use AsAction;
    use Response;
    private $user;
    
    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(int $id)
    {
        return $this->user->delete($id);
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(int $id)
    {
        try{
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('user.delete'))
                return $this->sendError('Forbidden',[],403);

            $this->handle($id);
            return $this->sendResponse('Success', 'User Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}