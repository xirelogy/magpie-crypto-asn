<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\BitString as SopAsn1BitString;

/**
 * ASN.1 bit string
 */
#[FactoryTypeClass(AsnBitString::TAG, AsnElement::class)]
class AsnBitString extends AsnBinaryStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'bit-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_BIT_STRING;
    /**
     * @var SopAsn1BitString Underlying element
     */
    protected readonly SopAsn1BitString $elStr;


    /**
     * Constructor
     * @param SopAsn1BitString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1BitString $elStr, ?AsnDecoderContext $context)
    {
        parent::__construct($elStr, $context);

        $this->elStr = $elStr;
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
        return new static($el->asUnspecified()->asBitString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(BinaryData|string $value) : static
    {
        $value = static::acceptBinaryString($value);

        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1BitString($value));

        return new static($elStr, null);
    }
}