<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/connection.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
session_start();
$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 2;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_connection, $connection);
$query_Recordset1 = "SELECT students.SUserName, students.FName, students.LName, students.SSN, students.EMail, students.Tel FROM students";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$maxRows_Recordset2 = 1;
$pageNum_Recordset2 = 0;
if (isset($_GET['pageNum_Recordset2'])) {
  $pageNum_Recordset2 = $_GET['pageNum_Recordset2'];
}
$startRow_Recordset2 = $pageNum_Recordset2 * $maxRows_Recordset2;

$colname_Recordset2 = "1";
if (isset($_GET['SSN2'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_GET['SSN2'] : addslashes($_GET['SSN2']);
}
mysql_select_db($database_connection, $connection);
$query_Recordset2 = sprintf("SELECT FName, LName, SSN, EMail, Tel, SUserName FROM students WHERE SSN = '%s'", $colname_Recordset2);
$query_limit_Recordset2 = sprintf("%s LIMIT %d, %d", $query_Recordset2, $startRow_Recordset2, $maxRows_Recordset2);
$Recordset2 = mysql_query($query_limit_Recordset2, $connection) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);

if (isset($_GET['totalRows_Recordset2'])) {
  $totalRows_Recordset2 = $_GET['totalRows_Recordset2'];
} else {
  $all_Recordset2 = mysql_query($query_Recordset2);
  $totalRows_Recordset2 = mysql_num_rows($all_Recordset2);
}
$totalPages_Recordset2 = ceil($totalRows_Recordset2/$maxRows_Recordset2)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<!-- Provided by MyFreeTemplates.com -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Welcome to ULearn
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<style type="text/css">
<!--
.style2 {color: #A0BFD0}
.style3 {color: #658393; }
.style8 {font-size: 14px}
.style9 {font-size: 14px; color: #658393; }
.style14 {	color: #FFFFFF;
	font-weight: bold;
	font-size: 18px;
}
.style15 {	color: #F6F8F8;
	font-size: 14px;
}
.style17 {color: #F6F8F8}
.style20 {font-size: 16px; color: #FFFFFF; font-style: italic; }
.style22 {color: #658393; font-size: 24px; }
.style23 {color: #FFFFFF}
.style26 {color: #FF0000; font-size: 12px; }
.style30 {font-size: 28px}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/home_s.gif','images/about_s.gif','images/schedule_s.gif','images/contact_s.gif','images/help_s.gif')">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td rowspan="6" valign="top">&nbsp;</td>
    <td width="42" rowspan="6" valign="top" background="images/sidebg_left.gif">&nbsp;</td>
    <td width="670" background="images/tabletopbg1.gif"><img src="images/tabletopbg1.gif" width="1" height="4"></td>
    <td width="46" rowspan="6" valign="top" background="images/sidebg_right.gif"><img src="images/sidebg_right.gif" width="46" height="3"></td>
    <td rowspan="6" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td width="670" height="0"> <table width="676" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td rowspan="2" background="images/pixi_greyblue.gif"><img src="images/logo.gif" width="166" height="92"></td>
          <td colspan="2" background="images/pixi_greyblue.gif"><img src="images/pixi_greyblue.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td valign="bottom" background="images/top_b.gif"><span class="style2"><img src="images/top_curve.gif" width="22" height="31"><a href="fhome.php" target="_top" onClick="MM_nbGroup('down','group1','Home','images/home_s.gif',1)" onMouseOver="MM_nbGroup('over','Home','images/home_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/home.gif" alt="" name="Home" border="0" onload=""></a><a href="fabout.php" target="_top" onClick="MM_nbGroup('down','group1','about','images/about_s.gif',1)" onMouseOver="MM_nbGroup('over','about','images/about_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/about.gif" alt="" name="about" border="0" onload=""></a><a href="fschedule.php" target="_top" onClick="MM_nbGroup('down','group1','schedule','images/schedule_s.gif',1)" onMouseOver="MM_nbGroup('over','schedule','images/schedule_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/schedule.gif" alt="" name="schedule" border="0" onload=""></a><a href="fcontact.php" target="_top" onClick="MM_nbGroup('down','group1','contact','images/contact_s.gif',1)" onMouseOver="MM_nbGroup('over','contact','images/contact_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="contact" src="images/contact.gif" border="0" alt="" onLoad=""></a><a href="fhelp.php" target="_top" onClick="MM_nbGroup('down','group1','help','images/help_s.gif',1)" onMouseOver="MM_nbGroup('over','help','images/help_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="help" src="images/help.gif" border="0" alt="" onLoad=""></a></span></td>
          <td background="images/pixi_lightblue.gif"><span class="style2"></span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="670" height="4" background="images/pixi_lightblue.gif"><img src="images/pixi_lightblue.gif" width="1" height="1"></td>
  </tr>
  <tr> 
    <td width="670" background="images/pixi_lightblue.gif"> <div align="left"> 
        <table width="676" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="434" align="left"><img src="images/toppic1.gif" width="187" height="122"><img src="images/toppic2.gif" width="167" height="122"><img src="images/toppic2a.gif" width="80" height="122"></td>
            <td width="242" align="right"><img src="images/toppic3.gif" width="242" height="122"></td>
          </tr>
        </table>
    </div></td>
  </tr>
  <tr> 
    <td width="670" height="4" background="images/pixi_lightblue.gif"><img src="images/pixi_lightblue.gif" width="1" height="1"></td>
  </tr>
  <tr> 
    <td align="right" valign="top"><table width="676" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td align="right" valign="top"><img src="images/midcurve.gif" width="192" height="17"><br> 
            <table width="460" border="0" cellspacing="0">
              <tr> 
                <td width="461"> <h1 align="justify" class="style3"><span class="style30">Search &amp; Edit Student:</span><br>
                    <span class="style8">Dear faculty As you can see this is the list of all students you can edit student's profile or student's history just click on student's SSN(Social Security Number) and for send email for that student click one email link.</span></h1>
                  <form name="form2" method="post" action="">
<table border="1" cellpadding="1" cellspacing="1">
  <tr align="center" class="style9">
    <td>SSN:</td>
    <td>Student Name: </td>
    <td>User Name: </td>
    <td>Email Address:</td>
    <td>Telephone No.</td>
  </tr>
  <?php do { ?>
  <tr align="center" class="style8">
    <td><a href="fedits.php?SSN=<?php echo $row_Recordset1['SSN']; ?>"><?php echo $row_Recordset1['SSN']; ?></a></td>
    <td><?php echo $row_Recordset1['LName']; ?> <?php echo $row_Recordset1['FName']; ?> </td>
    <td><?php echo $row_Recordset1['SUserName']; ?></td>
    <td><a href="mailto:<?php echo $row_Recordset1['EMail']; ?>" class="style8"><?php echo $row_Recordset1['EMail']; ?></a></td>
    <td><?php echo $row_Recordset1['Tel']; ?></td>
  </tr>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
Records <?php echo ($startRow_Recordset1 + 1) ?> to <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of <?php echo $totalRows_Recordset1 ?>
</table>
<table width="130%"  border="0" cellspacing="0" cellpadding="1">
                      <tr>
                        <td width="59%" height="34" align="left" valign="middle" class="style26">*For edit Student profile click on student SSN: </td>
                        <td align="center" valign="middle">
                          <table border="0" width="50%" align="center">
                            <tr>
                              <td width="23%" align="center"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                  <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><img src="First.gif" border=0></a>
                                  <?php } // Show if not first page ?>
                              </td>
                              <td width="31%" align="center"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                  <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><img src="Previous.gif" border=0></a>
                                  <?php } // Show if not first page ?>
                              </td>
                              <td width="23%" align="center"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                  <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><img src="Next.gif" border=0></a>
                                  <?php } // Show if not last page ?>
                              </td>
                              <td width="23%" align="center"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                  <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><img src="Last.gif" border=0></a>
                                  <?php } // Show if not last page ?>
                              </td>
                            </tr>
                          </table></td>
                        <td width="1%">&nbsp;</td>
                      </tr>
                    </table>
                  </form>                  
                  <form name="form1" method="get" action="">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="1">
                      <tr>
                        <td width="42%" align="right"><span class="style9">Enter Social Security Number: </span></td>
                        <td width="33%"><input name="SSN2" type="SSN2" id="SSN2"></td>
                        <td width="25%" align="right"><input type="submit" name="Submit" value=" Start Search"></td>
                      </tr>
                      <tr align="center">
                        <td colspan="3">&nbsp;                          </td>
                      </tr>
                    </table>
                  </form>                  
                  <table width="100%" border="1" cellpadding="1" cellspacing="1">
                    <tr align="center" class="style9">
                      <td>SSN:</td>
                      <td>Student Name: </td>
                      <td>User Name: </td>
                      <td>Email Address: </td>
                      <td>Telephone No.</td>
                    </tr>
                    <?php do { ?>
                    <tr align="center">
                      <td><a href="fedits.php?SSN=<?php echo $row_Recordset2['SSN']; ?>"><span class="style8"><?php echo $row_Recordset2['SSN']; ?></span></a></td>
                      <td><span class="style8"><?php echo $row_Recordset2['FName']; ?></span><span class="style8"> <?php echo $row_Recordset2['LName']; ?></span></td>
                      <td><span class="style8"><?php echo $row_Recordset2['SUserName']; ?></span></td>
                      <td><a href="mailto:<?php echo $row_Recordset2['EMail']; ?>" class="style8"><span class="style8"><?php echo $row_Recordset2['EMail']; ?></span></a></td>
                      <td><span class="style8"><?php echo $row_Recordset2['Tel']; ?></span></td>
                    </tr>
                    <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
                  </table>                  
                  <p><span class="style26">*For edit Student profile click on student SSN:</span></p>
                  <p>&nbsp;</p></td>
                <td width="15">&nbsp;</td>
              </tr>
          </table></td>
          <td width="191" align="center" valign="top" background="images/pixi_greyblue.gif" class="sidetable"> 
            <table width="100%"  border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td width="5%">&nbsp;</td>
                <td width="89%" align="center"><span class="style14">Main Menu:</span></td>
                <td width="6%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle"><a href="fedit.php"><span class="style15">Edit Profile </span></a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle"><a href="fadds.php" class="style15">Add Student </a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle" class="style15"><a href="faddf.php" class="style15">Search &amp; Add Faculty </a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle" class="style15"><a href="fcsearch.php" class="style15">Search &amp; Add Course</a> </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle" class="style15"><a href="fstsearch.php" class="style15">Search &amp; Edit Student </a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="top" class="style15 style23"><a href="<?php echo $logoutAction ?>" class="style15">Sign Out: </a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="top"><span class="style17"><span class="style22"><span class="style20"><?php echo $_SESSION['MM_Username'] ?></span></span></span></td>
                <td><span class="style17"><span class="style22"></span></span></td>
              </tr>
            </table>
            <table width="100%" border="0" cellpadding="10" cellspacing="0" bordercolor="#A0BFD0">
              <tr> 
           
              </tr>
            </table>  
          </td>
        </tr>
      </table> </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top" background="images/sidebg_left.gif">&nbsp;</td>
    <td valign="top" background="images/pixi_lightblue.gif">&nbsp;&nbsp;&nbsp;&nbsp;<span class="baseline">&copy; 
      2006 aRin. All rights reserved.<br>
    </span></td>
    <td valign="top" background="images/sidebg_right.gif">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
