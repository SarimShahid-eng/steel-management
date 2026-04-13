<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ExpenseController
 */
final class ExpenseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $expenses = Expense::factory()->count(3)->create();

        $response = $this->get(route('expenses.index'));

        $response->assertOk();
        $response->assertViewIs('expense.index');
        $response->assertViewHas('accounts');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('expenses.create'));

        $response->assertOk();
        $response->assertViewIs('expense.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ExpenseController::class,
            'store',
            \App\Http\Requests\ExpenseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $account = Account::factory()->create();
        $transaction = ::factory()->create();
        $amount = fake()->randomFloat(/** decimal_attributes **/);
        $date = Carbon::parse(fake()->date());

        $response = $this->post(route('expenses.store'), [
            'account_id' => $account->id,
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'date' => $date->toDateString(),
        ]);

        $expenses = Expense::query()
            ->where('account_id', $account->id)
            ->where('transaction_id', $transaction->id)
            ->where('amount', $amount)
            ->where('date', $date)
            ->get();
        $this->assertCount(1, $expenses);
        $expense = $expenses->first();

        $response->assertRedirect(route('expense.index'));
    }
}
