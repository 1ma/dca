<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use UMA\DCA\Kraken\DTO\QueryPrivate;

class Auth
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var NonceGenerator
     */
    private $nonceGenerator;

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $apiKey, string $secret)
    {
        $this->apiKey = $apiKey;
        $this->nonceGenerator = new NonceGenerator;
        $this->secret = $secret;
    }

    /**
     * @param string $method
     * @param array $postData
     *
     * @return QueryPrivate
     */
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

    /**
     * @param string $method
     * @param array $postData
     *
     * @return string
     */
    private function computeSignature(string $method, array $postData): string
    {
        return base64_encode(
            hash_hmac(
                'sha512',
                '/0/private/' . $method . hash(
                    'sha256',
                    $postData['nonce'] . http_build_query(
                            $postData,
                            '',
                            '&'
                        ),
                        true
                    ),
                base64_decode($this->secret),
                true
            )
        );

    }
}
