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

class CheckNINStatusJob implements ShouldQueue
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
                Log::error('CheckNINStatusJob: NIN_API_KEY is missing in .env');
                return;
            }

            $url = 'https://s8v.ng/api/validation/status';
            $payload = [
                'nin' => $this->agentService->nin,
                'token' => $apiKey
            ];

            $response = Http::post($url, $payload);
            $apiResponse = $response->json();

            // Check if response failed but contains record not found error
            if (!$response->successful()) {
                $errorMsg = $apiResponse['error'] ?? $apiResponse['message'] ?? 'API Error';
                if (stripos($errorMsg, 'record not found') !== false) {
                    $this->agentService->update(['comment' => 'Record not found on S8V API']);
                    return;
                }
                throw new \Exception($errorMsg);
            }

            // Clean the API response
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

            // Update the agent service record
            $this->agentService->update($updateData);

            Log::info("CheckNINStatusJob: Successfully updated status for NIN {$this->agentService->nin}", [
                'id' => $this->agentService->id,
                'status' => $this->agentService->status
            ]);

        } catch (\Exception $e) {
            Log::error('CheckNINStatusJob Error: ' . $e->getMessage(), [
                'id' => $this->agentService->id,
                'nin' => $this->agentService->nin
            ]);
        }
    }

    /**
     * Clean API response by removing unwanted characters
     * (Copied from ValidationController for consistency in the job)
     */
    private function cleanApiResponse($response): string
    {
        if (is_array($response)) {
            if (isset($response['message'])) return (string) $response['message'];
            if (isset($response['response'])) return (string) $response['response'];
            
            return collect($response)
                ->map(fn($v, $k) => is_array($v) ? "$k: " . json_encode($v) : "$k: $v")
                ->implode(' | ');
        }

        return trim((string) $response);
    }

    /**
     * Normalize status from various API response formats
     * (Copied from ValidationController for consistency in the job)
     */
    private function normalizeStatus($status): string
    {
        $s = strtolower(trim((string) $status));
        
        return match ($s) {
            'successful', 'success', 'resolved', 'approved', 'in-progress', 'completed' => 'successful',
            'processing', 'pending', 'submitted', 'new' => 'processing',
            'failed', 'rejected', 'error', 'declined', 'invalid', 'no record' => 'failed',
            'query' => 'query',
            'remark' => 'remark',
            default => 'pending',
        };
    }
}
