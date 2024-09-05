<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use App\Actions\Translations\GetModelDetailedTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => (int)$this->id
            ,'name'=>(string) $this->name,
            'specification_id'=>(int)$this->specification_id
            ,'added_price'=>(int) $this->added_price
            ,'created_at'=> $this->created_at
			,'updated_at'=> $this->updated_at
			,'deleted_at'=> $this->deleted_at

        ];
    
        return $result;
    }
}
