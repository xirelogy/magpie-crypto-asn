<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\Kdf;

use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5KdfOid;
use MagpieLib\CryptoAsn\FactoryPresets\Hmac\Hmac;
use MagpieLib\CryptoAsn\Objects\Traits\CommonPkcs5Algorithm;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Pkcs5\Pbkdf2Parameters;

/**
 * PBKDF2 Key derivation functions
 * @link https://www.rfc-editor.org/rfc/rfc2898#section-5.2
 */
#[FactoryTypeClass(Pbkdf2::OID, Pkcs5KdfOid::class)]
class Pbkdf2 extends Kdf
{
    use CommonPkcs5Algorithm;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.1.5.12';

    /**
     * @var Pbkdf2Parameters Associated parameters
     */
    protected readonly Pbkdf2Parameters $parameters;


    /**
     * Constructor
     * @param AlgorithmIdentifier $identifier
     * @param Pbkdf2Parameters $parameters
     */
    protected function __construct(AlgorithmIdentifier $identifier, Pbkdf2Parameters $parameters)
    {
        parent::__construct($identifier);

        $this->parameters = $parameters;
    }


    /**
     * @inheritDoc
     */
    protected function onDerive(BinaryData $password, ?int $derivedKeyLength, ?AsnDecoderEventHandleable $handle) : BinaryData
    {
        $prfOid = $this->parameters->prf->algorithm;
        $prf = Hmac::fromOid($prfOid, $handle);

        if ($prf === null) throw new UnsupportedValueException($prfOid, _l('PBKDF2 prf'));

        $ret = hash_pbkdf2(
            $prf->getHashAlgoTypeClass(),
            $password->asBinary(),
            $this->parameters->salt->asBinary(),
            $this->parameters->iterationCount,
            $derivedKeyLength ?? $this->parameters->keyLength ?? 0,
            true,
        );

        return BinaryData::fromBinary($ret);
    }


    /**
     * @inheritDoc
     */
    protected static function constructFromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle) : static
    {
        $parametersElement = static::requiresAlgorithmIdentifierParameters($identifier);
        $parameters = Pbkdf2Parameters::from($parametersElement, $handle);

        return new static($identifier, $parameters);
    }
}