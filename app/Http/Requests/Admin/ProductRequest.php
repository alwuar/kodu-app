<?php

namespace App\Http\Requests\Admin;

use App\Support\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $specs = collect($this->input('specs', []))
            ->map(fn ($row) => ['label' => trim((string) ($row['label'] ?? '')), 'value' => trim((string) ($row['value'] ?? ''))])
            ->filter(fn ($row) => $row['label'] !== '' || $row['value'] !== '')
            ->values()
            ->all();

        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'price_cents' => Money::toCents($this->input('price')),
            'is_active' => $this->boolean('is_active'),
            'sort' => (int) $this->input('sort', 0),
            'specs' => $specs,
            'machine' => $this->input('machine') ?: null,
            'box' => $this->input('box') ?: null,
            'tons_min' => $this->filled('tons_min') ? (int) $this->input('tons_min') : null,
            'tons_max' => $this->filled('tons_max') ? (int) $this->input('tons_max') : null,
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('products', 'slug')->where('category_id', $this->input('category_id'))->ignore($product),
            ],
            'sku' => ['nullable', 'string', 'max:60'],
            'kicker' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:600'],
            'price' => ['required'],
            'price_cents' => ['integer', 'min:0', 'max:99999999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'datasheet' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'remove_datasheet' => ['nullable', 'boolean'],
            'machine' => ['nullable', Rule::in(array_keys(config('catalog.machines')))],
            'tons_min' => ['nullable', 'integer', 'min:0', 'max:200'],
            'tons_max' => ['nullable', 'integer', 'min:0', 'max:200', 'gte:tons_min'],
            'box' => ['nullable', Rule::in(array_keys(config('catalog.boxes')))],
            'specs' => ['array', 'max:30'],
            'specs.*.label' => ['required', 'string', 'max:80'],
            'specs.*.value' => ['required', 'string', 'max:120'],
            'is_active' => ['boolean'],
            'sort' => ['integer', 'min:0', 'max:9999'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'categoría',
            'name' => 'nombre',
            'slug' => 'URL',
            'summary' => 'descripción',
            'price' => 'precio',
            'price_cents' => 'precio',
            'image' => 'imagen',
            'datasheet' => 'ficha técnica',
            'machine' => 'maquinaria',
            'tons_min' => 'tonelaje mínimo',
            'tons_max' => 'tonelaje máximo',
            'box' => 'tipo de caja',
            'specs.*.label' => 'nombre del dato',
            'specs.*.value' => 'valor',
        ];
    }

    public function messages(): array
    {
        return [
            'price_cents.max' => 'El precio máximo para cobrar con Stripe es $999,999.99.',
            'slug.unique' => 'Ya hay un producto con esa URL en la categoría.',
        ];
    }

    /** Datos listos para guardar (sin archivos). */
    public function productData(): array
    {
        $data = $this->safe()->except(['price', 'price_cents', 'image', 'remove_image', 'datasheet', 'remove_datasheet']);
        $data['price'] = $this->input('price_cents');

        return $data;
    }
}
