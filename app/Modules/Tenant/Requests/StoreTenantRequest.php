<?php

namespace App\Modules\Tenant\Requests;

use App\Http\Requests\FormRequest;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the tenant is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $baseDomain = config('app.env') === 'local'
            ? '.localhost'
            : '.example.com';

        $this->merge([
            'domain' => $this->domain.$baseDomain,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => 'required|string|unique:domains,domain|max:255',
        ];
    }
}
