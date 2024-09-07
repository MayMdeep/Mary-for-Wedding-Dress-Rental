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
            'id' => (int)$this->id,
            'specification_name'=>(string)$this->specification? $this->specification->name: "",
            'specification_id'=>$this->specification? $this->specification->id:"",
            'name'=>(string) $this->name,
            'added_price'=>(int) $this->added_price
        ];
    
        return $result;
    }
}
