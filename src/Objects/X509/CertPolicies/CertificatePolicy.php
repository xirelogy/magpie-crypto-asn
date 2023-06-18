<?php

namespace MagpieLib\CryptoAsn\Objects\X509\CertPolicies;

use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Certificate policy data (X.509 extension)
 */
class CertificatePolicy extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var ObjectIdentifier Certificate policy type
     */
    public readonly ObjectIdentifier $policyIdentifier;
    /**
     * @var array<CertificatePolicyQualifier> Associated qualifiers
     */
    public readonly array $policyQualifiers;


    /**
     * Constructor
     * @param ObjectIdentifier $policyIdentifier
     * @param iterable<CertificatePolicyQualifier> $policyQualifiers
     */
    protected function __construct(ObjectIdentifier $policyIdentifier, iterable $policyQualifiers)
    {
        $this->policyIdentifier = $policyIdentifier;
        $this->policyQualifiers = iter_flatten($policyQualifiers, false);
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($element);
        $cursor = $obj->iterate();

        $policyIdentifier = AsnObjectIdentifier::cast($cursor->requiresNextElement())->getOid();

        $policyQualifiers = [];
        $policyQualifiersElement = $cursor->getNextElement();
        if ($policyQualifiersElement !== null) {
            foreach (AsnSequence::cast($policyQualifiersElement)->getElements() as $subElement) {
                $policyQualifiers[] = CertificatePolicyQualifier::decode($subElement, $handle);
            }
        }

        return new static($policyIdentifier, $policyQualifiers);
    }
}