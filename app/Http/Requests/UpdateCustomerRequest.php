<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        $method = $this->method();

        if ($method === 'PUT') {

            return [

                'name' => ['required'],
                'email' => ['required'],
                'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
                'address' => ['required'],
                'city' => ['required'],
                'postalCode' => ['required'],

            ];
        } else {

            return [
                'name' => ['sometimes', 'required'],
                'email' => ['sometimes', 'required'],
                'type' => ['sometimes', 'required', Rule::in(['I', 'B', 'i', 'b'])],
                'address' => ['sometimes', 'required'],
                'city' => ['sometimes', 'required'],
                'postalCode' => ['sometimes', 'required'],
            ];
        }
    }
}
