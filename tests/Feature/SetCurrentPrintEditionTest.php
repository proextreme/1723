<?php

namespace Tests\Feature;

use App\Actions\SetCurrentPrintEdition;
use App\Models\PrintEdition;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SetCurrentPrintEditionTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function action(): SetCurrentPrintEdition
    {
        return app(SetCurrentPrintEdition::class);
    }

    public function test_promotes_the_given_edition_and_demotes_the_previous_current_one(): void
    {
        $previous = PrintEdition::factory()->current()->create();
        $next = PrintEdition::factory()->create();

        $this->action()->handle($next);

        $this->assertTrue($next->fresh()->is_current);
        $this->assertFalse($previous->fresh()->is_current);
        $this->assertSame(1, PrintEdition::query()->where('is_current', true)->count());
    }

    public function test_leaves_exactly_one_current_edition_when_called_repeatedly(): void
    {
        $editions = PrintEdition::factory()->count(3)->create();

        $this->action()->handle($editions[0]);
        $this->action()->handle($editions[2]);
        $this->action()->handle($editions[2]);

        $this->assertSame(1, PrintEdition::query()->where('is_current', true)->count());
        $this->assertTrue($editions[2]->fresh()->is_current);
    }

    public function test_current_scope_returns_the_current_edition(): void
    {
        PrintEdition::factory()->count(2)->create();
        $current = PrintEdition::factory()->current()->create();

        $this->assertTrue($current->is($current->newQuery()->current()->sole()));
    }
}
