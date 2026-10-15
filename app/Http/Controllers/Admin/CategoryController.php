<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['is_active' => true, 'show_in_menu' => true, 'sort' => Category::query()->max('sort') + 1]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::query()->create($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Categoría creada.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Categoría guardada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'No se puede borrar: la categoría tiene productos. Muévelos o bórralos primero, o desactívala.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categoría eliminada.');
    }
}
