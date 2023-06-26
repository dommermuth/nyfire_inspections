<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5dc2a80f4ac7f9bfed9aed99accf7647
{
    public static $files = array (
        '3917c79c5052b270641b5a200963dbc2' => __DIR__ . '/..' . '/kint-php/kint/init.php',
    );

    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Kint\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Kint\\' => 
        array (
            0 => __DIR__ . '/..' . '/kint-php/kint/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WP_Async_Request' => __DIR__ . '/..' . '/a5hleyrich/wp-background-processing/classes/wp-async-request.php',
        'WP_Background_Process' => __DIR__ . '/..' . '/a5hleyrich/wp-background-processing/classes/wp-background-process.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5dc2a80f4ac7f9bfed9aed99accf7647::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5dc2a80f4ac7f9bfed9aed99accf7647::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5dc2a80f4ac7f9bfed9aed99accf7647::$classMap;

        }, null, ClassLoader::class);
    }
}