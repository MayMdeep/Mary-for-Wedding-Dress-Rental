<?php
namespace App\Actions\Reservations;

use App\Actions\Translations\UpdateTranslationAction;
use App\Actions\Uploads\UploadImageAction;
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
        if (array_key_exists('file', $data)) {
            $data['photo'] = UploadImageAction::run($data['file'], ImageDimensions::BLOG_IMAGE);
        }

        if (array_key_exists('languages', $data)) {
            foreach ($data['languages'] as $key => $lang) {
                foreach ($lang as $translationKey => $translation) {
                    UpdateTranslationAction::run([
                        'text_type' => $translationKey,
                        'value' => $translation,
                    ], (int) $key);
                }

            }
        }

        $reservation = $this->reservation->Update($data, $id);
        return new ReservationResource($reservation);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:reservations,name,' . $request->route('id')],
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
