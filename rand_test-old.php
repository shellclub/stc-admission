<?php
$tsubject =array("ความถนัดทางการเรียน(SAT)","วิทยาศาสตร์พื้นฐาน","คณิตศาสตร์พื้นฐาน","ความถนัดเชิงช่าง","อังกฤษพื้นฐาน","อนุกรมเลขคณิต","ภาษาไทย");
$anum   = array(5,10,10,5,10,10,10);
$numtest = 1;
$data = array(); // Initialize an array to hold all the results
//$numtest = 1; /
require('config.inc.php');
for($i=0;$i<count($tsubject);$i++){
mysqli_set_charset($conn,"utf8");    
$sql="select * from tb_test where subject ='$tsubject[$i]' order by rand() limit  $anum[$i]";
 $result = $conn->query($sql); 
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //echo "num: ".$numtest .":". $row['id']. " pic_test" . $row["q_pic"]. "Q" . $row["q_t"]. "<br>";
        
        $id_s[$numtest-1] = $row['id'];
	    $img_q[$numtest-1] = $row['q_pic'];
	    $question[$numtest-1] = $row['q_t'];
	    $choice1_img[$numtest-1] = $row['c1_pic'];
	    $choice1[$numtest-1] = $row['c1'];
	    $choice2_img[$numtest-1] = $row['c2_pic'];
	    $choice2[$numtest-1] = $row['c2'];
	    $choice3_img[$numtest-1] = $row['c3_pic'];
	    $choice3[$numtest-1] = $row['c3'];
	    $choice4_img[$numtest-1] = $row['c4_pic'];
	    $choice4[$numtest-1]= $row['c4'];
		$ans[$numtest-1] = $row['answer'];
		$data[] = array(
			'id' => $row['id'],
			'q_pic' => $row['q_pic'],
			'q_t' => $row['q_t'],
			'choices' => array(
				'choice1_img' => $row['c1_pic'],
				'choice1' => $row['c1'],
				'choice2_img' => $row['c2_pic'],
				'choice2' => $row['c2'],
				'choice3_img' => $row['c3_pic'],
				'choice3' => $row['c3'],
				'choice4_img' => $row['c4_pic'],
				'choice4' => $row['c4'],
				'answer' => $row['answer']
			),
			'answer' => $row['answer']
		);
        $numtest++;

    }
	$datas =  json_encode($data);
	 
}
//$conn->close();
?>

 