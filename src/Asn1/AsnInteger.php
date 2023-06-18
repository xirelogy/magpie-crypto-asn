<?php

namespace MagpieLib\CryptoAsn\Asn1;

use Exception;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Cryptos\Numerals;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\General\Packs\PackContext;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Impls\SopAsn1Api;
use MagpieLib\CryptoAsn\Strategies\AsnDecoderContext;
use Sop\ASN1\Element as SopAsn1Element;
use Sop\ASN1\Feature\ElementBase as SopAsn1ElementBase;
use Sop\ASN1\Type\Primitive\Integer as SopAsn1Integer;

/**
 * ASN.1 integer (big number)
 */
#[FactoryTypeClass(AsnInteger::TAG, AsnElement::class)]
class AsnInteger extends AsnPrimitiveElement
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'integer';
    /**
     * Current tag type
     */
    public const TAG = SopAsn1Element::TYPE_INTEGER;
    /**
     * @var SopAsn1Integer Underlying element
     */
    protected readonly SopAsn1Integer $elInt;


    /**
     * Constructor
     * @param SopAsn1Integer $elInt
     * @param AsnDecoderContext|null $context
     */
    protected function __construct(SopAsn1Integer $elInt, ?AsnDecoderContext $context)
    {
        parent::__construct($elInt, $context);

        $this->elInt = $elInt;
    }


    /**
     * Corresponding number
     * @return Numerals
     * @throws CryptoException
     */
    public function getInteger() : Numerals
    {
        $bin = SopAsn1Api::wrapped(function () {
            $gmp = \gmp_init($this->elInt->number(), 10);
            $bin = \gmp_export($gmp, 1, \GMP_MSW_FIRST | \GMP_BIG_ENDIAN);
            if ($bin === false) throw new Exception('Cannot export from GMP');
            if (strlen($bin) == 0) $bin = "\x00";

            return $bin;
        });

        return Numerals::fromBinary($bin);
    }


    /**
     * Corresponding integer number value
     * @return int
     * @throws CryptoException
     */
    public function getIntegerValue() : int
    {
        return SopAsn1Api::wrapped(fn () => $this->elInt->intNumber());
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->integer = $this->getInteger();
    }


    /**
     * @inheritDoc
     */
    protected function onDumpValue() : string
    {
        return $this->getInteger();
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
    public static function getTagClass() : int
    {
        return static::TAG;
    }


    /**
     * @inheritDoc
     */
    protected static function onFromBase(SopAsn1ElementBase $el, ?AsnDecoderContext $context) : static
    {
        return new static($el->asUnspecified()->asInteger(), $context);
    }


    /**
     * Decode big endian bytes into corresponding integer (unsigned)
     * @param BinaryData|string $bytes
     * @return int
     */
    public static function decodeUnsignedIntFromBigEndian(BinaryData|string $bytes) : int
    {
        $bytes = BinaryData::acceptBinary($bytes)->asBinary();

        $bits = 0;

        $bytesLength = strlen($bytes);
        for ($i = 0; $i < $bytesLength; ++$i) {
            $n = ord(substr($bytes, $i, 1));
            $bits = ($bits << 8) + $n;
        }

        return $bits;
    }


    /**
     * Create an instance
     * @param Numerals|int $value
     * @return static
     * @throws CryptoException
     */
    public static function create(Numerals|int $value) : static
    {
        $elInt = SopAsn1Api::wrapped(function () use ($value) {
            $value = static::acceptInteger($value);
            return new SopAsn1Integer($value);
        });

        return new static($elInt, null);
    }


    /**
     * Accept integer value
     * @param Numerals|int $value
     * @return int|\GMP
     * @throws Exception
     */
    protected static function acceptInteger(Numerals|int $value) : int|\GMP
    {
        if (is_int($value)) return $value;

        $gmp = \gmp_import($value->asBinary(), 1, \GMP_MSW_FIRST | \GMP_BIG_ENDIAN);
        if ($gmp === false) throw new Exception('Cannot import to GMP');

        return $gmp;
    }
}