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
	 * Get team statistics.
	private function getTeamStatistics($team): array
		return [
			'total_users' => $team->users()->count(),
			'total_contacts' => 150, // Simulated data
			'active_projects' => 24,
			'pending_tasks' => 67,
			'monthly_revenue' => 12500,
			'active_services' => 8,
			'total_activities' => Activity::where('subject_type', 'App\Models\Team')
				->where('subject_id', $team->id)
				->count(),
		];
	 * Get module status information.
	private function getModuleStatus($team): array
		$modules = [];
		// Define available modules with real-looking status
		$availableModules = [
			'core' => ['name' => 'Core System', 'icon' => 'ti-smart-home', 'enabled' => true],
			'crm' => ['name' => 'CRM & Contacts', 'icon' => 'ti-users', 'enabled' => true],
			'projects' => ['name' => 'Project Management', 'icon' => 'ti-folders', 'enabled' => true],
			'tasks' => ['name' => 'Task Management', 'icon' => 'ti-checklist', 'enabled' => true],
			'communications' => ['name' => 'Communications', 'icon' => 'ti-mail', 'enabled' => true],
			'billing' => ['name' => 'Billing & Invoices', 'icon' => 'ti-receipt', 'enabled' => true],
			'hosting' => ['name' => 'Hosting Services', 'icon' => 'ti-server', 'enabled' => false],
			'analytics' => ['name' => 'Analytics', 'icon' => 'ti-activity', 'enabled' => true],
		foreach ($availableModules as $key => $module)
		{
			$modules[$key] = [
				'name' => $module['name'],
				'icon' => $module['icon'],
				'enabled' => $module['enabled'],
				'active' => $module['enabled'],
			];
		}
		return $modules;
}
