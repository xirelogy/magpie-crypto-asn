<?php

namespace MagpieLib\CryptoAsn;

use Magpie\Cryptos\Contents\CryptoFormatContent;
use Magpie\Cryptos\Contents\DerCryptoFormatContent;
use Magpie\Cryptos\Contents\PemCryptoFormatContent;
use Magpie\Cryptos\Encodings\Pem;
use Magpie\Exceptions\PersistenceException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\StreamException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Traits\StaticClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Objects\BinaryBlockContent;

/**
 * DER support
 */
class Der
{
    use StaticClass;


    /**
     * Decode and get for DER bytes
     * @param CryptoFormatContent $source
     * @return iterable<BinaryBlockContent>
     * @throws SafetyCommonException
     * @throws PersistenceException
     * @throws StreamException
     */
    public static function getDerBytes(CryptoFormatContent $source) : iterable
    {
        if ($source instanceof PemCryptoFormatContent) {
            // Treated as PEM
            $data = $source->data->getData();

            // When PEM header not expected, decode directly
            if (!Pem::hasContentType($data)) {
                yield new BinaryBlockContent(null, BinaryData::fromBase64($data));
                return;
            }

            // Otherwise, iterate through all blocks
            foreach (Pem::decode($data) as $pemBlock) {
                yield new BinaryBlockContent($pemBlock->type, BinaryData::fromBase64($pemBlock->data));
            }

            return;
        }

        if ($source instanceof DerCryptoFormatContent) {
            // Treated as DER
            yield new BinaryBlockContent(null, BinaryData::fromBinary($source->data->getData()));
            return;
        }

        throw new UnsupportedValueException($source);
    }
}