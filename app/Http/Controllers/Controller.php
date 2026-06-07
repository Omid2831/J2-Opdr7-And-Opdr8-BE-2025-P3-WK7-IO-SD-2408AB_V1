<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

abstract class Controller
{
    protected function buildFullName(?string $firstName, ?string $tussenvoegsel, ?string $lastName): string
    {
        $parts = array_filter(
            [$firstName, $tussenvoegsel, $lastName],
            static fn (?string $value): bool => $value !== null && $value !== ''
        );

        return implode(' ', $parts);
    }

    protected function formatNlDate(?string $date): string
    {
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)
            ->timezone('Europe/Amsterdam')
            ->format('d-m-Y');
    }

    protected function extractYear(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            $year = (int) $value;

            return $year >= 1900 && $year <= (int) date('Y') ? $year : null;
        }

        try {
            return (int) Carbon::parse($value)->format('Y');
        } catch (\Exception) {
            return null;
        }
    }
}
