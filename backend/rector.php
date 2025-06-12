<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

    // Skip vendor and other directories
    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/storage',
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/node_modules',
        __DIR__ . '/public',
        // Skip some specific files that might cause issues
        __DIR__ . '/config/app.php',
        __DIR__ . '/bootstrap/app.php',
    ]);

    // Import rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
        SetList::STRICT_BOOLEANS,
        SetList::RECTOR_PRESET,
    ]);

    // Configure specific rules
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(AddOverrideAttributeToOverriddenMethodsRector::class);
    
    // Skip readonly class rule for now as it might be too aggressive for Laravel
    $rectorConfig->skip([
        ReadOnlyClassRector::class,
        // Skip some Laravel-specific patterns that Rector might incorrectly "fix"
        \Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector::class => [
            __DIR__ . '/app/Providers',
            __DIR__ . '/database/migrations',
            __DIR__ . '/database/seeders',
        ],
    ]);

    // Parallel processing for faster execution
    $rectorConfig->parallel();
    
    // Import names to clean up use statements
    $rectorConfig->importNames();
    
    // Cache configuration for better performance
    $rectorConfig->cacheDirectory(__DIR__ . '/storage/cache/rector');
};
