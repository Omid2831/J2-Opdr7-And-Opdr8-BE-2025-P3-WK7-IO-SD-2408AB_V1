<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Instructor extends Model
{
    protected $table = 'users';

    public function fetchInService(): array
    {
        try {
            Log::info('Instructors in service fetch started.');
            $rows = DB::select('CALL sp_get_instructors_in_service()') ?? [];
            Log::info('Instructors in service fetched.', ['count' => count($rows)]);

            return $rows;
        } catch (Throwable $exception) {
            Log::error('Instructors in service fetch failed.', ['exception' => $exception]);
            throw $exception;
        }
    }

    public function fetchById(int $instructorId): ?object
    {
        try {
            Log::info('Instructor detail fetch started.', ['instructor_id' => $instructorId]);
            $rows = DB::select('CALL sp_get_instructor_by_id(?)', [$instructorId]);
            Log::info('Instructor detail fetched.', ['instructor_id' => $instructorId]);

            return $rows[0] ?? null;
        } catch (Throwable $exception) {
            Log::error('Instructor detail fetch failed.', [
                'instructor_id' => $instructorId,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    public function resolveLegacyInstructorId(int $userId): ?int
    {
        $instructor = $this->fetchById($userId);

        if (! $instructor || empty($instructor->mobile)) {
            return null;
        }

        try {
            $rows = DB::select(
                'SELECT Id FROM Instructeur WHERE Mobiel = ? LIMIT 1',
                [$instructor->mobile],
            );

            return isset($rows[0]->Id) ? (int) $rows[0]->Id : null;
        } catch (Throwable $exception) {
            Log::error('Legacy instructor id resolve failed.', [
                'user_id' => $userId,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }
}
