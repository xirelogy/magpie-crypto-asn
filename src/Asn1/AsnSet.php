<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Constructed\Set as SopAsn1Set;

/**
 * ASN.1 set
 */
#[FactoryTypeClass(AsnSet::TAG, AsnElement::class)]
class AsnSet extends AsnStructureElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'set';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_SET;
    /**
     * @var SopAsn1Set Underlying element
     */
    protected SopAsn1Set $elSet;


    /**
     * Constructor
     * @param SopAsn1Set $elSet
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Set $elSet, ?AsnDecoderContext $context)
    {
        parent::__construct($elSet, $context);

        $this->elSet = $elSet;
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
        return new static($el->asUnspecified()->asSet(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(iterable $elements) : static
    {
        $elSet = SopAsn1Api::wrapped(fn () => new SopAsn1Set(...static::acceptElements($elements)));

        return new static($elSet, null);
    }
}