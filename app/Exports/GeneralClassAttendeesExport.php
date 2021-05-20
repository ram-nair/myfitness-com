<?php

namespace App\Exports;

use App\GeneralClassAttendees;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class GeneralClassAttendeesExport implements FromQuery, WithMapping, WithHeadings, WithTitle, WithDrawings, WithEvents, WithCustomStartCell
{
    use Exportable;

    public $classTitle;
    public $classInstructor;
    public $sessionDate;
    public $sessionId;
    public function __construct($classTitle, $classInstructor, $sessionDate, $sessionId)
    {
        $this->classTitle = $classTitle;
        $this->classInstructor = $classInstructor;
        $this->sessionDate = $sessionDate;
        $this->sessionId = $sessionId;
    }

    public function query()
    {
        return GeneralClassAttendees::with(['user'])->where('slot_id', $this->sessionId);
    }

    public function map($attendees): array
    {
        $enrolledAt = Carbon::parse($attendees['enrolled_at'])->format('Y-m-d');
        $attendance = $attendees['attendance'] === 'absent' ? 'Absent' : 'Present';
        return [
            $attendees['user']['first_name'],
            $attendees['user']['email'],
            $attendees['user']['phone'],
            $enrolledAt,
            $attendance,
        ];
    }

    public function headings(): array
    {
        $title = "Attendees List";
        return [
            [
                "Class: $this->classTitle  Instructor: $this->classInstructor  Session: $this->sessionDate"
            ],
            [],
            [
                "Participant Name", "Participant Email", "Participant Phone", "Enrolled At", "Attendance"
            ]
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function title(): string
    {
        return 'Attendees List';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/provis-excel-banner.jpg'));
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $columns = ['A', 'B', 'C', 'D', 'E'];
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(30);
                }
                $event->sheet->mergeCells('A1:E4');
                $event->sheet->mergeCells('A5:E6');
                $cellRange1 = 'A5:E6';
                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setSize(14);
                $cellRange2 = 'A7:E7';
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(12);
                $styleArray1 = [
                    'font' => [
                        'bold' => true,
                        'underline' => true,
                    ],

                ];
                $styleArray2 = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '14849c'],
                        ]
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A5:E6')->applyFromArray($styleArray1);
                $event->sheet->getDelegate()->getStyle('A7:E7')->applyFromArray($styleArray2);
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
            },
        ];
    }
}
