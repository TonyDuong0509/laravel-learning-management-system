<?php

namespace Modules\Course\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
        $id = request()->course;

        $uniqueRule = 'unique:courses,code';

        if ($id) {
            $uniqueRule .= ',' . $id;
        }

        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'detail' => 'required',
            'teacher_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if ($value == 0) {
                    $fail(__('course::validation.select'));
                }
            }],
            'thumbnail' => 'required|mimes:png,jpg,jpeg',
            'code' => 'required|max:255|' . $uniqueRule,
            'is_document' => 'required|integer',
            'supports' => 'required',
            'status' => 'required|integer',
            'categories' => 'required'
        ];

        if ($id) {
            $rules['thumbnail'] = 'mimes:png,jpg,jpeg';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => __('course::validation.required'),
            'unique' => __('course::validation.unique'),
            'min' => __('course::validation.min'),
            'max' => __('course::validation.max'),
            'integer' => __('course::validation.integer'),
        ];
    }

    public function attributes()
    {
        return __('course::validation.attributes');
    }
}
