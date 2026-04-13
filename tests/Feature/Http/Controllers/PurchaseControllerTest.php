<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Purchase;
use App\Models\SupplierAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PurchaseController
 */
final class PurchaseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $purchases = Purchase::factory()->count(3)->create();

        $response = $this->get(route('purchases.index'));

        $response->assertOk();
        $response->assertViewIs('purchase.index');
        $response->assertViewHas('purchases', $purchases);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('purchases.create'));

        $response->assertOk();
        $response->assertViewIs('purchase.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PurchaseController::class,
            'store',
            \App\Http\Requests\PurchaseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $supplier_account = SupplierAccount::factory()->create();
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $date = Carbon::parse(fake()->date());

        $response = $this->post(route('purchases.store'), [
            'supplier_account_id' => $supplier_account->id,
            'total_amount' => $total_amount,
            'date' => $date,
        ]);

        $purchases = Purchase::query()
            ->where('supplier_account_id', $supplier_account->id)
            ->where('total_amount', $total_amount)
            ->where('date', $date)
            ->get();
        $this->assertCount(1, $purchases);
        $purchase = $purchases->first();

        $response->assertRedirect(route('purchase.index'));
    }
}
