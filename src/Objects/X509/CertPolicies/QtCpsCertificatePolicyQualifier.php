<?php

namespace MagpieLib\CryptoAsn\Objects\X509\CertPolicies;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnIa5String;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Certificate policy qualifier - CPS URI (X.509 extension)
 */
#[FactoryTypeClass(QtCpsCertificatePolicyQualifier::OID, CertificatePolicyQualifier::class)]
class QtCpsCertificatePolicyQualifier extends CertificatePolicyQualifier
{
    /**
     * Current OID
     */
    public const OID = '1.3.6.1.5.5.7.2.1';

    /**
     * @var string CPS URI
     */
    public readonly string $uri;


    /**
     * Constructor
     * @param ObjectIdentifier $policyQualifierId
     * @param string $uri
     */
    protected function __construct(ObjectIdentifier $policyQualifierId, string $uri)
    {
        parent::__construct($policyQualifierId);

        $this->uri = $uri;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(ObjectIdentifier $policyQualifierId, AsnElement $qualifierElement, ?AsnDecoderEventHandleable $handle) : static
    {
        $uri = AsnIa5String::cast($qualifierElement)->getString();

        return new static($policyQualifierId, $uri);
    }
}