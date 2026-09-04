<?php

namespace App\Actions;

use App\Models\PrintEdition;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Promote one print edition to "current" and demote every other.
 *
 * Only one edition may be current at a time. MySQL cannot express that as a
 * partial unique index, so the invariant is held here: an atomic cache lock
 * serialises concurrent callers (and absorbs a double-submit), and a database
 * transaction keeps the demote-then-promote pair all-or-nothing.
 */
class SetCurrentPrintEdition
{
    private const LOCK_KEY = 'print-editions:current';

    public function handle(PrintEdition $edition): PrintEdition
    {
        return Cache::lock(self::LOCK_KEY, 10)->block(5, fn (): PrintEdition => DB::transaction(function () use ($edition): PrintEdition {
            PrintEdition::query()
                ->where('is_current', true)
                ->whereKeyNot($edition->getKey())
                ->update(['is_current' => false]);

            $edition->forceFill(['is_current' => true])->save();

            return $edition->refresh();
        }));
    }
}
