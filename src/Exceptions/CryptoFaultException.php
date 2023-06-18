<?php

namespace MagpieLib\CryptoAsn\Exceptions;

use Magpie\Cryptos\Exceptions\CryptoException;
use Throwable;

/**
 * An exception due to fault in crypto (generally)
 */
class CryptoFaultException extends CryptoException
{
    /**
     * Constructor
     * @param string|null $message
     * @param Throwable|null $previous
     * @param int $code
     */
    public function __construct(?string $message = null, ?Throwable $previous = null, int $code = 0)
    {
        $message = $message ?? _l('General crypto fault');

        parent::__construct($message, $previous, $code);
    }
}