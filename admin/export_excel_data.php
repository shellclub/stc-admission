
<?php
date_default_timezone_set('Asia/Bangkok');
header('Content-type: text/html; charset=tis-620');	
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");

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
               
//$objWorkSheet = $objPHPExcel->createSheet();

  $objPHPExcel->getActiveSheet()
    ->getStyle('A1:F1')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
unset($styleArray);	

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ลำดับ')
            ->setCellValue('B1', 'รหัสประจำตัวสอบ')
            ->setCellValue('C1', 'ชื่อ')
            ->setCellValue('D1', 'นามสกุล')
            ->setCellValue('E1', 'สาขาวิชา 1')
            ->setCellValue('F1', 'คะแนนที่ได้');

            //->setCellValue('G1', 'สาขาวิชา 3')
			      //->setCellValue('H1', 'คะแนนที่ได้');
			
			
$i=2;
$sql =  "select *  from tb_depart   INNER JOIN tb_user ON tb_user.de_id=tb_depart.id_std_name order by tb_depart.id_std_name , tb_user.score desc";
	$result = $conn->query($sql); 
   	if ($result->num_rows > 0) {	  
		while($row = $result->fetch_assoc()){
		     
         $id_std = $row['id'];    
         $sql = "select * from tb_std where std_id = '".$id_std."'"; 
         $result1 = $conn->query($sql);
         $row1 = $result1->fetch_assoc();
         if($row1['std_sex']==1) $sex="นาย";
         else $sex="นางสาว";
         $d0 = $row['name'];
		     $d1 = $row['username'];
         $d2 = $row['de_id'];
		     $d3 = $row['score'];
         $d_name = $row['de_name'];

        
         $sql_id2 = "select * from tb_depart where id_std_name= '".$d2."'";
         $result_id2 = $conn->query($sql_id2);
         $row_id2 = $result_id2->fetch_assoc();
         $dname_d2 = $row_id2['de_name'];

         


	for ($col = 'A'; $col != 'F'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
   }

    $objPHPExcel->getActiveSheet()
    ->getStyle('A'.$i.':F'.$i)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($styleArray);
unset($styleArray);

		   $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, ($i-1))  
            ->setCellValue('B'.$i, $id_std)          
            ->setCellValue('C'.$i, $sex."".$d0)
            ->setCellValue('D'.$i, $d1)
            ->setCellValue('E'.$i,$d_name)
            ->setCellValue('F'.$i,$d3);
            //->setCellValue('G'.$i,$dname_d3)
			//->setCellValue('H'.$i,$d3);
			 
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