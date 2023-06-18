<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PublicKey;
use Magpie\Cryptos\Algorithms\AsymmetricCryptos\Rsa\RsaPrivateKey;

/**
 * RSA-based algorithms
 */
abstract class Rsa extends AsymmSignature
{
    /**
     * Current asymmetric algorithm
     */
    public const ASYMM_TYPECLASS = RsaPrivateKey::TYPECLASS;


    /**
     * @param PublicKey $key
     * @return bool
     */
    public function isPublicKeySupported(PublicKey $key) : bool
    {
        return $key->getAlgoTypeClass() == static::ASYMM_TYPECLASS;
    }
}