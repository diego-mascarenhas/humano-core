<?php

namespace Idoneo\HumanoCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class HumanoCoreServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		// Merge configuration
		$this->mergeConfigFrom(__DIR__ . '/../../config/humano-core.php', 'humano-core');

		// Register commands
		if ($this->app->runningInConsole()) {
			$this->commands([
				\Idoneo\HumanoCore\Console\InstallCommand::class,
			]);
		}
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// Load migrations
		$this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

		// Load views
		$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'humano-core');

		// Load translations
		$this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'humano-core');

		// Load routes
		$this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

		// Publish configuration
		if ($this->app->runningInConsole())
		{
			$this->publishes([
				__DIR__ . '/../../config/humano-core.php' => config_path('humano-core.php'),
			], 'humano-core-config');

			// Publish views
			$this->publishes([
				__DIR__ . '/../../resources/views' => resource_path('views/vendor/humano-core'),
			], 'humano-core-views');

			// Publish translations
			$this->publishes([
				__DIR__ . '/../../resources/lang' => $this->app->langPath('vendor/humano-core'),
			], 'humano-core-lang');
		}
	}
}
