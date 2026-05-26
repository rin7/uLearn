<?php require_once('Connections/connection.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE course SET CName=%s, FUserName=%s, CDetails=%s WHERE CCode=%s",
                       GetSQLValueString($_POST['CName'], "text"),
                       GetSQLValueString($_POST['FUserName'], "text"),
                       GetSQLValueString($_POST['CDetails'], "text"),
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "fcsearch.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "1";
if (isset($_GET['ccode'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['ccode'] : addslashes($_GET['ccode']);
}
mysql_select_db($database_connection, $connection);
$query_Recordset1 = sprintf("SELECT CCode, CName, FUserName, CDetails FROM course WHERE CCode = %s", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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
.style24 {color: #FF0000}
.style25 {font-size: 14px; color: #658393; }
.style14 {color: #FFFFFF;
	font-weight: bold;
	font-size: 18px;
}
.style15 {color: #F6F8F8;
	font-size: 14px;
}
.style17 {color: #F6F8F8}
.style20 {font-size: 16px; color: #FFFFFF; font-style: italic; }
.style29 {color: #0000FF}
.style30 {
	font-size: 16px;
	color: #658393;
	font-weight: bold;
}
.style31 {color: #FFFFFF}
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
          <td valign="bottom" background="images/top_b.gif"><span class="style2"><img src="images/top_curve.gif" width="22" height="31"><a href="index.php" target="_top" onClick="MM_nbGroup('down','group1','Home','images/home_s.gif',1)" onMouseOver="MM_nbGroup('over','Home','images/home_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/home.gif" alt="" name="Home" border="0" onload=""></a><a href="about.php" target="_top" onClick="MM_nbGroup('down','group1','about','images/about_s.gif',1)" onMouseOver="MM_nbGroup('over','about','images/about_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/about.gif" alt="" name="about" border="0" onload=""></a><a href="schedule.php" target="_top" onClick="MM_nbGroup('down','group1','schedule','images/schedule_s.gif',1)" onMouseOver="MM_nbGroup('over','schedule','images/schedule_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img src="images/schedule.gif" alt="" name="schedule" border="0" onload=""></a><a href="contact.php" target="_top" onClick="MM_nbGroup('down','group1','contact','images/contact_s.gif',1)" onMouseOver="MM_nbGroup('over','contact','images/contact_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="contact" src="images/contact.gif" border="0" alt="" onLoad=""></a><a href="help.php" target="_top" onClick="MM_nbGroup('down','group1','help','images/help_s.gif',1)" onMouseOver="MM_nbGroup('over','help','images/help_s.gif','',1)" onMouseOut="MM_nbGroup('out')"><img name="help" src="images/help.gif" border="0" alt="" onLoad=""></a></span></td>
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
                <td width="461"> <h1 class="style8">Change Course :</h1>
                  <p class="style8">-<span class="style9">Please fill this form carefully, we need your information in near futher:</span></p>
                  <form action="<?php echo $editFormAction; ?>" method="POST" name="form3">
                    <table align="center">
                      <tr valign="baseline">
                        <td width="121" align="right" nowrap class="style25"><span class="style24">*</span>Course Code:</td>
                        <td align="left" valign="bottom"><span class="style29">
                          <label><span class="style30"><span class="style31">.</span><?php echo $row_Recordset1['CCode']; ?></span></label>
                        &nbsp;</span></td>
                        <td align="right"><input name="hiddenField" type="hidden" value="<?php echo $row_Recordset1['CCode']; ?>"></td>
                      </tr>
                      <tr valign="baseline">
                        <td align="right" nowrap class="style25">Course Name:</td>
                        <td colspan="2"><input type="text" name="CName" value="<?php echo $row_Recordset1['CName']; ?>" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td align="right" nowrap class="style25"><span class="style24">*</span>Faculty User Name:</td>
                        <td colspan="2"><input type="text" name="FUserName" value="<?php echo $row_Recordset1['FUserName']; ?>" size="32">
                        </td>
                      </tr>
                      <tr valign="baseline">
                        <td align="right" nowrap class="style25">Course Details:</td>
                        <td colspan="2"><input type="text" name="CDetails" value="<?php echo $row_Recordset1['CDetails']; ?>" size="32"></td>
                      </tr>
                      <tr valign="baseline">
                        <td nowrap align="right">&nbsp;</td>
                        <td width="132"><input type="submit" value="Edit Course"></td>
                        <td width="73" align="right"><input type="reset" name="Reset" value="Reset"></td>
                      </tr>
                    </table>
                    <div align="justify">
                      <p>
                        <input type="hidden" name="MM_insert" value="form3">
                        <span class="style24">*Be carefull if the faculty that you want to select for that course didin't have yet register first you have to add faculty and then submit the course that you want to add.<br>
      *Each course have uniqe Course Code you couldn't change it. </span></p>
                    </div>
                      <input type="hidden" name="MM_update" value="form3">
                  </form>
                  <p>&nbsp; </p>
                <p>&nbsp;</p></td>
                <td width="15">&nbsp;</td>
              </tr>
          </table></td>
          <td width="191" align="center" valign="top" background="images/pixi_greyblue.gif" class="sidetable"> 
            <table width="100%" border="0" cellpadding="10" cellspacing="0" bordercolor="#A0BFD0">
              <tr> 
           
              </tr>
            </table>  
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
                <td align="center" valign="top"><span class="style17"><span class="style8"><span class="style20"><?php echo $_SESSION['MM_Username'] ?></span></span></span></td>
                <td><span class="style17"><span class="style8"></span></span></td>
              </tr>
            </table></td>
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
?>
