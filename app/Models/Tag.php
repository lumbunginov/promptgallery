<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];

    // Relasi many-to-many ke ImageStyle
    public function imageStyles(): BelongsToMany
    {
        return $this->belongsToMany(ImageStyle::class);
    }
}