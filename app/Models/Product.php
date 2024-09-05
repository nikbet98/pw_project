<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'name',
        'description',
        'price',
        'release_date',
    ];

    public $timestamps = false;

    public function subcategory() : BelongsTo {
        return $this->belongsTo(Subcategory::class);
    }

    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    public function carts() : BelongsToMany {
        return $this->belongsToMany(Cart::class,'product_cart')
            ->withPivot('quantity');
    }

    public function orders() : BelongsToMany {
        return $this->belongsToMany(Order::class,'product_order');
    }

    public function wishlists() : BelongsToMany {
        return $this->belongsToMany(Wishlist::class,'product_wishlist');
    }

    public function promotion() : BelongsToMany {
        return $this->belongsToMany(Promotion::class,'product_promotion')
            ->withPivot('discount');
    }

    public function reviews() : HasMany {
        return $this->hasMany(Review::class);
    }
}
