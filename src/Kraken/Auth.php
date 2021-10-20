<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use UMA\DCA\Kraken\DTO\QueryPrivate;
use function base64_decode;
use function base64_encode;
use function hash;
use function hash_hmac;
use function http_build_query;

final class Auth
{
    private string $apiKey;
    private NonceGenerator $nonceGenerator;
    private string $secret;

    public function __construct(string $apiKey, string $secret)
    {
        $this->apiKey = $apiKey;
        $this->nonceGenerator = new NonceGenerator();
        $this->secret = $secret;
    }

    public function getQuery(string $method, array $postData): QueryPrivate
    {
        if(!isset($postData['nonce'])) {
            $postData['nonce'] = $this->nonceGenerator->next();
        }

        return new QueryPrivate([
            'API-Key: ' . $this->apiKey,
            'API-Sign: ' . $this->computeSignature($method, $postData)
        ], $postData);
    }

    private function computeSignature(string $method, array $postData): string
    {
        return base64_encode(
            hash_hmac(
                'sha512',
                '/0/private/' . $method . hash(
                    'sha256',
                    $postData['nonce'] . http_build_query($postData),
                    true
                ),
                base64_decode($this->secret),
                true
            )
        );

    }
}
