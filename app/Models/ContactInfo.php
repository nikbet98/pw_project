<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInfo extends Model
{
    use HasFactory;

    protected $table = 'contat_info';

    protected $fillable = [
        'date_of_birth',
        'phone_number',
        'address',
        'zipcode', 
    ];

    public $timestamps = false;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
