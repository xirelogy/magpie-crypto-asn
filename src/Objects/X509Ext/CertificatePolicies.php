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
use MagpieLib\CryptoAsn\Objects\X509\CertPolicies\CertificatePolicy;

/**
 * X.509 extension certificate policies
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.5
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.4
 */
#[FactoryTypeClass(CertificatePolicies::OID, X509ExtensionOid::class)]
class CertificatePolicies extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'certificate-policies';
    /**
     * Current OID
     */
    public const OID = '2.5.29.32';

    /**
     * @var array<CertificatePolicy> Associated certificate policies
     */
    public array $certificatePolicies;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable $certificatePolicies
     */
    protected function __construct(bool $isCritical, iterable $certificatePolicies)
    {
        parent::__construct($isCritical);

        $this->certificatePolicies = iter_flatten($certificatePolicies, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->certificatePolicies = $this->certificatePolicies;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);

        return new static($isCritical, static::decodePolicies($element, $handle));
    }


    /**
     * Decode all policies
     * @param AsnSequence $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<CertificatePolicy>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodePolicies(AsnSequence $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        foreach ($element->getElements() as $subElement) {
            yield CertificatePolicy::decode($subElement, $handle);
        }
    }
}