<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

class Auth
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $customerId;

    /**
     * @var NonceGenerator
     */
    private $nonceGenerator;

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $apiKey, string $customerId, string $secret)
    {
        $this->apiKey = $apiKey;
        $this->customerId = $customerId;
        $this->nonceGenerator = new NonceGenerator;
        $this->secret = $secret;
    }

    /**
     * @example
     * [
     *   'key' => '1234',
     *   'signature' => 'A3011914998470DA8C1B14F47A237A1C8428F93CBC6B98A76C84561FC929F024',
     *   'nonce' => '1500000000000000'
     * ]
     */
    public function getCredentials(): array
    {
        $nonce = $this->nonceGenerator->next();

        return [
            'key' => $this->apiKey,
            'signature' => $this->computeSignature($nonce),
            'nonce' => (string) $nonce
        ];
    }

    private function computeSignature(int $nonce): string
    {
        return strtoupper(
            hash_hmac(
                'sha256',
                $nonce . $this->customerId . $this->apiKey,
                $this->secret
            )
        );
    }
}
