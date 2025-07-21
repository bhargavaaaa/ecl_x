<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
