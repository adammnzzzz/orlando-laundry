<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 25);

        $customers = Customer::latest()
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer ditambahkan');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer diperbarui');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer dihapus');
    }
}
