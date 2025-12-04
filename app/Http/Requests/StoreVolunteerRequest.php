<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'skills' => 'nullable|string|max:1000',
            'availability' => 'required|string|max:500',
            'motivation' => 'required|string|min:50|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Please provide your phone number.',
            'address.required' => 'Please provide your address.',
            'availability.required' => 'Please let us know your availability.',
            'motivation.required' => 'Please tell us why you want to volunteer.',
            'motivation.min' => 'Please provide more detail about your motivation (at least 50 characters).',
        ];
    }
}

class LogVolunteerHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'nullable|exists:campaigns,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:24',
            'description' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'date.before_or_equal' => 'You cannot log hours for future dates.',
            'hours.min' => 'Hours must be at least 0.5 (30 minutes).',
            'hours.max' => 'You cannot log more than 24 hours in a day.',
            'description.required' => 'Please describe what you did.',
        ];
    }
}