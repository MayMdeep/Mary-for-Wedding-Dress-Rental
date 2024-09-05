<?php
namespace App\Actions\Reservations;

use App\Helpers\ImageDimensions;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Reservation;
use App\Implementations\ReservationImplementation;
use App\Http\Resources\ReservationResource;

use Hash;
class StoreReservationAction
{
    use AsAction;
    use Response;
    private $reservation;

    function __construct(ReservationImplementation $ReservationImplementation)
    {
        $this->reservation = $ReservationImplementation;
    }

    public function handle(array $data)
    {
        
        $reservation = $this->reservation->Create($data);
        return new ReservationResource($reservation);
    }
    public function rules()
    {
        return [
            'rental_duration' => ['required'],
            'reservation_date' => ['required'],
            'user_id' => ['required', 'exists:users,id'],
            'dress_id' => ['required', 'exists:dresses,id'],
        ];
    }

    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('reservation.add'))
            return $this->sendError('Forbidden',[],403);

        $reservation = $this->handle($request->all());

        return $this->sendResponse($reservation,'');
    }
}
