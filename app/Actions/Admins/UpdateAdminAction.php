<?php
namespace App\Actions\Admins;

use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Http\Resources\AdminResource;
use Lorisleiva\Actions\ActionRequest;
use App\Actions\Photos\StorePhotoAction;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\Uploads\UploadImageAction;
use App\Implementations\AdminImplementation;
use App\Actions\Auth\CheckIfPasswordRepeatedAction;
use App\Actions\AdminPasswords\StoreAdminPasswordAction;

class UpdateAdminAction
{
    use AsAction;
    use Response;
    private $admin;


    function __construct(AdminImplementation $AdminImplementation)
    {
        $this->admin = $AdminImplementation;
    }

    public function handle(array $data, int $id)
    {

        if(array_key_exists('file',$data)){
            $data['photo'] = UploadImageAction::run(['photo'=>$data['file']]);
        }

        return new AdminResource($this->admin->update($data, $id));
    }
    public function rules(Request $request)
    {
        return [
            'username' => ['unique:admins,username,' . $request->route('id')],
           // 'photo' => ['required'],
            'phone' => ['unique:admins,phone,' . $request->route('id')],
        ];
    }
   

    public function asController(Request $request, int $id)
    {
        if (auth('sanctum')->check() && (!auth('sanctum')->user()->has_permission('admin.edit') && (auth('sanctum')->admin()->id != $id))) {
            return $this->sendError('Forbidden', [], 403);
        }

        $admin = $this->handle($request->all(), $id);

        return $this->sendResponse($admin, 'Updated Admin Successfly');
    }
}
