<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alle voertuigen') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-700 mb-4">
                    <span class="font-semibold">{{ __('Aantal voertuigen:') }}</span>
                    {{ $vehicles->total() }}
                </div>

                <div class="overflow-x-auto border border-gray-300 rounded-md">
                    <table class="w-full table-fixed border-collapse text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left">
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Type voertuig') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Type') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Kenteken') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Bouwjaar') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Brandstof') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Rijbewijscategorie') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Instructeur') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Wijzigen') }}
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Verwijderen') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($vehicles as $vehicle)
                                <tr>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle['vehicle_type'] }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle['vehicle_model'] }}</td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">{{ $vehicle['license_plate'] }}</td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">{{ $vehicle['build_year'] }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle['fuel_type'] }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle['license_category'] }}</td>
                                    <td class="px-4 py-2 align-middle">{{ $vehicle['instructor_name'] }}</td>
                                    @if ($vehicle['is_assigned'])
                                        <td class="px-4 py-2 text-center align-middle">
                                            <a href="{{ route('rijschoolhouder.vehicles.edit', $vehicle['id']) }}"
                                                class="inline-flex items-center justify-center text-gray-700 hover:text-gray-900">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 20h4l10.5-10.5a1.5 1.5 0 10-4-4L4 16v4z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M13.5 6.5l4 4" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-center align-middle">
                                            <form method="POST"
                                                action="{{ route('rijschoolhouder.instructors.vehicles.destroy', $vehicle['id']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to"
                                                    value="{{ route('rijschoolhouder.vehicles.all') }}">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center text-red-600 hover:text-red-800"
                                                    title="{{ __('Verwijderen') }}"
                                                    onclick="return confirm('{{ __('Weet u zeker dat u dit voertuig wilt verwijderen?') }}')">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"
                                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    @else
                                        <td class="px-4 py-2 text-center align-middle text-gray-400">-</td>
                                        <td class="px-4 py-2 text-center align-middle">
                                            <a href="{{ route('loadbar', [
                                                'redirectTo' => route('rijschoolhouder.vehicles.all'),
                                                'message' => 'Het door u geselecteerde voertuig staat op non actief en kan niet worden verwijderd',
                                            ]) }}"
                                                class="inline-flex items-center justify-center text-red-600 hover:text-red-800"
                                                title="{{ __('Verwijderen') }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('Geen voertuigen gevonden.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $vehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
