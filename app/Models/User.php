<?php

namespace App\Models;

use App\Enums\UserStatus;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Booking\Models\Booking;
use Modules\Property\Models\Property;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    protected function statusKey(): Attribute
    {
        return Attribute::get(fn () => $this->status?->key() ?? UserStatus::Active->key());
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => $this->status?->label() ?? UserStatus::Active->label());
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'host_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isHost(): bool
    {
        return $this->role && $this->role->name === 'host';
    }
}
