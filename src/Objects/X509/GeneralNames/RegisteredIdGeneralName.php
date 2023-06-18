<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Alternative name entry - registered ID (X.509 extension)
 */
#[FactoryTypeClass(RegisteredIdGeneralName::TAG, GeneralName::class)]
class RegisteredIdGeneralName extends GeneralName
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'registered-id';
    /**
     * Current tag
     */
    public const TAG = 8;
    /**
     * @var ObjectIdentifier ID value
     */
    public readonly ObjectIdentifier $value;


    /**
     * Constructor
     * @param ObjectIdentifier $value
     */
    protected function __construct(ObjectIdentifier $value)
    {
        parent::__construct();

        $this->value = $value;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : string
    {
        return 'Registered ID:' . $this->value->getString();
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->value = $this->value;
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
        $element = AsnObjectIdentifier::cast($element->implicit(AsnObjectIdentifier::class));
        return new static($element->getOid());
    }
}