<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\ObjectIdentifier as SopAsn1ObjectIdentifier;

/**
 * ASN.1 object identifier (oid)
 */
#[FactoryTypeClass(AsnObjectIdentifier::TAG, AsnElement::class)]
class AsnObjectIdentifier extends AsnPrimitiveElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'object-identifier';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_OBJECT_IDENTIFIER;
    /**
     * @var SopAsn1ObjectIdentifier Underlying element
     */
    protected readonly SopAsn1ObjectIdentifier $elOid;


    /**
     * Constructor
     * @param SopAsn1ObjectIdentifier $elOid
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1ObjectIdentifier $elOid, ?AsnDecoderContext $context)
    {
        parent::__construct($elOid, $context);

        $this->elOid = $elOid;
    }


    /**
     * Corresponding OID string
     * @return ObjectIdentifier
     * @throws CryptoException
     */
    public function getOid() : ObjectIdentifier
    {
        $oidString = SopAsn1Api::wrapped(fn () => $this->elOid->oid());

        return ObjectIdentifier::fromString($oidString);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->oid = $this->getOid();
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return $this->getOid();
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
        return new static($el->asUnspecified()->asObjectIdentifier(), $context);
    }


    /**
     * Create an instance
     * @param ObjectIdentifier $value
     * @return static
     * @throws CryptoException
     */
    public static function create(ObjectIdentifier $value) : static
    {
        $elOid = SopAsn1Api::wrapped(fn () => new SopAsn1ObjectIdentifier($value->getString()));

        return new static($elOid, null);
    }
}