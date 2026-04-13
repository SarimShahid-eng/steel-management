<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Plaza;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\UnitController
 */
final class UnitControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $units = Unit::factory()->count(3)->create();

        $response = $this->get(route('units.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\UnitController::class,
            'store',
            \App\Http\Requests\Api\UnitControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $plaza = Plaza::factory()->create();
        $unit_number = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $unit_type = fake()->randomElement(/** enum_attributes **/);
        $due = fake()->randomFloat(/** decimal_attributes **/);
        $monthly_dues_amount = fake()->randomFloat(/** decimal_attributes **/);

        $response = $this->post(route('units.store'), [
            'plaza_id' => $plaza->id,
            'unit_number' => $unit_number,
            'status' => $status,
            'unit_type' => $unit_type,
            'due' => $due,
            'monthly_dues_amount' => $monthly_dues_amount,
        ]);

        $units = Unit::query()
            ->where('plaza_id', $plaza->id)
            ->where('unit_number', $unit_number)
            ->where('status', $status)
            ->where('unit_type', $unit_type)
            ->where('due', $due)
            ->where('monthly_dues_amount', $monthly_dues_amount)
            ->get();
        $this->assertCount(1, $units);
        $unit = $units->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $unit = Unit::factory()->create();

        $response = $this->get(route('units.show', $unit));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\UnitController::class,
            'update',
            \App\Http\Requests\Api\UnitControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $unit = Unit::factory()->create();
        $plaza = Plaza::factory()->create();
        $unit_number = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $unit_type = fake()->randomElement(/** enum_attributes **/);
        $due = fake()->randomFloat(/** decimal_attributes **/);
        $monthly_dues_amount = fake()->randomFloat(/** decimal_attributes **/);

        $response = $this->put(route('units.update', $unit), [
            'plaza_id' => $plaza->id,
            'unit_number' => $unit_number,
            'status' => $status,
            'unit_type' => $unit_type,
            'due' => $due,
            'monthly_dues_amount' => $monthly_dues_amount,
        ]);

        $unit->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $unit->plaza_id);
        $this->assertEquals($unit_number, $unit->unit_number);
        $this->assertEquals($status, $unit->status);
        $this->assertEquals($unit_type, $unit->unit_type);
        $this->assertEquals($due, $unit->due);
        $this->assertEquals($monthly_dues_amount, $unit->monthly_dues_amount);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $unit = Unit::factory()->create();

        $response = $this->delete(route('units.destroy', $unit));

        $response->assertNoContent();

        $this->assertModelMissing($unit);
    }
}
