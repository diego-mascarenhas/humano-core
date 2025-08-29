<?php

namespace Idoneo\HumanoCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
	/**
	 * Display the main dashboard.
	 */
	public function index(Request $request): View
	{
		$user = auth()->user();
		$team = $user->currentTeam;

		// Get recent activities
		$recentActivities = Activity::query()
			->where('subject_type', 'App\Models\Team')
			->where('subject_id', $team->id)
			->orWhere('causer_id', $user->id)
			->orderBy('created_at', 'desc')
			->limit(10)
			->get();

		// Get team statistics
		$teamStats = $this->getTeamStatistics($team);

		// Get module status
		$moduleStatus = $this->getModuleStatus($team);

		return view('humano-core::dashboard.analytics', compact(
			'user',
			'team',
			'recentActivities',
			'teamStats',
			'moduleStatus'
		));
	}

	/**
	 * Get team statistics.
	 */
	private function getTeamStatistics($team): array
	{
		return [
			'total_users' => $team->users()->count(),
			'active_modules' => $team->modules()->active()->count(),
			'total_activities' => Activity::where('subject_type', 'App\Models\Team')
				->where('subject_id', $team->id)
				->count(),
		];
	}

	/**
	 * Get module status information.
	 */
	private function getModuleStatus($team): array
	{
		$modules = [];

		// Define available modules
		$availableModules = [
			'crm' => ['name' => 'CRM', 'icon' => 'ti-users'],
			'billing' => ['name' => 'Billing', 'icon' => 'ti-receipt'],
			'communications' => ['name' => 'Communications', 'icon' => 'ti-mail'],
			'hosting' => ['name' => 'Hosting', 'icon' => 'ti-server'],
		];

		foreach ($availableModules as $key => $module)
		{
			$modules[$key] = [
				'name' => $module['name'],
				'icon' => $module['icon'],
				'enabled' => class_exists("Idoneo\\Humano" . ucfirst($key) . "\\Providers\\" . ucfirst($key) . "ServiceProvider"),
				'active' => true, // This should be checked against team settings
			];
		}

		return $modules;
	}
}
