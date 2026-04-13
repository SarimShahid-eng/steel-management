<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SaleController
 */
final class SaleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $sales = Sale::factory()->count(3)->create();

        $response = $this->get(route('sales.index'));

        $response->assertOk();
        $response->assertViewIs('sale.index');
        $response->assertViewHas('sales', $sales);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('sales.create'));

        $response->assertOk();
        $response->assertViewIs('sale.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SaleController::class,
            'store',
            \App\Http\Requests\SaleStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $customer_account = CustomerAccount::factory()->create();
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $date = Carbon::parse(fake()->date());

        $response = $this->post(route('sales.store'), [
            'customer_account_id' => $customer_account->id,
            'total_amount' => $total_amount,
            'date' => $date,
        ]);

        $sales = Sale::query()
            ->where('customer_account_id', $customer_account->id)
            ->where('total_amount', $total_amount)
            ->where('date', $date)
            ->get();
        $this->assertCount(1, $sales);
        $sale = $sales->first();

        $response->assertRedirect(route('sale.index'));
    }
}
