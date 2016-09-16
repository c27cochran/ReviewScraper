<?php

require_once ('Classes/Scraper.php');
require_once ('Classes/Yelp.php');
require_once ('Classes/GoogleAPI.php');

$scraper = new Scraper();
$yelp = new Yelp();
$google = new GoogleMapsAPI();

$fileName = $scraper->getFileName();

$objPHPExcel = PHPExcel_IOFactory::load($fileName);

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0);

$column = 'A';
$lastRow = $sheet->getHighestRow();
for ($row = 1; $row <= $lastRow; $row++) {

    $cell = $sheet->getCell($column.$row);

    $unsigned_url = $objPHPExcel->getActiveSheet()->getCell('A'.$row)->getFormattedValue();

    if (strpos($unsigned_url,'yelp.com') !== false) {

        $businessName = $yelp->getYelpBusinessName($unsigned_url);

        $reviewCount = $yelp->getYelpReviewCount($unsigned_url);

        $averageRating = $yelp->getYelpAverageRating($unsigned_url);

        $publicUrl = $yelp->getYelpPublicURL($unsigned_url);

        $html = file_get_html($publicUrl);

        // Find filtered number
        foreach($html->find('div[class=not-recommended]') as $element)

            $notRecommended = $element->plaintext;

        //filter out only the number from the class not-recommended string
        $filteredNumber = preg_replace( '/[^0-9]/', '', $notRecommended);

        $sheet->setCellValue('A'.$row, $businessName);
        $sheet->setCellValue('B'.$row, $reviewCount);
        $sheet->setCellValue('C'.$row, $averageRating);
        $sheet->setCellValue('D'.$row, $filteredNumber);

    } else if (strpos($unsigned_url,'google.com') !== false) {

        $google->formatGoogleURL($unsigned_url);

        $googleRating = $google->getGoogleRating($unsigned_url);
        $googleReviewCount = $google->getGoogleReviewCount($unsigned_url);
        $googleBusiness = $google->getGoogleBusiness($unsigned_url);

        $sheet->setCellValue('A'.$row, $googleBusiness);
        $sheet->setCellValue('E'.$row, $googleRating);
        $sheet->setCellValue('F'.$row, $googleReviewCount);
    }
}


$sheet->insertNewRowBefore($num_rows + 1, 1);

$sheet->setCellValue('A1', 'Business Name');
$sheet->setCellValue('B1', 'Yelp Review Count');
$sheet->setCellValue('C1', 'Yelp Average Rating');
$sheet->setCellValue('D1', 'Yelp Filtered Number');
$sheet->setCellValue('E1', 'Google Average Rating');
$sheet->setCellValue('F1', 'Google Review Count');


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
header('Cache-Control: max-age=0');
header('Content-Transfer-Encoding: binary');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

ob_clean();
flush();
readfile($objWriter->save('php://output'));

// remove temporary file from the server once it's been downloaded
unlink($fileName);