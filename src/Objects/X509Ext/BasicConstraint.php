<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnBoolean;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension subject key identifier
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.10
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.9
 */
#[FactoryTypeClass(BasicConstraint::OID, X509ExtensionOid::class)]
class BasicConstraint extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'basic-constraint';
    /**
     * Current OID
     */
    public const OID = '2.5.29.19';

    /**
     * @var bool If certificate subject is a CA
     */
    public readonly bool $isCa;
    /**
     * @var int Maximum path length
     */
    public readonly int $maxPathLength;


    /**
     * Constructor
     * @param bool $isCritical
     * @param bool $isCa
     * @param int $maxPathLength
     */
    protected function __construct(bool $isCritical, bool $isCa, int $maxPathLength)
    {
        parent::__construct($isCritical);

        $this->isCa = $isCa;
        $this->maxPathLength = $maxPathLength;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->isCa = $this->isCa;
        $ret->maxPathLength = $this->maxPathLength;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);
        $cursor = $element->iterate();

        $isCa = false;
        $maxPathLength = 0;

        $childElement = $cursor->getNextElement();
        if ($childElement instanceof AsnBoolean) {
            $isCa = $childElement->getBoolean();
            $childElement = $cursor->getNextElement();
        }

        if ($childElement instanceof AsnInteger) {
            $maxPathLength = $childElement->getIntegerValue();
        }

        return new static($isCritical, $isCa, $maxPathLength);
    }
}