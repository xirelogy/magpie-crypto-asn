<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Exception;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Type\BaseString as SopAsn1BaseString;

/**
 * ASN.1 display string element
 */
abstract class AsnDisplayStringElement extends AsnPrimitiveElement
{
    /**
     * @var SopAsn1BaseString Underlying element
     */
    protected readonly SopAsn1BaseString $elBaseStr;


    /**
     * Constructor
     * @param SopAsn1BaseString $elBaseStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1BaseString $elBaseStr, ?AsnDecoderContext $context)
    {
        parent::__construct($elBaseStr, $context);

        $this->elBaseStr = $elBaseStr;
    }


    /**
     * The display string
     * @return string
     * @throws CryptoException
     */
    public final function getString() : string
    {
        return SopAsn1Api::wrapped(fn() => $this->getStringValue());
    }


    /**
     * The display string
     * @return string
     * @throws Exception
     */
    protected function getStringValue() : string
    {
        return $this->elBaseStr->string();
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->string = $this->getString();
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return $this->getString();
    }


    /**
     * Create an instance
     * @param string $value
     * @return static
     * @throws CryptoException
     */
    public static abstract function create(string $value) : static;
}