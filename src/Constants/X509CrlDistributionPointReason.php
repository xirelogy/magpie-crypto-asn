<?php

namespace MagpieLib\CryptoAsn\Constants;

use Magpie\General\Traits\StaticClass;
use MagpieLib\CryptoAsn\Asn1\AsnInteger;
use MagpieLib\CryptoAsn\Constants\Traits\CommonAsnBitMask;

/**
 * X.509 CRL distribution point reason (bitmask)
 */
class X509CrlDistributionPointReason
{
    use StaticClass;
    use CommonAsnBitMask;


    /**
     * Bit #0: unused
     */
    public const UNUSED = 1;
    /**
     * Bit #1: keyCompromise
     */
    public const KEY_COMPROMISE = 2;
    /**
     * Bit #2: caCompromise
     */
    public const CA_COMPROMISE = 4;
    /**
     * Bit #3: affiliationChanged
     */
    public const AFFILIATION_CHANGED = 8;
    /**
     * Bit #4: superseded
     */
    public const SUPERSEDED = 16;
    /**
     * Bit #5: cessationOfOperation
     */
    public const CESSATION_OF_OPERATION = 32;
    /**
     * Bit #6: certificateHold
     */
    public const CERTIFICATE_HOLD = 64;
    /**
     * Bit #7: privilegeWithdrawn
     */
    public const PRIVILEGE_WITHDRAWN = 128;
    /**
     * Bit #8: aACompromise
     */
    public const AA_COMPROMISE = 256;

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