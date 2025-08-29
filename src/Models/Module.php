<?php

namespace Idoneo\HumanoCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Module extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'slug',
		'description',
		'version',
		'is_active',
		'settings',
		'team_id',
	];

	protected $casts = [
		'is_active' => 'boolean',
		'settings' => 'array',
	];

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted()
	{
		static::addGlobalScope('team', function (Builder $builder)
		{
			if (auth()->check())
			{
				$builder->where('team_id', auth()->user()->currentTeam->id);
			}
		});
	}

	/**
	 * Get the team that owns the module.
	 */
	public function team()
	{
		return $this->belongsTo(Team::class);
	}

	/**
	 * Scope a query to only include active modules.
	 */
	public function scopeActive($query)
	{
		return $query->where('is_active', true);
	}

	/**
	 * Get module setting by key.
	 */
	public function getSetting(string $key, $default = null)
	{
		return data_get($this->settings, $key, $default);
	}

	/**
	 * Set module setting.
	 */
	public function setSetting(string $key, $value): void
	{
		$settings = $this->settings ?? [];
		data_set($settings, $key, $value);
		$this->settings = $settings;
		$this->save();
	}
}
