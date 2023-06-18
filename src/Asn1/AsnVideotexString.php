<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\VideotexString as SopAsn1VideotexString;

/**
 * ASN.1 Videotex (T.100/T.101) string (ASN.1 deprecated type)
 */
#[FactoryTypeClass(AsnVideotexString::TAG, AsnElement::class)]
class AsnVideotexString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'videotex-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_VIDEOTEX_STRING;
    /**
     * @var SopAsn1VideotexString Underlying element
     */
    protected readonly SopAsn1VideotexString $elStr;


    /**
     * Constructor
     * @param SopAsn1VideotexString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1VideotexString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asVideotexString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1VideotexString($value));

        return new static($elStr, null);
    }
}