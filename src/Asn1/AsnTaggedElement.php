<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnexpectedException;
use Magpie\Exceptions\UnsupportedValueException;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType as SopAsn1ExplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType as SopAsn1ImplicitlyTaggedType;
use Sop\ASN1\Type\TaggedType as SopAsn1TaggedType;

/**
 * ASN.1 Tagged element
 */
class AsnTaggedElement extends AsnElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'tagged';
    /**
     * @var SopAsn1TaggedType Underlying element
     */
    protected readonly SopAsn1TaggedType $elTagged;


    /**
     * Constructor
     * @param SopAsn1TaggedType $elTagged
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1TaggedType $elTagged, ?AsnDecoderContext $context)
    {
        parent::__construct($elTagged, $context);

        $this->elTagged = $elTagged;
    }


    /**
     * Tag number
     * @return int
     */
    public function getTag() : int
    {
        return $this->elTagged->tag();
    }


    /**
     * Implicitly convert the tagged entry as specific element
     * @param string $className
     * @param AsnDecoderContext|null $context
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function implicit(string $className, ?AsnDecoderContext $context = null) : AsnElement
    {
        if (!is_subclass_of($className, AsnElement::class)) throw new ClassNotOfTypeException($className, AsnElement::class);

        $tagClass = $className::getTagClass();
        if ($tagClass < 0) throw new UnsupportedValueException($className);

        $el = $this->elTagged->asImplicit($tagClass, $this->elTagged->tag());
        return AsnElement::_fromBase($el, $context ?? $this->context);
    }


    /**
     * Explicitly convert the tagged entry as specific element
     * @param AsnDecoderContext|null $context
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function explicit(?AsnDecoderContext $context = null) : AsnElement
    {
        $el = $this->elTagged->asExplicit($this->elTagged->tag());
        return AsnElement::_fromBase($el, $context ?? $this->context);
    }


    /**
     * @inheritDoc
     */
    protected function onDump(int $level) : iterable
    {
        yield static::formatDump($level, 'tagged[' . $this->elTagged->tag() . ']');
    }


    /**
     * @inheritDoc
     */
    public static function getTypeClass() : string
    {
        return static::TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    public static function getTagClass() : int
    {
        return -1;  // Purposely undefined
    }


    /**
     * @inheritDoc
     */
    protected static function onFromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static
    {
        throw new UnexpectedException();
    }


    /**
     * Create specific type
     * @param SopAsn1TaggedType $elTagged
     * @param AsnDecoderContext|null $context
     * @return static
     * @internal
     */
    public static function _createSpecific(SopAsn1TaggedType $elTagged, ?AsnDecoderContext $context) : static
    {
        return new static($elTagged, $context);
    }


    /**
     * Create a new explicit instance
     * @param int $tag
     * @param AsnElement $element
     * @return static
     * @throws CryptoException
     */
    public static function createExplicit(int $tag, AsnElement $element) : static
    {
        $elTagged = SopAsn1Api::wrapped(fn () => new SopAsn1ExplicitlyTaggedType($tag, $element->el));

        return new static($elTagged, null);
    }


    /**
     * Create a new implicit instance
     * @param int $tag
     * @param AsnElement $element
     * @return static
     * @throws CryptoException
     */
    public static function createImplicit(int $tag, AsnElement $element) : static
    {
        $elTagged = SopAsn1Api::wrapped(fn () => new SopAsn1ImplicitlyTaggedType($tag, $element->el));

        return new static($elTagged, null);
    }
}