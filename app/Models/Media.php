<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use Auditable, HasFactory, SoftDeletes;

    protected $table = 'media';

    protected $fillable = ['disk', 'path', 'original_name', 'mime_type', 'size', 'width', 'height', 'alt_text', 'created_by'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
