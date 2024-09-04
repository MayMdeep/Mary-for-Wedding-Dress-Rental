<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function permissions()
    {
        return $this->hasMany(RolePermissionView::class, 'id', 'role_id')->pluck('permission')->toArray();;
    }
    public function has_permission($permission)
    {
        return $this->hasMany(RolePermissionView::class, 'id', 'role_id')->where('permission', $permission)->get()->isNotEmpty();
    }
}
