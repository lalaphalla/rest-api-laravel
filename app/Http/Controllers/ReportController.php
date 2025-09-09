<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    // protected ReportService $reportService;

    // public function __construct(ReportService $reportService)
    // {
    //     $this->reportService = $reportService;
    // }

    // public function show($type)
    // {
    //     $report = $this->reportService->generate($type);
    //     return response()->json(["report" => $report]);
    // }

    public function show(Request $request, $type)
    {
        $format = $request->query("format", "json");
        $reportFormat = "report.{$format}";

        $service = app(abstract: "report.{$format}");

        $report = $service->generate($type);


        return response($report);

    }
}