<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X501;

use Magpie\Cryptos\X509\Name as MagpieX509Name;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnSet;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * X.501 Name
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.1.2.4
 * @link https://www.ietf.org/rfc/rfc4514.txt
 */
class Name extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var array<RelativeDistinguishedName> RDN attributes
     */
    public readonly array $attributes;


    /**
     * Constructor
     * @param iterable<RelativeDistinguishedName> $attributes
     */
    public function __construct(iterable $attributes)
    {
        $this->attributes = iter_flatten($attributes, false);
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        $retAttributes = [];
        foreach ($this->attributes as $attribute) {
            $retAttributes[] = $attribute->to();
        }

        return AsnSequence::create($retAttributes);
    }


    /**
     * Decode as X.509 name
     * @param AsnDecoderEventHandleable|null $handle
     * @return MagpieX509Name
     * @throws SafetyCommonException
     */
    public function decode(?AsnDecoderEventHandleable $handle = null) : MagpieX509Name
    {
        $retAttributes = [];
        foreach ($this->attributes as $attribute) {
            $retAttributes[] = $attribute->decode($handle);
        }

        return MagpieX509Name::fromAttributes($retAttributes);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $ret = [];

        $obj = AsnSequence::cast($value);
        foreach ($obj->getElements() as $objElement) {
            $set = AsnSet::cast($objElement);
            foreach ($set->getElements() as $setElement) {
                $ret[] = RelativeDistinguishedName::from($setElement, $handle);
            }
        }

        return new static($ret);
    }
}