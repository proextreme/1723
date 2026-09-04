<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Support\Settings\SettingsRepository;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A single editable site-configuration value. Reads should go through
 * {@see SettingsRepository}, which caches the whole table and casts values by
 * `type`.
 */
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use Auditable, HasFactory;

    protected $fillable = ['key', 'value', 'type'];
}
