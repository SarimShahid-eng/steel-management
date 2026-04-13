<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Plaza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\PlazaController
 */
final class PlazaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $plazas = Plaza::factory()->count(3)->create();

        $response = $this->get(route('plazas.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\PlazaController::class,
            'store',
            \App\Http\Requests\Api\PlazaControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
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

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $plaza = Plaza::factory()->create();

        $response = $this->get(route('plazas.show', $plaza));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\PlazaController::class,
            'update',
            \App\Http\Requests\Api\PlazaControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $plaza = Plaza::factory()->create();
        $name = fake()->name();
        $city = fake()->city();
        $country = fake()->country();
        $total_units = fake()->numberBetween(-10000, 10000);
        $master_pool_balance = fake()->randomFloat(/** decimal_attributes **/);
        $currency_code = fake()->word();

        $response = $this->put(route('plazas.update', $plaza), [
            'name' => $name,
            'city' => $city,
            'country' => $country,
            'total_units' => $total_units,
            'master_pool_balance' => $master_pool_balance,
            'currency_code' => $currency_code,
        ]);

        $plaza->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $plaza->name);
        $this->assertEquals($city, $plaza->city);
        $this->assertEquals($country, $plaza->country);
        $this->assertEquals($total_units, $plaza->total_units);
        $this->assertEquals($master_pool_balance, $plaza->master_pool_balance);
        $this->assertEquals($currency_code, $plaza->currency_code);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $plaza = Plaza::factory()->create();

        $response = $this->delete(route('plazas.destroy', $plaza));

        $response->assertNoContent();

        $this->assertModelMissing($plaza);
    }
}
