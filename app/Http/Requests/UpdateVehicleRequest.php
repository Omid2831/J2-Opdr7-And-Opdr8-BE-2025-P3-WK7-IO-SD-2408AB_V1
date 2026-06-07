<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->hasRole('rijschoolhouder');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_type' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:120'],
            'license_plate' => ['required', 'string', 'max:20'],
            'build_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'fuel_type' => ['required', 'string', 'max:50'],
            'license_category' => ['required', 'string', 'max:20'],
            'instructor_id' => ['required', 'integer'],
        ];
    }
}
