<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'number' => 'required|string|max:50',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_Date',
            'product' => 'required|string|max:50',
            'section_id' => 'required|exists:sections,id',
            'collection_amount' => 'required|numeric',
            'commission_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'vat_value' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'pic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
