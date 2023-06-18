<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\IA5String as SopAsn1IA5String;

/**
 * ASN.1 IA5 string
 */
#[FactoryTypeClass(AsnIa5String::TAG, AsnElement::class)]
class AsnIa5String extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'ia5-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_IA5_STRING;
    /**
     * @var SopAsn1IA5String Underlying element
     */
    protected readonly SopAsn1IA5String $elStr;


    /**
     * Constructor
     * @param SopAsn1IA5String $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1IA5String $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asIA5String(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1IA5String($value));

        return new static($elStr, null);
    }
}