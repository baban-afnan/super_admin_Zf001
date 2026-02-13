<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SmeDataController extends Controller
{
    /**
     * Standard networks available for grouping
     */
    protected $availableNetworks = [
        'MTN' => ['name' => 'MTN SME', 'icon' => 'ti ti-device-mobile', 'color' => 'warning'],
        'AIRTEL' => ['name' => 'Airtel SME', 'icon' => 'ti ti-device-mobile', 'color' => 'danger'],
        'GLO' => ['name' => 'Glo SME', 'icon' => 'ti ti-device-mobile', 'color' => 'success'],
        '9MOBILE' => ['name' => '9mobile SME', 'icon' => 'ti ti-device-mobile', 'color' => 'dark'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availableNetworks = $this->availableNetworks;

        // Get counts for each network
        $networkCounts = SmeData::select('network', DB::raw('count(*) as total'))
            ->groupBy('network')
            ->pluck('total', 'network')
            ->toArray();

        // Stats for the index page
        $totalVariationsCount = SmeData::count();
        $activeVariationsCount = SmeData::where('status', 'enabled')->count();
        $inactiveVariationsCount = SmeData::where('status', 'disabled')->count();

        return view('admin.sme-data.index', compact(
            'availableNetworks', 
            'networkCounts', 
            'totalVariationsCount', 
            'activeVariationsCount', 
            'inactiveVariationsCount'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $network)
    {
        $networkKey = strtoupper($network);
        if (!isset($this->availableNetworks[$networkKey])) {
            return redirect()->route('admin.sme-data.index')->with('error', 'Invalid network specified.');
        }

        $networkName = $this->availableNetworks[$networkKey]['name'];
        $query = SmeData::where('network', $networkKey);

        // Advanced Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('size', 'like', "%{$search}%")
                  ->orWhere('data_id', 'like', "%{$search}%")
                  ->orWhere('plan_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount')) {
            $query->where('amount', $request->amount);
        }

        $variations = $query->latest()->paginate(15)->withQueryString();

        // Specific Stats
        $stats = [
            'total' => SmeData::where('network', $networkKey)->count(),
            'active' => SmeData::where('network', $networkKey)->where('status', 'enabled')->count(),
            'inactive' => SmeData::where('network', $networkKey)->where('status', 'disabled')->count(),
        ];

        return view('admin.sme-data.show', compact('variations', 'network', 'networkName', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'network' => 'required|string',
            'data_id' => 'required|string|unique:sme_datas,data_id',
            'plan_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'size' => 'required|string|max:255',
            'validity' => 'required|string|max:255',
        ]);

        $data = $validated;
        $data['status'] = $request->has('status') ? 'enabled' : 'disabled';

        SmeData::create($data);

        return back()->with('success', 'SME Data plan added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmeData $smeData)
    {
        $validated = $request->validate([
            'data_id' => 'required|string|unique:sme_datas,data_id,' . $smeData->id,
            'plan_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'size' => 'required|string|max:255',
            'validity' => 'required|string|max:255',
        ]);

        $data = $validated;
        $data['status'] = $request->has('status') ? 'enabled' : 'disabled';

        $smeData->update($data);

        return back()->with('success', 'SME Data plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmeData $smeData)
    {
        $smeData->delete();
        return back()->with('success', 'SME Data plan deleted successfully.');
    }

    /**
     * Download a sample Excel file for plan import.
     */
    public function downloadSample()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Headers
        $headers = ['Data ID', 'Network', 'Plan Type', 'Amount', 'Size', 'Validity', 'Status'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
            $sheet->getStyle(chr(65 + $index) . '1')->getFont()->setBold(true);
        }

        // Add Example Row
        $example = ['MTN_500MB', 'MTN', 'SME', '250', '500MB', '30 Days', 'enabled'];
        foreach ($example as $index => $value) {
            $sheet->setCellValue(chr(65 + $index) . '2', $value);
        }

        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'sme_plans_import_sample.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import plans from Excel/CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Check if file is empty
            if (count($rows) <= 1) {
                return back()->with('error', 'The uploaded file is empty or missing data rows.');
            }

            $header = array_shift($rows); // Remove header row
            $count = 0;
            $updated = 0;

            DB::beginTransaction();

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) continue;

                // Expected columns: Data ID (0), Network (1), Plan Type (2), Amount (3), Size (4), Validity (5), Status (6)
                if (count($row) < 6) continue;

                $data = [
                    'data_id' => trim($row[0]),
                    'network' => strtoupper(trim($row[1])),
                    'plan_type' => trim($row[2]),
                    'amount' => (float)$row[3],
                    'size' => trim($row[4]),
                    'validity' => trim($row[5]),
                    'status' => isset($row[6]) ? strtolower(trim($row[6])) : 'enabled',
                ];

                // Validate network
                if (!isset($this->availableNetworks[$data['network']])) {
                    continue;
                }

                // Update or Create
                $plan = SmeData::where('data_id', $data['data_id'])->first();
                if ($plan) {
                    $plan->update($data);
                    $updated++;
                } else {
                    SmeData::create($data);
                    $count++;
                }
            }

            DB::commit();

            return back()->with('success', "$count plans imported and $updated plans updated successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred during import: ' . $e->getMessage());
        }
    }

    /**
     * Delete all data plans from the table.
     */
    public function deleteAll()
    {
        try {
            SmeData::truncate();
            return back()->with('success', 'All SME data plans have been deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting plans: ' . $e->getMessage());
        }
    }
}
