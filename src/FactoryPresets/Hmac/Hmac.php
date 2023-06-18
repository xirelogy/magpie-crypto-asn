<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\Hmac;

use Magpie\General\Factories\ClassFactory;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\OidAssociable;
use MagpieLib\CryptoAsn\Factories\HmacOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;

/**
 * Digest / HMAC (Hash-based message authentication code) algorithms
 */
abstract class Hmac implements OidAssociable
{
    /**
     * The hash algorithm
     * @return string
     */
    public abstract function getHashAlgoTypeClass() : string;


    /**
     * Resolve from OID
     * @param string $oid
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     */
    public static function fromOid(string $oid, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $className = ClassFactory::safeResolve($oid, HmacOid::class);
        if ($className === null) return $localHandle->warnUnsupported($oid, _l('HMAC OID'));
        if (!is_subclass_of($className, self::class)) return null;

        return new $className();
    }
}