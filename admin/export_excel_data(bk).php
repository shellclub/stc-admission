<?php
include "connect.php";
//mysqli_set_charset($conn,"utf8");

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("supachoke Panthong")
							 ->setLastModifiedBy("supachoke Panthong")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("stec test exam");


  $objPHPExcel->getActiveSheet()
    ->getStyle('A1:E1')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
unset($styleArray);	

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ลำดับ')
            ->setCellValue('B1', 'ชื่อ')
            ->setCellValue('C1', 'นามสกุล')
            ->setCellValue('D1', 'สาขาวิชา')
			->setCellValue('E1', 'คะแนนที่ได้');
			
			
$i=2;
$sql =  "select * from tb_user order by score desc , de_id asc ";
	$result = $conn->query($sql); 
   	if ($result->num_rows > 0) {	  
		while($row = $result->fetch_assoc()){
		     
             
         $d0 = $row['name'];
		     $d1 = $row['username'];
		     $d2 = $row['de_id'];
		     $d3 = $row['score'];
         $sql = "select de_name from tb_depart where de_id = '".$d2."'"; 
         $result1 = $conn->query($sql);
         $row1 = $result1->fetch_assoc();
         $d_name = $row1['de_name'];

	for ($col = 'A'; $col != 'F'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
   }

    $objPHPExcel->getActiveSheet()
    ->getStyle('A'.$i.':E'.$i)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray);
unset($styleArray);

		   $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, ($i-1))            
            ->setCellValue('B'.$i, $d0)
            ->setCellValue('C'.$i, $d1)
            ->setCellValue('D'.$i,$d_name)
			->setCellValue('E'.$i,$d3);
			 
		   $i++;	
		}
	}

	
/*	
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

 */
// Rename worksheet
 $date = date('d_m_Y');
$objPHPExcel->getActiveSheet()->setTitle("คะแนนสอบ_".$date);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
// ----------------------- 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="exam_data_"'.$date.'".xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>