<?php

namespace MagpieLib\CryptoAsn\Objects\X509;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Cryptos\Numerals;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnDisplayStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;

/**
 * Notice reference
 */
class NoticeReference extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var string Organization of the notice reference
     */
    public readonly string $organization;
    /**
     * @var array<Numerals> Notice numbers
     */
    public readonly array $noticeNumbers;


    /**
     * Constructor
     * @param string $organization
     * @param iterable<Numerals> $noticeNumbers
     */
    public function __construct(string $organization, iterable $noticeNumbers)
    {
        $this->organization = $organization;
        $this->noticeNumbers = iter_flatten($noticeNumbers, false);
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        _used($handle);

        $element = AsnSequence::cast($element);
        $organization = AsnDisplayStringElement::cast($element->getElementAt(0))->getString();
        $noticeNumbers = static::decodeNoticeNumbers($element->getElementAt(1));

        return new static($organization, $noticeNumbers);
    }


    /**
     * Decode notice numbers
     * @param AsnElement $element
     * @return iterable<Numerals>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeNoticeNumbers(AsnElement $element) : iterable
    {
        $element = AsnSequence::cast($element);
        foreach ($element->getElements() as $subElement) {
            $subElement = AsnInteger::cast($subElement);
            yield $subElement->getInteger();
        }
    }
}