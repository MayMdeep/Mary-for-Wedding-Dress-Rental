<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
			'user_id' => (int)$this->user_id,
			'to_user_id' => (int)$this->to_user_id,
			'amount' => (float)round($this->amount,2),
			'move_type' => (int)$this->move_type,
			'active' => (int)$this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $result;
    }
}
