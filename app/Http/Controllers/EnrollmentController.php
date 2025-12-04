<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EnrollmentController extends Controller
{
    const ALLOWED_STATUSES = [
        'pending','ongoing','failed','rejected','successful'
    ];

    public function index(Request $request)
    {
        $query = Enrollment::query();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('TICKET_NUMBER', 'like', "%$s%")
                  ->orWhere('BVN', 'like', "%$s%")
                  ->orWhere('AGENT_NAME', 'like', "%$s%");
            });
        }

        if ($request->status && in_array($request->status, self::ALLOWED_STATUSES)) {
            $query->where('validation_status', $request->status);
        }

        $data = $query->paginate(15);

        $status = [
            'total'      => Enrollment::count(),
            'successful' => Enrollment::where('validation_status', 'successful')->count(),
            'rejected'   => Enrollment::where('validation_status', 'rejected')->count(),
            'failed'     => Enrollment::where('validation_status', 'failed')->count(),
            'pending'    => Enrollment::where('validation_status', 'pending')->count(),
            'ongoing'    => Enrollment::where('validation_status', 'ongoing')->count(),
        ];

        return view('enrollments', compact('data', 'status'));
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120'
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            DB::transaction(function () use ($rows) {
                foreach ($rows as $i => $row) {
                    if ($i == 1) continue;

                    $ticket = trim($row['A'] ?? '');

                    if (!$ticket) continue;

                    $status = strtolower(trim($row['L'] ?? ''));
                    if (!in_array($status, self::ALLOWED_STATUSES)) {
                        $status = 'pending';
                    }

                    Enrollment::updateOrCreate(
                        ['TICKET_NUMBER' => $ticket],
                        [
                            'BVN' => trim($row['B'] ?? ''),
                            'AGT_MGT_INST_NAME' => trim($row['C'] ?? ''),
                            'AGT_MGT_INST_CODE' => trim($row['D'] ?? ''),
                            'AGENT_NAME' => trim($row['E'] ?? ''),
                            'AGENT_CODE' => trim($row['F'] ?? ''),
                            'ENROLLER_CODE' => trim($row['G'] ?? ''),
                            'LATITUDE' => trim($row['H'] ?? ''),
                            'LONGITUDE' => trim($row['I'] ?? ''),
                            'FINGER_PRINT_SCANNER' => trim($row['J'] ?? ''),
                            'BMS_IMPORT_ID' => trim($row['K'] ?? ''),
                            'VALIDATION_STATUS' => $status,
                            'VALIDATION_MESSAGE' => trim($row['M'] ?? ''),
                            'AMOUNT' => trim($row['N'] ?? ''),
                            'CAPTURE_DATE' => trim($row['O'] ?? ''),
                            'SYNC_DATE' => trim($row['P'] ?? ''),
                            'VALIDATION_DATE' => trim($row['Q'] ?? ''),
                        ]
                    );
                }
            });

            return back()->with('success', 'File uploaded successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: '.$e->getMessage());
        }
    }
}
