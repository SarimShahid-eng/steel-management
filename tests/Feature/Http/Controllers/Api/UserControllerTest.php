<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\UserController
 */
final class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\UserController::class,
            'store',
            \App\Http\Requests\Api\UserControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $email = fake()->safeEmail();
        $password = fake()->password();
        $full_name = fake()->word();
        $role = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('users.store'), [
            'email' => $email,
            'password' => $password,
            'full_name' => $full_name,
            'role' => $role,
        ]);

        $users = User::query()
            ->where('email', $email)
            ->where('password', $password)
            ->where('full_name', $full_name)
            ->where('role', $role)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\UserController::class,
            'update',
            \App\Http\Requests\Api\UserControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $user = User::factory()->create();
        $email = fake()->safeEmail();
        $password = fake()->password();
        $full_name = fake()->word();
        $role = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('users.update', $user), [
            'email' => $email,
            'password' => $password,
            'full_name' => $full_name,
            'role' => $role,
        ]);

        $user->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($full_name, $user->full_name);
        $this->assertEquals($role, $user->role);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
