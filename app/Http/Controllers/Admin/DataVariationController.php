<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataVariation;
use Illuminate\Http\Request;

class DataVariationController extends Controller
{
    /**
     * Standard services available for grouping
     */
    protected $availableServices = [
        'mtn-data' => ['name' => 'MTN Data', 'icon' => 'ti ti-device-mobile', 'color' => 'warning'],
        'airtel-data' => ['name' => 'Airtel Data', 'icon' => 'ti ti-device-mobile', 'color' => 'danger'],
        'glo-data' => ['name' => 'Glo Data', 'icon' => 'ti ti-device-mobile', 'color' => 'success'],
        'etisalat-data' => ['name' => '9mobile Data', 'icon' => 'ti ti-device-mobile', 'color' => 'dark'],
        'waec' => ['name' => 'WAEC PIN', 'icon' => 'ti ti-school', 'color' => 'secondary'],
        'smile-direct' => ['name' => 'Smile Direct', 'icon' => 'ti ti-wifi', 'color' => 'info'],
        'dstv' => ['name' => 'DStv Subscription', 'icon' => 'ti ti-device-tv-old', 'color' => 'primary'],
        'gotv' => ['name' => 'GOtv Subscription', 'icon' => 'ti ti-device-tv-old', 'color' => 'primary'],
        'startimes' => ['name' => 'StarTimes Subscription', 'icon' => 'ti ti-device-tv-old', 'color' => 'primary'],
        'showmax' => ['name' => 'Showmax', 'icon' => 'ti ti-brand-netflix', 'color' => 'danger'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availableServices = $this->availableServices;

        // Get counts for each service using service_id
        $serviceCounts = DataVariation::select('service_id', \DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->pluck('total', 'service_id')
            ->toArray();

        // Stats for the index page
        $totalVariationsCount = DataVariation::count();
        $activeVariationsCount = DataVariation::where('status', 'enabled')->count();
        $inactiveVariationsCount = DataVariation::where('status', 'disabled')->count();

        return view('admin.data-variations.index', compact(
            'availableServices', 
            'serviceCounts', 
            'totalVariationsCount', 
            'activeVariationsCount', 
            'inactiveVariationsCount'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $serviceId)
    {
        if (!isset($this->availableServices[$serviceId])) {
            return redirect()->route('admin.data-variations.index')->with('error', 'Invalid service specified.');
        }

        $serviceName = $this->availableServices[$serviceId]['name'];
        $query = DataVariation::where('service_id', $serviceId);

        // Advanced Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('variation_code', 'like', "%{$search}%")
                  ->orWhere('service_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount')) {
            $query->where('variation_amount', $request->amount);
        }

        $variations = $query->latest()->paginate(15)->withQueryString();

        // Specific Service Stats
        $stats = [
            'total' => DataVariation::where('service_id', $serviceId)->count(),
            'active' => DataVariation::where('service_id', $serviceId)->where('status', 'enabled')->count(),
            'inactive' => DataVariation::where('service_id', $serviceId)->where('status', 'disabled')->count(),
        ];

        return view('admin.data-variations.show', compact('variations', 'serviceId', 'serviceName', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|string',
            'name' => 'required|string|max:255',
            'variation_amount' => 'required|numeric|min:0',
            'variation_code' => 'required|string|unique:data_variations,variation_code',
            'convinience_fee' => 'nullable|numeric|min:0',
        ]);

        $serviceId = $request->service_id;
        $serviceName = $this->availableServices[$serviceId]['name'] ?? 'Generic Data';

        $data = [
            'service_id' => $serviceId,
            'service_name' => $serviceName,
            'name' => $validated['name'],
            'variation_amount' => $validated['variation_amount'],
            'variation_code' => $validated['variation_code'],
            'convinience_fee' => $validated['convinience_fee'] ?? 0,
            'status' => $request->has('status') ? 'enabled' : 'disabled',
            'fixedPrice' => $request->has('fixedPrice') ? 'Yes' : 'No', // Mapping to string as per common practice if field is not boolean
        ];

        DataVariation::create($data);

        return back()->with('success', 'Variation added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataVariation $dataVariation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'variation_amount' => 'required|numeric|min:0',
            'variation_code' => 'required|string|unique:data_variations,variation_code,' . $dataVariation->id,
            'convinience_fee' => 'nullable|numeric|min:0',
            'service_id' => 'nullable|string',
        ]);

        $serviceId = $request->service_id ?? $dataVariation->service_id;
        $serviceName = $this->availableServices[$serviceId]['name'] ?? $dataVariation->service_name;

        $data = [
            'service_id' => $serviceId,
            'service_name' => $serviceName,
            'name' => $validated['name'],
            'variation_amount' => $validated['variation_amount'],
            'variation_code' => $validated['variation_code'],
            'convinience_fee' => $validated['convinience_fee'] ?? 0,
            'status' => $request->has('status') ? 'enabled' : 'disabled',
            'fixedPrice' => $request->has('fixedPrice') ? 'Yes' : 'No',
        ];

        $dataVariation->update($data);

        return back()->with('success', 'Variation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataVariation $dataVariation)
    {
        $dataVariation->delete();
        return back()->with('success', 'Variation deleted successfully.');
    }
}
