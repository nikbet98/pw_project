<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'total', 
    ];
    
    public $timestamps = false;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_cart')
            ->withPivot('quantity');
    }
}
