<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb436428499b44864197b635d7963aeab
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zigaform\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zigaform\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Zigaform\\Admin\\List_data' => __DIR__ . '/../..' . '/includes/admin/class-admin-list.php',
        'Zigaform\\Template' => __DIR__ . '/../..' . '/includes/class-template.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb436428499b44864197b635d7963aeab::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb436428499b44864197b635d7963aeab::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb436428499b44864197b635d7963aeab::$classMap;

        }, null, ClassLoader::class);
    }
}
