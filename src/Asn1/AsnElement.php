<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Exception;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\NotOfTypeException;
use Magpie\Exceptions\NullException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Concepts\Packable;
use Magpie\General\Concepts\TypeClassable;
use Magpie\General\Factories\ClassFactory;
use Magpie\General\Packs\PackContext;
use Magpie\General\Traits\CommonPackable;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnTagClassable;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;

/**
 * ASN.1 element
 */
abstract class AsnElement implements Packable, TypeClassable, AsnTagClassable
{
    use CommonPackable;

    /**
     * @var SopAsn1Element Underlying element
     */
    protected readonly SopAsn1Element $el;
    /**
     * @var AsnDecoderContext|null Associated context
     */
    protected readonly ?AsnDecoderContext $context;


    /**
     * Constructor
     * @param SopAsn1Element $el
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Element $el, ?AsnDecoderContext $context)
    {
        $this->el = $el;
        $this->context = $context;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        $ret->typeClass = static::getTypeClass();
    }


    /**
     * Encode as DER
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function encodeDer() : BinaryData
    {
        _throwable() ?? throw new NullException();

        $ret = SopAsn1Api::wrapped(fn () => $this->el->toDER());

        return BinaryData::fromBinary($ret);
    }


    /**
     * Dump as ASN.1 structure
     * @return iterable<string>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function dump() : iterable
    {
        $level = 0;
        yield from $this->onDump($level);
    }


    /**
     * Dump as ASN.1 structure
     * @param int $level
     * @return iterable<string>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected abstract function onDump(int $level) : iterable;


    /**
     * Expect the given value to be of specific element type
     * @param AsnElement $value
     * @return static
     * @throws SafetyCommonException
     */
    public static final function cast(self $value) : static
    {
        if (!is_subclass_of($value, static::class) && !is_a($value, static::class)) throw new NotOfTypeException($value, static::class);
        return $value;
    }


    /**
     * Decode from binary data
     * @param BinaryData|string $binData
     * @param AsnDecoderContext|null $context
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public static final function decodeFrom(BinaryData|string $binData, ?AsnDecoderContext $context = null) : static
    {
        $binData = BinaryData::acceptBinary($binData)->asBinary();
        $el = SopAsn1Api::decodeDer($binData);

        return static::cast(static::_fromBase($el, $context));
    }


    /**
     * Convert from base
     * @param SopAsn1ElementBase $el
     * @param AsnDecoderContext|null $context
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     * @internal
     */
    public static final function _fromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static
    {
        if ($el->isTagged()) {
            $elTagged = SopAsn1Api::wrapped(fn () => $el->asUnspecified()->asTagged());
            $ret = AsnTaggedElement::_createSpecific($elTagged, $context);
            if ($context !== null) $ret = $context->handleTagged($ret);
            return $ret;
        }

        $tag = $el->tag();
        $className = ClassFactory::resolve($tag, self::class);
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return SopAsn1Api::wrapped(fn () => $className::onFromBase($el, $context));
    }


    /**
     * Convert from base (specifically)
     * @param SopAsn1ElementBase $el
     * @param AsnDecoderContext|null $context
     * @return static
     * @throws Exception
     */
    protected static abstract function onFromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static;


    /**
     * Format as dump output
     * @param int $level
     * @param string $payload
     * @return string
     */
    protected static final function formatDump(int $level, string $payload) : string
    {
        return str_repeat('  ', $level) . $payload;
    }
}