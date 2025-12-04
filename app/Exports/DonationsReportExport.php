<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DonationsReportExport implements FromCollection, WithHeadings, WithTitle
{
    protected $report;
    protected $period;

    public function __construct($report, $period)
    {
        $this->report = $report;
        $this->period = $period;
    }

    public function collection()
    {
        $rows = [];

        // Summary
        $rows[] = ['Donation Report', $this->period];
        $rows[] = [];
        $rows[] = ['Summary', ''];
        $rows[] = ['Total Donations', $this->report['summary']['total_donations']];
        $rows[] = ['Total Revenue (Base)', $this->report['summary']['total_revenue']];
        $rows[] = ['Total Fees', $this->report['summary']['total_fees']];
        $rows[] = ['Net Revenue', $this->report['summary']['net_revenue']];
        $rows[] = ['Average Donation', $this->report['summary']['avg_donation']];
        $rows[] = [];

        // By Campaign
        $rows[] = ['Campaign Breakdown', '', ''];
        $rows[] = ['Campaign', 'Donations', 'Amount'];
        foreach ($this->report['by_campaign'] as $item) {
            $rows[] = [$item['campaign'], $item['count'], $item['total']];
        }
        $rows[] = [];

        // By Category
        $rows[] = ['By Category', '', ''];
        $rows[] = ['Category', 'Donations', 'Amount'];
        foreach ($this->report['by_category'] as $item) {
            $rows[] = [$item['category'], $item['count'], $item['total']];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Donation Report';
    }
}