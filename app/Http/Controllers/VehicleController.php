<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    public function index()
    {
        try {
            $vehicles = Vehicle::latest()->paginate(15);
            return view('vehicles.index', compact('vehicles'));
        } catch (\Throwable $e) {
            Log::error('Vehicles index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data kendaraan.');
        }
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'plate_no' => ['required','string','max:30','unique:vehicles,plate_no'],
                'brand'    => ['nullable','string','max:80'],
                'model'    => ['nullable','string','max:80'],
                'capacity' => ['nullable','integer','min:1'],
                'status'   => ['required','in:available,in_use,maintenance'],
            ]);

            $vehicle = Vehicle::create($data);
            Log::info('Vehicle created', ['vehicle_id' => $vehicle->id]);

            return redirect()->route('vehicles.show', $vehicle)->with('success', 'Kendaraan berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Vehicle store error', ['payload' => $request->all(), 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal membuat kendaraan.');
        }
    }

    public function show(Vehicle $vehicle)
    {
        try {
            $vehicle->load('assignments.order.customer');
            return view('vehicles.show', compact('vehicle'));
        } catch (\Throwable $e) {
            Log::error('Vehicle show error', ['vehicle_id' => $vehicle->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat detail kendaraan.');
        }
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            $data = $request->validate([
                'plate_no' => ['required','string','max:30',"unique:vehicles,plate_no,{$vehicle->id}"],
                'brand'    => ['nullable','string','max:80'],
                'model'    => ['nullable','string','max:80'],
                'capacity' => ['nullable','integer','min:1'],
                'status'   => ['required','in:available,in_use,maintenance'],
            ]);

            $vehicle->update($data);
            Log::info('Vehicle updated', ['vehicle_id' => $vehicle->id]);

            return redirect()->route('vehicles.show', $vehicle)->with('success', 'Kendaraan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Vehicle update error', ['vehicle_id' => $vehicle->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui kendaraan.');
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        try {
            $id = $vehicle->id;
            $vehicle->delete();
            Log::info('Vehicle deleted', ['vehicle_id' => $id]);

            return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Vehicle delete error', ['vehicle_id' => $vehicle->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus kendaraan.');
        }
    }
}
