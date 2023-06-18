<?php

namespace MagpieLib\CryptoAsn\Objects\X509\CertPolicies;

use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Certificate policy qualifier data - generic (X.509 extension)
 */
class GenericCertificatePolicyQualifier extends CertificatePolicyQualifier
{
    /**
     * @inheritDoc
     */
    protected static function onDecode(ObjectIdentifier $policyQualifierId, AsnElement $qualifierElement, ?AsnDecoderEventHandleable $handle) : static
    {
        return new static($policyQualifierId);
    }
}