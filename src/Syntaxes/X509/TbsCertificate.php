<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X509;

use Magpie\Cryptos\Numerals;
use Magpie\Objects\BinaryData;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnBinaryStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnBitString;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\PublicKeyInfo;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;
use MagpieLib\CryptoAsn\Syntaxes\X501\Name;

/**
 * The TBSCertificate (To-be-signed Certificate) within an X.509 certificate
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.1
 */
class TbsCertificate extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var Numerals Certificate version: v1(0), v2(1), v3(2)
     */
    public readonly Numerals $version;
    /**
     * @var Numerals Certificate serial number
     */
    public readonly Numerals $serialNumber;
    /**
     * @var AlgorithmIdentifier Signature algorithm
     */
    public readonly AlgorithmIdentifier $signature;
    /**
     * @var Name Certificate issuer
     */
    public readonly Name $issuer;
    /**
     * @var TbsCertificateValidity Certificate validity
     */
    public readonly TbsCertificateValidity $validity;
    /**
     * @var Name Certificate subject
     */
    public readonly Name $subject;
    /**
     * @var PublicKeyInfo Subject's public key
     */
    public readonly PublicKeyInfo $subjectPublicKeyInfo;
    /**
     * @var BinaryData|null Unique ID for issuer (Optional for v2,v3)
     */
    public readonly ?BinaryData $issuerUniqueID;
    /**
     * @var BinaryData|null Unique ID for subject (Optional for v2,v3)
     */
    public readonly ?BinaryData $subjectUniqueID;
    /**
     * @var array<AsnElement>|null Extensions (Optional for v3)
     */
    public readonly ?array $extensions;


    /**
     * Constructor
     * @param Numerals $version
     * @param Numerals $serialNumber
     * @param AlgorithmIdentifier $signature
     * @param Name $issuer
     * @param TbsCertificateValidity $validity
     * @param Name $subject
     * @param PublicKeyInfo $subjectPublicKeyInfo
     * @param BinaryData|null $issuerUniqueID
     * @param BinaryData|null $subjectUniqueID
     * @param array<AsnElement>|null $extensions
     */
    public function __construct(Numerals $version, Numerals $serialNumber, AlgorithmIdentifier $signature, Name $issuer, TbsCertificateValidity $validity, Name $subject, PublicKeyInfo $subjectPublicKeyInfo, ?BinaryData $issuerUniqueID = null, ?BinaryData $subjectUniqueID = null, ?array $extensions = null)
    {
        $this->version = $version;
        $this->serialNumber = $serialNumber;
        $this->signature = $signature;
        $this->issuer = $issuer;
        $this->validity = $validity;
        $this->subject = $subject;
        $this->subjectPublicKeyInfo = $subjectPublicKeyInfo;
        $this->issuerUniqueID = $issuerUniqueID;
        $this->subjectUniqueID = $subjectUniqueID;
        $this->extensions = $extensions;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        $ret = [
            AsnTaggedElement::createExplicit(0, AsnInteger::create($this->version)),
            AsnInteger::create($this->serialNumber),
            $this->signature->to(),
            $this->issuer->to(),
            $this->validity->to(),
            $this->subject->to(),
            $this->subjectPublicKeyInfo->to(),
        ];

        if ($this->issuerUniqueID !== null) {
            $ret[] = AsnTaggedElement::createImplicit(0, AsnBitString::create($this->issuerUniqueID));
        }

        if ($this->subjectUniqueID !== null) {
            $ret[] = AsnTaggedElement::createImplicit(1, AsnBitString::create($this->subjectUniqueID));
        }

        if ($this->extensions !== null && count($this->extensions) > 0) {
            $extensionsElement = AsnSequence::create($this->extensions);
            $ret[] = AsnTaggedElement::createExplicit(3, $extensionsElement);
        }

        return AsnSequence::create($ret);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $version = AsnInteger::cast($cursor->requiresTaggedElement(0)->explicit())->getInteger();
        $serialNumber = AsnInteger::cast($cursor->requiresNextElement())->getInteger();
        $signature = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $issuer = Name::from($cursor->requiresNextElement(), $handle);
        $validity = TbsCertificateValidity::from($cursor->requiresNextElement(), $handle);
        $subject = Name::from($cursor->requiresNextElement(), $handle);
        $subjectPublicKeyInfo = PublicKeyInfo::from($cursor->requiresNextElement(), $handle);

        $issuerUniqueID = null;
        $subjectUniqueID = null;
        $extensions = null;

        if ($version->asHex() == '01') {
            $issuerElement = $cursor->getTaggedElement(1);
            if ($issuerElement !== null) $issuerUniqueID = AsnBinaryStringElement::cast($issuerElement->implicit(AsnBitString::class))->getString();

            $subjectElement = $cursor->getTaggedElement(2);
            if ($subjectElement !== null) $subjectUniqueID = AsnBinaryStringElement::cast($subjectElement->implicit(AsnBitString::class))->getString();
        }

        if ($version->asHex() == '01' || $version->asHex() == '02') {
            $extensionsElement = $cursor->getTaggedElement(3);
            if ($extensionsElement !== null) $extensions = iter_flatten(AsnSequence::cast($extensionsElement->explicit())->getElements(), false);
        }

        return new static($version, $serialNumber, $signature, $issuer, $validity, $subject, $subjectPublicKeyInfo, $issuerUniqueID, $subjectUniqueID, $extensions);
    }
}