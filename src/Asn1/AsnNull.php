<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\NullType as SopAsn1NullType;

/**
 * ASN.1 null
 */
#[FactoryTypeClass(AsnNull::TAG, AsnElement::class)]
class AsnNull extends AsnPrimitiveElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'null';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_NULL;
    /**
     * @var SopAsn1NullType Underlying element
     */
    protected readonly SopAsn1NullType $elNull;


    /**
     * Constructor
     * @param SopAsn1NullType $elNull
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1NullType $elNull, ?AsnDecoderContext $context)
    {
        parent::__construct($elNull, $context);

        $this->elNull = $elNull;
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return '';
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
        return new static($el->asUnspecified()->asNull(), $context);
    }


    /**
     * Create a new instance
     * @return static
     * @throws CryptoException
     */
    public static function create() : static
    {
        $elNull = SopAsn1Api::wrapped(fn () => new SopAsn1NullType());

        return new static($elNull, null);
    }
}