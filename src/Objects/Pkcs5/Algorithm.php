<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\MissingArgumentException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\CommonObject;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\OidAssociable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * Algorithm related to PKCS5
 */
abstract class Algorithm extends CommonObject implements OidAssociable
{
    /**
     * @var AlgorithmIdentifier Associated algorithm identifier
     */
    protected readonly AlgorithmIdentifier $identifier;


    /**
     * Constructor
     * @param AlgorithmIdentifier $identifier
     */
    protected function __construct(AlgorithmIdentifier $identifier)
    {
        $this->identifier = $identifier;
    }


    /**
     * Create an instance from given algorithm identifier
     * @param AlgorithmIdentifier $identifier
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public static abstract function fromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle = null) : static;


    /**
     * Construct an instance from given algorithm identifier
     * @param AlgorithmIdentifier $identifier
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static abstract function constructFromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle) : static;


    /**
     * Enforce requirement of parameters element from given algorithm identifier
     * @param AlgorithmIdentifier $identifier
     * @return AsnElement
     * @throws SafetyCommonException
     */
    protected static final function requiresAlgorithmIdentifierParameters(AlgorithmIdentifier $identifier) : AsnElement
    {
        return $identifier->parameters ?? throw new MissingArgumentException('paramters');
    }
}