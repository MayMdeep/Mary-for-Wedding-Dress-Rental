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
class GetReservationAction
{
    use AsAction;
    use Response;
    private $reservation;
    
    function __construct(ReservationImplementation $ReservationImplementation)
    {
        $this->reservation = $ReservationImplementation;
    }

    public function handle(int $id)
    {
        return new ReservationResource($this->reservation->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('reservation.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}