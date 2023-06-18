<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\Scheme;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5SchemeOid;
use MagpieLib\CryptoAsn\Objects\Pkcs5\Kdf\Kdf;
use MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo\SymmAlgo;
use MagpieLib\CryptoAsn\Objects\Traits\CommonPkcs5Algorithm;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Pkcs5\Pbes2Parameters;

/**
 * PBES2 Encryption Scheme
 * @link https://www.rfc-editor.org/rfc/rfc8018#section-6.2
 */
#[FactoryTypeClass(Pbes2::OID, Pkcs5SchemeOid::class)]
class Pbes2 extends Scheme
{
    use CommonPkcs5Algorithm;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.1.5.13';

    /**
     * @var Pbes2Parameters Associated parameters
     */
    protected readonly Pbes2Parameters $parameters;


    /**
     * Constructor
     * @param AlgorithmIdentifier $identifier
     * @param Pbes2Parameters $parameters
     */
    protected function __construct(AlgorithmIdentifier $identifier, Pbes2Parameters $parameters)
    {
        parent::__construct($identifier);

        $this->parameters = $parameters;
    }


    /**
     * @inheritDoc
     */
    protected function onDecrypt(BinaryData $payload, BinaryData $password, ?AsnDecoderEventHandleable $handle) : BinaryData
    {
        $kdf = Kdf::fromAlgorithmIdentifier($this->parameters->keyDerivationFunc, $handle);
        $algo = SymmAlgo::fromAlgorithmIdentifier($this->parameters->encryptionScheme, $handle);

        $key = $kdf->derive($password, $algo->getKeyLength(), $handle);
        return $algo->decrypt($payload, $key, $handle);
    }


    /**
     * @inheritDoc
     */
    protected static function constructFromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle) : static
    {
        $parametersElement = static::requiresAlgorithmIdentifierParameters($identifier);
        $parameters = Pbes2Parameters::from($parametersElement, $handle);

        return new static($identifier, $parameters);
    }
}