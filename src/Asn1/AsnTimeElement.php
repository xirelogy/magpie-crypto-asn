<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Carbon\CarbonInterface;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Packs\PackContext;

/**
 * ASN.1 time related element
 */
abstract class AsnTimeElement extends AsnPrimitiveElement
{
    /**
     * Time value
     * @return CarbonInterface
     * @throws CryptoException
     */
    public abstract function getTime() : CarbonInterface;


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->time = $this->getTime();
    }


    /**
     * @inheritDoc
     */
    protected final function onDumpValue() : string
    {
        return $this->getTime()->format('Y-m-d H:i:s P');
    }


    /**
     * Create an instance
     * @param CarbonInterface $value
     * @return static
     * @throws CryptoException
     */
    public static abstract function create(CarbonInterface $value) : static;
}