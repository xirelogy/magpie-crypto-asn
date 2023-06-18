<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Constructed\Sequence as SopAsn1Sequence;

/**
 * ASN.1 sequence
 */
#[FactoryTypeClass(AsnSequence::TAG, AsnElement::class)]
class AsnSequence extends AsnStructureElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'sequence';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_SEQUENCE;
    /**
     * @var SopAsn1Sequence Underlying element
     */
    protected readonly SopAsn1Sequence $elSeq;


    /**
     * Constructor
     * @param SopAsn1Sequence $elSeq
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Sequence $elSeq, ?AsnDecoderContext $context)
    {
        parent::__construct($elSeq, $context);

        $this->elSeq = $elSeq;
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
        return new static($el->asUnspecified()->asSequence(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(iterable $elements) : static
    {
        $elSeq = SopAsn1Api::wrapped(fn () => new SopAsn1Sequence(...static::acceptElements($elements)));

        return new static($elSeq, null);
    }
}