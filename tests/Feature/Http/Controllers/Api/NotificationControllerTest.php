<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\NotificationController
 */
final class NotificationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $notifications = Notification::factory()->count(3)->create();

        $response = $this->get(route('notifications.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\NotificationController::class,
            'update',
            \App\Http\Requests\Api\NotificationControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $notification = Notification::factory()->create();
        $is_read = fake()->boolean();

        $response = $this->put(route('notifications.update', $notification), [
            'is_read' => $is_read,
        ]);

        $notification->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($is_read, $notification->is_read);
    }
}
