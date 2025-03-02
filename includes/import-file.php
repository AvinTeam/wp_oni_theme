<?php

use oniclass\ONIDB;
use PhpOffice\PhpSpreadsheet\IOFactory;

$onidb = new ONIDB('question');

$count_row = 0;

// بررسی آپلود فایل
if (isset($_FILES[ 'excel_file' ]) && $_FILES[ 'excel_file' ][ 'error' ] === UPLOAD_ERR_OK) {
    $fileTmpPath   = $_FILES[ 'excel_file' ][ 'tmp_name' ];
    $fileName      = $_FILES[ 'excel_file' ][ 'name' ];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // بررسی فرمت فایل
    $allowedExtensions = [ 'xls', 'xlsx' ];
    if (! in_array($fileExtension, $allowedExtensions)) {
        die("فرمت فایل پشتیبانی نمی‌شود. لطفاً یک فایل اکسل انتخاب کنید.");
    }

    try {
        // خواندن فایل اکسل
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet       = $spreadsheet->getActiveSheet();          // شیت فعال
        $data        = $sheet->toArray(null, true, true, true); // تبدیل به آرایه

        foreach ($data as $rowIndex => $row) {

            if ($rowIndex === 1) {continue;}

            $mappedRow = [

                'id'             => $row[ 'A' ],
                'chapter'        => $row[ 'B' ],
                'chapter_number' => $row[ 'C' ],
                'verse'          => $row[ 'D' ],
                'q_type'         => $row[ 'E' ],
                'question'       => $row[ 'F' ],
                'option1'        => $row[ 'G' ],
                'option2'        => $row[ 'H' ],
                'option3'        => $row[ 'I' ],
                'option4'        => $row[ 'J' ],
                'answer'         => $row[ 'K' ],

             ];

            $insert_id = $onidb->insert($mappedRow);

            $count_row++;

        }

    } catch (Exception $e) {
        die("خطا در خواندن فایل اکسل: " . $e->getMessage());
    }
} else {
    die("لطفاً یک فایل اکسل آپلود کنید.");
}