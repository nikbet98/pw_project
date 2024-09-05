<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotion';

    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'description',
        'start',
        'end',
        'image',
    ];

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class);
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class,'promotion_category');
    }

    public function subcategories(): BelongsToMany {
        return $this->belongsToMany(Subcategory::class,'promotion_subcategory');
    }

    public function brands(): BelongsToMany {
        return $this->belongsToMany(Brand::class,'promotion_brand');
    }


}
