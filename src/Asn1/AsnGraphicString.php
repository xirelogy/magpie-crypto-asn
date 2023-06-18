<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\GeneralString as SopAsn1GeneralString;
use Sop\ASN1\Type\Primitive\GraphicString as SopAsn1GraphicString;

/**
 * ASN.1 graphic string
 */
#[FactoryTypeClass(AsnGraphicString::TAG, AsnElement::class)]
class AsnGraphicString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'graphic-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_GRAPHIC_STRING;
    /**
     * @var SopAsn1GraphicString Underlying element
     */
    protected readonly SopAsn1GraphicString $elStr;


    /**
     * Constructor
     * @param SopAsn1GraphicString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1GraphicString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asGraphicString(), $context);
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