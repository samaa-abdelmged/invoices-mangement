<?php
namespace App\ExcelExports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class ExportExcelClass
{
    public function InvoicesReport($invoices)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()
            ->setRightToLeft(true);

        $sheet->getStyle('1')->getFont()->getColor()->setARGB('3355FF');

        $sheet->getStyle('A1:L1000')->getFont()->setSize(13);

        $sheet->getStyle('A1:L1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getDefaultColumnDimension()->setWidth(13 + 5);
        $columnHeaders = ['رقم الفاتورة', 'تاريخ الفاتورة', 'تاريخ الأستحقاق', 'المنتج', 'القسم', 'الخصم', 'نسبة الضريبة', 'قيمة الضريبة', 'الاجمالي', 'حالة الدفع', 'الوصف'];

        $i = 'A1';
        $maxCol = 'L';

        foreach ($columnHeaders as $key => $colName) {

            $sheet->setCellValue($i, $colName);

            // تحويل الحرف إلى رقم
            $colNum = ord($i[0]) - ord('A') + 1;

            // زيادة رقم العمود
            $colNum++;

            // تحويل رقم العمود إلى حرف
            $nextCol = chr($colNum + ord('A') - 1);

            // إعادة تكوين الإحداثيات
            $i = $nextCol . $i[1];

            // التحقق من تجاوز أقصى عمود
            if ($nextCol > $maxCol) {
                // الانتقال إلى السطر التالي
                $i = 'A' . (substr($i, 1) + 1);
            }
        }

        $row = 2;
        foreach ($invoices as $key => $invoice) {
            for ($col = 'A'; $col <= 'L'; $col++) {
                switch ($row) {
                    case $row:

                        $sheet->setCellValue($col . $row, $invoice->invoice_number);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->invoice_Date);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Due_date);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->product);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->section->section_name);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Discount);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Rate_VAT);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Value_VAT);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Total);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->Status);
                        $col++;

                        $sheet->setCellValue($col . $row, $invoice->note);
                        $col++;

                        $row++;
                }

            }
        }

        ob_end_clean();
        $filename = 'invoices.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $xlsxWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $xlsxWriter = new Xlsx($spreadsheet);
        exit($xlsxWriter->save('php://output'));
    }
}