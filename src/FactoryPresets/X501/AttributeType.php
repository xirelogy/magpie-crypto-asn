<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\ClassFactory;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\OidAssociable;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;

/**
 * X.501 attribute type
 */
abstract class AttributeType implements OidAssociable
{
    /**
     * Short name
     * @return string
     */
    public abstract function getShortName() : string;


    /**
     * Long name
     * @return string
     */
    public abstract function getLongName() : string;


    /**
     * Resolve from OID
     * @param string $oid
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     */
    public static function fromOid(string $oid, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $className = ClassFactory::safeResolve($oid, X501AttributeTypeOid::class);
        if ($className === null) return $localHandle->warnUnsupported($oid, _l('X501 attribute OID'));
        if (!is_subclass_of($className, self::class)) return null;

        return new $className();
    }


    /**
     * Resolve from short name
     * @param string $shortName
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     */
    public static function fromShortName(string $shortName, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $className = ClassFactory::safeResolve($shortName, X501AttributeTypeShortName::class);
        if ($className === null) return $localHandle->warnUnsupported($shortName, _l('X501 attribute short name'));
        if (!is_subclass_of($className, self::class)) return null;

        return new $className();
    }
}