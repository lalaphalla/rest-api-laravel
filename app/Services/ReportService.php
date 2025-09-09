<?php

namespace App\Services;

class ReportService
{
    public function generate(string $type): string
    {
        switch ($type) {
            case "sales":
                return "Sale Report: 100 sales this month";
            case "users":
                return "User Report: 50 new users registerd.";
            default:
                return "General Report: Nothing special.";
        }
    }
}