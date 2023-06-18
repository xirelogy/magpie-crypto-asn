<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension extended key usage
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.3
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.12
 */
#[FactoryTypeClass(ExtendedKeyUsage::OID, X509ExtensionOid::class)]
class ExtendedKeyUsage extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'extended-key-usage';
    /**
     * Current OID
     */
    public const OID = '2.5.29.37';

    /**
     * @var array<ObjectIdentifier> OID of the usages
     */
    public readonly array $usages;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable<ObjectIdentifier> $usages
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
        $element = AsnSequence::decodeFrom($payload);

        return new static($isCritical, static::decodeUsages($element, $handle));
    }


    /**
     * Decode as corresponding list of OIDs for usages
     * @param AsnSequence $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<ObjectIdentifier>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeUsages(AsnSequence $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        _used($handle);

        foreach ($element->getElements() as $subElement) {
            $subElement = AsnObjectIdentifier::cast($subElement);
            yield $subElement->getOid();
        }
    }
}