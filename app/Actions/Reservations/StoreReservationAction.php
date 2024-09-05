<?php
namespace App\Actions\Reservations;

use Hash;
use App\Traits\Response;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Helpers\ImageDimensions;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\ReservationResource;

use App\Implementations\DressImplementation;
use App\Implementations\ReservationImplementation;

class StoreReservationAction
{
    use AsAction;
    use Response;
    private $reservation;
    private $dress;

    function __construct(ReservationImplementation $ReservationImplementation, DressImplementation $DressImplementation)
    {
        $data['user_id']= auth('sanctum')->user()->id;
        $this->reservation = $ReservationImplementation;
        $this->dress = $DressImplementation;
    }

    public function handle(array $data)
    {
        // update the dress availabilty 
        $this->dress->Update(['availability'=>1],$data['dress_id']);
        // create reservation
        $reservation = $this->reservation->Create($data);
        return new ReservationResource($reservation);
    }
    public function rules()
    {
        return [
            'rental_duration' => ['required'],
            'reservation_date' => ['required'],
            // 'user_id' => ['required', 'exists:users,id'],
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
