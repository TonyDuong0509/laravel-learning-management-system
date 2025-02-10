<?php

namespace Modules\Category\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'parent_id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'required' => __('category::validation.required'),
            'max' => __('category::validation.max'),
            'integer' => __('category::validation.integer'),
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('category::validation.attributes.name'),
            'slug' => __('category::validation.attributes.slug'),
            'parent_id' => __('category::validation.attributes.parent_id'),
        ];
    }
}
