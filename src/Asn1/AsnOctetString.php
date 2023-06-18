<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\OctetString as SopAsn1OctetString;

/**
 * ASN.1 octet string
 */
#[FactoryTypeClass(AsnOctetString::TAG, AsnElement::class)]
class AsnOctetString extends AsnBinaryStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'octet-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_OCTET_STRING;
    /**
     * @var SopAsn1OctetString Underlying element
     */
    protected readonly SopAsn1OctetString $elStr;


    /**
     * Constructor
     * @param SopAsn1OctetString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1OctetString $elStr, ?AsnDecoderContext $context)
    {
        parent::__construct($elStr, $context);

        $this->elStr = $elStr;
    }


    /**
     * Treat current binary data as DER content and decode it as element
     * @param AsnDecoderContext|null $context
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function decodeAsDer(?AsnDecoderContext $context = null) : AsnElement
    {
        return AsnElement::decodeFrom($this->getString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function getTypeClass() : string
    {
        return static::TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    public static function getTagClass() : int
    {
        return static::TAG;
    }


    /**
     * @inheritDoc
     */
    protected static function onFromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static
    {
        return new static($el->asUnspecified()->asOctetString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(BinaryData|string $value) : static
    {
        $value = static::acceptBinaryString($value);

        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1OctetString($value));

        return new static($elStr, null);
    }
}