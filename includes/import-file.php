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

    // دریافت تطبیق ستون‌ها از کاربر
    if (isset($_POST[ 'frm' ]) && ! empty($_POST[ 'frm' ])) {
        $columnMapping = $_POST[ 'frm' ]; // کلید: ستون (مثلاً 'question' => 'A')
    } else {
        die("لطفاً ستون‌های مورد نظر را تطبیق دهید.");
    }

    try {
        // خواندن فایل اکسل
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet       = $spreadsheet->getActiveSheet();          // شیت فعال
        $data        = $sheet->toArray(null, true, true, true); // تبدیل به آرایه

        foreach ($data as $rowIndex => $row) {

            if ($rowIndex === 1) {continue;}

            $mappedRow = [  ];
            foreach ($columnMapping as $key => $columnLetter) {
                $value = "";

                if ($key == 'question') {$value = sanitize_textarea_field($row[ $columnLetter ]);}
                if ($key == 'option1') {$value = sanitize_text_field($row[ $columnLetter ]);}
                if ($key == 'option2') {$value = sanitize_text_field($row[ $columnLetter ]);}
                if ($key == 'option3') {$value = sanitize_text_field($row[ $columnLetter ]);}
                if ($key == 'option4') {$value = sanitize_text_field($row[ $columnLetter ]);}
                if ($key == 'answer') {$value = (isset($row[ $columnLetter ])) ? absint($row[ $columnLetter ]) : 0;}

                $mappedRow[ $key ] = $value ?? null; // مقدار ستون موردنظر
            }
            $insert_id = $onidb->insert($mappedRow);

            $count_row++;

        }

    } catch (Exception $e) {
        die("خطا در خواندن فایل اکسل: " . $e->getMessage());
    }
} else {
    die("لطفاً یک فایل اکسل آپلود کنید.");
}
