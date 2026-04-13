<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\AuditLog;
use App\Models\Plaza;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\AuditLogController
 */
final class AuditLogControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $auditLogs = AuditLog::factory()->count(3)->create();

        $response = $this->get(route('audit-logs.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\AuditLogController::class,
            'store',
            \App\Http\Requests\Api\AuditLogControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $plaza = Plaza::factory()->create();
        $user = User::factory()->create();
        $action = fake()->word();
        $resource_type = fake()->word();
        $resource = Resource::factory()->create();

        $response = $this->post(route('audit-logs.store'), [
            'plaza_id' => $plaza->id,
            'user_id' => $user->id,
            'action' => $action,
            'resource_type' => $resource_type,
            'resource_id' => $resource->id,
        ]);

        $auditLogs = AuditLog::query()
            ->where('plaza_id', $plaza->id)
            ->where('user_id', $user->id)
            ->where('action', $action)
            ->where('resource_type', $resource_type)
            ->where('resource_id', $resource->id)
            ->get();
        $this->assertCount(1, $auditLogs);
        $auditLog = $auditLogs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $auditLog = AuditLog::factory()->create();

        $response = $this->get(route('audit-logs.show', $auditLog));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\AuditLogController::class,
            'update',
            \App\Http\Requests\Api\AuditLogControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $auditLog = AuditLog::factory()->create();
        $plaza = Plaza::factory()->create();
        $user = User::factory()->create();
        $action = fake()->word();
        $resource_type = fake()->word();
        $resource = Resource::factory()->create();

        $response = $this->put(route('audit-logs.update', $auditLog), [
            'plaza_id' => $plaza->id,
            'user_id' => $user->id,
            'action' => $action,
            'resource_type' => $resource_type,
            'resource_id' => $resource->id,
        ]);

        $auditLog->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $auditLog->plaza_id);
        $this->assertEquals($user->id, $auditLog->user_id);
        $this->assertEquals($action, $auditLog->action);
        $this->assertEquals($resource_type, $auditLog->resource_type);
        $this->assertEquals($resource->id, $auditLog->resource_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $auditLog = AuditLog::factory()->create();

        $response = $this->delete(route('audit-logs.destroy', $auditLog));

        $response->assertNoContent();

        $this->assertModelMissing($auditLog);
    }
}
