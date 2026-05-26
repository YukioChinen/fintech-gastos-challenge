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
            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição precisa ser um texto válido.',
            'description.max' => 'A descrição pode ter no máximo 255 caracteres.',

            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor precisa ser numérico.',
            'amount.min' => 'O valor deve ser maior ou igual a 0,01.',

            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data informada é inválida.',
            'date.before_or_equal' => 'A data não pode ser maior que amanhã.',

            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.integer' => 'A categoria informada é inválida.',
            'category_id.exists' => 'A categoria selecionada não existe.',
        ];
    }
}
