<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\Supports\AsnStructureElementCursor;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Type\Structure as SopAsn1Structure;

/**
 * ASN.1 structure element
 */
abstract class AsnStructureElement extends AsnElement
{
    /**
     * @var SopAsn1Structure Underlying element
     */
    protected readonly SopAsn1Structure $elStruct;


    /**
     * Constructor
     * @param SopAsn1Structure $elStruct
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Structure $elStruct, ?AsnDecoderContext $context)
    {
        parent::__construct($elStruct, $context);

        $this->elStruct = $elStruct;
    }


    /**
     * Total number of elements
     * @return int
     * @throws CryptoException
     */
    public final function getElementsCount() : int
    {
        return SopAsn1Api::wrapped(fn () => $this->elStruct->count());
    }


    /**
     * Children elements
     * @return iterable<AsnElement>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function getElements() : iterable
    {
        /** @var iterable<SopAsn1Element> $els */
        $els = SopAsn1Api::wrapped(fn () => $this->elStruct->elements());

        foreach ($els as $el) {
            yield AsnElement::_fromBase($el, $this->context);
        }
    }


    /**
     * Element at index
     * @param int $index
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function getElementAt(int $index) : AsnElement
    {
        $el = SopAsn1Api::wrapped(fn () => $this->elStruct->at($index));
        return AsnElement::_fromBase($el, $this->context);
    }


    /**
     * Iterate through the elements
     * @return AsnStructureElementCursor
     */
    public final function iterate() : AsnStructureElementCursor
    {
        return AsnStructureElementCursor::_create($this->elStruct);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->elements = $this->getElements();
    }


    /**
     * @inheritDoc
     */
    protected final function onDump(int $level) : iterable
    {
        yield static::formatDump($level, static::getTypeClass() . ': [');

        foreach ($this->getElements() as $element) {
            yield from $element->onDump($level + 1);
        }

        yield static::formatDump($level, ']');
    }


    /**
     * Create a new instance
     * @param iterable<AsnElement> $elements
     * @return static
     * @throws CryptoException
     */
    public static abstract function create(iterable $elements) : static;


    /**
     * Accept elements
     * @param iterable<AsnElement|null> $elements
     * @return iterable<SopAsn1Element>
     */
    protected static function acceptElements(iterable $elements) : iterable
    {
        foreach ($elements as $element) {
            if ($element === null) continue;
            yield $element->el;
        }
    }
}