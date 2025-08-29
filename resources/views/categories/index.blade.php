@extends('layouts/layoutMaster')

@section('title', __('Categories'))

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
	<div class="d-flex flex-column justify-content-center">
		<h4 class="mb-1 mt-3">{{ __('Categories') }}</h4>
		<p class="text-muted">{{ __('Manage categories for your modules') }}</p>
	</div>
	<div class="mt-3 mt-md-0">
		<a href="{{ route('categories.create') }}" class="btn btn-primary">
			<i class="ti ti-plus me-1"></i> {{ __('Add Category') }}
		</a>
	</div>
</div>

<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0">{{ __('All Categories') }}</h5>

		<!-- Filter by Module -->
		<div class="dropdown">
			<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="moduleFilter" data-bs-toggle="dropdown" aria-expanded="false">
				{{ request('module') ? ucfirst(request('module')) : __('All Modules') }}
			</button>
			<ul class="dropdown-menu" aria-labelledby="moduleFilter">
				<li><a class="dropdown-item" href="{{ route('categories.index') }}">{{ __('All Modules') }}</a></li>
				@foreach(config('humano-core.modules.enabled_modules', []) as $module)
				<li>
					<a class="dropdown-item" href="{{ route('categories.index', ['module' => $module]) }}">
						{{ ucfirst($module) }}
					</a>
				</li>
				@endforeach
			</ul>
		</div>
	</div>

	<div class="card-body">
		@if($categories->count() > 0)
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>{{ __('Name') }}</th>
							<th>{{ __('Module') }}</th>
							<th>{{ __('Description') }}</th>
							<th>{{ __('Status') }}</th>
							<th>{{ __('Actions') }}</th>
						</tr>
					</thead>
					<tbody class="table-border-bottom-0">
						@foreach($categories as $category)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									@if($category->icon)
									<i class="{{ $category->icon }} me-2"></i>
									@endif
									<div>
										<h6 class="mb-0">{{ $category->name }}</h6>
									</div>
									@if($category->color)
									<span class="badge ms-2" style="background-color: {{ $category->color }};">&nbsp;</span>
									@endif
								</div>
							</td>
							<td>
								<span class="badge bg-label-primary">{{ ucfirst($category->module_key) }}</span>
							</td>
							<td>
								<span class="text-truncate d-block" style="max-width: 200px;">
									{{ $category->description ?? __('No description') }}
								</span>
							</td>
							<td>
								@if($category->is_active)
								<span class="badge bg-label-success">{{ __('Active') }}</span>
								@else
								<span class="badge bg-label-secondary">{{ __('Inactive') }}</span>
								@endif
							</td>
							<td>
								<div class="d-flex justify-content-center align-items-center">
									<a href="{{ route('categories.show', $category) }}" class="text-body">
										<i class="ti ti-eye ti-sm me-2"></i>
									</a>
									<a href="{{ route('categories.edit', $category) }}" class="text-body">
										<i class="ti ti-edit ti-sm me-2"></i>
									</a>
									<form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm text-danger p-0"
											onclick="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
											<i class="ti ti-trash ti-sm"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- Pagination -->
			<div class="d-flex justify-content-center mt-4">
				{{ $categories->links() }}
			</div>
		@else
			<div class="text-center py-4">
				<i class="ti ti-category ti-3x text-muted mb-3"></i>
				<h5>{{ __('No categories found') }}</h5>
				<p class="text-muted">{{ __('Create your first category to organize your data.') }}</p>
				<a href="{{ route('categories.create') }}" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i> {{ __('Add Category') }}
				</a>
			</div>
		@endif
	</div>
</div>
@endsection
