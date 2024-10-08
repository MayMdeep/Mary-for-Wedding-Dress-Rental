<?php
namespace App\Actions\Reservations;

use App\Actions\Translations\UpdateTranslationAction;
use App\Helpers\ImageDimensions;
use App\Http\Resources\ReservationResource;
use App\Implementations\ReservationImplementation;
use App\Models\Reservation;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateReservationAction
{
    use AsAction;
    use Response;
    private $reservation;

    function __construct(ReservationImplementation $ReservationImplementation)
    {
        $this->reservation = $ReservationImplementation;
    }

    public function handle(array $data, int $id)
    {
        $reservation = $this->reservation->Update($data, $id);
        return new ReservationResource($reservation);
    }
    public function rules(Request $request)
    {
        return [
            'dress_id' => [ 'exists:dresses,id'],
            'user_id' => [ 'exists:users,id'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if (auth('sanctum')->check() && !auth('sanctum')->user()->has_permission('reservation.edit')) {
            return $this->sendError('Forbidden', [], 403);
        }

        $reservation = $this->handle($request->all(), $id);

        return $this->sendResponse($reservation, '');
    }
}
