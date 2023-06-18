<?php

namespace MagpieLib\CryptoAsn\Syntaxes;

use Exception;
use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PublicKey;
use Magpie\Cryptos\Contents\BlockContent;
use Magpie\Cryptos\Encodings\Pem;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\OperationFailedException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnBinaryStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnBitString;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * PublicKeyInfo (PKCS#8)
 */
class PublicKeyInfo extends Syntax
{
    /**
     * @var AlgorithmIdentifier Asymmetric key algorithm used
     */
    public readonly AlgorithmIdentifier $algorithm;
    /**
     * @var BinaryData Payload
     */
    public readonly BinaryData $publicKey;
    /**
     * @var BinaryData|null Corresponding DER binary data
     */
    protected readonly ?BinaryData $derBinary;


    /**
     * Constructor
     * @param AlgorithmIdentifier $algorithm
     * @param BinaryData $publicKey
     * @param BinaryData|null $derBinary
     */
    protected function __construct(AlgorithmIdentifier $algorithm, BinaryData $publicKey, ?BinaryData $derBinary)
    {
        $this->algorithm = $algorithm;
        $this->publicKey = $publicKey;
        $this->derBinary = $derBinary;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            $this->algorithm->to(),
            AsnBitString::create($this->publicKey),
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->algorithm = $this->algorithm;
        $ret->publicKey = $this->publicKey;
    }


    /**
     * Decode as public key instance
     * @param AsnDecoderEventHandleable|null $handle
     * @return PublicKey
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function decode(?AsnDecoderEventHandleable $handle = null) : PublicKey
    {
        _used($handle);

        if ($this->derBinary === null) throw new UnsupportedException();

        try {
            $pemData = Pem::encode([
                new BlockContent('PUBLIC KEY', $this->derBinary->asBase64()),
            ]);
            return PublicKey::import($pemData);
        } catch (SafetyCommonException|CryptoException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new OperationFailedException(previous: $ex);
        }
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $algorithm = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $publicKey = AsnBinaryStringElement::cast($cursor->requiresNextElement())->getString();

        return new static($algorithm, $publicKey, $obj->encodeDer());
    }


    /**
     * Create a new instance
     * @param AlgorithmIdentifier $algorithm
     * @param BinaryData|string $publicKey
     * @return static
     */
    public static function create(AlgorithmIdentifier $algorithm, BinaryData|string $publicKey) : static
    {
        return new static($algorithm, BinaryData::acceptBinary($publicKey), null);
    }
}