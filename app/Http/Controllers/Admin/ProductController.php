<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->query('categoria'), fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")))
            ->orderBy('category_id')
            ->orderBy('sort')
            ->orderBy('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('sort')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.products.form', [
            'product' => new Product([
                'is_active' => true,
                'category_id' => $request->query('categoria'),
                'specs' => [['label' => 'Máquina compatible', 'value' => ''], ['label' => 'Peso operativo', 'value' => '']],
            ]),
            'categories' => Category::query()->orderBy('sort')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = new Product($request->productData());
        $this->handleFiles($request, $product);
        $product->save();

        return redirect()->route('admin.products.edit', $product)->with('status', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->orderBy('sort')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->fill($request->productData());
        $this->handleFiles($request, $product);
        $product->save();

        return redirect()->route('admin.products.edit', $product)->with('status', 'Producto guardado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteFile($product->image);
        $this->deleteFile($product->datasheet);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado.');
    }

    protected function handleFiles(ProductRequest $request, Product $product): void
    {
        if ($request->boolean('remove_image') || $request->hasFile('image')) {
            $this->deleteFile($product->getOriginal('image'));
            $product->image = null;
        }

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('productos', 'public');
        }

        if ($request->boolean('remove_datasheet') || $request->hasFile('datasheet')) {
            $this->deleteFile($product->getOriginal('datasheet'));
            $product->datasheet = null;
        }

        if ($request->hasFile('datasheet')) {
            $name = $product->slug.'-ficha-tecnica-'.now()->format('ymdHis').'.pdf';
            $product->datasheet = $request->file('datasheet')->storeAs('fichas', $name, 'public');
        }
    }

    /** Solo borra archivos subidos desde el panel (no las imágenes de la maqueta en /public/images). */
    protected function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
