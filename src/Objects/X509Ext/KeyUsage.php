<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnBitString;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Constants\X509KeyUsage;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension key usage
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.3
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.3
 */
#[FactoryTypeClass(KeyUsage::OID, X509ExtensionOid::class)]
class KeyUsage extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'key-usage';
    /**
     * Current OID
     */
    public const OID = '2.5.29.15';

    /**
     * @var array<int> Key usages
     */
    public readonly array $usages;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable<int> $usages
     */
    protected function __construct(bool $isCritical, iterable $usages)
    {
        parent::__construct($isCritical);

        $this->usages = iter_flatten($usages, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->usages = $this->usages;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnBitString::decodeFrom($payload);
        $data = $element->getString();

        return new static($isCritical, X509KeyUsage::fromBigEndianBytes($data->asBinary()));
    }
}