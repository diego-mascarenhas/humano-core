<?php

namespace Idoneo\HumanoCore\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'humano:install {--modules= : Comma-separated list of modules to install}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install Humano system with specified modules';

	/**
	 * Available modules.
	 *
	 * @var array
	 */
	protected array $availableModules = [
		'crm' => 'Customer Relationship Management',
		'billing' => 'Invoicing and Payments',
		'communications' => 'Email, Chat, and Notifications',
		'hosting' => 'Server and Domain Management',
	];

	/**
	 * Execute the console command.
	 */
	public function handle(): int
	{
		$this->info('🚀 Installing Humano System...');
		$this->newLine();

		// Get modules to install
		$modulesToInstall = $this->getModulesToInstall();

		// Display installation plan
		$this->displayInstallationPlan($modulesToInstall);

		// Confirm installation
		if (!$this->confirmInstallation()) {
			$this->warn('Installation cancelled.');
			return Command::FAILURE;
		}

		// Start installation
		$this->newLine();
		$this->info('Starting installation...');

		// Install core system
		$this->installCore();

		// Install selected modules
		foreach ($modulesToInstall as $module) {
			$this->installModule($module);
		}

		// Finalize installation
		$this->finalizeInstallation();

		$this->newLine();
		$this->info('✅ Humano system installed successfully!');
		$this->displayNextSteps();

		return Command::SUCCESS;
	}

	/**
	 * Get modules to install from options or prompt user.
	 */
	protected function getModulesToInstall(): array
	{
		$moduleOption = $this->option('modules');

		if ($moduleOption) {
			$modules = array_map('trim', explode(',', $moduleOption));
			return array_intersect($modules, array_keys($this->availableModules));
		}

		// Prompt user to select modules
		$choices = [];
		foreach ($this->availableModules as $key => $description) {
			$choices[] = "{$key} - {$description}";
		}

		$selected = $this->choice(
			'Which modules would you like to install?',
			$choices,
			null,
			null,
			true
		);

		// Extract module keys from selected choices
		return array_map(function ($choice) {
			return explode(' - ', $choice)[0];
		}, $selected);
	}

	/**
	 * Display the installation plan.
	 */
	protected function displayInstallationPlan(array $modules): void
	{
		$this->info('📋 Installation Plan:');
		$this->line('  • Core System (required)');

		foreach ($modules as $module) {
			$description = $this->availableModules[$module] ?? ucfirst($module);
			$this->line("  • {$description} Module");
		}

		$this->newLine();
	}

	/**
	 * Confirm installation with user.
	 */
	protected function confirmInstallation(): bool
	{
		return $this->confirm('Do you want to proceed with the installation?', true);
	}

	/**
	 * Install core system.
	 */
	protected function installCore(): void
	{
		$this->info('📦 Installing Core System...');

		// Run core migrations
		$this->callSilently('migrate', [
			'--path' => 'vendor/idoneo/humano-core/database/migrations',
			'--force' => true,
		]);

		// Seed core data
		$this->call('db:seed', [
			'--class' => 'Idoneo\\HumanoCore\\Database\\Seeders\\HumanoCoreSeeder',
		]);

		$this->info('  ✅ Core system installed');
	}

	/**
	 * Install a specific module.
	 */
	protected function installModule(string $module): void
	{
		$description = $this->availableModules[$module] ?? ucfirst($module);
		$this->info("📦 Installing {$description} Module...");

		$namespace = 'Idoneo\\Humano' . ucfirst($module);
		$migrationPath = "vendor/idoneo/humano-{$module}/database/migrations";

		// Run module migrations if they exist
		if (File::exists(base_path($migrationPath))) {
			$this->callSilently('migrate', [
				'--path' => $migrationPath,
				'--force' => true,
			]);
		}

		// Run module seeder if it exists
		$seederClass = "{$namespace}\\Database\\Seeders\\" . ucfirst($module) . "Seeder";
		if (class_exists($seederClass)) {
			$this->callSilently('db:seed', ['--class' => $seederClass]);
		}

		$this->info("  ✅ {$description} module installed");
	}

	/**
	 * Finalize the installation.
	 */
	protected function finalizeInstallation(): void
	{
		$this->info('🔧 Finalizing installation...');

		// Clear caches
		$this->callSilently('config:clear');
		$this->callSilently('cache:clear');
		$this->callSilently('view:clear');

		// Create storage links
		$this->callSilently('storage:link');

		$this->info('  ✅ Installation finalized');
	}

	/**
	 * Display next steps to the user.
	 */
	protected function displayNextSteps(): void
	{
		$this->newLine();
		$this->info('🎯 Next Steps:');
		$this->line('  1. Configure your .env file with database and mail settings');
		$this->line('  2. Create your first team and user');
		$this->line('  3. Access the dashboard at /dashboard');
		$this->line('  4. Configure team settings for installed modules');
		$this->newLine();
		$this->info('📚 Documentation: https://docs.humano.idoneo.com');
		$this->info('🆘 Support: dev@idoneo.com');
	}
}
