<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'blocked',
        'gender',
        'photo',
        'nif',
        'default_delivery_address',
        'default_payment_type',
        'default_payment_reference',
        'custom',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'custom'            => 'array',
            'blocked'           => 'boolean',
            'photo_url'         => 'string',
        ];
    }

    public function getImageUrlAttribute()
    {
        if ($this->photo && \Storage::disk('public')->exists('users/' . $this->photo)) {
            return asset("storage/users/$this->photo");
        } else {
            return asset("storage/users/anonymous.png");
        }
    }


    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function firstLastInitial(): string
    {
        $allNames = Str::of($this->name)
            ->explode(' ');
        $firstName = $allNames->first();
        $lastName  = $allNames->count() > 1 ? $allNames->last() : '';
        return Str::of($firstName)->substr(0, 1)
            ->append(' ')
            ->append(Str::of($lastName)->substr(0, 1));
    }

    public function firstLastName(): string
    {
        $allNames = Str::of($this->name)
            ->explode(' ');
        $firstName = $allNames->first();
        $lastName  = $allNames->count() > 1 ? $allNames->last() : '';
        return Str::of($firstName)
            ->append(' ')
            ->append(Str::of($lastName));
    }

    public function card()
    {
        return $this->hasOne(Card::class, 'id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'card_id', 'id');
    }

    public function isPending(): bool
    {return $this->type === 'pending_member';}

    public function isMember(): bool
    {return in_array($this->type, ['member', 'board'], true);}

    public function isEmployee(): bool
    {return $this->type === 'employee';}

    public function isBoard(): bool
    {return $this->type === 'board';}
}
