<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Concepts\TypeClassable;
use Magpie\General\Factories\ClassFactory;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\CommonObject;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;

/**
 * Alternative name entry (X.509 extension)
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.7
 */
abstract class GeneralName extends CommonObject implements TypeClassable, AsnStaticDecodable
{
    /**
     * Constructor
     */
    protected function __construct()
    {

    }


    /**
     * Corresponding string value
     * @return string
     */
    public abstract function getValue() : string;


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->typeClass = static::getTypeClass();
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $element = AsnTaggedElement::cast($element);

        $tag = $element->getTag();
        $className = ClassFactory::safeResolve($tag, self::class);
        if ($className === null) {
            $localHandle->warnUnsupported($tag, _l('General name tag'));
            throw new UnsupportedValueException($tag, _l('General name tag'));
        }
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::onDecode($element, $handle);
    }


    /**
     * Decode from ASN.1
     * @param AsnTaggedElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static abstract function onDecode(AsnTaggedElement $element, ?AsnDecoderEventHandleable $handle) : static;
}