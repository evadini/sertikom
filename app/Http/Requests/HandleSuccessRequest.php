<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandleSuccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer',
            'transaction_id' => 'required|string',
            'payment_type' => 'required|string',
        ];
    }
}
