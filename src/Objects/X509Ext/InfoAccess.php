<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\X509\AccessDescription;

/**
 * X.509 extension's common info access
 */
abstract class InfoAccess extends X509Extension
{
    /**
     * @var array<AccessDescription> Access descriptions
     */
    public readonly array $descriptions;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable<AccessDescription> $descriptions
     */
    protected function __construct(bool $isCritical, iterable $descriptions)
    {
        parent::__construct($isCritical);

        $this->descriptions = iter_flatten($descriptions, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->descriptions = $this->descriptions;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);
        $descriptions = static::decodeDescriptions($element, $handle);

        return new static($isCritical, $descriptions);
    }


    /**
     * Decode for all descriptions
     * @param AsnSequence $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<AccessDescription>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeDescriptions(AsnSequence $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        foreach ($element->getElements() as $subElement) {
            yield AccessDescription::decode($subElement, $handle);
        }
    }
}