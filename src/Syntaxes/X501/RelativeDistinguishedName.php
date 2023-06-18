<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X501;

use Magpie\Cryptos\X509\NameAttribute;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnDisplayStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnUtf8String;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\FactoryPresets\X501\AttributeType;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * X.501 Relative distinguished name
 * @link https://www.ietf.org/rfc/rfc4514.txt
 */
class RelativeDistinguishedName extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var ObjectIdentifier RDN type
     */
    public readonly ObjectIdentifier $type;
    /**
     * @var string RDN value
     */
    public readonly string $value;


    /**
     * Constructor
     * @param ObjectIdentifier $type
     * @param string $value
     */
    public function __construct(ObjectIdentifier $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            AsnObjectIdentifier::create($this->type),
            AsnUtf8String::create($this->value),
        ]);
    }


    /**
     * Decode as X.509 name attribute
     * @param AsnDecoderEventHandleable|null $handle
     * @return NameAttribute
     * @throws SafetyCommonException
     */
    public function decode(?AsnDecoderEventHandleable $handle = null) : NameAttribute
    {
        $oid = $this->type->getString();
        $attrType = AttributeType::fromOid($oid, $handle);
        if ($attrType === null) throw new UnsupportedValueException($oid);

        return new NameAttribute($attrType->getShortName(), $this->value);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $type = AsnObjectIdentifier::cast($cursor->requiresNextElement())->getOid();
        $value = AsnDisplayStringElement::cast($cursor->requiresNextElement())->getString();

        return new static($type, $value);
    }
}