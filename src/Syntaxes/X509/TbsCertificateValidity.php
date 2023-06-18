<?php

namespace MagpieLib\CryptoAsn\Syntaxes\X509;

use Carbon\CarbonInterface;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnGeneralizedTime;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Asn1\AsnTimeElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

/**
 * The TBSCertificate's validity within an X.509 certificate
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.1.2.5
 */
class TbsCertificateValidity extends Syntax
{
    use CommonObjectPackAll;

    /**
     * @var CarbonInterface When validity period begins
     */
    public readonly CarbonInterface $notBefore;
    /**
     * @var CarbonInterface When validity period ends
     */
    public readonly CarbonInterface $notAfter;


    /**
     * Constructor
     * @param CarbonInterface $notBefore
     * @param CarbonInterface $notAfter
     */
    public function __construct(CarbonInterface $notBefore, CarbonInterface $notAfter)
    {
        $this->notBefore = $notBefore;
        $this->notAfter = $notAfter;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            AsnGeneralizedTime::create($this->notBefore),
            AsnGeneralizedTime::create($this->notAfter),
        ]);
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $notBefore = AsnTimeElement::cast($cursor->requiresNextElement())->getTime();
        $notAfter = AsnTimeElement::cast($cursor->requiresNextElement())->getTime();

        return new static($notBefore, $notAfter);
    }
}