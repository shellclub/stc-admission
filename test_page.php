<?php 
 require('config.inc.php');
 $ids1 = $_REQUEST['std_id'];
 mysqli_set_charset($conn,"utf8");
 $sql = "select * from tb_std where std_id='".$ids1."'";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    // output data of each row
         $row = $result->fetch_assoc(); 

         $sname = $row['std_fname'];
         $lname = $row['std_lname'];
         $de_id = $row['std_s1'];  
}
$sql = "select * from tb_depart where de_id='".$de_id."'";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    // output data of each row
         $row = $result->fetch_assoc(); 

         $de_name= $row['de_name'];
}

/*
 if ($conn->query($sql) === TRUE) {
   //
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
*/

$conn->close();
  
?>
<html>
<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
 <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/navbar-top-fixed/">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
 

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="1">

    <nav class="navbar navbar-inverse navbar-fixed-top">
         <div class="collapse navbar-collapse" id="navbarCollapse">
          
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
              <div class="widget-user-image">
                <img class="img-circle" src="images/logo_web.png" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">ระบบทดสอบวัดความรู้ </h3>
              <h5 class="widget-user-desc">วิทยาลัยเทคนิคสุพรรณบุรี</h5>
            </div>
          <div class="box-footer no-padding">

               <div class="col-md-12">
                        <div class="col-sm-4">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">ผู้ประเมินวัดความรู้คือ</h3>
                                         <!-- /.box-tools -->
                                </div>
                            <!-- /.box-header -->
                                    <div class="box-body">
                                           <p class="text-primary" >รหัสผู้สมัคร : <?php echo $ids1;  ?>   </p>
                                           <p class="text-primary">ผู้สมัคร: ชื่อ  <?php echo $sname;  ?>  นามสกุล <?php echo $lname;  ?>  </p>
                                            <p class="text-primary">สาขาวิชา  <?php echo $de_name;  ?>   </p>
                                     </div>
                                <!-- /.box-body -->
                                  </div>
                                    <!-- /.box -->
                            </div>

                             <div class="col-sm-4 ">
                                <div class="info-box bg-green">
                                            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">เวลาในการทำข้อสอบ 60 นาที</span>
                                            <span class="info-box-number"><div id="timer"></div> </span>
                                        <div class="progress">
                                            <div class="progress-bar"  id="perc"></div>
                                        </div>
                                            <span class="progress-description">
                                                    <label id="nump">  </label>  
                                            </span>
                                         </div>
                                     <!-- /.info-box-content -->
                                </div>
                                     <!-- /.info-box -->
                            </div>

                            <div class="col-sm-4 ">
                                <div class="info-box bg-green">
                                            <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">คุณทำข้อสอบทั้งหมด </span>
                                            <span class="info-box-number"> <div id="numtest"></div> </span>
                                        <div class="progress">
                                            <div class="progress-bar"  id="numtest_perc"></div>
                                        </div>
                                            <span class="progress-description">
                                                    <label id="e_total_num">  </label>  
                                            </span>
                                         </div>
                                     <!-- /.info-box-content -->
                                </div>
                                     <!-- /.info-box -->
                            </div>
                     

             </div>
        </nav>
         
             
               <div class="collapse navbar-collapse" id="myNavbar">
                    
                   
                   <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">ข้อ 1-10 | 
                        <span class="pull-right badge bg-green"><p id="num0">0 </p> </span></a></li>
                    <li><a data-toggle="tab" href="#menu1">ข้อ 11-20 |
                        <span class="pull-right badge bg-green"><p id="num1">0 </p></span></a></li>
                    <li><a data-toggle="tab" href="#menu2">ข้อ 21-30 |
                        <span class="pull-right badge bg-green"><p id="num2">0 </p></span></a></li>
                    <li><a data-toggle="tab" href="#menu3">ข้อ 31-40 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu4">ข้อ 41-50 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu5">ข้อ 51-60 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu6">ข้อ 61-70 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu7">ข้อ 71-80 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu8">ข้อ 81-90 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                    <li><a data-toggle="tab" href="#menu9">ข้อ 91-100 |
                        <span class="pull-right badge bg-green">0</span></a></li>
                </ul>
               </div>

            <div id="section1" class="container-fluid">
            <form action="check_ans.php" method="get" name="form1" id="form1">
        <div class="tab-content">
         <?php include("rand_test.php"); ?>
         
              
            <div id="home" class="tab-pane fade in active">
                <?php for($i=0;$i<10;$i++){    ?>
                     <!-- div head -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h2 class="text-primary">ข้อที่ <?php echo $i+1; ?>:</h2>                                     
                                        <?php if($img_q[$i]!=''){ ?>  
                                        <img  src="ImagesQuestions/<?php echo $img_q[$i];  ?>" />
                                         <?php } ?>                                   
                                        <h3><?php echo $question[$i];   ?> </h3>
                                </div>
                                <div class="box-body">
                                   <div class="btn-group" data-toggle="buttons">
                                    <div class="col-md-12">
                                         <div class="col-sm-6">
                                             <label class="btn btn-purple btn-rounded active btn-md form-check-label">
                                                 <input type="radio" class="form-check-input"  name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="1"><?php if($choice1_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice1_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice1[$i]; ?> </p>  
                                             </lable>
                                        </div>   
                                        <div class="col-sm-6"> 
                                             <label class="btn btn-purple btn-rounded active btn-md form-check-label">
                                                 <input type="radio"  class="form-check-input" name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="2"><?php if($choice2_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice2_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice2[$i]; ?> </p> 
                                             </lable> 
                                        </div>  
                                    </div>
                                    <div class="col-md-12">  
                                        <div class="col-sm-6"> 
                                             <label class="btn btn-purple btn-rounded active btn-md form-check-label">
                                                 <input type="radio"  class="form-check-input" name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);"  value="3"><?php if($choice3_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice3_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice3[$i]; ?> </p>  
                                            </label>
                                        </div> 
                                        <div class="col-sm-6"> 
                                              <label class="btn btn-purple btn-rounded active btn-md form-check-label"> 
                                                 <input type="radio"  class="form-check-input"  name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="4"><?php if($choice4_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice4_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice4[$i]; ?> </p>  
                                              </label>
                                         </div>       
                                     </div>                 
                                                                    
                                </div>
                               </div>
                            </div>
                         
              
                       <!-- div close 1/2 page -->
                <?php } ?>

            </div>  
         <?php for($a=0;$a<=9;$a++) { ?> 
            <div id="menu<?php echo $a; ?>" class="tab-pane fade">
                <?php for($i=($a*10);$i<($a*10)+10;$i++){    ?>
                     <!-- div head -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h2 class="text-primary">ข้อที่ <?php echo $i+1; ?>:</h2>                                     
                                        <?php if($img_q[$i]!=''){ ?>  
                                        <img  src="ImagesQuestions/<?php echo $img_q[$i];  ?>" />
                                         <?php } ?>                                   
                                        <h3><?php echo $question[$i];   ?> </h3>
                                </div>
                                <div class="box-body">
                                   <div class="btn-group" data-toggle="buttons">
                                    <div class="col-md-12">
                                         <div class="col-sm-6">
                                           <label class="btn btn-purple btn-rounded btn-md form-check-label">
                                                 <input type="radio" class="form-check-input"   name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);"  value="1"><?php if($choice1_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice1_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice1[$i]; ?> </p>
                                            </lable>          
                                        </div>   
                                        <div class="col-sm-6"> 
                                            <label class="btn btn-purple btn-rounded btn-md form-check-label">
                                                 <input type="radio"  class="form-check-input" name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="2"><?php if($choice2_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice2_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice2[$i]; ?> </p>  
                                             </lable>
                                        </div>  
                                    </div>
                                    <div class="col-md-12">  
                                        <div class="col-sm-6"> 
                                               <label class="btn btn-purple btn-rounded btn-md form-check-label">
                                                 <input type="radio"   class="form-check-input" name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="3"><?php if($choice3_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice3_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice3[$i]; ?> </p>  
                                                </lable>
                                        </div> 
                                        <div class="col-sm-6">  
                                             <label class="btn btn-purple btn-rounded btn-md form-check-label">
                                                 <input type="radio"  class="form-check-input"  name="select_<?php echo $id_s[$i];  ?>" onchange="radio_check(<?php echo $i+1 ?>);" value="4"><?php if($choice4_img[$i]!='')  { ?>
                                                    <img  src="ImagesQuestions/<?php echo $choice4_img[$i];  ?>" />
                                                    <?php } ?> <p class="lead">  <?php echo $choice4[$i]; ?> </p> 
                                             </label> 
                                         </div>       
                                     </div>                 
                                                                           
                                </div>
                              </div>
                            </div>
                         
              
                       <!-- div close 1/2 page -->
                <?php } ?>
            </div>
            <?php } ?> 
            
       </div>
       
     
         
           
        </div> 
        <div class="col-lg-4 col-lg-offset-4">
          <input type="submit"  class="btn btn-danger" value="ส่งคำตอบ">
          </div> 
         </form>
       
       
    </div>
 



     
    
   

 
 

<!-- jQuery 3 -->

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>


 

<script language="javascript" type="text/javascript"> 
 
var timerVar = setInterval(countTimer, 1000);
var totalSeconds = 0;
var percen=0;
var totalExamArray = new Array();
var totalExam = 0;
var num = new Array();
num[10]=null;
totalExamArray[101] = null;

for(var a=1;a<101;a++) totalExamArray[a]=0;

function radio_check(examDone) {
		 
        //document.form1.Submit.disabled = false;
		//var arrIndex = new Number(examDone);
		totalExamArray[Number(examDone)] = 1;
		//alert(totalExamArray.length);
		for(var i=1;i<=100;i++) {
			 totalExam = totalExam+Number(totalExamArray[i]) ;
              
              
              
		}
        for(var s=1;s<=10;s++) num[0] = num[0]+Number(totalExamArray[s]);
        document.getElementById("num0").innerHTML=Number(num[0]);

        for(var s=11;s<=20;s++) num[1] = num[1]+Number(totalExamArray[s]);
        document.getElementById("num1").innerHTML=Number(num[1]);

         for(var s=21;s<=30;s++) num[2] = num[2]+Number(totalExamArray[s]);
        document.getElementById("num2").innerHTML=Number(num[2]);

        //document.form1.totalExam.value = totalExam;
        document.getElementById("numtest").innerHTML = "คุณทำข้อสอบไป "+totalExam+" ข้อ";
		totalExam =0;
        num[0]=0;
        num[1]=0;
        num[2]=0;
		 
        //alert(totalExam);
         
        
        
}

function check_ans(){
    var s=0;
    //var num1=5;
    for(s=1;s<10;s++){
        if(totalExamArray[s]!=0) num1++;
    }
    document.getElementById("num1").innerHTML=num1;

}


function countTimer() {
   ++totalSeconds;
   var hour = Math.floor(totalSeconds /3600);
   var minute = Math.floor((totalSeconds - hour*3600)/60);
   var seconds = totalSeconds - (hour*3600 + minute*60);

   if(hour == 1) {
        document.getElementById("timer").innerHTML = "เวลาผ่านไป = " + minute + "." + seconds+" นาที";
        document.getElementById("nump").innerHTML = "หมดเวลาในการทำข้อสอบ";
        document.getElementById("perc").style.width = "0%";
   }else{
    
    document.getElementById("timer").innerHTML = "ผ่านไป = " + minute + "." + seconds+" นาที";
    percen = (totalSeconds *100)/3600;
    document.getElementById("nump").innerHTML = "เวลาคงเหลือ : "+ (60 - minute)+" นาที";
    document.getElementById("perc").style.width = 100-percen.toFixed(0)+"%";
   }
}
 
 

</script>
</body>
</html>