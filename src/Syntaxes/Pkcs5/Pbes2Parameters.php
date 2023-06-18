<?php

namespace MagpieLib\CryptoAsn\Syntaxes\Pkcs5;

use Magpie\General\Packs\PackContext;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;
use MagpieLib\CryptoAsn\Syntaxes\Syntax;

class Pbes2Parameters extends Syntax
{
    /**
     * @var AlgorithmIdentifier Respective KDF used
     */
    public readonly AlgorithmIdentifier $keyDerivationFunc;
    /**
     * @var AlgorithmIdentifier Encryption scheme used
     */
    public readonly AlgorithmIdentifier $encryptionScheme;


    /**
     * Constructor
     * @param AlgorithmIdentifier $keyDerivationFunc
     * @param AlgorithmIdentifier $encryptionScheme
     */
    protected function __construct(AlgorithmIdentifier $keyDerivationFunc, AlgorithmIdentifier $encryptionScheme)
    {
        $this->keyDerivationFunc = $keyDerivationFunc;
        $this->encryptionScheme = $encryptionScheme;
    }


    /**
     * @inheritDoc
     */
    public function to() : AsnElement
    {
        return AsnSequence::create([
            $this->keyDerivationFunc->to(),
            $this->encryptionScheme->to(),
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function onPack(object $ret, PackContext $context) : void
    {
        parent::onPack($ret, $context);

        $ret->keyDerivationFunc = $this->keyDerivationFunc;
        $ret->encryptionScheme = $this->encryptionScheme;
    }


    /**
     * @inheritDoc
     */
    public static function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $obj = AsnSequence::cast($value);
        $cursor = $obj->iterate();

        $keyDerivationFunc = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);
        $encryptionScheme = AlgorithmIdentifier::from($cursor->requiresNextElement(), $handle);

        return new static($keyDerivationFunc, $encryptionScheme);
    }
}