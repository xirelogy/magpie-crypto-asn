<?php

namespace MagpieLib\CryptoAsn\Strategies;

use Magpie\Locales\Concepts\Localizable;
use Magpie\Logs\Logger;
use Magpie\Logs\Loggers\DummyLogger;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * Default implementation to handle events during ASN decoding
 */
class DefaultAsnDecoderEventHandler implements AsnDecoderEventHandleable
{
    protected Logger $logger;


    /**
     * Constructor
     * @param Logger|null $logger
     */
    public function __construct(?Logger $logger = null)
    {
        $this->logger = $logger ?? new DummyLogger();
    }


    /**
     * @inheritDoc
     */
    public function getLogger() : Logger
    {
        return $this->logger;
    }


    /**
     * @inheritDoc
     */
    public function warnUnsupported(string $value, Localizable|string $subject) : void
    {

    }
}