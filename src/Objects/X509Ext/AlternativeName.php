<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\X509\GeneralNames\GeneralName;

/**
 * X.509 extension's common alternative name
 */
abstract class AlternativeName extends X509Extension
{
    /**
     * @var array<GeneralName> List of alternative names
     */
    public readonly array $names;


    /**
     * Constructor
     * @param bool $isCritical
     * @param iterable<GeneralName> $names
     */
    protected function __construct(bool $isCritical, iterable $names)
    {
        parent::__construct($isCritical);

        $this->names = iter_flatten($names, false);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->names = $this->names;
    }


    /**
     * @inheritDoc
     */
    protected static function onDecode(bool $isCritical, BinaryData $payload, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnSequence::decodeFrom($payload);
        $names = static::decodeNames($element, $handle);

        return new static($isCritical, $names);
    }


    /**
     * Decode all names
     * @param AsnSequence $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<GeneralName>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeNames(AsnSequence $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        foreach ($element->getElements() as $subElement) {
            yield GeneralName::decode($subElement, $handle);
        }
    }
}