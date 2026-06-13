<?php

namespace App\Http\Controllers\Rijschoolhouder;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function __construct(private Instructor $instructorModel) {}

    public function index(): View
    {
        $instructors = $this->instructorModel->fetchInService();

        $mappedInstructors = collect($instructors)->map(function (object $instructor): array {
            $legacyInstructorId = $this->instructorModel->resolveLegacyInstructorId($instructor->id);
            $legacyStatus = $legacyInstructorId
                ? $this->instructorModel->fetchByLegacyId($legacyInstructorId)
                : null;
            $isActief = $legacyStatus ? (bool) $legacyStatus->IsActief : true;

            return [
                'id' => $instructor->id,
                'legacy_id' => $legacyInstructorId,
                'full_name' => $this->buildFullName(
                    $instructor->first_name ?? '',
                    $instructor->tussenvoegsel ?? '',
                    $instructor->last_name ?? '',
                ),
                'mobile' => $instructor->mobile ?? '-',
                'datum_in_dienst' => $this->formatNlDate($instructor->datum_in_dienst ?? ''),
                'aantal_sterren' => $instructor->aantal_sterren ?? 0,
                'is_actief' => $isActief,
            ];
        });

        return view('rijschoolhouder.instructors.index', [
            'instructors' => $mappedInstructors,
            'instructorCount' => $mappedInstructors->count(),
        ]);
    }

    public function toggleStatus(Request $request, int $instructor): RedirectResponse
    {
        $user = $this->instructorModel->fetchById($instructor);

        if (! $user) {
            abort(404, 'Instructor not found.');
        }

        $legacyInstructorId = $this->instructorModel->resolveLegacyInstructorId($instructor);

        if (! $legacyInstructorId) {
            return back()->withErrors(['instructor' => 'Instructeur niet gevonden in legacysysteem.']);
        }

        $currentStatus = $this->instructorModel->fetchByLegacyId($legacyInstructorId);
        $currentIsActief = $currentStatus ? (bool) $currentStatus->IsActief : true;
        $newStatus = ! $currentIsActief;

        if (! $this->instructorModel->toggleStatus($legacyInstructorId, $newStatus)) {
            return back()->withErrors(['instructor' => 'Status kon niet worden gewijzigd.']);
        }

        $instructorName = $this->buildFullName(
            $user->first_name ?? '',
            $user->tussenvoegsel ?? '',
            $user->last_name ?? '',
        );

        $message = $newStatus
            ? "Instructeur {$instructorName} is weer actief"
            : "Instructeur {$instructorName} is ziek/met verlof gemeld";

        return redirect()->route('loadbar', [
            'redirectTo' => route('rijschoolhouder.instructors.index'),
            'message' => $message,
        ]);
    }
}
