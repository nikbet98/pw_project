<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total', 
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class,'product_wishlist');
    }
}
