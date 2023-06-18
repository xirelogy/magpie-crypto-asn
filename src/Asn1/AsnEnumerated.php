<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\Enumerated as SopAsn1Enumerated;

/**
 * ASN.1 enumerated value
 */
#[FactoryTypeClass(AsnEnumerated::TAG, AsnElement::class)]
class AsnEnumerated extends AsnPrimitiveElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'enumerated';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_ENUMERATED;
    /**
     * @var SopAsn1Enumerated Underlying element
     */
    protected readonly SopAsn1Enumerated $elEnum;


    /**
     * Constructor
     * @param SopAsn1Enumerated $elEnum
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Enumerated $elEnum, ?AsnDecoderContext $context)
    {
        parent::__construct($elEnum, $context);

        $this->elEnum = $elEnum;
    }


    /**
     * Corresponding integer number value
     * @return int
     * @throws CryptoException
     */
    public function getIntegerValue() : int
    {
        return SopAsn1Api::wrapped(fn () => $this->elEnum->intNumber());
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->intValue = $this->getIntegerValue();
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return $this->getIntegerValue();
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
        return new static($el->asUnspecified()->asEnumerated(), $context);
    }


    /**
     * Create an instance
     * @param int $value
     * @return static
     * @throws CryptoException
     */
    public static function create(int $value) : static
    {
        $elEnum = SopAsn1Api::wrapped(fn () => new SopAsn1Enumerated($value));

        return new static($elEnum, null);
    }
}