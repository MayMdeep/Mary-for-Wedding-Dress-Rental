<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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

            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'username' => (string) $this->username,
            'email' => (string) $this->email,
            'photo' => url($this->photo),
           // 'role' => $this->role,
            'active' => (int) $this->active,
        ];
        return $result;
    }
}
