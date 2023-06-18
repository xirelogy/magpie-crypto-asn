<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\Cryptos\X509\Name as MagpieName;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\AsnSet;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\X501\Name;

/**
 * Alternative name entry - directory (X.509 extension)
 */
#[FactoryTypeClass(DirectoryGeneralName::TAG, GeneralName::class)]
class DirectoryGeneralName extends GeneralName
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'directory';
    /**
     * Current tag
     */
    public const TAG = 4;
    /**
     * @var MagpieName Name value
     */
    public readonly MagpieName $value;


    /**
     * Constructor
     * @param MagpieName $value
     */
    protected function __construct(MagpieName $value)
    {
        parent::__construct();

        $this->value = $value;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : string
    {
        return 'DirName:' . $this->value;
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
        $element = AsnSet::cast($element->implicit(AsnSet::class));
        $name = Name::from($element->getElementAt(0), $handle);
        return new static($name->decode($handle));
    }
}