<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
namespace App\Enums;
enum ReportFormat: string
{
    case CSV = 'report.csv';
    case JSON = 'report.json';
}

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
        $format = $request->query("format", "csv");
        ReportFormat $reportFormat = "report.{$format}";

        $service = app(abstract: reportFormat);

        $report = $service->generate($type);
        dd($report);

        return response($report);

    }
}