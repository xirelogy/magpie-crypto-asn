<?php

namespace MagpieLib\CryptoAsn\Impls;

use Magpie\Locales\Concepts\Localizable;
use Magpie\Logs\Logger;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Strategies\DefaultAsnDecoderEventHandler;

/**
 * Intermediate ASN decoder event handle
 * @internal
 */
class CommonAsnDecoderEventHandle
{
    /**
     * @var AsnDecoderEventHandleable Sub handle
     */
    protected AsnDecoderEventHandleable $subHandle;


    /**
     * Constructor
     * @param AsnDecoderEventHandleable $subHandle
     */
    protected function __construct(AsnDecoderEventHandleable $subHandle)
    {
        $this->subHandle = $subHandle;
    }


    /**
     * Associated logger
     * @return Logger
     */
    public function getLogger() : Logger
    {
        return $this->subHandle->getLogger();
    }


    /**
     * Warn for unsupported value
     * @param string $value
     * @param string|Localizable $subject
     * @return mixed|null
     */
    public function warnUnsupported(string $value, string|Localizable $subject) : mixed
    {
        $this->subHandle->warnUnsupported($value, $subject);
        return null;
    }


    /**
     * Create an instance
     * @param AsnDecoderEventHandleable|null $subHandle
     * @return static
     */
    public static function create(?AsnDecoderEventHandleable $subHandle) : static
    {
        return new static($subHandle ?? new DefaultAsnDecoderEventHandler());
    }
}