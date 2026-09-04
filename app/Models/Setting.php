<?php

namespace App\Models;

use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * A single editable site-configuration value. Reads should go through
 * {@see SettingsRepository}, which caches the whole table
 * and casts values by `type`.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];
}
