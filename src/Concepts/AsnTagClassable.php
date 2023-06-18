<?php

namespace MagpieLib\CryptoAsn\Concepts;

/**
 * ASN.1 Taggable
 */
interface AsnTagClassable
{
    /**
     * Tag class
     * @return int
     */
    public static function getTagClass() : int;
}