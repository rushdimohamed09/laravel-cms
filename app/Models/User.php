<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'createdBy',
        'approvedBy',
        'approved_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define a relationship with the createdBy user.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approvedBy');
    }

    protected $dates = ['deleted_at'];

    public function roles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function getRoleAttribute()
    {
        $userRole = $this->roles->last();

        if ($userRole) {
            return $userRole->role;
        }

        return null;
    }

    public function permissions()
    {
        $role = $this->getRoleAttribute();

        if ($role) {
            $permissions = RolesPermissions::where('role_id', $role->id)->pluck('permission_id')->toArray();

            return Permission::whereIn('id', $permissions)->pluck('name')->toArray();
        }

        return [];
    }
}
