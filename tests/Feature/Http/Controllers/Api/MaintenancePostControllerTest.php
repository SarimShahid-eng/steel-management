<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\MaintenancePost;
use App\Models\Plaza;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\MaintenancePostController
 */
final class MaintenancePostControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $maintenancePosts = MaintenancePost::factory()->count(3)->create();

        $response = $this->get(route('maintenance-posts.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\MaintenancePostController::class,
            'store',
            \App\Http\Requests\Api\MaintenancePostControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title = fake()->sentence(4);
        $category = fake()->randomElement(/** enum_attributes **/);
        $cost = fake()->randomFloat(/** decimal_attributes **/);
        $vendor_name = fake()->word();

        $response = $this->post(route('maintenance-posts.store'), [
            'title' => $title,
            'category' => $category,
            'cost' => $cost,
            'vendor_name' => $vendor_name,
        ]);

        $maintenancePosts = MaintenancePost::query()
            ->where('title', $title)
            ->where('category', $category)
            ->where('cost', $cost)
            ->where('vendor_name', $vendor_name)
            ->get();
        $this->assertCount(1, $maintenancePosts);
        $maintenancePost = $maintenancePosts->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $maintenancePost = MaintenancePost::factory()->create();

        $response = $this->get(route('maintenance-posts.show', $maintenancePost));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\MaintenancePostController::class,
            'update',
            \App\Http\Requests\Api\MaintenancePostControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $maintenancePost = MaintenancePost::factory()->create();
        $plaza = Plaza::factory()->create();
        $title = fake()->sentence(4);
        $category = fake()->randomElement(/** enum_attributes **/);
        $cost = fake()->randomFloat(/** decimal_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $created_by = User::factory()->create();

        $response = $this->put(route('maintenance-posts.update', $maintenancePost), [
            'plaza_id' => $plaza->id,
            'title' => $title,
            'category' => $category,
            'cost' => $cost,
            'status' => $status,
            'created_by' => $created_by->id,
        ]);

        $maintenancePost->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $maintenancePost->plaza_id);
        $this->assertEquals($title, $maintenancePost->title);
        $this->assertEquals($category, $maintenancePost->category);
        $this->assertEquals($cost, $maintenancePost->cost);
        $this->assertEquals($status, $maintenancePost->status);
        $this->assertEquals($created_by->id, $maintenancePost->created_by);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $maintenancePost = MaintenancePost::factory()->create();

        $response = $this->delete(route('maintenance-posts.destroy', $maintenancePost));

        $response->assertNoContent();

        $this->assertModelMissing($maintenancePost);
    }
}
