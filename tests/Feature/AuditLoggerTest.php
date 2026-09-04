<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return Collection<int, AuditLog>
     */
    private function auditRowsFor(SiteLink $link): Collection
    {
        return AuditLog::query()
            ->where('auditable_type', $link->getMorphClass())
            ->where('auditable_id', $link->getKey())
            ->orderBy('id')
            ->get();
    }

    public function test_creating_an_audited_model_records_the_acting_user_and_subject(): void
    {
        $actor = User::factory()->administrator()->create();

        $this->actingAs($actor);
        $link = SiteLink::factory()->create();

        $row = $this->auditRowsFor($link)->firstWhere('action', 'created');

        $this->assertNotNull($row);
        $this->assertSame($actor->id, $row->user_id);
        $this->assertSame($link->getMorphClass(), $row->auditable_type);
    }

    public function test_an_update_records_which_attributes_changed(): void
    {
        $link = SiteLink::factory()->create(['is_active' => true]);

        $link->update(['is_active' => false, 'label' => 'Renamed']);

        $row = $this->auditRowsFor($link)->firstWhere('action', 'updated');

        $this->assertNotNull($row);
        $this->assertEqualsCanonicalizing(['is_active', 'label'], $row->properties['changed']);
    }

    public function test_a_no_op_update_records_nothing(): void
    {
        $link = SiteLink::factory()->create(['label' => 'Same']);

        $link->update(['label' => 'Same']);

        $this->assertNull($this->auditRowsFor($link)->firstWhere('action', 'updated'));
    }

    public function test_a_delete_is_recorded(): void
    {
        $link = SiteLink::factory()->create();

        $link->delete();

        $this->assertNotNull($this->auditRowsFor($link)->firstWhere('action', 'deleted'));
    }

    public function test_a_password_change_is_not_written_into_the_audit_properties(): void
    {
        $user = User::factory()->create();

        $user->update(['password' => 'a-new-secret', 'name' => 'New Name']);

        $row = AuditLog::query()
            ->where('auditable_type', $user->getMorphClass())
            ->where('auditable_id', $user->getKey())
            ->where('action', 'updated')
            ->first();

        $this->assertNotNull($row);
        $this->assertNotContains('password', $row->properties['changed']);
        $this->assertContains('name', $row->properties['changed']);
    }
}
