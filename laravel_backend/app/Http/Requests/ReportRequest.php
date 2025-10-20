<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:5120', // 5MB
                function ($attribute, $value, $fail) {
                    // Additional image validation
                    if ($value) {
                        $imageInfo = getimagesize($value->getPathname());
                        if (!$imageInfo) {
                            $fail('The uploaded file is not a valid image.');
                        }
                        
                        // Check minimum dimensions
                        if ($imageInfo[0] < 100 || $imageInfo[1] < 100) {
                            $fail('Image must be at least 100x100 pixels.');
                        }
                        
                        // Check maximum dimensions
                        if ($imageInfo[0] > 4096 || $imageInfo[1] > 4096) {
                            $fail('Image must not exceed 4096x4096 pixels.');
                        }
                    }
                }
            ],
            'lat' => [
                'required',
                'numeric',
                'between:-90,90'
            ],
            'lng' => [
                'required',
                'numeric',
                'between:-180,180'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s\.,!?-]*$/' // Only allow safe characters
            ],
            'address' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Please select an image to upload.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Only JPEG, PNG, and JPG images are allowed.',
            'image.max' => 'Image size must not exceed 5MB.',
            'lat.required' => 'Location latitude is required.',
            'lat.between' => 'Invalid latitude value.',
            'lng.required' => 'Location longitude is required.',
            'lng.between' => 'Invalid longitude value.',
            'description.max' => 'Description must not exceed 500 characters.',
            'description.regex' => 'Description contains invalid characters.',
            'address.max' => 'Address must not exceed 255 characters.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize description
        if ($this->has('description')) {
            $this->merge([
                'description' => strip_tags($this->description)
            ]);
        }

        // Sanitize address
        if ($this->has('address')) {
            $this->merge([
                'address' => strip_tags($this->address)
            ]);
        }
    }
}
