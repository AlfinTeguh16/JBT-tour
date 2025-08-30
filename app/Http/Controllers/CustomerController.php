<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::latest()->paginate(15);
            return view('activities.customers.index', compact('customers'));
        } catch (\Throwable $e) {
            Log::error('Customers index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data customer.');
        }
    }

    public function search(Request $request)
    {
        try {
            $q = trim($request->get('q', ''));
            $customers = Customer::when($q, fn($qr) =>
                    $qr->where('name', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                )->paginate(15)->withQueryString();

            return view('customers.index', compact('customers', 'q'));
        } catch (\Throwable $e) {
            Log::error('Customers search error', ['q' => $request->q, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal melakukan pencarian.');
        }
    }

    public function create()
    {
        return view('activities.customers.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'    => ['required','string','max:150'],
                'email'   => ['nullable','email','max:150'],
                'phone'   => ['nullable','string','max:30'],
                'address' => ['nullable','string','max:255'],
            ]);

            $customer = Customer::create($data);
            Log::info('Customer created', ['customer_id' => $customer->id]);

            return redirect()->route('customers.show', $customer)->with('success', 'Customer berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Customer store error', ['payload' => $request->all(), 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal membuat customer.');
        }
    }

    public function show(Customer $customer)
    {
        try {
            $customer->load('orders');
            return view('activities.customers.show', compact('customer'));
        } catch (\Throwable $e) {
            Log::error('Customer show error', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat detail customer.');
        }
    }

    public function edit(Customer $customer)
    {
        return view('activities.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $data = $request->validate([
                'name'    => ['required','string','max:150'],
                'email'   => ['nullable','email','max:150'],
                'phone'   => ['nullable','string','max:30'],
                'address' => ['nullable','string','max:255'],
            ]);

            $customer->update($data);
            Log::info('Customer updated', ['customer_id' => $customer->id]);

            return redirect()->route('customers.show', $customer)->with('success', 'Customer berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Customer update error', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui customer.');
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $id = $customer->id;
            $customer->delete();
            Log::info('Customer deleted', ['customer_id' => $id]);

            return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Customer delete error', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus customer.');
        }
    }
}
