<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Plaza;
use App\Models\SpecialAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\SpecialAssessmentController
 */
final class SpecialAssessmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $specialAssessments = SpecialAssessment::factory()->count(3)->create();

        $response = $this->get(route('special-assessments.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\SpecialAssessmentController::class,
            'store',
            \App\Http\Requests\Api\SpecialAssessmentControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $plaza = Plaza::factory()->create();
        $title = fake()->sentence(4);
        $reason = fake()->text();
        $required_amount = fake()->randomFloat(/** decimal_attributes **/);
        $shortfall = fake()->randomFloat(/** decimal_attributes **/);
        $occupied_units = fake()->numberBetween(-10000, 10000);
        $per_unit_amount = fake()->randomFloat(/** decimal_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $created_by = User::factory()->create();

        $response = $this->post(route('special-assessments.store'), [
            'plaza_id' => $plaza->id,
            'title' => $title,
            'reason' => $reason,
            'required_amount' => $required_amount,
            'shortfall' => $shortfall,
            'occupied_units' => $occupied_units,
            'per_unit_amount' => $per_unit_amount,
            'status' => $status,
            'created_by' => $created_by->id,
        ]);

        $specialAssessments = SpecialAssessment::query()
            ->where('plaza_id', $plaza->id)
            ->where('title', $title)
            ->where('reason', $reason)
            ->where('required_amount', $required_amount)
            ->where('shortfall', $shortfall)
            ->where('occupied_units', $occupied_units)
            ->where('per_unit_amount', $per_unit_amount)
            ->where('status', $status)
            ->where('created_by', $created_by->id)
            ->get();
        $this->assertCount(1, $specialAssessments);
        $specialAssessment = $specialAssessments->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $specialAssessment = SpecialAssessment::factory()->create();

        $response = $this->get(route('special-assessments.show', $specialAssessment));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\SpecialAssessmentController::class,
            'update',
            \App\Http\Requests\Api\SpecialAssessmentControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $specialAssessment = SpecialAssessment::factory()->create();
        $plaza = Plaza::factory()->create();
        $title = fake()->sentence(4);
        $reason = fake()->text();
        $required_amount = fake()->randomFloat(/** decimal_attributes **/);
        $shortfall = fake()->randomFloat(/** decimal_attributes **/);
        $occupied_units = fake()->numberBetween(-10000, 10000);
        $per_unit_amount = fake()->randomFloat(/** decimal_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $created_by = User::factory()->create();

        $response = $this->put(route('special-assessments.update', $specialAssessment), [
            'plaza_id' => $plaza->id,
            'title' => $title,
            'reason' => $reason,
            'required_amount' => $required_amount,
            'shortfall' => $shortfall,
            'occupied_units' => $occupied_units,
            'per_unit_amount' => $per_unit_amount,
            'status' => $status,
            'created_by' => $created_by->id,
        ]);

        $specialAssessment->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $specialAssessment->plaza_id);
        $this->assertEquals($title, $specialAssessment->title);
        $this->assertEquals($reason, $specialAssessment->reason);
        $this->assertEquals($required_amount, $specialAssessment->required_amount);
        $this->assertEquals($shortfall, $specialAssessment->shortfall);
        $this->assertEquals($occupied_units, $specialAssessment->occupied_units);
        $this->assertEquals($per_unit_amount, $specialAssessment->per_unit_amount);
        $this->assertEquals($status, $specialAssessment->status);
        $this->assertEquals($created_by->id, $specialAssessment->created_by);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $specialAssessment = SpecialAssessment::factory()->create();

        $response = $this->delete(route('special-assessments.destroy', $specialAssessment));

        $response->assertNoContent();

        $this->assertModelMissing($specialAssessment);
    }
}
