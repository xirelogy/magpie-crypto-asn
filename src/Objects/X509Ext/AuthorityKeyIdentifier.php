<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Cryptos\Numerals;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;
use MagpieLib\CryptoAsn\Objects\X509\GeneralNames\GeneralName;

/**
 * X.509 extension subject key identifier
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.1
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.1
 */
#[FactoryTypeClass(AuthorityKeyIdentifier::OID, X509ExtensionOid::class)]
class AuthorityKeyIdentifier extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'authority-key-identifier';
    /**
     * Current OID
     */
    public const OID = '2.5.29.35';

    /**
     * @var BinaryData|null keyIdentifier field
     */
    public readonly ?BinaryData $keyIdentifier;
    /**
     * @var array<GeneralName>|null Certificate issuer
     */
    public readonly ?array $authorityCertIssuer;
    /**
     * @var Numerals|null Certificate serial number
     */
    public readonly ?Numerals $authorityCertSerialNumber;


    /**
     * Constructor
     * @param bool $isCritical
     * @param BinaryData|null $keyIdentifier
     * @param array<GeneralName>|null $authorityCertIssuer
     * @param Numerals|null $authorityCertSerialNumber
     */
    protected function __construct(bool $isCritical, ?BinaryData $keyIdentifier, ?array $authorityCertIssuer, ?Numerals $authorityCertSerialNumber)
    {
        parent::__construct($isCritical);

        $this->keyIdentifier = $keyIdentifier;
        $this->authorityCertIssuer = $authorityCertIssuer;
        $this->authorityCertSerialNumber = $authorityCertSerialNumber;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->keyIdentifier = $this->keyIdentifier;
        $ret->authorityCertIssuer = $this->authorityCertIssuer;
        $ret->authorityCertSerialNumber = $this->authorityCertSerialNumber;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);
        $cursor = $element->iterate();

        $keyIdentifier = null;
        $authorityCertIssuer = null;
        $authorityCertSerialNumber = null;

        $keyElement = $cursor->getTaggedElement(0);
        if ($keyElement !== null) $keyIdentifier = AsnOctetString::cast($keyElement->implicit(AsnOctetString::class))->getString();

        $issuerElement = $cursor->getTaggedElement(1);
        if ($issuerElement !== null) $authorityCertIssuer = iter_flatten(static::decodeGeneralNames($issuerElement->implicit(AsnSequence::class), $handle), false);

        $serialNumberElement = $cursor->getTaggedElement(2);
        if ($serialNumberElement !== null) $authorityCertSerialNumber = AsnInteger::cast($serialNumberElement->implicit(AsnInteger::class))->getInteger();

        return new static($isCritical, $keyIdentifier, $authorityCertIssuer, $authorityCertSerialNumber);
    }


    /**
     * Decode general names
     * @param AsnElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<GeneralName>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeGeneralNames(AsnElement $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        $element = AsnSequence::cast($element);
        foreach ($element->getElements() as $subElement) {
            yield GeneralName::decode($subElement, $handle);
        }
    }
}