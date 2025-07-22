<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function titles(): HasMany
    {
        return $this->hasMany(Title::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function rich_texts(): HasMany
    {
        return $this->hasMany(RichText::class, 'form_id', 'id');
    }
}
