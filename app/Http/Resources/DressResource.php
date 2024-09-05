<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use App\Actions\Translations\GetModelDetailedTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DressResource extends JsonResource
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
            'name' => (string) $this->name,
            'description' => (string) $this->description,
            'image' => $this->image,
            'rental_price' => (double)$this->rental_price,
            'options' => SpecificationOptionResource::collection($this->options),
            // 'specifications' => SpecificationResource::collection($this->specifications),
            'quantity' => (int)$this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    
        return $result;
    }
}
