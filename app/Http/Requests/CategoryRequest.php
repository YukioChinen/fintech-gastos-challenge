<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        $uniqueRule = Rule::unique('categories', 'name')->where(function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        });

        if ($this->route('category')) {
            $uniqueRule = $uniqueRule->ignore($this->route('category')->id);
        }

        return [
            'name' => ['required', 'string', 'max:255', $uniqueRule],
        ];
    }
}
