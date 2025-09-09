<?php

namespace App\Services;

use App\Contracts\ReportInterface;

class CsvReportService implements ReportInterface
{

    public function generate(string $type): string
    {
        $data = match ($type) {
            'sales' => ['Sales Report', '100 sales this month'],
            'users' => ['User Report', '50 new users registered'],
            default => ['General Report', 'Nothing special'],
        };

        return implode(',', $data); // CSV string
    }
}