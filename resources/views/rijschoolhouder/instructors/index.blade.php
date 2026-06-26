<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instructeurs in dienst') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-700 mb-4">
                    <span class="font-semibold">{{ __('Aantal instructeurs:') }}</span>
                    {{ $instructorCount }}
                </div>

                <div class="mb-6">
                    <a href="{{ route('rijschoolhouder.vehicles.all') }}"
                        class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 5h2l1 10h12l1-7H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        {{ __('Alle voertuigen') }}
                    </a>
                </div>

                <div class="overflow-x-auto border border-gray-300 rounded-md">
                    <table class="w-full table-fixed border-collapse text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left">
                                <th class="w-[24%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Naam') }}
                                </th>
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Mobiel') }}
                                </th>
                                <th class="w-[14%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Datum in dienst') }}
                                </th>
                                <th class="w-[10%] px-4 py-2 border-b border-gray-300 align-middle whitespace-nowrap">
                                    {{ __('Aantal sterren') }}
                                </th>
                                <th class="w-24 px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Ziekte/Verlof') }}
                                </th>
                                <th class="w-24 px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Voertuigen') }}
                                </th>
                                <th class="w-16 px-4 py-2 border-b border-gray-300 text-center align-middle whitespace-nowrap">
                                    {{ __('Verwijderen') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($instructors as $instructor)
                                <tr>
                                    <td class="px-4 py-2 align-middle">{{ $instructor['full_name'] }}</td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">{{ $instructor['mobile'] }}
                                    </td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">
                                        {{ $instructor['datum_in_dienst'] }}</td>
                                    <td class="px-4 py-2 align-middle whitespace-nowrap">
                                        {{ $instructor['aantal_sterren'] }}</td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <form method="POST"
                                            action="{{ route('rijschoolhouder.instructors.toggleStatus', $instructor['id']) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center justify-center text-gray-700 hover:text-gray-900"
                                                title="{{ __('Ziekte/Verlof wijzigen') }}">
                                                @if ($instructor['is_actief'])
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"
                                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 1.5-1.5-1-3-1.5-4.5-1.5A5.5 5.5 0 002 11.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
                                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <a href="{{ route('rijschoolhouder.instructors.vehicles.index', $instructor['id']) }}"
                                            class="inline-flex items-center justify-center text-gray-700 hover:text-gray-900">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 16l1.5-4.5a3 3 0 012.85-2.05h5.3a3 3 0 012.85 2.05L19 16"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6 16h12v3a1 1 0 01-1 1h-1a2 2 0 11-4 0H9a2 2 0 11-4 0H4a1 1 0 01-1-1v-3h3z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        <form method="POST"
                                            action="{{ route('rijschoolhouder.instructors.destroy', $instructor['id']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center text-red-600 hover:text-red-800"
                                                title="{{ __('Verwijderen') }}"
                                                onclick="return confirm('{{ __('Weet u zeker dat u deze instructeur wilt verwijderen?') }}')">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('Geen instructeurs gevonden.') }}
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
