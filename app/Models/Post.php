<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    // Colonnes modifiables par l'utilisateur
    protected $fillable = [
        'title',
        'slug',
        'content',
        'published',
        'published_at',
        'user_id',
    ];

    // Conversion automatique des types
    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relation : un Post appartient à un User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Génère le slug automatiquement depuis le titre
    public static function generateSlug(string $title): string
    {
        return Str::slug($title);
    }

    // Scope : uniquement les posts publiés
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
