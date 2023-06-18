<?php

namespace MagpieLib\CryptoAsn\Syntaxes;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\Pkcs5\Scheme\Scheme;

/**
 * EncryptedPrivateKeyInfo (PKCS#8)
 * @link https://www.rfc-editor.org/rfc/rfc5208#section-6
 */
class EncryptedPrivateKeyInfo extends Syntax
{
    /**
     * @var AlgorithmIdentifier Encryption algorithm used
     */
    public readonly AlgorithmIdentifier $encryptionAlgorithm;
    /**
     * @var BinaryData The encrypted data
     */
    public readonly BinaryData $encryptedData;


    /**
     * Constructor
     * @param AlgorithmIdentifier $encryptionAlgorithm
     * @param BinaryData $encryptedData
     */
    protected function __construct(AlgorithmIdentifier $encryptionAlgorithm, BinaryData $encryptedData)
    {
        $this->encryptionAlgorithm = $encryptionAlgorithm;
        $this->encryptedData = $encryptedData;
    }


    /**
     * Decrypt the encrypted data using given password
     * @param BinaryData|string $password
     * @param AsnDecoderEventHandleable|null $handle
     * @return PrivateKeyInfo
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function decrypt(BinaryData|string $password, ?AsnDecoderEventHandleable $handle = null) : PrivateKeyInfo
    {
        $scheme = Scheme::fromAlgorithmIdentifier($this->encryptionAlgorithm, $handle);
        $plainBytes = $scheme->decrypt($this->encryptedData, $password, $handle);

        $element = AsnElement::decodeFrom($plainBytes);
        return PrivateKeyInfo::from($element, $handle);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->encryptionAlgorithm = $this->encryptionAlgorithm;
        $ret->encryptedData = $this->encryptedData;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            $this->encryptionAlgorithm->to(),
            AsnOctetString::create($this->encryptedData),
        ]);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $encryptionAlgorithm = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $encryptedData = AsnOctetString::cast($cursor->requiresNextElement())->getString();

        return new static($encryptionAlgorithm, $encryptedData);
    }


    /**
     * Create a new instance
     * @param AlgorithmIdentifier $encryptionAlgorithm
     * @param BinaryData|string $encryptedData
     * @return static
     */
    public static function create(AlgorithmIdentifier $encryptionAlgorithm, BinaryData|string $encryptedData) : static
    {
        return new static($encryptionAlgorithm, BinaryData::acceptBinary($encryptedData));
    }
}