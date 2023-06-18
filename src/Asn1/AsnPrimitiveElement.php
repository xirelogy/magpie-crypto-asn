<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Exception;
use Magpie\General\Sugars\Excepts;
use MagpieLib\CryptoAsn\Constants\AsnDisplay;

/**
 * ASN.1 primitive element
 */
abstract class AsnPrimitiveElement extends AsnElement
{
    /**
     * @inheritDoc
     */
    protected final function onDump(int $level) : iterable
    {
        $dumpValue = Excepts::noThrow(fn () => $this->onDumpValue(), AsnDisplay::ERROR);

        yield static::formatDump($level, static::getTypeClass() . ': ' . $dumpValue);
    }


    /**
     * Dump as value
     * @return string
     * @throws Exception
     */
    protected abstract function onDumpValue() : string;
}