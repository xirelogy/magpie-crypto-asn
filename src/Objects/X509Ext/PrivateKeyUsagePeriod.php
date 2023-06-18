<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Carbon\CarbonInterface;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnTimeElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension private key usage period
 * @note This extension is no longer recommended as of RFC5280
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.4
 */
#[FactoryTypeClass(PrivateKeyUsagePeriod::OID, X509ExtensionOid::class)]
class PrivateKeyUsagePeriod extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'private-key-usage-period';
    /**
     * Current OID
     */
    public const OID = '2.5.29.16';

    /**
     * @var CarbonInterface When validity period begins
     */
    public readonly CarbonInterface $notBefore;
    /**
     * @var CarbonInterface When validity period ends
     */
    public readonly CarbonInterface $notAfter;


    /**
     * Constructor
     * @param bool $isCritical
     * @param CarbonInterface $notBefore
     * @param CarbonInterface $notAfter
     */
    protected function __construct(bool $isCritical, CarbonInterface $notBefore, CarbonInterface $notAfter)
    {
        parent::__construct($isCritical);

        $this->notBefore = $notBefore;
        $this->notAfter = $notAfter;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->notBefore = $this->notBefore;
        $ret->notAfter = $this->notAfter;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);

        $notBefore = AsnTimeElement::cast($element->getElementAt(0))->getTime();
        $notAfter = AsnTimeElement::cast($element->getElementAt(1))->getTime();

        return new static($isCritical, $notBefore, $notAfter);
    }
}