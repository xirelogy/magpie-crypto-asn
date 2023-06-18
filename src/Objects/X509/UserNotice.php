<?php

namespace MagpieLib\CryptoAsn\Objects\X509;

use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnDisplayStringElement;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;

/**
 * User notice
 */
class UserNotice extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var NoticeReference|null Notice reference (if any)
     */
    public readonly ?NoticeReference $noticeRef;
    /**
     * @var string|null Notice text (if any)
     */
    public readonly ?string $explicitText;


    /**
     * Constructor
     * @param NoticeReference|null $noticeRef
     * @param string|null $explicitText
     */
    public function __construct(?NoticeReference $noticeRef, ?string $explicitText)
    {
        $this->noticeRef = $noticeRef;
        $this->explicitText = $explicitText;
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $element = AsnSequence::cast($element);
        $cursor = $element->iterate();

        $noticeRef = null;
        $explicitText = null;

        $childElement = $cursor->getNextElement();
        if ($childElement instanceof AsnSequence) {
            $noticeRef = NoticeReference::decode($childElement, $handle);
            $childElement = $cursor->getNextElement();
        }

        if ($childElement instanceof AsnDisplayStringElement) {
            $explicitText = $childElement->getString();
        }

        return new static($noticeRef, $explicitText);
    }
}