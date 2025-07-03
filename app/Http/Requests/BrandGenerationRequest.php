<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandGenerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keywords' => 'required|string|max:255',
            'niches' => 'array',
            'niches.*' => 'exists:niches,id',
            'name_description' => 'nullable|string',
            'language' => 'nullable|string|in:en,ru,es,multi',
            'name_type' => 'nullable|string|in:neologism,compound,prefix_suffix,short_catchy,long_descriptive',
            'max_length' => 'nullable|integer|min:5|max:20',
            'domain_zones' => 'array',
            'domain_zones.*' => 'exists:domain_zones,id',
        ];
    }
}