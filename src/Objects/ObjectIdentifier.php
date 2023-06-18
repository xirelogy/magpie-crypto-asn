<?php

namespace MagpieLib\CryptoAsn\Objects;

use Magpie\Codecs\Concepts\PreferStringable;

/**
 * An object identifier
 */
class ObjectIdentifier implements PreferStringable
{
    /**
     * @var string Base value (expressed as a string)
     */
    protected string $value;


    /**
     * Constructor
     * @param string $value
     */
    protected function __construct(string $value)
    {
        $this->value = $value;
    }


    /**
     * Expressed in string
     * @return string
     */
    public function getString() : string
    {
        return $this->value;
    }


    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        return $this->value;
    }


    /**
     * Construct from string
     * @param string $value
     * @return static
     */
    public static function fromString(string $value) : static
    {
        return new static($value);
    }


    /**
     * Accept as object identifier
     * @param ObjectIdentifier|string $value
     * @return static
     */
    public static function accept(self|string $value) : static
    {
        if ($value instanceof static) return $value;

        return static::fromString($value);
    }
}