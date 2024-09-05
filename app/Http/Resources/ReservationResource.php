<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use App\Actions\Translations\GetModelDetailedTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => (int)$this->id,
            'user_id'=>(int)$this->user_id
            ,'dress_id'=>(int) $this->dress_id
            ,'rental_duration'=> $this->rental_duration
            ,'reservation_date'=> (string)$this->reservation_date
            ,'created_at'=> $this->created_at
			,'updated_at'=> $this->updated_at
			,'deleted_at'=> $this->deleted_at

        ];
    
        return $result;
    }
}
