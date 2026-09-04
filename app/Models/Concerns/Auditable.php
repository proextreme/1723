<?php

namespace App\Models\Concerns;

use App\Support\Audit\AuditLogger;
use Illuminate\Database\Eloquent\Model;

/**
 * Records create / update / delete / restore on a model to `audit_logs`.
 * A no-op update (nothing meaningful changed) is not recorded.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn (Model $model) => $model->recordAudit('created'));

        static::updated(fn (Model $model) => $model->recordAudit('updated', [
            'changed' => array_values(array_diff(
                array_keys($model->getChanges()),
                $model->auditIgnoredAttributes(),
            )),
        ]));

        static::deleted(fn (Model $model) => $model->recordAudit(
            $model->isAuditForceDeleting() ? 'force_deleted' : 'deleted',
        ));

        // `restored` is a SoftDeletes event; only register it when available.
        if (method_exists(static::class, 'restored')) {
            static::restored(fn (Model $model) => $model->recordAudit('restored'));
        }
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function recordAudit(string $action, array $properties = []): void
    {
        if ($action === 'updated' && ($properties['changed'] ?? []) === []) {
            return;
        }

        app(AuditLogger::class)->log($action, $this, $properties);
    }

    /**
     * @return array<int, string>
     */
    protected function auditIgnoredAttributes(): array
    {
        return ['updated_at', 'password', 'remember_token'];
    }

    private function isAuditForceDeleting(): bool
    {
        return method_exists($this, 'isForceDeleting') && $this->isForceDeleting();
    }
}
