<?php
namespace App\Actions\Auth\Admin;

use App\Actions\Uploads\UploadImageAction;
use App\Actions\UserPasswords\StoreUserPasswordAction;
use App\Implementations\UserImplementation;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\UserResource;
use App\Actions\Orders\GetAllOrdersListByVendorAction;
use App\Implementations\OrderImplementation;

class GetAuthinticatedUserBalanceAction
{
    use AsAction;
    use Response;
/////////////////////////// TODO HANDLE BALANCE FROM REMOTE DB///////////////////////////////////
    function __construct(UserImplementation $UserImplementation, OrderImplementation $orderImplementation)
    {
        $this->user = $UserImplementation;
        $this->order = $orderImplementation;
    }

    public function handle()
    {
        return auth('sanctum')->user()->balance();
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        $user = $this->handle();

        return $this->sendResponse($user, '');
    }
}
