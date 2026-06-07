<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wijzigen voertuiggegevens') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('rijschoolhouder.vehicles.update', $vehicle->id) }}"
                    class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="instructor_id">
                                {{ __('Instructeur:') }}
                            </label>
                            <div class="min-w-0">
                                <select class="block w-full rounded-md border-gray-300 shadow-sm" id="instructor_id"
                                    name="instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor['id'] }}" @selected(old('instructor_id', $vehicle->instructor_id) == $instructor['id'])>
                                            {{ $instructor['full_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="vehicle_type">
                                {{ __('Type voertuig:') }}
                            </label>
                            <div class="min-w-0">
                                <select class="block w-full rounded-md border-gray-300 shadow-sm" id="vehicle_type"
                                    name="vehicle_type">
                                    @foreach ($vehicleTypes as $type)
                                        <option value="{{ $type }}" @selected(old('vehicle_type', $vehicle->vehicle_type) === $type)>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="vehicle_model">
                                {{ __('Type:') }}
                            </label>
                            <div class="min-w-0">
                                <input class="block w-full rounded-md border-gray-300 shadow-sm" id="vehicle_model"
                                    name="vehicle_model" type="text"
                                    value="{{ old('vehicle_model', $vehicle->vehicle_model) }}">
                                @error('vehicle_model')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="build_year">
                                {{ __('Bouwjaar:') }}
                            </label>
                            <div class="{{ $vehicle->vehicle_model === 'DAF' ? 'relative ' : '' }}min-w-0">
                                <input class="block w-full rounded-md border-gray-300 shadow-sm{{ $vehicle->vehicle_model === 'DAF' ? ' pr-10 bg-gray-50' : '' }}" id="build_year"
                                    name="build_year" type="number"
                                    value="{{ old('build_year', $vehicle->build_year) }}"{{ $vehicle->vehicle_model === 'DAF' ? ' readonly' : '' }}>
                                @if ($vehicle->vehicle_model === 'DAF')
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 3v2M17 3v2M4 7h16M5 9h14v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                @endif
                                @error('build_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-start">
                            <span class="text-sm font-medium text-gray-700">{{ __('Brandstof:') }}</span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-4">
                                    @foreach ($fuelTypes as $fuel)
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" name="fuel_type" value="{{ $fuel }}"
                                                @checked(old('fuel_type', $vehicle->fuel_type) === $fuel)
                                                class="border-gray-300 text-gray-900 focus:ring-gray-900">
                                            {{ $fuel }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('fuel_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="license_plate">
                                {{ __('Kenteken:') }}
                            </label>
                            <div class="min-w-0">
                                <input class="block w-full rounded-md border-gray-300 shadow-sm" id="license_plate"
                                    name="license_plate" type="text"
                                    value="{{ old('license_plate', $vehicle->license_plate) }}">
                                @error('license_plate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <label class="text-sm font-medium text-gray-700" for="license_category">
                                {{ __('Rijbewijscategorie:') }}
                            </label>
                            <div class="min-w-0">
                                <input class="block w-full rounded-md border-gray-300 shadow-sm" id="license_category"
                                    name="license_category" type="text"
                                    value="{{ old('license_category', $vehicle->license_category) }}">
                                @error('license_category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @error('vehicle')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="grid gap-2 sm:grid-cols-[10rem_1fr] sm:items-center">
                        <div class="hidden sm:block"></div>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-900 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-800">
                                {{ __('Wijzig') }}
                            </button>
                            <a class="text-sm text-gray-600 hover:text-gray-900"
                                href="{{ route('rijschoolhouder.instructors.vehicles.index', $vehicle->instructor_id) }}">
                                {{ __('Annuleren') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
