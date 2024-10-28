<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $temp_fields = ['paid_at', 'subtotal', 'subtotal'];
        $invoices = $request
            ->user()
            ->invoices()
            ->select(['id', 'date', 'client_name', 'draft', 'series', 'number', ...$temp_fields])
            ->get()
            ->makeHidden($temp_fields);

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices
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
            'date' => ['required', 'date'],
            'draft' => ['required', 'boolean'],
            'paid' => ['required', 'boolean'],

            'series' => ['required', 'string'],

            'client_id' => ['required', 'integer', 'exists:clients,id'],

            'products.*.quantity' => ['requires', 'integer', 'min:1'],
            'products.*.id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $invoice = $request->user()->invoices()->create($data);

        error_log($invoice);

        return redirect(route('invoices.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
