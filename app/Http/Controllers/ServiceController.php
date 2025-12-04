<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceField;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount(['fields', 'prices'])->latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Store new image
            $path = $request->file('image')->store('profile_photos', 'public');
            $validated['image'] = config('app.url') . '/storage/' . $path;
        }

        Service::create($validated);

        return back()->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                $oldPath = str_replace(config('app.url') . '/storage/', '', $service->image);
                Storage::disk('public')->delete($oldPath);
            }

            // Store new image
            $path = $request->file('image')->store('services', 'public');

            // Save full URL
            $validated['image'] = config('app.url') . '/storage/' . $path;
        }

        $service->update($validated);

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        // Delete image from storage if exists
        if ($service->image) {
            $oldPath = str_replace(config('app.url') . '/storage/', '', $service->image);
            Storage::disk('public')->delete($oldPath);
        }

        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function show(Service $service)
    {
        $service->load(['fields', 'prices.field']);
        return view('services.show', compact('service'));
    }

    // Field Management
    public function storeField(Request $request, Service $service)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'field_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $service->fields()->create($validated);

        return back()->with('success', 'Service field added successfully.');
    }

    public function updateField(Request $request, ServiceField $field)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'field_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $field->update($validated);

        return back()->with('success', 'Service field updated successfully.');
    }

    public function destroyField(ServiceField $field)
    {
        $field->delete();
        return back()->with('success', 'Service field deleted successfully.');
    }

    // Price Management
    public function storePrice(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_fields_id' => 'nullable|exists:service_fields,id',
            'user_type' => 'required|in:personal,agent,partner,business,staff',
            'price' => 'required|numeric|min:0',
        ]);

        $service->prices()->create($validated);

        return back()->with('success', 'Price configuration added successfully.');
    }

    public function updatePrice(Request $request, ServicePrice $price)
    {
        $validated = $request->validate([
            'user_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $price->update($validated);

        return back()->with('success', 'Price configuration updated successfully.');
    }

    public function destroyPrice(ServicePrice $price)
    {
        $price->delete();
        return back()->with('success', 'Price configuration deleted successfully.');
    }
}
