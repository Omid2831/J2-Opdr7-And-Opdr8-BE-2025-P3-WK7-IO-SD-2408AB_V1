<?php

namespace App\Http\Controllers\Rijschoolhouder;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function __construct(private Instructor $instructorModel) {}

    public function index(): View
    {
        $instructors = $this->instructorModel->fetchInService();

        $mappedInstructors = collect($instructors)->map(function (object $instructor): array {
            return [
                'id' => $instructor->id,
                'full_name' => $this->buildFullName(
                    $instructor->first_name ?? '',
                    $instructor->tussenvoegsel ?? '',
                    $instructor->last_name ?? '',
                ),
                'mobile' => $instructor->mobile ?? '-',
                'datum_in_dienst' => $this->formatNlDate($instructor->datum_in_dienst ?? ''),
                'aantal_sterren' => $instructor->aantal_sterren ?? 0,
            ];
        });

        return view('rijschoolhouder.instructors.index', [
            'instructors' => $mappedInstructors,
            'instructorCount' => $mappedInstructors->count(),
        ]);
    }
}
