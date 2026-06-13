<?php

namespace App\Http\Controllers\Rijschoolhouder;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Instructor;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InstructorVehicleController extends Controller
{
    public function __construct(
        private Instructor $instructorModel,
        private Vehicle $vehicleModel,
    ) {}

    public function index(int $instructor): View
    {
        $instructorRow = $this->instructorModel->fetchById($instructor);

        if (! $instructorRow) {
            abort(404, 'Instructor not found.');
        }

        $vehicles = $this->vehicleModel->fetchForInstructor($instructor);

        return view('rijschoolhouder.instructors.vehicles.index', [
            'instructor' => [
                'id' => $instructorRow->id,
                'full_name' => $this->buildFullName(
                    $instructorRow->first_name ?? '',
                    $instructorRow->tussenvoegsel ?? '',
                    $instructorRow->last_name ?? '',
                ),
                'datum_in_dienst' => $this->formatNlDate($instructorRow->datum_in_dienst ?? ''),
                'aantal_sterren' => $instructorRow->aantal_sterren ?? 0,
            ],
            'vehicles' => $vehicles,
        ]);
    }

    public function allVehicles(Request $request): View
    {
        $rows = $this->vehicleModel->fetchAll();

        $perPage = 4;
        $page = (int) $request->input('page', 1);

        $collection = collect($rows)->map(function (object $vehicle): array {
            $isAssigned = ($vehicle->source ?? 'assigned') === 'assigned';

            return [
                'id' => $isAssigned ? (int) $vehicle->vehicle_id : null,
                'instructor_id' => $isAssigned ? (int) $vehicle->instructor_id : null,
                'vehicle_type' => $vehicle->vehicle_type ?? '-',
                'vehicle_model' => $vehicle->vehicle_model ?? '-',
                'license_plate' => $vehicle->license_plate ?? '-',
                'build_year' => $vehicle->build_year ?? '-',
                'fuel_type' => $vehicle->fuel_type ?? '-',
                'license_category' => $vehicle->license_category ?? '-',
                'instructor_name' => $isAssigned
                    ? $this->buildFullName(
                        $vehicle->first_name ?? '',
                        $vehicle->tussenvoegsel ?? '',
                        $vehicle->last_name ?? '',
                    )
                    : '-',
                'is_assigned' => $isAssigned,
            ];
        })->values();

        $vehicles = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('rijschoolhouder.vehicles.index', [
            'vehicles' => $vehicles,
        ]);
    }

    public function edit(int $vehicle): View
    {
        $vehicleRow = $this->vehicleModel->fetchById($vehicle);

        if (! $vehicleRow) {
            abort(404, 'Vehicle not found.');
        }

        $instructors = collect($this->instructorModel->fetchInService())
            ->map(function (object $instructor): array {
                return [
                    'id' => $instructor->id,
                    'full_name' => $this->buildFullName(
                        $instructor->first_name ?? '',
                        $instructor->tussenvoegsel ?? '',
                        $instructor->last_name ?? '',
                    ),
                ];
            })
            ->values();

        $vehicleTypes = ['Auto', 'Motor', 'Scooter', 'Brommer'];
        $currentType = $vehicleRow->vehicle_type ?? '';

        if ($currentType && ! in_array($currentType, $vehicleTypes, true)) {
            array_unshift($vehicleTypes, $currentType);
        }

        $fuelTypes = ['Diesel', 'Benzine', 'Elektrisch'];

        return view('rijschoolhouder.vehicles.edit', [
            'vehicle' => $vehicleRow,
            'instructors' => $instructors,
            'vehicleTypes' => $vehicleTypes,
            'fuelTypes' => $fuelTypes,
        ]);
    }

    public function update(UpdateVehicleRequest $request, int $vehicle): RedirectResponse
    {
        $validated = $request->validated();

        if (! $this->vehicleModel->updateDetails($vehicle, $validated)) {
            return back()
                ->withErrors(['vehicle' => 'Voertuig kon niet worden gewijzigd.'])
                ->withInput();
        }

        return redirect()
            ->route('rijschoolhouder.instructors.vehicles.index', ['instructor' => $validated['instructor_id']])
            ->with('status', 'Voertuig gewijzigd.');
    }

    public function availableVehicles(int $instructor): View
    {
        $instructorRow = $this->instructorModel->fetchById($instructor);

        if (! $instructorRow) {
            abort(404, 'Instructor not found.');
        }

        $legacyInstructorId = $this->instructorModel->resolveLegacyInstructorId($instructor);

        $vehicles = collect(
            $legacyInstructorId
                ? $this->vehicleModel->fetchAvailableVehicles($legacyInstructorId)
                : []
        )
            ->map(function (object $vehicle): object {
                return (object) [
                    'legacy_vehicle_id' => $vehicle->VoertuigId ?? $vehicle->voertuigid ?? null,
                    'vehicle_type' => $vehicle->TypeVoertuig ?? $vehicle->typevoertuig ?? '-',
                    'vehicle_model' => $vehicle->Type ?? $vehicle->type ?? '-',
                    'license_plate' => $vehicle->Kenteken ?? $vehicle->kenteken ?? '-',
                    'build_year' => $this->extractYear($vehicle->Bouwjaar ?? $vehicle->bouwjaar) ?? '-',
                    'fuel_type' => $vehicle->Brandstof ?? $vehicle->brandstof ?? '-',
                    'license_category' => $vehicle->Rijbewijscategorie ?? $vehicle->rijbewijscategorie ?? '-',
                ];
            })
            ->values()
            ->all();

        return view('rijschoolhouder.instructors.vehicles.available', [
            'instructor' => [
                'id' => $instructorRow->id,
                'full_name' => $this->buildFullName(
                    $instructorRow->first_name ?? '',
                    $instructorRow->tussenvoegsel ?? '',
                    $instructorRow->last_name ?? '',
                ),
                'datum_in_dienst' => $this->formatNlDate($instructorRow->datum_in_dienst ?? ''),
                'aantal_sterren' => $instructorRow->aantal_sterren ?? 0,
            ],
            'vehicles' => $vehicles,
        ]);
    }

    public function destroy(Request $request, int $vehicle): RedirectResponse
    {
        $vehicleRow = $this->vehicleModel->fetchById($vehicle);

        if (! $vehicleRow) {
            abort(404, 'Vehicle not found.');
        }

        $instructorId = $vehicleRow->instructor_id;

        if (! $this->vehicleModel->removeVehicleAssignment($vehicle)) {
            return back()
                ->withErrors([
                    'vehicle' => 'Voertuig kon niet worden verwijderd.',
                ]);
        }

        $redirectTo = $request->input('redirect_to')
            ?: route('rijschoolhouder.instructors.vehicles.index', ['instructor' => $instructorId]);

        return redirect()->route('loadbar', [
            'redirectTo' => $redirectTo,
            'message' => 'Het door u geselecteerde voertuig is verwijderd',
        ]);
    }

    public function addVehicle(Request $request, int $instructor): RedirectResponse
    {
        $validated = $request->validate([
            'legacy_vehicle_id' => ['required', 'integer'],
            'vehicle_type' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:120'],
            'license_plate' => ['required', 'string', 'max:20'],
            'build_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'fuel_type' => ['required', 'string', 'max:50'],
            'license_category' => ['required', 'string', 'max:20'],
        ]);

        if ($this->vehicleModel->isLegacyVehicleAssigned($validated['legacy_vehicle_id'])) {
            $existing = $this->vehicleModel->findVehicleByLicensePlate($instructor, $validated['license_plate']);

            if ($existing) {
                return redirect()
                    ->route('rijschoolhouder.instructors.vehicles.index', ['instructor' => $instructor])
                    ->with('status', 'Dit voertuig is al toegevoegd.');
            }
        }

        $vehicleId = $this->vehicleModel->addVehicleForInstructor($instructor, $validated);

        if ($vehicleId === false) {
            return back()
                ->withErrors([
                    'vehicle' => 'Voertuig kon niet worden toegevoegd.',
                ])
                ->withInput();
        }

        $legacyInstructorId = $this->instructorModel->resolveLegacyInstructorId($instructor);

        if ($legacyInstructorId && ! $this->vehicleModel->addLegacyVehicleAssignment($validated['legacy_vehicle_id'], $legacyInstructorId)) {
            Log::warning('Voertuig toegevoegd aan vehicles maar legacy toewijzing mislukt.', [
                'legacy_vehicle_id' => $validated['legacy_vehicle_id'],
                'legacy_instructor_id' => $legacyInstructorId,
            ]);
        }

        return redirect()
            ->route('rijschoolhouder.instructors.vehicles.index', ['instructor' => $instructor])
            ->with('status', 'Voertuig toegevoegd.');
    }

    public function addVehicleAndEdit(Request $request, int $instructor): RedirectResponse
    {
        $validated = $request->validate([
            'legacy_vehicle_id' => ['required', 'integer'],
            'vehicle_type' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:120'],
            'license_plate' => ['required', 'string', 'max:20'],
            'build_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'fuel_type' => ['required', 'string', 'max:50'],
            'license_category' => ['required', 'string', 'max:20'],
        ]);

        if ($this->vehicleModel->isLegacyVehicleAssigned($validated['legacy_vehicle_id'])) {
            $existing = $this->vehicleModel->findVehicleByLicensePlate($instructor, $validated['license_plate']);

            if ($existing) {
                return redirect()
                    ->route('rijschoolhouder.vehicles.edit', ['vehicle' => $existing->id])
                    ->with('status', 'Dit voertuig is al toegevoegd. U kunt de gegevens wijzigen.');
            }
        }

        $vehicleId = $this->vehicleModel->addVehicleForInstructor($instructor, $validated);

        if ($vehicleId === false) {
            return back()
                ->withErrors(['vehicle' => 'Voertuig kon niet worden toegevoegd.'])
                ->withInput();
        }

        $legacyInstructorId = $this->instructorModel->resolveLegacyInstructorId($instructor);

        if ($legacyInstructorId && ! $this->vehicleModel->addLegacyVehicleAssignment($validated['legacy_vehicle_id'], $legacyInstructorId)) {
            Log::warning('Voertuig toegevoegd aan vehicles maar legacy toewijzing mislukt.', [
                'legacy_vehicle_id' => $validated['legacy_vehicle_id'],
                'legacy_instructor_id' => $legacyInstructorId,
            ]);
        }

        return redirect()
            ->route('rijschoolhouder.vehicles.edit', ['vehicle' => $vehicleId])
            ->with('status', 'Voertuig toegevoegd. U kunt nu de gegevens wijzigen.');
    }
}
