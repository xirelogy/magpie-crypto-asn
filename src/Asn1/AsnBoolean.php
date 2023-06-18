<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Constants\AsnDisplay;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\Boolean as SopAsn1Boolean;

/**
 * ASN.1 boolean
 */
#[FactoryTypeClass(AsnBoolean::TAG, AsnElement::class)]
class AsnBoolean extends AsnPrimitiveElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'boolean';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_BOOLEAN;
    /**
     * @var SopAsn1Boolean Underlying element
     */
    protected readonly SopAsn1Boolean $elBool;


    /**
     * Constructor
     * @param SopAsn1Boolean $elBool
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Boolean $elBool, ?AsnDecoderContext $context)
    {
        parent::__construct($elBool, $context);

        $this->elBool = $elBool;
    }


    /**
     * Corresponding boolean value
     * @return bool
     * @throws CryptoException
     */
    public function getBoolean() : bool
    {
        return SopAsn1Api::wrapped(fn () => $this->elBool->value());
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return $this->getBoolean() ? AsnDisplay::BOOL_TRUE : AsnDisplay::BOOL_FALSE;
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
        return static::TAG;
    }


    /**
     * @inheritDoc
     */
    protected static function onFromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static
    {
        return new static($el->asUnspecified()->asBoolean(), $context);
    }


    /**
     * Create a new instance
     * @param bool $value
     * @return static
     * @throws CryptoException
     */
    public static function create(bool $value) : static
    {
        $elBool = SopAsn1Api::wrapped(fn () => new SopAsn1Boolean($value));

        return new static($elBool, null);
    }
}