<?php

namespace MagpieLib\CryptoAsn\Syntaxes;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnNull;
use MagpieLib\CryptoAsn\Asn1\AsnObjectIdentifier;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Objects\ObjectIdentifier;

/**
 * AlgorithmIdentifier as defined in X.509
 */
class AlgorithmIdentifier extends Syntax
{
    /**
     * @var ObjectIdentifier Algorithm
     */
    public readonly ObjectIdentifier $algorithm;
    /**
     * @var AsnElement|null Parameters as defined by algorithm
     */
    public readonly ?AsnElement $parameters;


    /**
     * Constructor
     * @param ObjectIdentifier $algorithm
     * @param AsnElement|null $parameters
     */
    public function __construct(ObjectIdentifier $algorithm, ?AsnElement $parameters)
    {
        $this->algorithm = $algorithm;
        $this->parameters = $parameters;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            AsnObjectIdentifier::create($this->algorithm),
            $this->parameters,
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        $ret->algorithm = $this->algorithm;
        $ret->parameters = $this->parameters;
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $version = AsnObjectIdentifier::cast($cursor->requiresNextElement())->getOid();
        $parameters = $cursor->getNextElement();

        return new static($version, $parameters);
    }


    /**
     * Create a new instance
     * @param ObjectIdentifier|string $oid
     * @param AsnElement|null $parameters
     * @return static
     */
    public static function create(ObjectIdentifier|string $oid, ?AsnElement $parameters) : static
    {
        return new static(ObjectIdentifier::accept($oid), $parameters);
    }


    /**
     * Create a new instance with 'null' as parameter
     * @param ObjectIdentifier|string $oid
     * @return static
     * @throws CryptoException
     */
    public static function createWithNull(ObjectIdentifier|string $oid) : static
    {
        return new static(ObjectIdentifier::accept($oid), AsnNull::create());
    }
}