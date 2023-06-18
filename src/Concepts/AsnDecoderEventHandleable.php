<?php

namespace MagpieLib\CryptoAsn\Concepts;

use Magpie\Locales\Concepts\Localizable;
use Magpie\Logs\Logger;

/**
 * May handle events during ASN decoding
 */
interface AsnDecoderEventHandleable
{
    /**
     * Associated logger
     * @return Logger
     */
    public function getLogger() : Logger;


    /**
     * Warn for unsupported value
     * @param string $value
     * @param string|Localizable $subject
     * @return void
     */
    public function warnUnsupported(string $value, string|Localizable $subject) : void;
}