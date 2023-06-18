<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\AsnIa5String;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * Alternative name entry - URI (X.509 extension)
 */
#[FactoryTypeClass(UriGeneralName::TAG, GeneralName::class)]
class UriGeneralName extends GeneralName
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'uri';
    /**
     * Current tag
     */
    public const TAG = 6;
    /**
     * @var string Name value
     */
    public readonly string $value;


    /**
     * Constructor
     * @param string $value
     */
    protected function __construct(string $value)
    {
        parent::__construct();

        $this->value = $value;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : string
    {
        return 'URI:' . $this->value;
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
        $element = AsnIa5String::cast($element->implicit(AsnIa5String::class));
        return new static($element->getString());
    }
}