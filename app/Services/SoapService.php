<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;

class SoapService
{
    private string $host;

    private int $port;

    private string $user;

    private string $password;

    private string $uri;

    private int $timeout;

    public function __construct()
    {
        $this->host = config('soap.host', '127.0.0.1');
        $this->port = (int) config('soap.port', 7878);
        $this->user = config('soap.user', '');
        $this->password = config('soap.password', '');
        $this->uri = config('soap.uri', 'urn:AC');
        $this->timeout = (int) config('soap.timeout', 5);
    }

    public function executeCommand(string $command): array
    {
        if (! extension_loaded('soap')) {
            return [
                'ok' => false,
                'error' => 'SOAP extension not loaded',
                'result' => null,
            ];
        }

        try {
            $options = [
                'location' => 'http://' . $this->host . ':' . $this->port . '/',
                'uri' => $this->uri,
                'connection_timeout' => $this->timeout,
                'exceptions' => true,
                'trace' => true,
            ];

            // Only add auth if credentials are set
            if ($this->user !== '' && $this->password !== '') {
                $options['login'] = $this->user;
                $options['password'] = $this->password;
            }

            $client = new SoapClient(null, $options);

            $result = $client->__soapCall('executeCommand', [
                ['command' => $command],
            ]);

            Log::debug('SOAP command executed', [
                'command' => $command,
                'result' => $result,
            ]);

            return [
                'ok' => true,
                'error' => null,
                'result' => $result,
            ];
        } catch (SoapFault $e) {
            Log::error('SOAP fault: ' . $e->getMessage(), [
                'command' => $command,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'ok' => false,
                'error' => $e->getMessage(),
                'result' => null,
            ];
        }
    }
}
