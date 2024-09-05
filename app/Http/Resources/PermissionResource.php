<?php

namespace App\Http\Resources;

use App\Actions\Translations\GetModelTranslationsAction;
use App\Actions\Translations\GetModelDetailedTranslationsAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Implementations\RoleImplementation ;

class PermissionResource extends JsonResource
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
            'name' => (string)$this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if($request->detailed)
            $result['detailed_translations'] = GetModelDetailedTranslationsAction::run($this);

        if(!$request->route('id')){
            $result['has_permission']=0;
        }
        else $result['has_permission']=$this->permissions_roles->where('id',$request->route('id'))->count();

        return $result;
    }
}
