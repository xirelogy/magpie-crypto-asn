<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\General\Sugars\Quote;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Alternative name entry - DNS (X.509 extension)
 */
#[FactoryTypeClass(OtherGeneralName::TAG, GeneralName::class)]
class OtherGeneralName extends GeneralName
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'other';
    /**
     * Current tag
     */
    public const TAG = 0;
    /**
     * @var ObjectIdentifier Type ID
     */
    public readonly ObjectIdentifier $typeId;
    /**
     * @var AsnTaggedElement|null Associated value element
     */
    public readonly ?AsnTaggedElement $valueElement;


    /**
     * Constructor
     * @param ObjectIdentifier $typeId
     * @param AsnTaggedElement|null $valueElement
     */
    protected function __construct(ObjectIdentifier $typeId, ?AsnTaggedElement $valueElement)
    {
        parent::__construct();

        $this->typeId = $typeId;
        $this->valueElement = $valueElement;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : string
    {
        return 'othername:' . Quote::square('OID=' . $this->typeId->getString());
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->typeId = $this->typeId;
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
    protected static function onDecode(AsnTaggedElement $element, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::cast($element->implicit(AsnSequence::class));
        $cursor = $element->iterate();

        $typeId = AsnObjectIdentifier::cast($cursor->requiresNextElement())->getOid();
        $valueElement = $cursor->getTaggedElement(0);

        return new static($typeId, $valueElement);
    }
}