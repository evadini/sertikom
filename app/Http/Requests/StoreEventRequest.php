<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'nullable';

        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string',
            'date_start' => $this->isMethod('POST') ? 'required|date|after_or_equal:today' : 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'time_start' => 'required|date_format:H:i',
            'time_end' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $start = $this->input('time_start');
                    if ($start && $value <= $start) {
                        $fail('Jam selesai harus setelah jam mulai.');
                    }
                },
            ],
            'description' => 'required|string',
            'terms' => 'required|string',
            'banner' => "{$required}|image|mimes:jpeg,png,jpg,webp|max:2048",
            'organiser_photo' => "{$required}|image|mimes:jpeg,png,jpg,webp|max:2048",
            'ticket_types' => 'required|array|min:1',
            'ticket_types.*.ticket_type' => 'required|string|max:255',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'date_start.after_or_equal' => 'Tanggal mulai event tidak boleh di masa lalu. Pilih hari ini atau tanggal yang akan datang.',
            'date_end.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];
    }
}
