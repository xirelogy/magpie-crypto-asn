<?php

namespace MagpieLib\CryptoAsn\Constants;

use Magpie\General\Traits\StaticClass;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Constants\Traits\CommonAsnBitMask;

/**
 * X.509 key usage (bitmask)
 */
class X509KeyUsage
{
    use StaticClass;
    use CommonAsnBitMask;


    /**
     * Bit #0: digitalSignature
     */
    public const DIGITAL_SIGNATURE = 1;
    /**
     * Bit #1: nonRepudiation
     */
    public const NON_REPUDIATION = 2;
    /**
     * Bit #2: keyEncipherment
     */
    public const KEY_ENCIPHERMENT = 4;
    /**
     * Bit #3: dataEncipherment
     */
    public const DATA_ENCIPHERMENT = 8;
    /**
     * Bit #4: keyAgreement
     */
    public const KEY_AGREEMENT = 16;
    /**
     * Bit #5: keyCertSign
     */
    public const KEY_CERT_SIGN = 32;
    /**
     * Bit #6: cRLSign
     */
    public const CRL_SIGN = 64;
    /**
     * Bit #7: encipherOnly
     */
    public const ENCIPHER_ONLY = 128;
    /**
     * Bit #8: decipherOnly
     */
    public const DECIPHER_ONLY = 256;

    /**
     * Bit #1: Renamed as contentCommitment
     */
    public const CONTENT_COMMITMENT = self::NON_REPUDIATION;

    /**
     * Maximum bit number currently supported
     */
    protected const MAX_BIT = 8;


    /**
     * Decode as bit-string from bytes
     * @param string $bytes
     * @return iterable<int>
     */
    public static function fromBigEndianBytes(string $bytes) : iterable
    {
        $bits = AsnInteger::decodeUnsignedIntFromBigEndian($bytes);

        yield from static::getActiveBits($bits, static::MAX_BIT);
    }
}