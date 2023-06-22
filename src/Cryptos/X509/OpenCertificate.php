<?php

namespace MagpieLib\CryptoAsn\Cryptos\X509;

use Carbon\CarbonInterface;
use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PublicKey as MagpiePublicKey;
use Magpie\Cryptos\Algorithms\Hashes\Hasher as MagpieHasher;
use Magpie\Cryptos\Contents\BinaryBlockContent;
use Magpie\Cryptos\Contents\BlockContent;
use Magpie\Cryptos\Contents\ExportOption;
use Magpie\Cryptos\Encodings\Pem;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Cryptos\Numerals;
use Magpie\Cryptos\X509\Certificate as MagpieCertificate;
use Magpie\Cryptos\X509\Name as MagpieName;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\General\Sugars\Excepts;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Cryptos\OpenContext;
use MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures\AsymmSignature;
use MagpieLib\CryptoAsn\Objects\X509Ext\AuthorityKeyIdentifier;
use MagpieLib\CryptoAsn\Objects\X509Ext\SubjectAlternativeName;
use MagpieLib\CryptoAsn\Objects\X509Ext\SubjectKeyIdentifier;
use MagpieLib\CryptoAsn\Objects\X509Ext\X509Extension;
use MagpieLib\CryptoAsn\Syntaxes\X501\Name;
use MagpieLib\CryptoAsn\Syntaxes\X509\Certificate as CertificateSyntax;

/**
 * Open implementation of certificate representation
 */
#[FactoryTypeClass(OpenCertificate::TYPECLASS, MagpieCertificate::class)]
class OpenCertificate extends MagpieCertificate
{
    /**
     * Current type class
     */
    public const TYPECLASS = OpenContext::TYPECLASS;
    /**
     * @var CertificateSyntax Decoded certificate data (ASN.1)
     */
    protected CertificateSyntax $syntax;
    /**
     * @var array<X509Extension>|null Decoded certificate extensions
     */
    protected ?array $extensions = null;
    /**
     * @var BinaryData Corresponding DER binary data
     */
    protected BinaryData $derBinary;
    /**
     * @var BinaryData Corresponding BER binary data (tbsCertificate)
     */
    protected BinaryData $tbsDerBinary;
    /**
     * @var AsnDecoderEventHandleable|null Associated decoder event handle
     */
    protected ?AsnDecoderEventHandleable $handle;


    /**
     * Constructor
     * @param CertificateSyntax $syntax
     * @param BinaryData $derBinary
     * @param BinaryData $tbsDerBinary
     * @param AsnDecoderEventHandleable|null $handle
     */
    protected function __construct(CertificateSyntax $syntax, BinaryData $derBinary, BinaryData $tbsDerBinary, ?AsnDecoderEventHandleable $handle = null)
    {
        parent::__construct();

        $this->syntax = $syntax;
        $this->derBinary = $derBinary;
        $this->tbsDerBinary = $tbsDerBinary;
        $this->handle = $handle;
    }


    /**
     * @inheritDoc
     */
    public static function getTypeClass() : string
    {
        return static::TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    public function getVersion() : int
    {
        return AsnInteger::decodeUnsignedIntFromBigEndian($this->syntax->tbsCertificate->version->asBinary()) + 1;
    }


    /**
     * @inheritDoc
     */
    public function getSerialNumber() : Numerals
    {
        return $this->syntax->tbsCertificate->serialNumber;
    }


    /**
     * @inheritDoc
     */
    public function getName() : MagpieName|string
    {
        return static::safeDecodeName($this->syntax->tbsCertificate->subject, $this->handle);
    }


    /**
     * @inheritDoc
     */
    public function getSubject() : MagpieName|string
    {
        return static::safeDecodeName($this->syntax->tbsCertificate->subject, $this->handle);
    }


    /**
     * @inheritDoc
     */
    public function getIssuer() : MagpieName|string
    {
        return static::safeDecodeName($this->syntax->tbsCertificate->issuer, $this->handle);
    }


    /**
     * @inheritDoc
     */
    public function getValidFrom() : CarbonInterface
    {
        return $this->syntax->tbsCertificate->validity->notBefore;
    }


    /**
     * @inheritDoc
     */
    public function getValidUntil() : CarbonInterface
    {
        return $this->syntax->tbsCertificate->validity->notAfter;
    }


    /**
     * @inheritDoc
     */
    public function getSubjectAltNames() : iterable
    {
        foreach ($this->getSafeExtensions() as $extension) {
            if ($extension instanceof SubjectAlternativeName) {
                foreach ($extension->names as $name) {
                    yield $name->getValue();
                }
            }
        }
    }


    /**
     * Subject key identifier
     * @return BinaryData|null
     */
    public function getSubjectKeyIdentifier() : ?BinaryData
    {
        foreach ($this->getSafeExtensions() as $extension) {
            if ($extension instanceof SubjectKeyIdentifier) {
                return $extension->keyIdentifier;
            }
        }

        return null;
    }


    /**
     * Authority key identifier
     * @return BinaryData|null
     */
    public function getAuthorityKeyIdentifier() : ?BinaryData
    {
        foreach ($this->getSafeExtensions() as $extension) {
            if ($extension instanceof AuthorityKeyIdentifier) {
                return $extension->keyIdentifier;
            }
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    public function getPublicKey() : MagpiePublicKey
    {
        return $this->syntax->tbsCertificate->subjectPublicKeyInfo->decode();
    }


    /**
     * @inheritDoc
     */
    protected function onGetFingerprint(string $hashTypeClass) : BinaryData
    {
        return MagpieHasher::hashStringWith($hashTypeClass, $this->derBinary->asBinary());
    }


    /**
     * @inheritDoc
     */
    public function verifyUsing(MagpieCertificate|MagpiePublicKey $verifier) : bool
    {
        /** @var MagpiePublicKey $verifierKey */
        $verifierKey = $verifier instanceof MagpieCertificate ? $verifier->getPublicKey() : $verifier;

        // Try to resolve the hashing algorithm
        $algorithm = AsymmSignature::fromAlgorithmIdentifier($this->syntax->signatureAlgorithm, $this->handle);
        if ($algorithm === null) return false;
        if (!$algorithm->isPublicKeySupported($verifierKey)) return false;

        $signatureHashTypeClass = $algorithm->getHashAlgoTypeClass();

        return $verifierKey->verify($this->tbsDerBinary, $this->syntax->signatureValue, $signatureHashTypeClass);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->subjectKeyIdentifier = $this->getSubjectKeyIdentifier();
        $ret->authorityKeyIdentifier = $this->getAuthorityKeyIdentifier();
    }


    /**
     * Access to extensions
     * @return iterable<X509Extension>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function getExtensions() : iterable
    {
        if ($this->extensions === null) {
            $decodedExtensions = [];
            foreach ($this->syntax->tbsCertificate->extensions as $extension) {
                $decodedExtension = X509Extension::decode($extension, $this->handle);
                if ($decodedExtension === null) continue;
                $decodedExtensions[] = $decodedExtension;
            }

            $this->extensions = $decodedExtensions;
        }

        return $this->extensions;
    }


    /**
     * Access to extensions (safe)
     * @return iterable<X509Extension>
     */
    public function getSafeExtensions() : iterable
    {
        if ($this->extensions === null) {
            $decodedExtensions = [];
            foreach ($this->syntax->tbsCertificate->extensions as $extension) {
                $decodedExtension = Excepts::noThrow(fn () => X509Extension::decode($extension, $this->handle));
                if ($decodedExtension === null) continue;
                $decodedExtensions[] = $decodedExtension;
            }

            $this->extensions = $decodedExtensions;
        }

        return $this->extensions;
    }


    /**
     * @inheritDoc
     */
    public function export(ExportOption ...$options) : string
    {
        return Pem::encode([
            new BlockContent('CERTIFICATE', $this->derBinary->asBase64()),
        ]);
    }


    /**
     * @inheritDoc
     */
    protected static function specificImportBinary(BinaryBlockContent $source, ?string $password) : ?static
    {
        $handle = OpenContext::getDecoderEventHandle();

        if ($source->type !== null && $source->type !== 'CERTIFICATE') return null;

        $element = AsnSequence::decodeFrom($source->data);
        $tbsElement = $element->getElementAt(0);
        return new static(CertificateSyntax::from($element, $handle), $source->data, $tbsElement->encodeDer(), $handle);
    }


    /**
     * Safely decode name
     * @param Name $name
     * @param AsnDecoderEventHandleable|null $handle
     * @return MagpieName|string
     */
    protected static function safeDecodeName(Name $name, ?AsnDecoderEventHandleable $handle = null) : MagpieName|string
    {
        return Excepts::noThrow(fn () => $name->decode($handle), '');
    }
}