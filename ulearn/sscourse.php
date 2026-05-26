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
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO studentscourse (SUserName, CCode) VALUES (%s, %s)",
                       GetSQLValueString($_POST['hiddenField'], "text"),
                       GetSQLValueString($_POST['t1'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "sscourse.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_Recordset1 = 2;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_connection, $connection);
$query_Recordset1 = "SELECT course.CCode, course.CName, faculty.FName, faculty.LName, course.CDetails FROM course, faculty  WHERE course.FUserName=faculty.FUserName";
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

$maxRows_Recordset2 = 4;
$pageNum_Recordset2 = 0;
if (isset($_GET['pageNum_Recordset2'])) {
  $pageNum_Recordset2 = $_GET['pageNum_Recordset2'];
}
$startRow_Recordset2 = $pageNum_Recordset2 * $maxRows_Recordset2;

$colname_Recordset2 = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_connection, $connection);
$query_Recordset2 = sprintf("SELECT studentscourse.CCode, course.CName FROM studentscourse, course WHERE course.CCode=studentscourse.CCode and SUserName = '%s'", $colname_Recordset2);
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

$queryString_Recordset2 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset2") == false && 
        stristr($param, "totalRows_Recordset2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset2 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset2 = sprintf("&totalRows_Recordset2=%d%s", $totalRows_Recordset2, $queryString_Recordset2);
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
.style8 {color: #658393; font-size: 24px; }
.style9 {font-size: 16px}
.style14 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 18px;
}
.style15 {
	color: #F6F8F8;
	font-size: 14px;
}
.style16 {
	font-size: 14px;
	color: #FFFFFF;
}
.style20 {font-size: 14px; color: #FFFFFF; font-style: italic; }
.style21 {font-size: 14px; color: #000000; }
.style23 {color: #658393; }
.style24 {font-size: 16px; color: #658393; }
.style25 {color: #FF0000; font-size: 18px; }
.style26 {color: #FF0000; font-size: 16px; }
.style28 {
	font-size: 14px;
	color: #658393;
}
.style29 {color: #FF0000}
.style30 {font-size: 14px}
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

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/home_s.gif','images/about_s.gif','images/schedule_s.gif','images/contact_s.gif','images/help_s.gif','images/schedule.gif')">
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
          <td valign="bottom" background="images/top_b.gif"><span class="style2"><img src="images/top_curve.gif" width="22" height="31"><a href="shome.php" target="_top" onClick="MM_nbGroup('down','group1','Home','images/home_s.gif',1)" onMouseOver="MM_nbGroup('over','Home','images/home_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/home.gif" alt="" name="Home" border="0" onload=""></a><a href="sabout.php" target="_top" onClick="MM_nbGroup('down','group1','about','images/about_s.gif',1)" onMouseOver="MM_nbGroup('over','about','images/about_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/about.gif" alt="" name="about" border="0" onload=""></a><a href="sscourse.php" target="_top" onClick="MM_nbGroup('down','group1','schedule','images/schedule_s.gif',1)" onMouseOver="MM_nbGroup('over','schedule','images/schedule_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/schedule_s.gif" alt="" name="schedule" border="0" onload="MM_nbGroup('init','group1','schedule','images/schedule.gif',1)"></a><a href="scontact.php" target="_top" onClick="MM_nbGroup('down','group1','contact','images/contact_s.gif',1)" onMouseOver="MM_nbGroup('over','contact','images/contact_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="contact" src="images/contact.gif" border="0" alt="" onLoad=""></a><a href="shelp.php" target="_top" onClick="MM_nbGroup('down','group1','help','images/help_s.gif',1)" onMouseOver="MM_nbGroup('over','help','images/help_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="help" src="images/help.gif" border="0" alt="" onLoad=""></a></span></td>
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
                <td width="461" valign="top"> <h1 class="style8">Select Course:</h1>
                  
                    <table width="100%"  border="0" cellspacing="2" cellpadding="1">
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;
                          <table border="1" cellpadding="1" cellspacing="1">
                            <tr>
                              <td width="40" class="style9"><div align="center" class="style23">Course Code: </div></td>
                              <td width="80" class="style9"><div align="center" class="style23">Course Name: </div></td>
                              <td width="100" class="style9"><div align="center" class="style23">Faculty Name: </div></td>
                              <td width="250" class="style24">Course Details: </td>
                            </tr>
                            <?php do { ?>
                            <tr>
                              <td class="style21"><div align="center"><?php echo $row_Recordset1['CCode']; ?></div></td>
                              <td class="style21"><div align="center"><?php echo $row_Recordset1['CName']; ?></div></td>
                              <td class="style21"><div align="center"><?php echo $row_Recordset1['FName']; ?> <?php echo $row_Recordset1['LName']; ?></div></td>
                              <td colspan="2" class="style21"><?php echo $row_Recordset1['CDetails']; ?></td>
                            </tr>
                            <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                          </table></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td align="center"><table border="0" width="50%" align="center">
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
                        </table>
                        
                          <span class="style30">Records <?php echo ($startRow_Recordset1 + 1) ?> to <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of <?php echo $totalRows_Recordset1 ?>   </span></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                                    <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
                                      <table width="100%" height="295"  border="0" cellpadding="1" cellspacing="1">
                                        <tr>
                                          <td width="1">&nbsp;</td>
                                          <td colspan="6" valign="top"><p class="style26"><span class="style30">*<span class="style23">Notice: For each semester you just can  choose  atleast 1 and maximum 4 course:</span></span><br>
                                          </p>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td colspan="6" rowspan="2" valign="top" class="style21"><div align="right"></div><div align="right"></div>                                            
                                            <span class="style23"><span class="style29">*</span>For submit your selected course please enter the Course Code in following textfiled and submit them.Atleast you have select one course .</span></td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td height="80" rowspan="3">&nbsp;</td>
                                          <td width="75" valign="top" class="style23"><div align="right" class="style28">Course Code:</div></td>
                                          <td width="34" valign="top"><input name="t1" type="text" id="t1" size="10" maxlength="10"></td>
                                          <td width="78" valign="top">
                                            <div align="left">
                                              <input type="submit" name="Submit" value="Submit">
                                            </div></td>
                                          <td width="24"><div align="right">
                                            <input name="hiddenField" type="hidden" value="<?php echo $_SESSION['MM_Username'] ?>">
                                          </div></td>
                                          <td width="1" rowspan="3" valign="top">&nbsp;</td>
                                          <td width="141" rowspan="2" valign="top"><table border="1" cellpadding="1" cellspacing="1">
                                              <tr>
                                                <td align="center" class="style23">Course Code:</td>
                                                <td align="center" class="style23">Course Name:</td>
                                              </tr>
                                              <?php do { ?>
                                              <tr>
                                                <td align="center"><?php echo $row_Recordset2['CCode']; ?></td>
                                                <td align="center"><?php echo $row_Recordset2['CName']; ?></td>
                                              </tr>
                                              <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td colspan="4" valign="top" class="style23"><p><span class="style29">*</span>You can see your selsected course in following table:<br>
                                            <span class="style23"><span class="style29">*</span>Be carefull if you submit a course you could not delete it later!</span></p>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td height="79" colspan="4" valign="top" class="style23">&nbsp;</td>
                                          <td align="center" valign="top">&nbsp;
                                            <table border="0" width="50%" align="center">
                                              <tr>
                                                <td width="23%" align="center"><?php if ($pageNum_Recordset2 > 0) { // Show if not first page ?>
                                                  <a href="<?php printf("%s?pageNum_Recordset2=%d%s", $currentPage, 0, $queryString_Recordset2); ?>"><img src="First.gif" border=0></a>
                                                  <?php } // Show if not first page ?>
                                                </td>
                                                <td width="31%" align="center"><?php if ($pageNum_Recordset2 > 0) { // Show if not first page ?>
                                                  <a href="<?php printf("%s?pageNum_Recordset2=%d%s", $currentPage, max(0, $pageNum_Recordset2 - 1), $queryString_Recordset2); ?>"><img src="Previous.gif" border=0></a>
                                                  <?php } // Show if not first page ?>
                                                </td>
                                                <td width="23%" align="center"><?php if ($pageNum_Recordset2 < $totalPages_Recordset2) { // Show if not last page ?>
                                                  <a href="<?php printf("%s?pageNum_Recordset2=%d%s", $currentPage, min($totalPages_Recordset2, $pageNum_Recordset2 + 1), $queryString_Recordset2); ?>"><img src="Next.gif" border=0></a>
                                                  <?php } // Show if not last page ?>
                                                </td>
                                                <td width="23%" align="center"><?php if ($pageNum_Recordset2 < $totalPages_Recordset2) { // Show if not last page ?>
                                                  <a href="<?php printf("%s?pageNum_Recordset2=%d%s", $currentPage, $totalPages_Recordset2, $queryString_Recordset2); ?>"><img src="Last.gif" border=0></a>
                                                  <?php } // Show if not last page ?>
                                                </td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td colspan="6" rowspan="2" align="left" valign="top"><div align="right"></div>                                            <div align="right"></div>                                            <div align="right"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td colspan="6" rowspan="2" valign="top"><div align="right"></div>                                            
                                            <div align="right"></div></td>
                                        </tr>
                                        <tr>
                                          <td height="21">&nbsp;</td>
                                        </tr>
                                      </table>
                                        <input type="hidden" name="MM_insert" value="form1">
                  </form>                                    <p class="style25">&nbsp;</p>
                                    <p class="style8"><span class="style9">:</span></p>
                  <p>&nbsp; </p>
                <p>&nbsp;</p></td>
                <td width="15">&nbsp;</td>
              </tr>
          </table></td>
          <td width="191" align="center" valign="top" background="images/pixi_greyblue.gif" class="sidetable"> 
            <table width="100%"  border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td width="5%">&nbsp;</td>
                <td width="89%" align="center"><span class="style14">Menu:</span></td>
                <td width="6%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle"><a href="sedit.php"><span class="style15">Edit Profile </span></a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle"><a href="sscourse.php"><span class="style16">Select course </span></a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle" class="style16"><a href="<?php echo $logoutAction ?>" class="style16">Sign Out</a></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="center" valign="middle"><span class="style16"><span class="style8"><span class="style20"><?php echo $_SESSION['MM_Username'] ?></span></span></span></td>
                <td>&nbsp;</td>
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
mysql_free_result($Recordset2);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
