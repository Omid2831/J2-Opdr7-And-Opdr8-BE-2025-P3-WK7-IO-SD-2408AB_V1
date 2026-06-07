<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Vehicle extends Model
{
    protected $fillable = [
        'instructor_id',
        'vehicle_type',
        'vehicle_model',
        'license_plate',
        'build_year',
        'fuel_type',
        'license_category',
    ];

    public function fetchForInstructor(int $instructorId): array
    {
        try {
            Log::info('Instructor vehicles fetch started.', ['instructor_id' => $instructorId]);
            $rows = DB::select('CALL sp_get_instructor_vehicles(?)', [$instructorId]);
            Log::info('Instructor vehicles fetched.', [
                'instructor_id' => $instructorId,
                'count' => count($rows),
            ]);

            return $rows ?? [];
        } catch (Throwable $exception) {
            Log::error('Instructor vehicles fetch failed.', [
                'instructor_id' => $instructorId,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    public function fetchById(int $vehicleId): ?object
    {
        try {
            Log::info('Vehicle detail fetch started.', ['vehicle_id' => $vehicleId]);
            $rows = DB::select('CALL sp_get_vehicle_by_id(?)', [$vehicleId]);
            Log::info('Vehicle detail fetched.', ['vehicle_id' => $vehicleId]);

            return $rows[0] ?? null;
        } catch (Throwable $exception) {
            Log::error('Vehicle detail fetch failed.', [
                'vehicle_id' => $vehicleId,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    public function updateDetails(int $vehicleId, array $data): bool
    {
        try {
            Log::info('Vehicle update started.', ['vehicle_id' => $vehicleId]);
            DB::table('vehicles')
                ->where('id', $vehicleId)
                ->update([
                    'instructor_id' => $data['instructor_id'],
                    'vehicle_type' => $data['vehicle_type'],
                    'vehicle_model' => $data['vehicle_model'],
                    'license_plate' => $data['license_plate'],
                    'build_year' => $data['build_year'],
                    'fuel_type' => $data['fuel_type'],
                    'license_category' => $data['license_category'],
                    'updated_at' => now(),
                ]);
            Log::info('Vehicle update completed.', ['vehicle_id' => $vehicleId]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Vehicle update failed.', [
                'vehicle_id' => $vehicleId,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    public function fetchAll(): array
    {
        try {
            Log::info('All vehicles fetch started.');
            $rows = DB::select('CALL sp_get_all_vehicles()') ?? [];
            Log::info('All vehicles fetched.', ['count' => count($rows)]);

            return $rows;
        } catch (Throwable $exception) {
            Log::error('All vehicles fetch failed.', ['exception' => $exception]);
            throw $exception;
        }
    }

    public function fetchAvailableVehicles(int $id): array
    {
        try {
            Log::info('Available vehicles fetch started.');
            $rows = DB::select('CALL sp_get_available_vehicles(?)', [$id]) ?? [];
            Log::info('Available vehicles fetched.', ['count' => count($rows)]);

            return $rows;
        } catch (Throwable $exception) {
            Log::error('Available vehicles fetch failed.', ['exception' => $exception]);
            throw $exception;
        }
    }

    public function addVehicleForInstructor(int $instructorId, array $data): int|false
    {
        try {
            Log::info('Vehicle add for instructor started.', ['instructor_id' => $instructorId]);

            $id = DB::table('vehicles')->insertGetId([
                'instructor_id' => $instructorId,
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_model' => $data['vehicle_model'],
                'license_plate' => $data['license_plate'],
                'build_year' => $data['build_year'],
                'fuel_type' => $data['fuel_type'],
                'license_category' => $data['license_category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Vehicle added for instructor.', [
                'instructor_id' => $instructorId,
                'vehicle_id' => $id,
            ]);

            return $id;
        } catch (Throwable $exception) {
            Log::error('Vehicle add for instructor failed.', [
                'instructor_id' => $instructorId,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    public function isLegacyVehicleAssigned(int $legacyVehicleId): bool
    {
        try {
            return DB::table('VoertuigInstructeur')
                ->where('VoertuigId', $legacyVehicleId)
                ->where('IsActief', 1)
                ->exists();
        } catch (Throwable) {
            return false;
        }
    }

    public function findVehicleByLicensePlate(int $instructorId, string $licensePlate): ?object
    {
        try {
            $row = DB::table('vehicles')
                ->where('instructor_id', $instructorId)
                ->where('license_plate', $licensePlate)
                ->first();

            return $row ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    public function removeVehicleAssignment(int $vehicleId): bool
    {
        try {
            DB::statement('CALL sp_remove_vehicle_assignment(?)', [$vehicleId]);

            Log::info('Vehicle assignment removed.', ['vehicle_id' => $vehicleId]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Vehicle removal failed.', [
                'vehicle_id' => $vehicleId,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    public function addLegacyVehicleAssignment(int $legacyVehicleId, int $legacyInstructorId): bool
    {
        try {
            Log::info('Legacy vehicle assignment started.', [
                'legacy_vehicle_id' => $legacyVehicleId,
                'legacy_instructor_id' => $legacyInstructorId,
            ]);

            DB::table('VoertuigInstructeur')->insert([
                'VoertuigId' => $legacyVehicleId,
                'InstructeurId' => $legacyInstructorId,
                'DatumToekenning' => now()->toDateString(),
                'IsActief' => 1,
                'Opmerking' => 'Toegevoegd via rijschoolhouder portaal',
                'DatumAangemaakt' => now(),
            ]);

            Log::info('Legacy vehicle assignment completed.');

            return true;
        } catch (Throwable $exception) {
            Log::error('Legacy vehicle assignment failed.', [
                'legacy_vehicle_id' => $legacyVehicleId,
                'legacy_instructor_id' => $legacyInstructorId,
                'exception' => $exception,
            ]);

            return false;
        }
    }
}
