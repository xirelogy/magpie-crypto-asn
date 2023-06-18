<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;
use MagpieLib\CryptoAsn\Objects\X509\CrlDistributionPoint;

/**
 * X.509 extension CRL distribution points
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.14
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.13
 */
#[FactoryTypeClass(CrlDistributionPoints::OID, X509ExtensionOid::class)]
class CrlDistributionPoints extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'crl-distribution-points';
    /**
     * Current OID
     */
    public const OID = '2.5.29.31';

    /**
     * @var array<CrlDistributionPoint> Distribution points
     */
    public readonly array $distributionPoints;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable<CrlDistributionPoint> $distributionPoints
     */
    protected function __construct(bool $isCritical, iterable $distributionPoints)
    {
        parent::__construct($isCritical);

        $this->distributionPoints = iter_flatten($distributionPoints, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->distributionPoints = $this->distributionPoints;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);

        return new static($isCritical, static::decodePoints($element, $handle));
    }


    /**
     * Decode for list of distribution points
     * @param AsnSequence $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<CrlDistributionPoint>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodePoints(AsnSequence $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        foreach ($element->getElements() as $subElement) {
            yield CrlDistributionPoint::decode($subElement, $handle);
        }
    }
}