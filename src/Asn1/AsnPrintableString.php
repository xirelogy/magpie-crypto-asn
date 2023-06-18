<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\PrintableString as SopAsn1PrintableString;

/**
 * ASN.1 printable string
 */
#[FactoryTypeClass(AsnPrintableString::TAG, AsnElement::class)]
class AsnPrintableString extends AsnDisplayStringElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'printable-string';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_PRINTABLE_STRING;
    /**
     * @var SopAsn1PrintableString Underlying element
     */
    protected readonly SopAsn1PrintableString $elStr;


    /**
     * Constructor
     * @param SopAsn1PrintableString $elStr
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1PrintableString $elStr, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asPrintableString(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(string $value) : static
    {
        $elStr = SopAsn1Api::wrapped(fn () => new SopAsn1PrintableString($value));

        return new static($elStr, null);
    }
}