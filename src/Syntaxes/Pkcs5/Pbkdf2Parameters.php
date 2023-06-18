<?php

namespace MagpieLib\CryptoAsn\Syntaxes\Pkcs5;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\BinaryData;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnBinaryStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * PBKDF2 parameters in AlgorithmIdentifier
 * @link https://www.rfc-editor.org/rfc/rfc2898#appendix-A.2
 */
class Pbkdf2Parameters extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var BinaryData|AlgorithmIdentifier Salt value
     */
    public readonly BinaryData|AlgorithmIdentifier $salt;
    /**
     * @var int Iteration count
     */
    public readonly int $iterationCount;
    /**
     * @var int|null Specific key length
     */
    public readonly ?int $keyLength;
    /**
     * @var AlgorithmIdentifier Underlying pseudo-random function
     */
    public readonly AlgorithmIdentifier $prf;


    /**
     * Constructor
     * @param BinaryData|AlgorithmIdentifier $salt
     * @param int $iterationCount
     * @param int|null $keyLength
     * @param AlgorithmIdentifier $prf
     */
    protected function __construct(BinaryData|AlgorithmIdentifier $salt, int $iterationCount, ?int $keyLength, AlgorithmIdentifier $prf)
    {
        $this->salt = $salt;
        $this->iterationCount = $iterationCount;
        $this->keyLength = $keyLength;
        $this->prf = $prf;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        $ret = [
            static::encodeSalt($this->salt),
            AsnInteger::create($this->iterationCount),
        ];

        if ($this->keyLength !== null) {
            $ret[] = AsnInteger::create($this->keyLength);
        }

        $ret[] = $this->prf->to();

        return AsnSequence::create($ret);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $salt = static::decodeSalt($cursor->requiresNextElement(), $handle);
        $iterationCount = AsnInteger::cast($cursor->requiresNextElement())->getIntegerValue();

        $subElement = $cursor->requiresNextElement();

        $keyLength = null;
        if ($subElement instanceof AsnInteger) {
            $keyLength = AsnInteger::cast($subElement)->getIntegerValue();
            $subElement = $cursor->requiresNextElement();
        }

        $prf = AlgorithmIdentifier::from($subElement, $handle);

        return new static($salt, $iterationCount, $keyLength, $prf);
    }


    /**
     * Encode salt value/specification into ASN.1 element
     * @param BinaryData|AlgorithmIdentifier $value
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function encodeSalt(BinaryData|AlgorithmIdentifier $value) : AsnElement
    {
        if ($value instanceof BinaryData) return AsnOctetString::create($value);
        return $value->to();
    }


    /**
     * Decode for salt value
     * @param AsnElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData|AlgorithmIdentifier
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeSalt(AsnElement $element, ?AsnDecoderEventHandleable $handle) : BinaryData|AlgorithmIdentifier
    {
        if ($element instanceof AsnBinaryStringElement) {
            // Specified salt
            return $element->getString();
        }

        // Assumed to be other source
        return AlgorithmIdentifier::from($element, $handle);
    }
}