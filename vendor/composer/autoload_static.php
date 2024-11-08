<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba5ec13b608a9d093bc629ca1333162d
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Bookster_Paypal\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Bookster_Paypal\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitba5ec13b608a9d093bc629ca1333162d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba5ec13b608a9d093bc629ca1333162d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitba5ec13b608a9d093bc629ca1333162d::$classMap;

        }, null, ClassLoader::class);
    }
}
