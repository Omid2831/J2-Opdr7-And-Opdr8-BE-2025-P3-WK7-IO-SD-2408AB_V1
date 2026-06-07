<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beschikbare voertuigen') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold underline mb-4">
                    {{ __('Alle beschikbare voertuigen') }}
                </h3>

                <div class="space-y-1 text-sm text-gray-700">
                    <div>
                        <span class="font-semibold">{{ __('Naam:') }}</span>
                        {{ $instructor['full_name'] }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Datum in dienst:') }}</span>
                        {{ $instructor['datum_in_dienst'] }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Aantal sterren:') }}</span>
                        {{ $instructor['aantal_sterren'] }}
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto border border-gray-300 rounded-md">
                    <table class="w-full table-fixed border-collapse text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left">
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Type voertuig') }}
                                </th>
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Type') }}
                                </th>
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Kenteken') }}
                                </th>
                                <th class="w-[12%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Bouwjaar') }}
                                </th>
                                <th class="w-[12%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Brandstof') }}
                                </th>
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Rijbewijscategorie') }}
                                </th>
                                <th
                                    class="w-24 px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Toevoegen') }}
                                </th>
                                <th
                                    class="w-24 px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Wijzigen') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($vehicles as $vehicle)
                                <tr>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle->vehicle_type }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle->vehicle_model }}</td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">{{ $vehicle->license_plate }}
                                    </td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">
                                        {{ $vehicle->build_year ?? '-' }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle->fuel_type }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle->license_category }}</td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <form method="POST"
                                            action="{{ route('rijschoolhouder.instructors.vehicles.add', $instructor['id']) }}">
                                            @csrf
                                            <input type="hidden" name="legacy_vehicle_id" value="{{ $vehicle->legacy_vehicle_id }}">
                                            <input type="hidden" name="vehicle_type" value="{{ $vehicle->vehicle_type }}">
                                            <input type="hidden" name="vehicle_model" value="{{ $vehicle->vehicle_model }}">
                                            <input type="hidden" name="license_plate" value="{{ $vehicle->license_plate }}">
                                            <input type="hidden" name="build_year" value="{{ $vehicle->build_year }}">
                                            <input type="hidden" name="fuel_type" value="{{ $vehicle->fuel_type }}">
                                            <input type="hidden" name="license_category" value="{{ $vehicle->license_category }}">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center text-gray-700 hover:text-gray-900"
                                                title="{{ __('Toevoegen') }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <form method="POST"
                                            action="{{ route('rijschoolhouder.instructors.vehicles.addAndEdit', $instructor['id']) }}">
                                            @csrf
                                            <input type="hidden" name="legacy_vehicle_id" value="{{ $vehicle->legacy_vehicle_id }}">
                                            <input type="hidden" name="vehicle_type" value="{{ $vehicle->vehicle_type }}">
                                            <input type="hidden" name="vehicle_model" value="{{ $vehicle->vehicle_model }}">
                                            <input type="hidden" name="license_plate" value="{{ $vehicle->license_plate }}">
                                            <input type="hidden" name="build_year" value="{{ $vehicle->build_year }}">
                                            <input type="hidden" name="fuel_type" value="{{ $vehicle->fuel_type }}">
                                            <input type="hidden" name="license_category" value="{{ $vehicle->license_category }}">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center text-gray-700 hover:text-gray-900"
                                                title="{{ __('Wijzigen') }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 20h4l10.5-10.5a1.5 1.5 0 10-4-4L4 16v4z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M13.5 6.5l4 4" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('Geen voertuigen gevonden.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
