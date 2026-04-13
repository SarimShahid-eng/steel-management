<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\TicketController
 */
final class TicketControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $tickets = Ticket::factory()->count(3)->create();

        $response = $this->get(route('tickets.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\TicketController::class,
            'store',
            \App\Http\Requests\Api\TicketControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $subject = fake()->word();
        $category = fake()->randomElement(/** enum_attributes **/);
        $description = fake()->text();
        $priority = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('tickets.store'), [
            'subject' => $subject,
            'category' => $category,
            'description' => $description,
            'priority' => $priority,
        ]);

        $tickets = Ticket::query()
            ->where('subject', $subject)
            ->where('category', $category)
            ->where('description', $description)
            ->where('priority', $priority)
            ->get();
        $this->assertCount(1, $tickets);
        $ticket = $tickets->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->get(route('tickets.show', $ticket));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\TicketController::class,
            'update',
            \App\Http\Requests\Api\TicketControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $ticket = Ticket::factory()->create();
        $unit = Unit::factory()->create();
        $subject = fake()->word();
        $category = fake()->randomElement(/** enum_attributes **/);
        $description = fake()->text();
        $status = fake()->randomElement(/** enum_attributes **/);
        $priority = fake()->randomElement(/** enum_attributes **/);
        $created_by = User::factory()->create();

        $response = $this->put(route('tickets.update', $ticket), [
            'unit_id' => $unit->id,
            'subject' => $subject,
            'category' => $category,
            'description' => $description,
            'status' => $status,
            'priority' => $priority,
            'created_by' => $created_by->id,
        ]);

        $ticket->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($unit->id, $ticket->unit_id);
        $this->assertEquals($subject, $ticket->subject);
        $this->assertEquals($category, $ticket->category);
        $this->assertEquals($description, $ticket->description);
        $this->assertEquals($status, $ticket->status);
        $this->assertEquals($priority, $ticket->priority);
        $this->assertEquals($created_by->id, $ticket->created_by);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->delete(route('tickets.destroy', $ticket));

        $response->assertNoContent();

        $this->assertModelMissing($ticket);
    }
}
