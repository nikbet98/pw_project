<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'subcategory';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
    
    public function category() : BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }

    public function promotion() : BelongsToMany {
        return $this->belongsToMany(Promotion::class,'promotion_subcategory');
    }
}
