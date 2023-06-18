<?php

namespace MagpieLib\CryptoAsn\Exceptions;

use Magpie\Cryptos\Exceptions\CryptoException;
use Throwable;

/**
 * Exception during DER decoding
 */
class CryptoDerDecodeException extends CryptoException
{
    /**
     * Constructor
     * @param string|null $message
     * @param string|null $subMessage
     * @param Throwable|null $previous
     * @param int $code
     */
    public function __construct(?string $message = null, ?string $subMessage = null, ?Throwable $previous = null, int $code = 0)
    {
        $message = static::formatMessage($message, $subMessage);

        parent::__construct($message, $previous, $code);
    }


    /**
     * Format message
     * @param string|null $message
     * @param string|null $subMessage
     * @return string
     */
    protected static function formatMessage(?string $message, ?string $subMessage) : string
    {
        if ($message !== null) return $message;
        if ($subMessage === null) return _l('Error while decoding DER');

        return _format_safe(_l('Error while decoding DER: {{0}}'), $subMessage) ??
            _l('Error while decoding DER');
    }
}