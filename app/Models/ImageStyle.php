<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImageStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'category_id',
        'description',
        'image'
    ];

    // Relasi ke Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi many-to-many ke Tag
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Ambil variasi berdasarkan kode utama
    public function variations()
    {
        // Ambil kode utama sebelum titik (misal 13 dari 13.1)
        $mainCode = strpos($this->code, '.') !== false ? explode('.', $this->code)[0] : $this->code;
        $query = self::where(function($q) use ($mainCode) {
            $q->where('code', 'like', $mainCode . '.%')
              ->orWhere('code', $mainCode);
        })
        ->orderBy('code');
        // Keluarkan style yang sedang aktif dari daftar variasi
        return $query->where('id', '!=', $this->id)->get();
    }
}