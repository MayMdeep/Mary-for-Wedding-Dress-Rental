<?php

namespace App\Http\Resources;

use App\Actions\Languages\GetLanguageListAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Actions\Conversations\GetAllConversationListAction;

class UserResource extends JsonResource
{
    //UserPassword
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            
            'id' =>(int)$this->id,
            'name' => (string)$this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

     

        return $result;
    }
}
