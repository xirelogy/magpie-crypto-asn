<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\UniversalString as SopAsn1UniversalString;

/**
 * ASN.1 universal string
 */
#[FactoryTypeClass(AsnUniversalString::TAG, AsnElement::class)]
class AsnUniversalString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'universal-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_UNIVERSAL_STRING;
    /**
     * @var SopAsn1UniversalString Underlying element
     */
    protected readonly SopAsn1UniversalString $elStr;


    /**
     * Constructor
     * @param SopAsn1UniversalString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1UniversalString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asUniversalString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1UniversalString($value));

        return new static($elStr, null);
    }
}