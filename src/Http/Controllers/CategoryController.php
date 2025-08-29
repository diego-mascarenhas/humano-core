<?php

namespace Idoneo\HumanoCore\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Idoneo\HumanoCore\Models\Category;
class CategoryController extends Controller
{
	/**
	 * Display a listing of the categories.
	 */
	public function index(Request $request): View
	{
		$categories = Category::query()
			->when($request->module, fn($q) => $q->forModule($request->module))
			->ordered()
			->paginate(20);
		return view('humano-core::categories.index', compact('categories'));
	}
	 * Show the form for creating a new category.
	public function create(): View
		$modules = config('humano-core.modules.enabled_modules', []);
		return view('humano-core::categories.create', compact('modules'));
	 * Store a newly created category in storage.
	public function store(Request $request): RedirectResponse
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'color' => ['nullable', 'string', 'max:7'],
			'icon' => ['nullable', 'string', 'max:255'],
			'module_key' => ['required', 'string', 'max:255'],
			'is_active' => ['boolean'],
		]);
		$validated['team_id'] = auth()->user()->currentTeam->id;
		$validated['sort_order'] = Category::max('sort_order') + 1;
		$category = Category::create($validated);
		activity()
			->performedOn($category)
			->withProperties($validated)
			->log('Category created');
		return redirect()->route('categories.index')
			->with('success', __('Category created successfully.'));
	 * Display the specified category.
	public function show(Category $category): View
		return view('humano-core::categories.show', compact('category'));
	 * Show the form for editing the specified category.
	public function edit(Category $category): View
		return view('humano-core::categories.edit', compact('category', 'modules'));
	 * Update the specified category in storage.
	public function update(Request $request, Category $category): RedirectResponse
		$originalData = $category->toArray();
		$category->update($validated);
			->withProperties([
				'old' => $originalData,
				'new' => $category->fresh()->toArray(),
			])
			->log('Category updated');
			->with('success', __('Category updated successfully.'));
	 * Remove the specified category from storage.
	public function destroy(Category $category): RedirectResponse
		$categoryData = $category->toArray();
		$category->delete();
			->withProperties($categoryData)
			->log('Category deleted');
			->with('success', __('Category deleted successfully.'));
	 * Update the order of categories.
	public function updateOrder(Request $request): RedirectResponse
		$request->validate([
			'categories' => ['required', 'array'],
			'categories.*' => ['exists:categories,id'],
		foreach ($request->categories as $index => $categoryId)
		{
			Category::where('id', $categoryId)
				->update(['sort_order' => $index + 1]);
		}
		return response()->json(['success' => true]);
}
