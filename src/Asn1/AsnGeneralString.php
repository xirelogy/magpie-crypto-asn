<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\GeneralString as SopAsn1GeneralString;

/**
 * ASN.1 general string
 */
#[FactoryTypeClass(AsnGeneralString::TAG, AsnElement::class)]
class AsnGeneralString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'general-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_GENERAL_STRING;
    /**
     * @var SopAsn1GeneralString Underlying element
     */
    protected readonly SopAsn1GeneralString $elStr;


    /**
     * Constructor
     * @param SopAsn1GeneralString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1GeneralString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asGeneralString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1GeneralString($value));

        return new static($elStr, null);
    }
}