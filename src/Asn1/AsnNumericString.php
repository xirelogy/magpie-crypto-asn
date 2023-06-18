<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\NumericString as SopAsn1NumericString;

/**
 * ASN.1 numeric string
 */
#[FactoryTypeClass(AsnNumericString::TAG, AsnElement::class)]
class AsnNumericString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'numeric-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_NUMERIC_STRING;
    /**
     * @var SopAsn1NumericString Underlying element
     */
    protected readonly SopAsn1NumericString $elStr;


    /**
     * Constructor
     * @param SopAsn1NumericString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1NumericString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asNumericString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1NumericString($value));

        return new static($elStr, null);
    }
}