<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X509;

use Magpie\Objects\BinaryData;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnBinaryStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnBitString;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * An X.509 certificate
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.1
 */
class Certificate extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var TbsCertificate TBS certificate
     */
    public readonly TbsCertificate $tbsCertificate;
    /**
     * @var AlgorithmIdentifier Signature algorithm
     */
    public readonly AlgorithmIdentifier $signatureAlgorithm;
    /**
     * @var BinaryData Signature value
     */
    public readonly BinaryData $signatureValue;


    /**
     * Constructor
     * @param TbsCertificate $tbsCertificate
     * @param AlgorithmIdentifier $signatureAlgorithm
     * @param BinaryData $signatureValue
     */
    public function __construct(TbsCertificate $tbsCertificate, AlgorithmIdentifier $signatureAlgorithm, BinaryData $signatureValue)
    {
        $this->tbsCertificate = $tbsCertificate;
        $this->signatureAlgorithm = $signatureAlgorithm;
        $this->signatureValue = $signatureValue;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            $this->tbsCertificate->to(),
            $this->signatureAlgorithm->to(),
            AsnBitString::create($this->signatureValue),
        ]);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $tbsCertificate = TbsCertificate::from($cursor->requiresNextElement(), $handle);
        $signatureAlgorithm = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $signatureValue = AsnBinaryStringElement::cast($cursor->requiresNextElement())->getString();

        return new static($tbsCertificate, $signatureAlgorithm, $signatureValue);
    }
}