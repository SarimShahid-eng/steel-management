<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Cash;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CashController
 */
final class CashControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $cashes = Cash::factory()->count(3)->create();

        $response = $this->get(route('cashes.index'));

        $response->assertOk();
        $response->assertViewIs('cash.index');
        $response->assertViewHas('accounts');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('cashes.create'));

        $response->assertOk();
        $response->assertViewIs('cash.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashController::class,
            'store',
            \App\Http\Requests\CashStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $response = $this->post(route('cashes.store'));

        $response->assertRedirect(route('supplier.index'));

        $this->assertDatabaseHas(suppliers, [ /* ... */ ]);
    }
}
