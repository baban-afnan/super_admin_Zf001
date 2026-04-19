<?php

namespace App\Jobs;

use App\Models\AgentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckIPEStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agentService;

    /**
     * Create a new job instance.
     *
     * @param AgentService $agentService
     */
    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $apiKey = env('NIN_API_KEY');
            if (!$apiKey) {
                Log::error('CheckIPEStatusJob: NIN_API_KEY is missing in .env');
                return;
            }

            $tracking_id = $this->agentService->tracking_id;
            
            if (!$tracking_id) {
                $fieldData = json_decode($this->agentService->field, true);
                $tracking_id = $fieldData['tracking_id'] ?? null;
            }

            if (!$tracking_id) {
                Log::warning("CheckIPEStatusJob: No tracking_id found for Request ID #{$this->agentService->id}");
                return;
            }

            $url = 'https://www.s8v.ng/api/clearance/status';
            $payload = [
                'tracking_id' => $tracking_id,
                'token' => $apiKey
            ];

            $response = Http::post($url, $payload);
            
            if ($response->successful()) {
                $apiResponse = $response->json();

                // Clean the API response for better readability
                $cleanResponse = $this->cleanApiResponse($apiResponse);
                
                // Prepare update data
                $updateData = [
                    'comment' => $cleanResponse,
                ];

                // Determine status from API response
                if (isset($apiResponse['status'])) {
                    $updateData['status'] = $this->normalizeStatus($apiResponse['status']);
                } elseif (isset($apiResponse['response'])) {
                    $updateData['status'] = $this->normalizeStatus($apiResponse['response']);
                }

                $this->agentService->update($updateData);

                Log::info("CheckIPEStatusJob: Successfully updated status for IPE Tracking ID {$tracking_id}", [
                    'id' => $this->agentService->id,
                    'status' => $this->agentService->status
                ]);
            } else {
                Log::warning("CheckIPEStatusJob: API failed for tracking ID: {$tracking_id}", ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('CheckIPEStatusJob Error: ' . $e->getMessage(), [
                'id' => $this->agentService->id,
                'tracking_id' => $tracking_id ?? 'unknown'
            ]);
        }
    }

    /**
     * Clean API response by removing unwanted characters
     */
    private function cleanApiResponse($response): string
    {
        if (is_array($response)) {
            $message = $response['message'] ?? ($response['response'] ?? ($response['status'] ?? null));
            $data = $response['data'] ?? [];
            if (!is_array($data)) $data = [];
            
            $nin = $response['nin'] ?? ($data['nin'] ?? null);
            $reply = $response['reply'] ?? ($data['reply'] ?? null);
            $name = $response['name'] ?? ($data['name'] ?? null);
            $dob = $response['dob'] ?? ($data['dob'] ?? null);

            $parts = [];
            if ($message) $parts[] = (string) $message;
            if ($nin) $parts[] = "NIN: $nin";
            if ($name) $parts[] = "Name: $name";
            if ($dob) $parts[] = "DOB: $dob";
            if ($reply) $parts[] = "Reply: $reply";

            if (!empty($parts)) {
                return implode(' | ', $parts);
            }
            
            return collect($response)
                ->map(fn($v, $k) => is_array($v) ? "$k: " . json_encode($v) : "$k: $v")
                ->implode(' | ');
        }

        return trim((string) $response);
    }

    /**
     * Normalize status from various API response formats
     */
    private function normalizeStatus($status): string
    {
        $s = strtolower(trim((string) $status));
        
        return match ($s) {
            'successful', 'success', 'resolved', 'in-progress', 'approved', 'completed' => 'successful',
            'processing', 'pending', 'submitted', 'new' => 'processing',
            'failed', 'rejected', 'error', 'cancelled', 'declined', 'invalid', 'no record' => 'failed',
            'query' => 'query',
            'remark' => 'remark',
            default => 'pending',
        };
    }
}
