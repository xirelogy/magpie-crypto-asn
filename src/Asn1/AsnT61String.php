<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\T61String as SopAsn1T61String;

/**
 * ASN.1 T61 string
 */
#[FactoryTypeClass(AsnT61String::TAG, AsnElement::class)]
class AsnT61String extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 't61-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_T61_STRING;
    /**
     * @var SopAsn1T61String Underlying element
     */
    protected readonly SopAsn1T61String $elStr;


    /**
     * Constructor
     * @param SopAsn1T61String $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1T61String $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asT61String(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1T61String($value));

        return new static($elStr, null);
    }
}