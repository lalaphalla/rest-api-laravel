<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function show($type)
    {
        $report = $this->reportService->generate($type);
        return response()->json(["report" => $report]);
    }
}