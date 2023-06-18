<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Exception;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Type\BaseString as SopAsn1BaseString;

/**
 * ASN.1 binary data element
 */
abstract class AsnBinaryStringElement extends AsnPrimitiveElement
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
     * Corresponding binary data
     * @return BinaryData
     * @throws CryptoException
     */
    public final function getString() : BinaryData
    {
        $ret = SopAsn1Api::wrapped(fn () => $this->getBinaryString());
        return BinaryData::fromBinary($ret);
    }


    /**
     * Corresponding binary data string
     * @return string
     * @throws Exception
     */
    protected function getBinaryString() : string
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
     * Create a new instance
     * @param BinaryData|string $value
     * @return static
     * @throws CryptoException
     */
    public static abstract function create(BinaryData|string $value) : static;


    /**
     * Accept binary string
     * @param BinaryData|string $value
     * @return string
     */
    protected static final function acceptBinaryString(BinaryData|string $value) : string
    {
        return BinaryData::acceptBinary($value)->asBinary();
    }
}