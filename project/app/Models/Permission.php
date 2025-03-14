<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permissionid', 'roleid');
    }

    public function listPermissions()
    {
        return $this->all();
    }
}