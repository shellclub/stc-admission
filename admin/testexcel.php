<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="MyXls.xls"');#ชื่อไฟล์
?>

<html xmlns:o=”urn:schemas-microsoft-com:office:office”

xmlns:x=”urn:schemas-microsoft-com:office:excel”

xmlns=”http://www.w3.org/TR/REC-html40″>

<HTML>

<HEAD>

<meta http-equiv=”Content-type” content=”text/html;charset=tis-620″ />

</HEAD><BODY>

<TABLE  x:str BORDER=”1″>

<TR>

<TD><b>AAA</b></TD>

<TD><b>AAA</b></TD>

<TD><b>AAA</b></TD>

</TR>

<TR>

<TD>BBB</TD>

<TD>BBB</TD>

<TD>BBB</TD>

</TR>

<TR>

<TD>001</TD>

<TD>002</TD>

<TD>003</TD>

</TR>

<TR>

<TD>ภาษาไทย</TD>

<TD>ภาษาไทย</TD>

<TD>ภาษาไทย</TD>

</TR>

</TABLE>

</BODY>

</HTML>