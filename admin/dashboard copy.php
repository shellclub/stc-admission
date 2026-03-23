<?php
 //session_start();
 //session_start();
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");

 $sql = "select count(id) as num  from tb_user where score > 0 "; 
 $result = $conn->query($sql); 
 if($result->num_rows >0){
      $row = $result->fetch_assoc(); 
      $num_total = $row['num'];
  }

 $sql = "select max(score) as maxs  from tb_user "; 
 $result = $conn->query($sql); 
 if($result->num_rows ){
    $row = $result->fetch_assoc(); 
    $num_max = $row['maxs']; 
 } 
 
$sql = "SELECT MIN( score ) AS min  FROM  `tb_user` where score > 0";
$result = $conn->query($sql);
$row = $result->fetch_assoc(); 
$num_min = $row['min'];


 $sql = "select count(id)  as num from tb_std   "; 
 $result = $conn->query($sql); 
  if($result->num_rows >0){
      $row = $result->fetch_assoc(); 
      $countstd = $row['num'];
  }

 $sql = "select AVG(score)  as num from tb_user "; 
 $result = $conn->query($sql); 
  if($result->num_rows >0){
      $row = $result->fetch_assoc(); 
      $avg_num = $row['num'];
  }

$avg_std = ( $num_total / $countstd  ) *100;
    $numd = date('d/m/Y');
    //echo $numd;
    $sql = "SELECT count(id) as num FROM `tb_log_test` WHERE LEFT(dete_log,10) ='$numd';"; 
    $result = $conn->query($sql); 
    if($result->num_rows >0){
        $row = $result->fetch_assoc(); 
        $day = $row['num'];
    }

 $conn->close();
?>  


 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ระบบทดสอบวัดความรู้  
        <small>วิทยาลัยเทคนิคสุพรรณบุรี</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
     <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $num_max."/".$num_min; ?></h3>

              <p>คะแนนสูงสุด/คะแนนต่ำสุด</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-pie"></i>
            </div>
            <a href="home_menu.php?page=show_score_std" class="small-box-footer">แสดงรายละเอียด <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo round($avg_std,2); ?><sup style="font-size: 20px">%</sup></h3>

              <p>จำนวนผู้เข้าสอบ </p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
       <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $day."/".$num_total;  ?></h3>

              <p>จำนวนผู้เข้าสอบวันนี้/ทั้งหมด</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo round($avg_num,2);  ?></h3>

              <p>คะแนนเฉลี่ยผู้เข้าสอบ</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
       
       <div class="box box-solid ">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">คะแนนที่ได้</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div style="width: 100%">
			          <canvas id="canvas" height="100%" width="100%"></canvas>
		          </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #ff0000">
                  <input type="text" class="knob" data-readonly="true" value="<?php echo $num_max; ?>" 
                  data-width="100" data-height="100"
                         data-fgColor="#ff0000">

                  <div class="knob-label">คะแนนสูงสุด</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #00ff00">
                  <input type="text" class="knob" data-readonly="true" value="<?php echo $num_min; ?>" 
                  data-width="100" data-height="100"
                         data-fgColor="#00ff00">

                  <div class="knob-label">คะแนนต่ำสุด</div>
                </div>
                <!-- ./col -->
                 <div class="col-xs-4 text-center" style="border-right: 1px solid #0000ff">
                  <input type="text" class="knob" data-readonly="true" value="<?php echo round($avg_num,2); ?>" 
                  data-width="100" data-height="100"
                         data-fgColor="#0000ff">

                  <div class="knob-label">คะแนนเฉลี่ย</div>
                </div>
                 
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          

           




      </section>    
  
</div>

<?php 
require('../config.inc.php');
  $sql = "select * from tb_depart where de_type ='1'";
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query($sql);
  $name_dep = array();
  while($row = $result->fetch_assoc()){
    $name_dep[] = $row['de_name'];
          
     $sql1 = "SELECT MIN( score ) AS min  FROM  `tb_user` where de_id = '".$row['de_id']."' and score > 0";
     $result1 = $conn->query($sql1);
     $row1 = $result1->fetch_assoc();
     $min_s[] = $row1['min'];

     $sql2 = "SELECT MAX( score ) AS max  FROM  `tb_user` where de_id = '".$row['de_id']."'";
     $result2 = $conn->query($sql2);
     $row2 = $result2->fetch_assoc();
     $max_s[] = $row2['max'];

  }


     
?>
<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : [<?php echo '"'.implode('","', $name_dep).'"' ?> ],
		datasets : [
			{
				fillColor : "rgba(219, 82, 36, 1)",
				strokeColor : "rgba(219, 82, 36, 1)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
        label: 'คะแนนสูงสุด',
				data : [<?php echo '"'.implode('","', $max_s).'"' ?>]
			},
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
        label: 'คะแนนต่ำสุด',
				data : [<?php echo '"'.implode('","', $min_s).'"' ?>]
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>