<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tomorrow = Carbon::today()->addDay()->toDateString();

        return [
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date', "before_or_equal:{$tomorrow}"],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'A descricao e obrigatoria.',
            'description.string' => 'A descricao precisa ser um texto valido.',
            'description.max' => 'A descricao pode ter no maximo 255 caracteres.',

            'amount.required' => 'O valor e obrigatorio.',
            'amount.numeric' => 'O valor precisa ser numerico.',
            'amount.min' => 'O valor deve ser maior ou igual a 0,01.',

            'date.required' => 'A data e obrigatoria.',
            'date.date' => 'A data informada e invalida.',
            'date.before_or_equal' => 'A data nao pode ser maior que amanha.',

            'category_id.required' => 'A categoria e obrigatoria.',
            'category_id.integer' => 'A categoria informada e invalida.',
            'category_id.exists' => 'A categoria selecionada nao existe.',
        ];
    }
}
