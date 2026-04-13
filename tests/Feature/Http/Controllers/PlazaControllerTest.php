<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Plaza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PlazaController
 */
final class PlazaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $plazas = Plaza::factory()->count(3)->create();

        $response = $this->get(route('plazas.index'));

        $response->assertOk();
        $response->assertViewIs('plaza.index');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PlazaController::class,
            'store',
            \App\Http\Requests\PlazaStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();
        $city = fake()->city();
        $country = fake()->country();
        $total_units = fake()->numberBetween(-10000, 10000);
        $master_pool_balance = fake()->randomFloat(/** decimal_attributes **/);
        $currency_code = fake()->word();

        $response = $this->post(route('plazas.store'), [
            'name' => $name,
            'city' => $city,
            'country' => $country,
            'total_units' => $total_units,
            'master_pool_balance' => $master_pool_balance,
            'currency_code' => $currency_code,
        ]);

        $plazas = Plaza::query()
            ->where('name', $name)
            ->where('city', $city)
            ->where('country', $country)
            ->where('total_units', $total_units)
            ->where('master_pool_balance', $master_pool_balance)
            ->where('currency_code', $currency_code)
            ->get();
        $this->assertCount(1, $plazas);
        $plaza = $plazas->first();

        $response->assertRedirect(route('plaza.index'));
        $response->assertSessionHas('plaza.saved', $plaza->saved);
    }
}
