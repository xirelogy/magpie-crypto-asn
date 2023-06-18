<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\GeneralizedTime as SopAsn1GeneralizedTime;

/**
 * ASN.1 Generalized time
 */
#[FactoryTypeClass(AsnGeneralizedTime::TAG, AsnElement::class)]
class AsnGeneralizedTime extends AsnTimeElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'generalized-time';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_GENERALIZED_TIME;
    /**
     * @var SopAsn1GeneralizedTime Underlying element
     */
    protected readonly SopAsn1GeneralizedTime $elTime;


    /**
     * Constructor
     * @param SopAsn1GeneralizedTime $elTime
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1GeneralizedTime $elTime, ?AsnDecoderContext $context)
    {
        parent::__construct($elTime, $context);

        $this->elTime = $elTime;
    }


    /**
     * @inheritDoc
     */
    public function getTime() : CarbonInterface
    {
        return SopAsn1Api::wrapped(fn () => CarbonImmutable::parse($this->elTime->dateTime()));
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
        return new static($el->asUnspecified()->asGeneralizedTime(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(CarbonInterface $value) : static
    {
        $elTime = SopAsn1Api::wrapped(fn () => new SopAsn1GeneralizedTime($value->toDateTimeImmutable()));

        return new static($elTime, null);
    }
}