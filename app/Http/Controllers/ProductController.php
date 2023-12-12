<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $request->user()->products()->get();

        return Inertia::render('Product/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'sku' => ['nullable', 'string'],
            'price' => ['required', 'decimal:0,2', 'gt:0'],
            'tax_percentage' => ['integer', 'gte:0'],
        ]);

        $request->user()->products()->create($data);

        return redirect(route('products.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'sku' => ['nullable', 'string'],
            'price' => ['required', 'decimal:0,2', 'gt:0'],
            'tax_percentage' => ['integer', 'gte:0'],
        ]);

        $product->update($data);

        return redirect(route('products.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect(route('products.index'));
    }
}
