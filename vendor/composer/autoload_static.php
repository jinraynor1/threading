<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita60b16b5e3f1289d2184ba4c466dbcac
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pcntl' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInita60b16b5e3f1289d2184ba4c466dbcac::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
