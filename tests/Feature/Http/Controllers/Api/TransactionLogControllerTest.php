<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Plaza;
use App\Models\RelatedResource;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\TransactionLogController
 */
final class TransactionLogControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $transactionLogs = TransactionLog::factory()->count(3)->create();

        $response = $this->get(route('transaction-logs.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\TransactionLogController::class,
            'store',
            \App\Http\Requests\Api\TransactionLogControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $plaza = Plaza::factory()->create();
        $transaction_type = fake()->randomElement(/** enum_attributes **/);
        $amount = fake()->randomFloat(/** decimal_attributes **/);
        $description = fake()->text();
        $balance_before = fake()->randomFloat(/** decimal_attributes **/);
        $balance_after = fake()->randomFloat(/** decimal_attributes **/);
        $recorded_by = User::factory()->create();
        $related_resource_type = fake()->randomElement(/** enum_attributes **/);
        $related_resource = RelatedResource::factory()->create();

        $response = $this->post(route('transaction-logs.store'), [
            'plaza_id' => $plaza->id,
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'description' => $description,
            'balance_before' => $balance_before,
            'balance_after' => $balance_after,
            'recorded_by' => $recorded_by->id,
            'related_resource_type' => $related_resource_type,
            'related_resource_id' => $related_resource->id,
        ]);

        $transactionLogs = TransactionLog::query()
            ->where('plaza_id', $plaza->id)
            ->where('transaction_type', $transaction_type)
            ->where('amount', $amount)
            ->where('description', $description)
            ->where('balance_before', $balance_before)
            ->where('balance_after', $balance_after)
            ->where('recorded_by', $recorded_by->id)
            ->where('related_resource_type', $related_resource_type)
            ->where('related_resource_id', $related_resource->id)
            ->get();
        $this->assertCount(1, $transactionLogs);
        $transactionLog = $transactionLogs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $transactionLog = TransactionLog::factory()->create();

        $response = $this->get(route('transaction-logs.show', $transactionLog));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\TransactionLogController::class,
            'update',
            \App\Http\Requests\Api\TransactionLogControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $transactionLog = TransactionLog::factory()->create();
        $plaza = Plaza::factory()->create();
        $transaction_type = fake()->randomElement(/** enum_attributes **/);
        $amount = fake()->randomFloat(/** decimal_attributes **/);
        $description = fake()->text();
        $balance_before = fake()->randomFloat(/** decimal_attributes **/);
        $balance_after = fake()->randomFloat(/** decimal_attributes **/);
        $recorded_by = User::factory()->create();
        $related_resource_type = fake()->randomElement(/** enum_attributes **/);
        $related_resource = RelatedResource::factory()->create();

        $response = $this->put(route('transaction-logs.update', $transactionLog), [
            'plaza_id' => $plaza->id,
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'description' => $description,
            'balance_before' => $balance_before,
            'balance_after' => $balance_after,
            'recorded_by' => $recorded_by->id,
            'related_resource_type' => $related_resource_type,
            'related_resource_id' => $related_resource->id,
        ]);

        $transactionLog->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($plaza->id, $transactionLog->plaza_id);
        $this->assertEquals($transaction_type, $transactionLog->transaction_type);
        $this->assertEquals($amount, $transactionLog->amount);
        $this->assertEquals($description, $transactionLog->description);
        $this->assertEquals($balance_before, $transactionLog->balance_before);
        $this->assertEquals($balance_after, $transactionLog->balance_after);
        $this->assertEquals($recorded_by->id, $transactionLog->recorded_by);
        $this->assertEquals($related_resource_type, $transactionLog->related_resource_type);
        $this->assertEquals($related_resource->id, $transactionLog->related_resource_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $transactionLog = TransactionLog::factory()->create();

        $response = $this->delete(route('transaction-logs.destroy', $transactionLog));

        $response->assertNoContent();

        $this->assertModelMissing($transactionLog);
    }
}
