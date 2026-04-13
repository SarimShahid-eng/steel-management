<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Profit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProfitController
 */
final class ProfitControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $profits = Profit::factory()->count(3)->create();

        $response = $this->get(route('profits.index'));

        $response->assertOk();
        $response->assertViewIs('profit.index');
        $response->assertViewHas('profits', $profits);
    }
}
