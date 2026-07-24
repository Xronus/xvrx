<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SoapService
{
    private string $url;
    private string $user;
    private string $pass;
    private Client $client;

    public function __construct()
    {
        $this->url  = env('SOAP_URL', 'http://127.0.0.1:7878/soap');
        $this->user = env('SOAP_USER', 'SoapAdmin');
        $this->pass = env('SOAP_PASS', 'Dext5aSd');

        if (empty($this->user) || empty($this->pass)) {
            Log::error('SOAP credentials missing in .env');
            throw new \RuntimeException('SOAP credentials are missing');
        }

        $parsed = parse_url($this->url);
        if (!$parsed || !isset($parsed['host'])) {
            throw new \InvalidArgumentException('Invalid SOAP_URL in .env');
        }

        $baseUri = $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '');

        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout'  => 10,
        ]);
    }

    /**
     * Отправляет команду через SOAP (простой POST с XML)
     */
    public function execute(string $command): string
    {
        $xml = '<?xml version="1.0"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Header/>
  <SOAP-ENV:Body>
    <executeCommand xmlns="urn:TC">
      <command>' . $this->escapeForXml($command) . '</command>
    </executeCommand>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        try {
            $response = $this->client->post('/soap', [
                'headers' => [
                    'Content-Type' => 'text/xml; charset=utf-8',
                    'Authorization' => 'Basic ' . base64_encode($this->user . ':' . $this->pass),
                ],
                'body' => $xml,
            ]);

            $body = (string)$response->getBody();
            Log::info('SOAP executed: ' . $command);
            return $body;
        } catch (\Exception $e) {
            Log::error('SOAP error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('SOAP response body: ' . $e->getResponse()->getBody()->getContents());
            }
            throw $e;
        }
    }

    private function escapeForXml(string $input): string
    {
        return str_replace(
            ['&', '<', '>', '"', "'"],
            ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'],
            $input
        );
    }
}
