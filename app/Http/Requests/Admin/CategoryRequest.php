<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'show_in_menu' => $this->boolean('show_in_menu'),
            'is_active' => $this->boolean('is_active'),
            'sort' => (int) $this->input('sort', 0),
        ]);
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:80'],
            'slug' => [
                'required', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/',
                Rule::notIn(Category::RESERVED_SLUGS),
                Rule::unique('categories', 'slug')->ignore($category),
            ],
            'singular' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:600'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:170'],
            'show_in_menu' => ['boolean'],
            'is_active' => ['boolean'],
            'sort' => ['integer', 'min:0', 'max:9999'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'slug' => 'URL',
            'singular' => 'nombre en singular',
            'description' => 'descripción',
            'seo_title' => 'título SEO',
            'seo_description' => 'descripción SEO',
        ];
    }

    public function messages(): array
    {
        return ['slug.not_in' => 'Esa URL la usa otra página del sitio; elige otra.'];
    }
}
