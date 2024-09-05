<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
    
    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }

    public function subcategories() : HasMany {
        return $this->hasMany(Subcategory::class);
    }

    public function promotion() : BelongsToMany {
        return $this->belongsToMany(Promotion::class,'promotion_category');
    }
}
