<?php
namespace App\Actions\Reservations;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\ReservationImplementation;
use App\Http\Resources\ReservationResource;
use Hash;
class GetReservationListAction
{
    use AsAction;
    use Response;
    private $reservation;
    
    function __construct(ReservationImplementation $ReservationImplementation)
    {
        $this->reservation = $ReservationImplementation;
    }

    public function handle(array $data = [], int $perPage = 10)
    {
        if (!is_numeric($perPage))
            $perPage = 10;
        
        return ReservationResource::collection($this->reservation->getPaginatedList($data, $perPage));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('reservation.get'))
            return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));
        
        return $this->sendPaginatedResponse($list,'');
    }
}