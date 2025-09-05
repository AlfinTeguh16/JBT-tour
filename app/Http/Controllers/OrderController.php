<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $q = $request->get('q', '');  

            if (in_array($user->role, ['admin', 'staff'])) {
                $orders = Order::with(['customer', 'assignment.driver', 'assignment.guide', 'assignment.vehicle'])
                    ->where(function($query) use ($q) {
                        if ($q) {
                            $query->where('pickup_location', 'like', "%$q%")
                                ->orWhere('dropoff_location', 'like', "%$q%")
                                ->orWhereHas('customer', function ($query) use ($q) {
                                    $query->where('name', 'like', "%$q%");
                                });
                        }
                    })
                    ->latest('requested_at')
                    ->paginate(15);
            } else {
                $orders = Order::whereHas('assignment', function ($q) use ($user) {
                        $field = $user->role === 'driver' ? 'driver_id' : 'guide_id';
                        $q->where($field, $user->id);
                    })
                    ->with(['customer', 'assignment.driver', 'assignment.guide', 'assignment.vehicle'])
                    ->where(function($query) use ($q) {
                        if ($q) {
                            $query->where('pickup_location', 'like', "%$q%")
                                ->orWhere('dropoff_location', 'like', "%$q%")
                                ->orWhereHas('customer', function ($query) use ($q) {
                                    $query->where('name', 'like', "%$q%");
                                });
                        }
                    })
                    ->latest('requested_at')
                    ->paginate(15);
            }

            return view('activities.orders.index', compact('orders', 'q'));

        } catch (\Throwable $e) {
            Log::error('Orders index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data order.');
        }
    }


    public function search(Request $request)
    {
        try {
            $q = trim($request->get('q', ''));
            $orders = Order::with('customer')
                ->when($q, function ($qr) use ($q) {
                    $qr->where('pickup_location', 'like', "%{$q}%")
                       ->orWhere('dropoff_location', 'like', "%{$q}%")
                       ->orWhereHas('customer', fn($c) => $c->where('name','like',"%{$q}%"));
                })
                ->latest('requested_at')->paginate(15)->withQueryString();

            return view('activities.orders.index', compact('orders', 'q'));
        } catch (\Throwable $e) {
            Log::error('Orders search error', ['q' => $request->q, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal melakukan pencarian.');
        }
    }

    public function create()
    {
        try {
            $customers = Customer::orderBy('name')->get();
            return view('activities.orders.create', compact('customers'));
        } catch (\Throwable $e) {
            Log::error('Orders create error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form pembuatan order.');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $data = $request->validate([
                'customer_id'      => ['required','exists:customers,id'],
                'requested_at'     => ['required','date'],
                'service_date'     => ['nullable','date'],
                'pickup_location'  => ['nullable','string','max:255'],
                'dropoff_location' => ['nullable','string','max:255'],
                'notes'            => ['nullable','string'],
                'status'           => ['nullable','in:pending,assigned,in_progress,completed,cancelled'],
            ]);

            $data['status'] = $data['status'] ?? 'pending';
            $order = Order::create($data);
            Log::info('Order created', ['order_id' => $order->id]);

            return redirect()->route('orders.show', $order)->with('success', 'Order berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Order store error', ['payload' => $request->all(), 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal membuat order.');
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load(['customer','assignment.driver','assignment.guide','assignment.vehicle']);
            return view('activities.orders.show', compact('order'));
        } catch (\Throwable $e) {
            Log::error('Order show error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat detail order.');
        }
    }

    public function edit(Order $order)
    {
        try {
            $customers = Customer::orderBy('name')->get();
            return view('activities.orders.edit', compact('order','customers'));
        } catch (\Throwable $e) {
            Log::error('Order edit error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form edit.');
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            $data = $request->validate([
                'customer_id'      => ['required','exists:customers,id'],
                'requested_at'     => ['required','date'],
                'service_date'     => ['nullable','date'],
                'pickup_location'  => ['nullable','string','max:255'],
                'dropoff_location' => ['nullable','string','max:255'],
                'notes'            => ['nullable','string'],
                'status'           => ['required','in:pending,assigned,in_progress,completed,cancelled'],
            ]);

            $order->update($data);
            Log::info('Order updated', ['order_id' => $order->id]);

            return redirect()->route('orders.show', $order)->with('success', 'Order berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Order update error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui order.');
        }
    }

    public function destroy(Order $order)
    {
        try {
            $id = $order->id;
            $order->delete();
            Log::info('Order deleted', ['order_id' => $id]);

            return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Order delete error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus order.');
        }
    }
}
