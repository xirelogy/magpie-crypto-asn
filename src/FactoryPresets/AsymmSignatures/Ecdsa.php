<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\AsymmetricCryptos\Ec\EcPrivateKey;
use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PublicKey;

/**
 * ECDSA-based (Elliptic Curves) algorithms
 */
abstract class Ecdsa extends AsymmSignature
{
    /**
     * Current asymmetric algorithm
     */
    public const ASYMM_TYPECLASS = EcPrivateKey::TYPECLASS;


    /**
     * @param PublicKey $key
     * @return bool
     */
    public function isPublicKeySupported(PublicKey $key) : bool
    {
        return $key->getAlgoTypeClass() == static::ASYMM_TYPECLASS;
    }
}