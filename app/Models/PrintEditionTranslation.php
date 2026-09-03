<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintEditionTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['print_edition_id', 'locale', 'title', 'slug', 'description'];

    public function printEdition(): BelongsTo
    {
        return $this->belongsTo(PrintEdition::class);
    }
}
