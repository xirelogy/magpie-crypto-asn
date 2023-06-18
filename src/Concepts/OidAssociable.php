<?php

namespace MagpieLib\CryptoAsn\Concepts;

/**
 * May have OID associated
 */
interface OidAssociable
{
    /**
     * Corresponding OID
     * @return string
     */
    public static function getOid() : string;
}