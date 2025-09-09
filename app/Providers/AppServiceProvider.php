<?php

namespace App\Providers;

use App\Contracts\ReportInterface;
use App\Services\CsvReportService;
use App\Services\JsonReportService;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(ReportInterface::class, ReportService::class);
        // $this->app->bind('report.json', JsonReportService::class);

        $this->app->bind('report.csv', CsvReportService::class);
        $this->app->bind('report.json', JsonReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
