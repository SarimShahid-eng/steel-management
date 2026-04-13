<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Broadcast;
use App\Models\Plaza;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\BroadcastController
 */
final class BroadcastControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $broadcasts = Broadcast::factory()->count(3)->create();

        $response = $this->get(route('broadcasts.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\BroadcastController::class,
            'store',
            \App\Http\Requests\Api\BroadcastControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $plaza = Plaza::factory()->create();
        $title = fake()->sentence(4);
        $message = fake()->text();
        $is_urgent = fake()->boolean();
        $sent_to_count = fake()->numberBetween(-10000, 10000);
        $created_by = User::factory()->create();

        $response = $this->post(route('broadcasts.store'), [
            'plaza_id' => $plaza->id,
            'title' => $title,
            'message' => $message,
            'is_urgent' => $is_urgent,
            'sent_to_count' => $sent_to_count,
            'created_by' => $created_by->id,
        ]);

        $broadcasts = Broadcast::query()
            ->where('plaza_id', $plaza->id)
            ->where('title', $title)
            ->where('message', $message)
            ->where('is_urgent', $is_urgent)
            ->where('sent_to_count', $sent_to_count)
            ->where('created_by', $created_by->id)
            ->get();
        $this->assertCount(1, $broadcasts);
        $broadcast = $broadcasts->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $broadcast = Broadcast::factory()->create();

        $response = $this->get(route('broadcasts.show', $broadcast));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\BroadcastController::class,
            'update',
            \App\Http\Requests\Api\BroadcastControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $broadcast = Broadcast::factory()->create();
        $plaza = Plaza::factory()->create();
        $title = fake()->sentence(4);
        $message = fake()->text();
        $is_urgent = fake()->boolean();
        $sent_to_count = fake()->numberBetween(-10000, 10000);
        $created_by = User::factory()->create();

        $response = $this->put(route('broadcasts.update', $broadcast), [
            'plaza_id' => $plaza->id,
            'title' => $title,
            'message' => $message,
            'is_urgent' => $is_urgent,
            'sent_to_count' => $sent_to_count,
            'created_by' => $created_by->id,
        ]);

        $broadcast->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $broadcast->plaza_id);
        $this->assertEquals($title, $broadcast->title);
        $this->assertEquals($message, $broadcast->message);
        $this->assertEquals($is_urgent, $broadcast->is_urgent);
        $this->assertEquals($sent_to_count, $broadcast->sent_to_count);
        $this->assertEquals($created_by->id, $broadcast->created_by);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $broadcast = Broadcast::factory()->create();

        $response = $this->delete(route('broadcasts.destroy', $broadcast));

        $response->assertNoContent();

        $this->assertModelMissing($broadcast);
    }
}
