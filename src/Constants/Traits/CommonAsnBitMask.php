<?php

namespace MagpieLib\CryptoAsn\Constants\Traits;

trait CommonAsnBitMask
{
    /**
     * Get active bits according to bitmask
     * @param int $bits
     * @param int $maxBitPos
     * @return iterable<int>
     */
    protected static function getActiveBits(int $bits, int $maxBitPos) : iterable
    {
        $ret = [];
        $bv = 1;
        for ($b = 0; $b < $maxBitPos + 1; ++$b) {
            if (($bits & $bv) != 0) $ret[] = $bv;
            $bv <<= 1;
        }

        return $ret;
    }
}