<?php
namespace App\Actions\Users;

use App\Http\Resources\TopVendorsResource;
use App\Implementations\UserImplementation;
use App\Implementations\VendorImplementation;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTopVendorsListAction
{
    use AsAction;
    use Response;
    private $vendor;
    private $user;

    function __construct(VendorImplementation $vendorImplementation, UserImplementation $UserImplementation)
    {
        $this->vendor = $vendorImplementation;
        $this->user = $UserImplementation;
    }

    public function handle(array $data, int $perPage)
    {
        if (!is_numeric($perPage)) {
            $perPage = 10;
        }

        $vendors = $this->vendor->getList($data);
        if ($vendors->isNotEmpty()) {

            $maxTotalCount = max(array_column($this->vendor->getList([])->toArray(), 'total_count'));
            foreach ($vendors as $vendor) {
                $percentage = $vendor->total_count / $maxTotalCount * 100;
                if ($percentage > 75) {
                    $this->user->update(['badge_id' => 4], $vendor->id);
                } elseif ($percentage > 50) {
                    $this->user->update(['badge_id' => 3], $vendor->id);
                } elseif ($percentage > 25) {
                    $this->user->update(['badge_id' => 2], $vendor->id);
                } else {
                    $this->user->update(['badge_id' => 1], $vendor->id);
                }
            }
        }
        // Get all users that not blocked
        if (auth('sanctum')->check()) {
            $data['without_blocked_users'] = auth('sanctum')->id();
        }
        return TopVendorsResource::collection($this->vendor->getPaginatedList($data,$perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {}

    public function asController(Request $request)
    {
        $list = $this->handle($request->all(), $request->input('results', 10));

//        return $this->sendResponse($list,'');
        return $this->sendPaginatedResponse($list, '');
    }
}
