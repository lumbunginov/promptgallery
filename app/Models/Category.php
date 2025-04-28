<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = ['category'];

    // Relasi ke ImageStyle
    public function imageStyles(): HasMany
    {
        return $this->hasMany(ImageStyle::class);
    }
}