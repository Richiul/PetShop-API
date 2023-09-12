<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit868cf12a101484f9855b54e9240994d0
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Exchange\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Exchange\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit868cf12a101484f9855b54e9240994d0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit868cf12a101484f9855b54e9240994d0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit868cf12a101484f9855b54e9240994d0::$classMap;

        }, null, ClassLoader::class);
    }
}