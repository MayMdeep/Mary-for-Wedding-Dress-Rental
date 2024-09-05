<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use App\Actions\Translations\GetModelDetailedTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
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
            'name'=>(string)$this->name
            ,'created_at'=> $this->created_at
			,'updated_at'=> $this->updated_at
			,'deleted_at'=> $this->deleted_at

        ];
    
        return $result;
    }
}
