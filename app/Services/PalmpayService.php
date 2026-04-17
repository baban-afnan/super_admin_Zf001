<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PalmpayService
{
    protected $baseUrl;
    protected $merchantId;
    protected $bearerToken;
    protected $version;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('BASE_URL3', 'https://open-gw-prod.palmpay-inc.com/'), '/');
        $this->merchantId = env('MERCHANTID');
        $this->bearerToken = env('BEARER_TOKEN');
        $this->version = env('VERSION', 'V2.0');
    }

    /**
     * Query the merchant balance from PalmPay
     * 
     * @return array|null
     */
    public function queryBalance()
    {
        $endpoint = $this->baseUrl . '/api/v2/merchant/manage/account/queryBalance';
        
        $requestTime = (int) (microtime(true) * 1000);
        $nonceStr = bin2hex(random_bytes(16));
        
        $body = [
            'requestTime' => $requestTime,
            'version' => $this->version,
            'nonceStr' => $nonceStr,
            'merchantId' => $this->merchantId,
        ];

        $signature = $this->generateSignature($body);

        try {
            Log::info('PalmPay Request Data', ['url' => $endpoint, 'body' => $body, 'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'signature' => $signature,
            ]]);

            $response = Http::withHeaders([
                'Accept' => 'application/json, text/plain, */*',
                'CountryCode' => 'NG',
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'signature' => $signature,
                'Content-Type' => 'application/json',
            ])->post($endpoint, $body);

            Log::info('PalmPay Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['respCode']) && $data['respCode'] === '00000000') {
                    return $data['data'];
                }
                
                Log::error('PalmPay Balance Query Error: ' . ($data['respMsg'] ?? 'Unknown Error'), $data);
            } else {
                Log::error('PalmPay Balance Query Failed: ' . $response->status(), [
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('PalmPay Balance Query Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate the signature for the request body
     * 
     * @param array $body
     * @return string
     */
    protected function generateSignature(array $body)
    {
        // 1. Sort the parameters alphabetically by key (ksort)
        ksort($body);

        // 2. Concatenate parameters into key=value&key=value string
        $params = [];
        foreach ($body as $key => $value) {
            $params[] = $key . '=' . $value;
        }
        $dataToSign = implode('&', $params);

        // 3. PalmPay V2 Requirement: MD5 hash the string and convert to UPPERCASE
        $md5Hash = strtoupper(md5($dataToSign));

        // 4. Load the private key from config
        $rawKey = config('keys.private');
        
        // 5. Ensure PEM formatting
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" . 
                      wordwrap($rawKey, 64, "\n", true) . 
                      "\n-----END PRIVATE KEY-----";

        // 6. Sign the UPPECASE MD5 hash using RSA-SHA1 (SHA1WithRSA)
        $signatureBinary = '';
        $algo = OPENSSL_ALGO_SHA1; // SHA1WithRSA is required for PalmPay V2 signing of the MD5 hash
        
        $keyResource = openssl_get_privatekey($privateKey);
        if (!$keyResource) {
            Log::error('PalmPay Signature: Failed to load private key');
            return '';
        }

        openssl_sign($md5Hash, $signatureBinary, $keyResource, $algo);

        // 7. Base64 encode the binary signature
        return base64_encode($signatureBinary);
    }
}
