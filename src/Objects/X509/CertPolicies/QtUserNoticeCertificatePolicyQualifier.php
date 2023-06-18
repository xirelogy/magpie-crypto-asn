<?php

namespace MagpieLib\CryptoAsn\Objects\X509\CertPolicies;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Objects\X509\UserNotice;

/**
 * Certificate policy qualifier - user notice (X.509 extension)
 */
#[FactoryTypeClass(QtUserNoticeCertificatePolicyQualifier::OID, CertificatePolicyQualifier::class)]
class QtUserNoticeCertificatePolicyQualifier extends CertificatePolicyQualifier
{
    /**
     * Current OID
     */
    public const OID = '1.3.6.1.5.5.7.2.2';

    /**
     * @var UserNotice User notice
     */
    public readonly UserNotice $userNotice;


    /**
     * Constructor
     * @param ObjectIdentifier $policyQualifierId
     * @param UserNotice $userNotice
     */
    protected function __construct(ObjectIdentifier $policyQualifierId, UserNotice $userNotice)
    {
        parent::__construct($policyQualifierId);

        $this->userNotice = $userNotice;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(ObjectIdentifier $policyQualifierId, AsnElement $qualifierElement, ?AsnDecoderEventHandleable $handle) : static
    {
        $userNotice = UserNotice::decode($qualifierElement, $handle);

        return new static($policyQualifierId, $userNotice);
    }
}