<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{

    use HasFactory;

    protected $table = 'brand';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
    
    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }
    
    public function brands(): BelongsToMany {
        return $this->belongsToMany(Brand::class,'promotion_brand');
    }
}
