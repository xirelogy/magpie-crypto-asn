<?php

namespace MagpieLib\CryptoAsn\Objects\X509\GeneralNames;

use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * Alternative name entry - IP address (X.509 extension)
 */
#[FactoryTypeClass(IpAddressGeneralName::TAG, GeneralName::class)]
class IpAddressGeneralName extends GeneralName
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'ip';
    /**
     * Current tag
     */
    public const TAG = 7;
    /**
     * @var string IP address
     */
    public readonly string $value;


    /**
     * Constructor
     * @param BinaryData $value
     * @throws SafetyCommonException
     */
    protected function __construct(BinaryData $value)
    {
        parent::__construct();

        $this->value = static::decodeIpAddress($value);
    }


    /**
     * @inheritDoc
     */
    public function getValue() : string
    {
        return 'IP Address:' . $this->value;
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->value = $this->value;
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
    protected static function onDecode(AsnTaggedElement $element, ?AsnDecoderEventHandleable $handle) : static
    {
        $element = AsnOctetString::cast($element->implicit(AsnOctetString::class));
        return new static($element->getString());
    }


    /**
     * Decode as IP address
     * @param BinaryData $data
     * @return string
     * @throws SafetyCommonException
     */
    protected static function decodeIpAddress(BinaryData $data) : string
    {
        $dataStr = $data->asBinary();
        $dataLen = strlen($dataStr);

        if ($dataLen === 4) {
            return implode('.', [
                ord($dataStr[0]),
                ord($dataStr[1]),
                ord($dataStr[2]),
                ord($dataStr[3]),
            ]);
        }

        if ($dataLen === 16) {
            $ret = [];
            for ($i = 0; $i < 16; $i += 2) {
                $block = substr($dataStr, $i, 2);
                $hex = BinaryData::fromBinary($block)->asLowerHex();
                while (str_starts_with($hex, '0') && strlen($hex) > 1) {
                    $hex = substr($hex, 1);
                }
                $ret[] = $hex;
            }
            return implode(':', $ret);
        }

        throw new UnsupportedValueException($data);
    }
}