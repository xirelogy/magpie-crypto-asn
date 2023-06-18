<?php

namespace MagpieLib\CryptoAsn\Cryptos;

use Magpie\Cryptos\Context;
use Magpie\Cryptos\Context as MagpieContext;
use Magpie\Cryptos\Providers\CertificateImporter;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\System\Kernel\BootRegistrar;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

#[FactoryTypeClass(OpenContext::TYPECLASS, MagpieContext::class)]
class OpenContext extends MagpieContext
{
    /**
     * Current type class
     */
    public const TYPECLASS = 'open';
    /**
     * @var AsnDecoderEventHandleable|null Current decoder event handle
     */
    protected static ?AsnDecoderEventHandleable $handle = null;


    /**
     * The current (global) decoder event handle
     * @return AsnDecoderEventHandleable|null
     */
    public static function getDecoderEventHandle() : ?AsnDecoderEventHandleable
    {
        return static::$handle;
    }


    /**
     * The current (global) decoder event handle
     * @param AsnDecoderEventHandleable|null $handle
     * @return void
     */
    public static function setDecoderEventHandle(?AsnDecoderEventHandleable $handle) : void
    {
        static::$handle = $handle;
    }


    /**
     * Use OpenContext as default certificate importing context
     * @return void
     * @throws SafetyCommonException
     */
    public static function registerAsDefaultCertificateContext() : void
    {
        CertificateImporter::registerDefaultContext(Context::initialize(static::TYPECLASS));
    }


    /**
     * @inheritDoc
     */
    public static function getTypeClass() : string
    {
        return static::TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    protected static function specificInitialize() : static
    {
        return new static();
    }


    /**
     * @inheritDoc
     */
    public static function systemBootRegister(BootRegistrar $registrar) : bool
    {
        return true;
    }
}