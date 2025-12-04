<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CampaignReportExport implements FromCollection, WithHeadings, WithTitle
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function collection()
    {
        $rows = [
            ['Campaign Performance Report', now()->format('d M Y')],
            [],
            ['Summary', 'Value'],
            ['Total Campaigns', $this->report['total_campaigns']],
            ['Goal Achieved', $this->report['goal_achieved_count']],
            ['Success Rate', $this->report['success_rate'] . '%'],
            ['Avg Time to Goal', $this->report['avg_time_to_goal_days'] . ' days'],
            ['Total Raised', '₦' . number_format($this->report['total_raised'], 2)],
            [],
            ['Top Performing Campaigns'],
            ['Title', 'Category', 'Goal', 'Raised', 'Progress %', 'Donations', 'Donors', 'Status'],
        ];

        foreach ($this->report['top_performers'] as $c) {
            $rows[] = [
                $c['title'],
                $c['category'] ?? 'N/A',
                '₦' . number_format($c['goal']),
                '₦' . number_format($c['raised']),
                $c['progress'] . '%',
                $c['donations'],
                $c['donors'],
                $c['status'],
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Campaign Report';
    }
}