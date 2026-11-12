<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],

            'delivery_method' => ['required', 'in:personal,office,speedy,pigeon'],

            'city' => ['required_if:delivery_method,personal', 'nullable', 'string', 'max:255'],
            'billing_address' => ['required_if:delivery_method,personal', 'nullable', 'string', 'max:255'],

            'office_list' => [
                'required_if:delivery_method,office,speedy,pigeon',
                'nullable',
                'string',
                'max:255',
            ],

            'request_invoice' => ['nullable', 'boolean'],

            'company_name' => ['nullable', 'string', 'max:255'],
            'company_mol' => ['nullable', 'string', 'max:255'],
            'company_bulstat' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get the custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fname.required' => 'Моля, въведете вашето име.',
            'fname.max' => 'Името не може да бъде по-дълго от 255 символа.',

            'lname.required' => 'Моля, въведете вашата фамилия.',
            'lname.max' => 'Фамилията не може да бъде по-дълга от 255 символа.',

            'phone.required' => 'Моля, въведете телефонен номер.',
            'phone.max' => 'Телефонният номер не може да бъде по-дълъг от 255 символа.',

            'email.required' => 'Моля, въведете имейл адрес.',
            'email.email' => 'Моля, въведете валиден имейл адрес.',
            'email.max' => 'Имейл адресът не може да бъде по-дълъг от 255 символа.',

            'delivery_method.required' => 'Моля, изберете начин на доставка.',
            'delivery_method.in' => 'Невалиден начин на доставка.',

            'city.required_if' => 'Моля, изберете град.',
            'city.max' => 'Градът не може да бъде по-дълъг от 255 символа.',

            'billing_address.required_if' => 'Моля, въведете адрес за доставка.',
            'billing_address.max' => 'Адресът не може да бъде по-дълъг от 255 символа.',

            'office_list.required_if' => 'Моля, изберете офис на куриер.',
            'office_list.max' => 'Избраният офис не може да бъде по-дълъг от 255 символа.',

            'company_name.max' => 'Името на фирмата не може да бъде по-дълго от 255 символа.',
            'company_mol.max' => 'Името на МОЛ не може да бъде по-дълго от 255 символа.',
            'company_bulstat.max' => 'Булстатът не може да бъде по-дълъг от 255 символа.',
            'company_address.max' => 'Адресът на фирмата не може да бъде по-дълъг от 255 символа.',
        ];
    }
}
