<?php 
 date_default_timezone_set('Asia/Bangkok');
 require('config.inc.php');
 $ids1 = $_REQUEST['std_id'];
 mysqli_set_charset($conn,"utf8");
 $sql = "select * from tb_user_new where id='".$ids1."'";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    // output data of each row
         $row = $result->fetch_assoc(); 

         $sname = $row['name'];
         //$lname = $row['std_lname'];
         $de_id = $row['de_id'];
         $major = $row['major'];  
}
else{
    echo "<script> alert('เข้าสู่ระบบไม่ถูกต้อง กรุณาติดต่อเจ้าหน้าที่'); window.location= 'index.html'; </script>"; 
}

/*
$sql = "select * from tb_depart where id_std_name='".$de_id."'";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    // output data of each row
         $row = $result->fetch_assoc(); 

         $de_name= $row['de_name'];
}
*/


$sql = "select * from tb_log_test  where id='".$ids1."' and  num_test > 0";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    // output data of each row 
    echo "<script> alert('คุณได้ทำข้อสอบแล้วไม่สามารถทำข้อสอบได้อีก'); window.location= 'index.html'; </script>";     
}else{
 $date_in = date('d/m/Y,h:i');
 $sql = "select * from tb_log_test  where id='".$ids1."'";
 $result = $conn->query($sql); 
if ($result->num_rows > 0) {
  $log_data =0;
} else{
$sql = "insert into tb_log_test values('".$ids1."','".$date_in."','0','0')";
$log_data =0;
 if ($conn->query($sql) === TRUE) {
   // echo "New record created successfully";
 } else {
    //echo "<script> alert('เกิดปัญหาในการเชื่อมต่อข้อมูล กรุณาติดต่อเจ้าหน้าที่'); window.location= 'index.html'; </script>";    
 }
}
}
$conn->close();
  
?>
<html>
<head>
  <title>ระบบทดสอบวัดความรู้ วิทยาลัยเทคนิคสุพรรณบุรี</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <script src="swalert/dist/sweetalert2.min.js"> </script>
  <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
<script type="text/javascript">
window.addEventListener("keydown", function(event) {
    // Prevent refresh by F5 and Ctrl + R
    if ((event.key === "F5") || (event.ctrlKey && event.key === "r")) {
        event.preventDefault();  // Prevent the default action (refresh)
        alert("Refresh is disabled.");
    }
});
function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

$(document).ready(function(){
     $(document).on("keydown", disableF5);
});
</script> 

<style>
  
  body {
      position: relative; 
  }
   #section1 {padding-top:80px;height:300px;color: #fff; background-color: #00bcd4;}
   #section2 {padding-top:80px;   background-color: #00bcd4;}
   #section3 {padding-top:80px;   background-color: #00bcd4;}
   #section4 {padding-top:80px;   background-color: #00bcd4;}
   #section5 {padding-top:80px;   background-color: #00bcd4;}
   #section6 {padding-top:80px;   background-color: #00bcd4;}
   #section7 {padding-top:80px;   background-color: #00bcd4;}
   #section8 {padding-top:80px;  background-color: #00bcd4;}
   #section9 {padding-top:80px;   background-color: #00bcd4;}
   #section10 {padding-top:80px;   background-color: #00bcd4;}
   #section11 {padding-top:80px;   background-color: #00bcd4;}
   #section12 {padding-top:80px;   background-color: #00bcd4;}
   #section_end {padding-top:50px;height:80px;color: #fff; background-color: #00bcd4;}

   #myBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}
.button {
  padding: 15px 25px;
  font-size: 24px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button:hover {background-color: #3e8e41}

.button:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}


  </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50" onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" >
<button onclick="topFunction()" id="myBtn" title="Go to top">ทำข้อสอบแล้ว 0 ข้อ </button>
 
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
      </button>
      
       <p class="navbar-brand"  id="time_tt">เวลาคงเหลือ :  60 นาที</p>
      <p class="navbar-brand" id="numtest" >คุณทำข้อสอบไป 0 ข้อ</p>
    </div>

    <div>
       <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1"> Home </a></li>
          <li><a href="#section2"> 1-10 </a></li>
          <li><a href="#section3">11-20</a></li>
          <li><a href="#section4">21-30</a></li>
          <li><a href="#section5">31-40</a></li>
          <li><a href="#section6">41-50</a></li>
          <li><a href="#section7">51-60</a></li>
          <li><a href="#section8">61-70</a></li>
          <li><a href="#section9">71-80</a></li>
          <li><a href="#section10">81-90</a></li>
          <li><a href="#section11">91-100</a></li>
          <li><a href="#section_end">ส่งคำตอบ</a></li>
           
        </ul>
      </div>

    </div>

  </div>
</nav>

<form action="check_ans.php" method="POST" name="form1" id="form1" onkeydown="return event.key != 'Enter';">
<div id="section1" class="container-fluid">
    <div class="col-sm-4 bg-green">
              <div class="widget-user-image">
                <img class="img-circle" src="images/logo_web.png" alt="User Avatar" width="180">
              </div>
              <!-- /.widget-user-image -->
              <input type="hidden" id="std_id" name="std_id" value="<?php echo $ids1; ?>";
              <h3  class="widget-user-username">ระบบทดสอบวัดความรู้ </h3>
              <h5 class="widget-user-desc">วิทยาลัยเทคนิคสุพรรณบุรี</h5>
           </div>   
           <div class="col-sm-4">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">ผู้ประเมินวัดความรู้คือ</h3>
                                         <!-- /.box-tools -->
                                </div>
                            <!-- /.box-header -->
                                    <div class="box-body">
                                           <p class="text-primary" >รหัสผู้สมัคร : <?php echo $ids1;  ?>   </p>
                                           <p class="text-primary">ผู้สมัคร: ชื่อ  <?php echo $sname;  ?>  </p>
                                            <p class="text-primary">สาขาวิชา  <?php echo $major;  ?>   </p>
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
</div>          
 <?php if($log_data==0) include("rand_test.php"); ?>  
<?php for($a=0;$a<10;$a++) { ?>
<div id="section<?php echo $a+2; ?>" class="container-fluid">
     <?php for($i=($a*10);$i<($a*10)+10;$i++){    ?>
                     <!-- div head -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="text-primary">ข้อที่ <?php echo $i+1; ?>:</h3>                                     
                                        <?php if($img_q[$i]!=''){ ?>  
                                        <img  src="ImagesQuestions/<?php echo $img_q[$i];  ?>" />
                                         <?php } ?>                                   
                                        <h4><?php echo $question[$i];   ?> </h4>
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
<div id="section_end" class="container-fluid">
  <p> พัฒนาโปรแกรม โดย นายศุภโชค พานทอง และ นายสุธีร์  แบนประเสริฐ แผนกอิเล็กทรอนิกส์  </p>
</div>
        <div class="col-lg-4 col-lg-offset-4">
          <input type="submit"  class="button"   value="ส่งคำตอบ" onClick="return submitExam()">
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

<script>

window.addEventListener('beforeunload', function (e) {
    // แสดง alert เมื่อผู้ใช้พยายามออกจากหน้าเว็บ
    alert("Are you sure you want to leave? Any unsaved data will be lost.");
    
    // การใช้ returnValue หรือ return ที่ไม่จำเป็นต้องใช้ในกรณีนี้
    // แต่สามารถใช้เพื่อป้องกันการออกจากหน้าก็ได้ในบางเบราว์เซอร์
    e.returnValue = ''; // สำหรับเบราว์เซอร์บางตัว
    return ''; // สำหรับเบราว์เซอร์บางตัว
});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}



// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}



   function closedWin() {
    confirm("close ?");
    return false; /* which will not allow to close the window */
    }
    if(window.addEventListener) {
     window.addEventListener("close", closedWin, false);
    }

    window.onclose = closedWin;
    

    window.history.forward();
        function noBack()
        {
            window.history.forward();
        }

     window.onload = function() {
    document.addEventListener("contextmenu", function(e){
      e.preventDefault();
    }, false);
    document.addEventListener("keydown", function(e) {
    //document.onkeydown = function(e) {
      // "I" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
      }
      // "J" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
      }
      // "S" key + macOS
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
      }
      // "U" key
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
      }
      // "F12" key
      if (event.keyCode == 123) {
        disabledEvent(e);
      }
    }, false);
    function disabledEvent(e){
      if (e.stopPropagation){
        e.stopPropagation();
      } else if (window.event){
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  };

var ctrlKeyDown = false;

$(document).ready(function(){    
    $(document).on("keydown", keydown);
    $(document).on("keyup", keyup);
});

function keydown(e) { 

    if ((e.which || e.keyCode) == 116 || ((e.which || e.keyCode) == 82 && ctrlKeyDown)) {
        // Pressing F5 or Ctrl+R
        e.preventDefault();
    } else if ((e.which || e.keyCode) == 17) {
        // Pressing  only Ctrl
        ctrlKeyDown = true;
    }
};

function keyup(e){
    // Key up Ctrl
    if ((e.which || e.keyCode) == 17) 
        ctrlKeyDown = false;
};

 
</script>
 

<script language="javascript" type="text/javascript"> 
var warnnig_test = true;
var sendresult = true;
var timerVar = setInterval(countTimer, 1000);
var totalSeconds = 0;
var percen=0;
var totalExamArray = new Array();
var totalExam = 0;
var num = new Array();
num[10]=null;
totalExamArray[101] = null;
var missedExam = "";
var tx_score="";
var numt=0;
for(var a=1;a<101;a++) totalExamArray[a]=0;
var data;
 var chk=0;
 var hour  =0;
 var minute =0;
 var seconds=0;
function radio_check(examDone) {
		 
        //document.form1.Submit.disabled = false;
		//var arrIndex = new Number(examDone);
		totalExamArray[Number(examDone)] = 1;
		//alert(totalExamArray.length);
		for(var i=1;i<=100;i++) {
                totalExam = totalExam+Number(totalExamArray[i]) ;            
		}
         
        //document.form1.totalExam.value = totalExam;
        if(totalExam==100){
            document.getElementById("numtest").style.color ="#ff0000";
            document.getElementById("numtest").innerHTML = "คุณทำข้อสอบครบ 100 ข้อ";
            tx_scroe ="ทำข้อสอบครบ 100 ข้อ";
        }
        else {
            document.getElementById("numtest").style.color ="#00ff00";
            document.getElementById("numtest").innerHTML = "คุณทำข้อสอบไป "+totalExam+" ข้อ";
             //document.getElementById("myBtn").innerHTML = "ทำข้อสอบแล้ว  "+totalExam+" ข้อ";
             tx_score = "ทำข้อสอบแล้ว  "+totalExam+" ข้อ";
        }
		numt = totalExam;
        totalExam =0;
        missedExam =""; 
		  
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
 var tx_msg;
function ping(){
       $.ajax({
          url: 'check_anss.php',
          success: function(result){
              if(numt < 100) {
        for(var x=1;x<101;x++){
            if(totalExamArray[x]==0) {
				 missedExam = missedExam+" "+x+",\t";
				if (x%10==0)  missedExam = missedExam+"\n";
		     }
        }  
       
       
       
       
       
       //alertS();
        
       var answer = confirm("คุณทำข้อสอบไป "+numt+" ข้อ ยังไม่ครบ 100 ข้อ ต้องการส่งคำตอบ กด ปุ่ม OK ถ้าต้องการ ยกเลิกกดปุ่ม Cancel  ข้อสอบที่ยังไม่ได้ทำคือ \n "+missedExam+" \n ต้องการส่งคำตอบ กด ปุ่ม OK ถ้าต้องการ ยกเลิกกดปุ่ม Cancel");
        missedExam ="";
        if (answer) {
            
            var cm2 = confirm("คุณต้องการส่งคำตอบ ใช่ หรือ ไม่"); 
            if(cm2){
              return true;
              document.form1.submit();
            }else{
              return false;
            }
            
        }
        else {
                return false;
        }

      
      } 
          },     
          error: function(result){
             //('timeout/error');
             document.getElementById("myBtn").innerHTML = "เช็คความพร้อม ในการเชื่อมต่อ Server ";
          }
       });
         
}
var answer;
function sendTestEnd(){
   
   // ping();
    data =2;
    Swal.fire({
                  position: "top-end",
                  icon: 'warning',
				          title: 'หมดเวลาในการทำข้อสอบ ระบบทำการส่งอัตโนมัติ กรุณารอสักครู่............. ',
				          showConfirmButton: false,
				          timer: 3000
			          }).then(function() {
					        //document.form1.submit();
          //window.location = "index.php";});
                    //document.form1.submit();
                    var xmlhttp = new XMLHttpRequest();       
                    xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        data =1;  
                        document.form1.submit(); 
                    }else{
                //data=2;
                    } 
                    };
                        xmlhttp.open("GET", "tests.php?d=1", true);
                        xmlhttp.send();
      });    
      
    
    if(data==1){
      document.form1.submit();
    }else if (data==2){
        data =0;
        document.getElementById("myBtn").innerHTML = "เช็คความพร้อม Server กรุณารอสักครู่....";
        //alert("ไม่สามารถเชื่อมต่อ Server ได้ ");
        chk = 1;
        sendresult = true;
        return false;
    }
    
}

function alertS(){
  const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
          },
                buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
              title: " คุณทำข้อสอบไป "+numt+" ข้อ ยังไม่ครบ   ต้องการ  ส่งคำตอบ ใช่ หรือ ไม่?",
              text: "คุณทำข้อสอบไป "+numt+" ข้อ จาก 100 ข้อ  ต้องการส่งคำตอบ กด ตกลงส่งคำตอบ  ถ้าต้องการยกเลิกกดปุ่ม กลับไปทำข้อสอบ  ข้อสอบที่ยังไม่ได้ทำคือ ข้อ : \n "+missedExam+" \n",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "ตกลงยืนยันการส่งคำตอบ",
              cancelButtonText: "กลับไปทำข้อสอบ",
              reverseButtons: true
          }).then((result) => {
          if (result.isConfirmed) {
                Swal.fire({
                    title: "ต้องการส่งตำตอบ ใช่หรือ ไม่ ?",
                    text: "ยืนยันการส่งคำตอบ แล้วไม่สามารถสอบได้อีก ต้องการส่งคำตอบหรือไม่ ",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่ต้องการ"
                }).then((result) => {
                  if (result.isConfirmed) {
                      Swal.fire({
                          title: "ส่งคำตอบเรียบร้อย",
                          text: "คุณได้ยืนยันการส่งคำตอบเรียบร้อยแล้ว .",
                          icon: "success"
                      });
                      document.form1.submit();
                      return true;
                  }
                });
          } else if (
    /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
          ) {
                return false;
          }
          });
}
function submitExam(){
   // ping();
    data =2;
    
    var xmlhttp = new XMLHttpRequest();       
      xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                   data =1;
                   chk=2;
                        for(var x=1;x<101;x++){
                            if(totalExamArray[x]==0) {
				                missedExam = missedExam+" "+x+",\t";
				                if (x%10==0)  missedExam = missedExam+"\n";
		                    }
                         }  
        //ping();       if()
                        if(minute > 59) document.form1.submit();
                        else{
                            if(numt==100) document.form1.submit() ; 
                            else {
                              alertS();
                            }
                                
                        }
                        
                        missedExam ="";
                        
             } 
        };
        xmlhttp.open("GET", "tests.php?d=1", true);
        xmlhttp.send();
     
    if(data==1){

     if(numt < 100) {
        for(var x=1;x<101;x++){
            if(totalExamArray[x]==0) {
              missedExam = missedExam+" "+x+",\t";
				if (x%10==0)  missedExam = missedExam+"\n";
		     }
        }  
            alertS();
      } 
    }else if (data==2){
        data =0;
        document.getElementById("myBtn").innerHTML = "เช็คความพร้อม Server กรุณารอสักครู่....";
        //alert("ไม่สามารถเชื่อมต่อ Server ได้ ");
        chk = 1;
        sendresult = true;
        return false;
    }
    
}


function countTimer() {
   ++totalSeconds;
   hour = Math.floor(totalSeconds /3600);
   minute = Math.floor((totalSeconds - hour*3600)/60);
   seconds = totalSeconds - (hour*3600 + minute*60);
   
   if(hour >= 1) {
        document.getElementById("timer").innerHTML = "เวลาผ่านไป = " + minute + "." + seconds+" นาที";
        document.getElementById("nump").innerHTML = "หมดเวลาในการทำข้อสอบ";
        document.getElementById("time_tt").innerHTML = "หมดเวลาในการทำข้อสอบ";
        document.getElementById("perc").style.width = "0%";
        //if(chk==2) document.form1.submit();
       //submitExam();
       if(sendresult){ 
          sendTestEnd();
          sendresult = false;
       }
   }else{
    if(minute == 50 ){
          if(warnnig_test){
            Swal.fire({
              position: "top-end",
              icon: "info",
              title: "เหลือเวลาในการทำข้อสอบ 10 นาที",
              showConfirmButton: false,
              timer: 1500
            });
            warnnig_test = false;
          }
         document.getElementById("time_tt").style.color ="#ff0000";
         document.getElementById("time_tt").innerHTML = "เหลือเวลาในการทำข้อสอบ 10 นาที ";
    }
    else if(minute == 55 ){
          if(warnnig_test){
            Swal.fire({
              position: "top-end",
              icon: "info",
              title: "เหลือเวลาในการทำข้อสอบ 5 นาที",
              showConfirmButton: false,
              timer: 1500
            });
            warnnig_test = false;
          }
        document.getElementById("time_tt").style.color ="#ff0000";
        document.getElementById("time_tt").innerHTML = "เหลือเวลาในการทำข้อสอบ 5 นาที";
    }else if(minute == 58 ){
        if(warnnig_test){
          Swal.fire({
              position: "top-end",
              icon: "info",
              title: "เหลือเวลาในการทำข้อสอบ 2 นาที",
              showConfirmButton: false,
              timer: 1500
          });
          warnnig_test = false;
        }
        document.getElementById("time_tt").style.color ="#ff0000";
        document.getElementById("time_tt").innerHTML = "เหลือเวลาในการทำข้อสอบ 2 นาที";
    }else if(minute == 59 ){
          if(warnnig_test){
          Swal.fire({
              position: "top-end",
              icon: "info",
              title: "เหลือเวลาในการทำข้อสอบ 1 นาที",
              showConfirmButton: false,
              timer: 1500
          });
          warnnig_test = false;
        }
        document.getElementById("time_tt").style.color ="#ff0000";
        document.getElementById("time_tt").innerHTML = "เหลือเวลาในการทำข้อสอบ 1 นาที";
    }  
    else  {  
        warnnig_test = true; 
            
        document.getElementById("time_tt").style.color ="#ffffff";
        document.getElementById("time_tt").innerHTML = "เวลา : "+ (60 - minute)+" นาที";
    } 
    document.getElementById("timer").innerHTML = "ผ่านไป = " + minute + "." + seconds+" นาที";
    percen = (totalSeconds *100)/3600; 
    if(numt==100) 
        document.getElementById("myBtn").innerHTML = "ทำข้อสอบครบ 100 ข้อ";
    else{
        if(chk==1) {
           document.getElementById("myBtn").innerHTML = "เกิดปัญหาเชื่อมต่อ SERVER ติดต่อผู้ดูแล";    
           sendresult = true;
        }
        else 
           document.getElementById("myBtn").innerHTML = tx_score+" เวลาผ่านไป "+  minute + "." + seconds+" นาที"; 
    }
    document.getElementById("nump").innerHTML = "เวลา : "+ (60 - minute)+" นาที";
    document.getElementById("perc").style.width = 100-percen.toFixed(0)+"%";
   }
}
 
 

</script>



</body>
</html>