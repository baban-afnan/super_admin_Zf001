<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DataVariation;
use Exception;

class VariationController extends Controller
{
    /**
     * Refresh and update data variations from VTPass API.
     */
    public function refresh()
    {
        $urls = [
            'https://vtpass.com/api/service-variations?serviceID=waec-registration',
            'https://vtpass.com/api/service-variations?serviceID=waec',
            'https://vtpass.com/api/service-variations?serviceID=jamb',
            'https://vtpass.com/api/service-variations?serviceID=mtn-data',
            'https://vtpass.com/api/service-variations?serviceID=airtel-data',
            'https://vtpass.com/api/service-variations?serviceID=glo-data',
            'https://vtpass.com/api/service-variations?serviceID=etisalat-data',
            'https://vtpass.com/api/service-variations?serviceID=smile-direct',
            'https://vtpass.com/api/service-variations?serviceID=spectranet',
            'https://vtpass.com/api/service-variations?serviceID=dstv',
            'https://vtpass.com/api/service-variations?serviceID=gotv',
            'https://vtpass.com/api/service-variations?serviceID=startimes',
            'https://vtpass.com/api/service-variations?serviceID=showmax',


        ];

        $updated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($urls as $url) {
            try {
                // Fetch API data with timeout
                $response = Http::timeout(20)->get($url);

                if (!$response->ok()) {
                    Log::warning("⚠️ Failed to fetch {$url}. HTTP Code: " . $response->status());
                    $errors++;
                    continue;
                }

                $data = $response->json();

                // Skip invalid services (like '011' errors)
                if (isset($data['code']) && $data['code'] == '011') {
                    Log::info("⏭️ Skipped invalid service from {$url}");
                    $skipped++;
                    continue;
                }

                $content = $data['content'] ?? [];
                $serviceName = $content['ServiceName'] ?? 'Unknown';
                $serviceId = $content['serviceID'] ?? '';
                $fee = $content['convinience_fee'] ?? 0;
                $variations = $content['variations'] ?? $content['varations'] ?? [];

                if (empty($variations)) {
                    Log::info("ℹ️ No variations found for {$serviceName}");
                    continue;
                }

                foreach ($variations as $var) {
                    if (empty($var['variation_code'])) {
                        Log::warning("⚠️ Missing variation_code for service {$serviceName}");
                        continue;
                    }

                    DataVariation::updateOrCreate(
                        ['variation_code' => $var['variation_code']],
                        [
                            'service_name' => $serviceName,
                            'service_id' => $serviceId,
                            'convinience_fee' => $fee,
                            'name' => $var['name'] ?? '',
                            'variation_amount' => $var['variation_amount'] ?? 0,
                            'fixedPrice' => $var['fixedPrice'] ?? 'No',
                            'status' => 'enabled',
                        ]
                    );

                    $updated++;
                }
            } catch (Exception $e) {
                Log::error("❌ Error fetching {$url}: " . $e->getMessage());
                $errors++;
            }
        }

        // Return status message to the dashboard
        return back()->with('status', "✅ {$updated} variations updated. ⏭️ {$skipped} skipped. ⚠️ {$errors} errors logged.");
    }
}
