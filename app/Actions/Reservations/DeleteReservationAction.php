<?php
namespace App\Actions\Reservations;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\ReservationImplementation;
use Hash;
class DeleteReservationAction
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
        return $this->reservation->delete($id);
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
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('reservation.delete'))
            return $this->sendError('Forbidden',[],403);
            $this->handle($id);
            return $this->sendResponse(['Success'], 'Reservation Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}