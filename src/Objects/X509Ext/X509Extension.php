<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Concepts\TypeClassable;
use Magpie\General\Factories\ClassFactory;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use Magpie\Objects\CommonObject;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticOptionalDecodable;
use MagpieLib\CryptoAsn\Concepts\OidAssociable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Syntaxes\X509\GenericExtension;

/**
 * X.509 extension
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.1
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2
 */
abstract class X509Extension extends CommonObject implements TypeClassable, OidAssociable, AsnStaticOptionalDecodable
{
    /**
     * @var bool If current extension is critical
     */
    public readonly bool $isCritical;


    /**
     * Constructor
     * @param bool $isCritical
     */
    protected function __construct(bool $isCritical)
    {
        $this->isCritical = $isCritical;
    }


    /**
     * If current extension is critical
     * @return bool
     */
    public function isCritical() : bool
    {
        return $this->isCritical;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->typeClass = static::getTypeClass();
        $ret->isCritical = $this->isCritical();
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $genericExt = GenericExtension::from($element, $handle);

        // Resolve for actual decoder from the extension's OID
        $oid = $genericExt->oid->getString();
        $className = ClassFactory::safeResolve($oid, X509ExtensionOid::class);
        if ($className === null) return $localHandle->warnUnsupported($oid, _l('X509 Extension OID'));
        if (!is_subclass_of($className, self::class)) return null;

        return $className::onDecode($genericExt->isCritical, $genericExt->payload, $handle);
    }


    /**
     * Decode from specific X509 extension payload
     * @param bool $isCritical
     * @param BinaryData $payload
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static abstract function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static;
}