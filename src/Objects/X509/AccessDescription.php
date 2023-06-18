<?php

namespace MagpieLib\CryptoAsn\Objects\X509;

use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;
use MagpieLib\CryptoAsn\Objects\X509\GeneralNames\GeneralName;

/**
 * Access description for authority/subject info access (X.509 Extension)
 */
class AccessDescription extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var ObjectIdentifier Access method
     */
    public readonly ObjectIdentifier $accessMethod;
    /**
     * @var GeneralName Access location
     */
    public readonly GeneralName $accessLocation;


    /**
     * Constructor
     * @param ObjectIdentifier $accessMethod
     * @param GeneralName $accessLocation
     */
    protected function __construct(ObjectIdentifier $accessMethod, GeneralName $accessLocation)
    {
        $this->accessMethod = $accessMethod;
        $this->accessLocation = $accessLocation;
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $element = AsnSequence::cast($element);

        $accessMethod = AsnObjectIdentifier::cast($element->getElementAt(0))->getOid();
        $accessLocation = GeneralName::decode($element->getElementAt(1));

        return new static($accessMethod, $accessLocation);
    }
}