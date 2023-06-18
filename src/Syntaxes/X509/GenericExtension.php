<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X509;

use Magpie\Objects\BinaryData;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnBoolean;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * An X.509 certificate extension's generic structure
 */
class GenericExtension extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var ObjectIdentifier Associated OID of the extension
     */
    public readonly ObjectIdentifier $oid;
    /**
     * @var bool If the extension is critical
     */
    public readonly bool $isCritical;
    /**
     * @var BinaryData Extension payload
     */
    public readonly BinaryData $payload;


    /**
     * Constructor
     * @param ObjectIdentifier $oid
     * @param bool $isCritical
     * @param BinaryData $payload
     */
    public function __construct(ObjectIdentifier $oid, bool $isCritical, BinaryData $payload)
    {
        $this->oid = $oid;
        $this->isCritical = $isCritical;
        $this->payload = $payload;
    }


    public function to() : AsnElement
    {
        $ret = [
            AsnObjectIdentifier::create($this->oid),
        ];

        if ($this->isCritical) {
            $ret[] = AsnBoolean::create($this->isCritical);
        }

        $ret[] = AsnOctetString::create($this->payload);

        return AsnSequence::create($ret);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $oid = AsnObjectIdentifier::cast($cursor->requiresNextElement())->getOid();

        $isCritical = false;
        $element = $cursor->requiresNextElement();
        if ($element instanceof AsnBoolean) {
            $isCritical = $element->getBoolean();
            $element = $cursor->requiresNextElement();
        }

        $payload = AsnOctetString::cast($element)->getString();

        return new static($oid, $isCritical, $payload);
    }
}
