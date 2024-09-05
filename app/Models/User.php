<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    
    public $timestamps = false;

    // Metodo per verificare se l'utente è admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }   

    // Metodo per verificare se l'utente è un utente normale
    public function isUser()
    {
        return $this->role === 'user';
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }

    public function wishlist() : HasOne {
        return $this->hasOne(Wishlist::class);
    }

    public function cart() : HasOne {
        return $this->hasOne(Cart::class);
    }

    public function reviews() : HasMany {
        return $this->hasMany(Review::class);
    }

    public function contactInfo() : HasOne {
        return $this->hasOne(ContactInfo::class);
    }
}
