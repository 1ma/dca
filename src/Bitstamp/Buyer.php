<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Bitstamp\Request\BuyOrder;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\Euro;
use UMA\DCA\Model\EuroBuyerInterface;

/**
 * Bitstamp implementation of the BuyerInterface.
 */
class Buyer implements EuroBuyerInterface
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Client
     */
    private $http;

    /**
     * @var ConverterInterface
     */
    private $converter;

    public function __construct(Auth $auth, Client $http, ConverterInterface $converter)
    {
        $this->auth = $auth;
        $this->http = $http;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function buy(Euro $euro): ResponseInterface
    {
        return $this->http->send(
            new BuyOrder($this->auth, $this->converter->EURBTC($euro))
        );
    }
}
