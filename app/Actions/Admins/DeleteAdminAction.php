<?php
namespace App\Actions\Admins;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\AdminImplementation;
use Hash;
class DeleteAdminAction
{
    use AsAction;
    use Response;
    private $admin;
    
    function __construct(AdminImplementation $AdminImplementation)
    {
        $this->admin = $AdminImplementation;
    }

    public function handle(int $id)
    {
        return $this->admin->delete($id);
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
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->admin()->has_permission('admin.delete'))
                return $this->sendError('Forbidden',[],403);

            $this->handle($id);
            return $this->sendResponse('Success', 'Admin Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}