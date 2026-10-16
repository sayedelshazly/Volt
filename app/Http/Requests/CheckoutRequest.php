<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        $card = $this->input('payment_method') === 'card';
        return [
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'phone' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'city' => ['required', 'string', 'max:60'],
            'address' => ['required', 'string', 'min:8', 'max:300'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,card'],
            'card_number' => [$card ? 'required' : 'nullable', 'regex:/^[0-9 ]{13,23}$/'],
            'card_exp' => [$card ? 'required' : 'nullable', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvc' => [$card ? 'required' : 'nullable', 'digits_between:3,4'],
        ];
    }
    protected function prepareForValidation(): void {
        $this->merge(['phone' => preg_replace('/[\s\-]/', '', (string) $this->phone)]);
    }
}
