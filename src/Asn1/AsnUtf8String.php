<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\UTF8String as SopAsn1UTF8String;

/**
 * ASN.1 printable string
 */
#[FactoryTypeClass(AsnUtf8String::TAG, AsnElement::class)]
class AsnUtf8String extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'utf8-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_UTF8_STRING;
    /**
     * @var SopAsn1UTF8String Underlying element
     */
    protected readonly SopAsn1UTF8String $elStr;


    /**
     * Constructor
     * @param SopAsn1UTF8String $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1UTF8String $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asUTF8String(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1UTF8String($value));

        return new static($elStr, null);
    }
}