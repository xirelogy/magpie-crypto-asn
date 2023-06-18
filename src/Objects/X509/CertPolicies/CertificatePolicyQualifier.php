<?php

namespace MagpieLib\CryptoAsn\Objects\X509\CertPolicies;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\ClassFactory;
use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * Certificate policy qualifier data (X.509 extension)
 */
abstract class CertificatePolicyQualifier extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var ObjectIdentifier Policy qualifier ID
     */
    public readonly ObjectIdentifier $policyQualifierId;


    /**
     * Constructor
     * @param ObjectIdentifier $policyQualifierId
     */
    protected function __construct(ObjectIdentifier $policyQualifierId)
    {
        $this->policyQualifierId = $policyQualifierId;
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $element = AsnSequence::cast($element);
        $policyQualifierId = AsnObjectIdentifier::cast($element->getElementAt(0))->getOid();
        $qualifier = $element->getElementAt(1);

        $policyQualifierIdOid = $policyQualifierId->getString();

        $className = ClassFactory::safeResolve($policyQualifierIdOid, self::class);
        if ($className === null) {
            $localHandle->warnUnsupported($policyQualifierIdOid, _l('Certificate policy qualifier'));
            return static::onDecodeFallback($policyQualifierId, $qualifier, $handle);
        }
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::onDecode($policyQualifierId, $qualifier, $handle);
    }


    /**
     * Decode specifically from ASN
     * @param ObjectIdentifier $policyQualifierId
     * @param AsnElement $qualifierElement
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static abstract function onDecode(ObjectIdentifier $policyQualifierId, AsnElement $qualifierElement, ?AsnDecoderEventHandleable $handle) : static;


    /**
     * Fallback to decode specifically from ASN
     * @param ObjectIdentifier $policyQualifierId
     * @param AsnElement $qualifierElement
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    private static function onDecodeFallback(ObjectIdentifier $policyQualifierId, AsnElement $qualifierElement, ?AsnDecoderEventHandleable $handle) : static
    {
        return GenericCertificatePolicyQualifier::onDecode($policyQualifierId, $qualifierElement, $handle);
    }
}