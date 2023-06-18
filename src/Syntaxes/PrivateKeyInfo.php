<?php

namespace MagpieLib\CryptoAsn\Syntaxes;

use Exception;
use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PrivateKey;
use Magpie\Cryptos\Contents\BlockContent;
use Magpie\Cryptos\Encodings\Pem;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Cryptos\Numerals;
use Magpie\Exceptions\OperationFailedException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * PrivateKeyInfo (PKCS#8)
 * @link https://www.rfc-editor.org/rfc/rfc5208#section-5
 */
class PrivateKeyInfo extends Syntax
{
    /**
     * @var Numerals Version
     */
    public readonly Numerals $version;
    /**
     * @var AlgorithmIdentifier Asymmetric key algorithm used
     */
    public readonly AlgorithmIdentifier $privateKeyAlgorithm;
    /**
     * @var BinaryData Payload
     */
    public readonly BinaryData $privateKey;
    /**
     * @var BinaryData|null Corresponding DER binary data
     */
    protected readonly ?BinaryData $derBinary;


    /**
     * Constructor
     * @param Numerals $version
     * @param AlgorithmIdentifier $privateKeyAlgorithm
     * @param BinaryData $privateKey
     * @param BinaryData|null $derBinary
     */
    protected function __construct(Numerals $version, AlgorithmIdentifier $privateKeyAlgorithm, BinaryData $privateKey, ?BinaryData $derBinary)
    {
        $this->version = $version;
        $this->privateKeyAlgorithm = $privateKeyAlgorithm;
        $this->privateKey = $privateKey;
        $this->derBinary = $derBinary;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            AsnInteger::create($this->version),
            $this->privateKeyAlgorithm->to(),
            AsnOctetString::create($this->privateKey),
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->version = $this->version;
        $ret->privateKeyAlgorithm = $this->privateKeyAlgorithm;
        $ret->privateKey = $this->privateKey;
    }


    /**
     * Decode as private key instance
     * @param AsnDecoderEventHandleable|null $handle
     * @return PrivateKey
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function decode(?AsnDecoderEventHandleable $handle = null) : PrivateKey
    {
        _used($handle);

        if ($this->derBinary === null) throw new UnsupportedException();

        try {
            $pemData = Pem::encode([
                new BlockContent('PRIVATE KEY', $this->derBinary->asBase64()),
            ]);
            return PrivateKey::import($pemData);
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

        $version = AsnInteger::cast($cursor->requiresNextElement())->getInteger();
        $privateKeyAlgorithm = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $privateKey = AsnOctetString::cast($cursor->requiresNextElement())->getString();

        return new static($version, $privateKeyAlgorithm, $privateKey, $obj->encodeDer());
    }


    /**
     * Create a new instance
     * @param Numerals $version
     * @param AlgorithmIdentifier $privateKeyAlgorithm
     * @param BinaryData|string $privateKey
     * @return static
     */
    public static function create(Numerals $version, AlgorithmIdentifier $privateKeyAlgorithm, BinaryData|string $privateKey) : static
    {
        return new static($version, $privateKeyAlgorithm, BinaryData::acceptBinary($privateKey), null);
    }
}