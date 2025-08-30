<?php

namespace Idoneo\HumanoCore\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Idoneo\HumanoCore\Console\InstallCommand;

class HumanoCoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('humano-core')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
            ->hasMigrations()
            ->hasRoutes('web')
            ->hasCommands([
                InstallCommand::class,
            ]);
    }
}
