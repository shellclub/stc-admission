<!DOCTYPE html>
<html>
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  body {
      position: relative; 
  }
  #section1 {padding-top:50px;height:500px;color: #fff; background-color: #1E88E5;}
  #section2 {padding-top:50px;height:500px;color: #fff; background-color: #673ab7;}
  #section3 {padding-top:50px;height:500px;color: #fff; background-color: #ff9800;}
  #section4 {padding-top:50px;height:500px;color: #fff; background-color: #00bcd4;}
  #section5 {padding-top:50px;height:500px;color: #fff; background-color: #009688;}
  </style>
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
      </button>
      
       <p class="navbar-brand" >เวลาคงเหลือ : 10. 1 นาที</p>
      <p class="navbar-brand" >ข้อสอบที่ทำ 10 ข้อ</p>
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
           
        </ul>
      </div>

    </div>

  </div>
</nav>    

<?php for($i=1;$i<=10;$i++){ ?>
<div id="section<?php echo $i; ?>" class="container-fluid">
  <h1>Section 1</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>

<?php } ?>

</body>
</html>
