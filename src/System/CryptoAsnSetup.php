<?php

namespace MagpieLib\CryptoAsn\System;

use Magpie\General\Factories\ClassFactory;
use Magpie\General\Traits\StaticClass;
use Magpie\System\Concepts\SystemBootable;
use Magpie\System\Kernel\BootContext;
use Magpie\System\Kernel\BootRegistrar;

/**
 * Current setup of the library
 */
class CryptoAsnSetup implements SystemBootable
{
    use StaticClass;


    /**
     * @inheritDoc
     */
    public static function systemBootRegister(BootRegistrar $registrar) : bool
    {
        $registrar->provides('crypto-asn');

        return true;
    }


    /**
     * @inheritDoc
     */
    public static function systemBoot(BootContext $context) : void
    {
        ClassFactory::includeDirectory(__DIR__ . '/../Asn1');
        ClassFactory::includeDirectory(__DIR__ . '/../Cryptos');
        ClassFactory::includeDirectory(__DIR__ . '/../FactoryPresets');
        ClassFactory::includeDirectory(__DIR__ . '/../Objects');
    }
}