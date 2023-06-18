<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\UTCTime as SopAsn1UTCTime;

/**
 * ASN.1 UTC time
 */
#[FactoryTypeClass(AsnUtcTime::TAG, AsnElement::class)]
class AsnUtcTime extends AsnTimeElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'utc-time';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_UTC_TIME;
    /**
     * @var SopAsn1UTCTime Underlying element
     */
    protected readonly SopAsn1UTCTime $elTime;


    /**
     * Constructor
     * @param SopAsn1UTCTime $elTime
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1UTCTime $elTime, ?AsnDecoderContext $context)
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
        return new static($el->asUnspecified()->asUTCTime(), $context);
    }


    /**
     * @inheritDoc
     */
    public static function create(CarbonInterface $value) : static
    {
        $elTime = SopAsn1Api::wrapped(fn () => new SopAsn1UTCTime($value->toDateTimeImmutable()));

        return new static($elTime, null);
    }
}