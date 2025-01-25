<?php

use oniclass\oni_export;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$oniexport = new oni_export('match');
$arrayuser = $oniexport->getuser($_POST);
$arrayall  = $oniexport->getall($_POST);

// ایجاد یک فایل اکسل جدید
$spreadsheet = new Spreadsheet();

// تابع برای پر کردن شیت‌ها با داده‌ها
function fillSheet($sheet, $data, $header, $title)
{
    $sheet->setTitle($title);

    // اضافه کردن هدر
    $rowNumber    = 1;
    $columnLetter = 'A';
    foreach ($header as $headerText) {
        $sheet->setCellValue($columnLetter . $rowNumber, $headerText);
        $columnLetter++;
    }

    $rowNumber = 2; // داده‌ها از ردیف دوم شروع می‌شوند
    foreach ($data as $row) {
        $row->unique_date = tarikh($row->unique_date);

        $columnLetter = 'A';
        foreach ($row as $cell) {

            $sheet->setCellValue($columnLetter . $rowNumber, $cell);
            $columnLetter++;
        }
        $rowNumber++;
    }

    // استایل و راست‌چین کردن متن‌ها
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $tableRange    = "A1:{$highestColumn}{$highestRow}";
    $sheet->getStyle($tableRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    // تنظیم جهت شیت به راست‌به‌چپ
    $sheet->setRightToLeft(true);
    $sheet->getStyle($tableRange)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [ 'argb' => '00000000' ],
             ],
         ],
        'fill'    => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => [ 'argb' => 'FFEFEFEF' ],
         ],
     ]);

}

// هدر مشترک برای هر شیت
$header1 = [ 'تاریخ', 'شماره موبایل', 'تعداد مسابقه', 'امتیاز' ];
$header2 = [ 'تاریخ', 'تعداد کاربران', 'تعداد مسابقه' ];

$sheet1 = $spreadsheet->getActiveSheet();
fillSheet($sheet1, $arrayuser, $header1, 'همه');

$sheet2 = $spreadsheet->createSheet();
fillSheet($sheet2, $arrayall, $header2, 'خلاصه');

// خروجی فایل اکسل
$writer   = new Xlsx($spreadsheet);
$fileName = 'report.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$fileName}\"");
$writer->save('php://output');
