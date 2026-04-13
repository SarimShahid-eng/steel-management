<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BankController
 */
final class BankControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $banks = Bank::factory()->count(3)->create();

        $response = $this->get(route('banks.index'));

        $response->assertOk();
        $response->assertViewIs('bank.index');
        $response->assertViewHas('accounts');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('banks.create'));

        $response->assertOk();
        $response->assertViewIs('bank.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BankController::class,
            'store',
            \App\Http\Requests\BankStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $response = $this->post(route('banks.store'));

        $response->assertRedirect(route('bank.index'));

        $this->assertDatabaseHas(banks, [ /* ... */ ]);
    }
}
