<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        $products = $query->latest()->paginate(10)->withQueryString();

        return view('product.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(ProductStoreRequest $request)
    {
        $isUpdate = filled($request->update_id);
        $message = $isUpdate ? 'updated' : 'created';
        $validated = $request->validated();
        Product::updateOrCreate([
            'id' => $validated['update_id'],
        ],
            $validated);
        return redirect()->route('product.index')->with('success', 'Product '.$message.' Successfully!');
    }

    public function edit(Product $product)
    {
        return view('product.create', compact('product'));
    }
}
