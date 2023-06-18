<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension subject key identifier
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.2
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.2
 */
#[FactoryTypeClass(SubjectKeyIdentifier::OID, X509ExtensionOid::class)]
class SubjectKeyIdentifier extends X509Extension
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'subject-key-identifier';
    /**
     * Current OID
     */
    public const OID = '2.5.29.14';

    /**
     * @var BinaryData Corresponding key identifier
     */
    public readonly BinaryData $keyIdentifier;


    /**
     * Constructor
     * @param bool $isCritical
     * @param BinaryData $keyIdentifier
     */
    protected function __construct(bool $isCritical, BinaryData $keyIdentifier)
    {
        parent::__construct($isCritical);

        $this->keyIdentifier = $keyIdentifier;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->keyIdentifier = $this->keyIdentifier;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnOctetString::decodeFrom($payload);

        return new static($isCritical, $element->getString());
    }
}