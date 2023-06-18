<?php

namespace MagpieLib\CryptoAsn\Asn1\Supports;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\NullException;
use Magpie\Exceptions\SafetyCommonException;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Type\Structure as SopAsn1Structure;

/**
 * A cursor to iterate through ASN.1 structure
 */
class AsnStructureElementCursor
{
    /**
     * @var SopAsn1Structure Underlying element
     */
    protected readonly SopAsn1Structure $elStruct;
    /**
     * @var int Total number of elements
     */
    protected readonly int $count;
    /**
     * @var int Current cursor
     */
    protected int $cursor = 0;


    /**
     * Constructor
     * @param SopAsn1Structure $elStruct
     */
    protected function __construct(SopAsn1Structure $elStruct)
    {
        $this->elStruct = $elStruct;
        $this->count = $elStruct->count();
    }


    /**
     * Next element (required)
     * @param AsnDecoderContext|null $context
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function requiresNextElement(?AsnDecoderContext $context = null) : AsnElement
    {
        return $this->getNextElement($context) ?? throw new NullException();
    }


    /**
     * Next element
     * @param AsnDecoderContext|null $context
     * @return AsnElement|null
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function getNextElement(?AsnDecoderContext $context = null) : ?AsnElement
    {
        if ($this->cursor >= $this->count) return null;

        $ret = $this->getElementAtIndex($this->cursor, $context);
        ++$this->cursor;

        return $ret;
    }


    /**
     * Requires for next element matching given tag number
     * @param int $tag
     * @param AsnDecoderContext|null $context
     * @return AsnTaggedElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function requiresTaggedElement(int $tag, ?AsnDecoderContext $context = null) : AsnTaggedElement
    {
        return $this->getTaggedElement($tag, $context) ?? throw new NullException();
    }


    /**
     * Try to search for next element matching given tag number
     * @param int $tag
     * @param AsnDecoderContext|null $context
     * @return AsnTaggedElement|null
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function getTaggedElement(int $tag, ?AsnDecoderContext $context = null) : ?AsnTaggedElement
    {
        $tryCursor = $this->cursor;
        while ($tryCursor < $this->count) {
            $ret = $this->getElementAtIndex($tryCursor, $context);
            ++$tryCursor;

            if (!$ret instanceof AsnTaggedElement) continue;
            if ($ret->getTag() != $tag) continue;

            $this->cursor = $tryCursor;
            return $ret;
        }

        return null;
    }


    /**
     * Element at given index
     * @param int $index
     * @param AsnDecoderContext|null $context
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected function getElementAtIndex(int $index, ?AsnDecoderContext $context) : AsnElement
    {
        $el = SopAsn1Api::wrapped(fn () => $this->elStruct->at($index));
        return AsnElement::_fromBase($el, $context);
    }


    /**
     * Create an instance
     * @param SopAsn1Structure $elStruct
     * @return static
     * @internal
     */
    public static function _create(SopAsn1Structure $elStruct) : static
    {
        return new static($elStruct);
    }
}