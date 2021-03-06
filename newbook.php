<?php require_once('Connections/mymember.php');?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if(isset($_POST['BSCORE'])&&($_POST['BSCORE']==""))
{  $_POST['BSCORE']=0;  }
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "thisform")) {
  $insertSQL = sprintf("INSERT INTO book (FSBN, FSBOOK, ISBN, AUTHOR, BNUM, PRICE, PUB, BDATE, ISBOR, BORNUM, BSCORE, STORAGE, INFO, NOTE) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FSBN'], "int"),
                       GetSQLValueString($_POST['FSBOOK'], "text"),
                       GetSQLValueString($_POST['ISBN'], "text"),
                       GetSQLValueString($_POST['AUTHOR'], "text"),
                       GetSQLValueString($_POST['BNUM'], "int"),
                       GetSQLValueString($_POST['PRICE'], "double"),
                       GetSQLValueString($_POST['PUB'], "text"),
                       GetSQLValueString($_POST['BDATE'], "date"),
                       GetSQLValueString($_POST['ISBOR'], "int"),
                       GetSQLValueString($_POST['BORNUM'], "int"),
                       GetSQLValueString($_POST['BSCORE'], "double"),
                       GetSQLValueString($_POST['STORAGE'], "text"),
                       GetSQLValueString($_POST['INFO'], "text"),
                       GetSQLValueString($_POST['NOTE'], "text"));

  mysql_select_db($database_mymember, $mymember);
  $Result1 = mysql_query($insertSQL, $mymember) or die(mysql_error());

  $insertGoTo = "book.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
mysql_select_db($database_mymember, $mymember);
$result = mysql_query ( "select status from member where ID=".$_SESSION['MM_Username'],$mymember);
$rs = mysql_fetch_array($result);
if($rs['status']<=2)
 {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
    echo "<script language='javascript' charset='UTF-8'> alert('图书管理员才可以添加哦!');
	 window.history.back();
	 </script>";   
    exit;
}


?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $_SESSION['webname'];?>-添加新书</title>
<?php
  include("headlink.php");
  ?>

<style type="text/css">


.form-control{
	font-size:small;
	text-align:center;
}

</style>

<script>
function samenum()
{
document.getElementById('ISBOR').value=document.getElementById('BNUM').value;
}
</script>

</head>

<body onload="ready();">
<?php
include("topwhite.php");
?>
<div>
  <h1>添加新书<small></small></h1>
  <hr>
</div>

<div id="content" class="container">
  <div id="maincontent">

  <form class="form-horizontal yunyou-background yunyou-bgblur" action="<?php echo $editFormAction; ?>" name="thisform" method="POST" style="padding: 30px;">
  <div class="form-inline">
    <div class="form-group">
      <label >书名：</label>
      <input name="FSBOOK" type="text" required="required" class="form-control">
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-3 form-group">
      <div for="FSBN" class="col-md-6 control-label">社团书号：</div>
      <div class="col-md-6">
      <input name="FSBN" id="FSBN" type="text" class="form-control" value="不填"  readonly style="background-color: transparent;">
      </div>
    </div>
    <div class="col-md-3 form-group ">
      <div for="BNUM" class="col-md-6 control-label">数量：</div>
      <div class="col-md-6 input-group">
      <input id="BNUM" name="BNUM" type="number" min="0" class="form-control" required="required" onKeyUp="samenum();">
      <span class="input-group-addon">本</span>
      </div>
    </div>
    <div class=" col-md-3 form-group" for="PRICE">
      <div class="col-md-6 control-label">价格：</div>
      <div class="col-md-6 input-group">
      <input name="PRICE" id="PRICE" class="form-control" >
      <span class="input-group-addon">￥</span>
      </div>
    </div>
    <div class="col-md-4 form-group ">
      <div class="col-md-4 control-label">作者：</div>
      <div class="col-md-8">
      <input name="AUTHOR" type="text" class="form-control" >
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-3 form-group">
      <div class="control-label col-md-6">保管者：</div>
      <div class="col-md-6"><input name="STORAGE" type="text" class="form-control" ></div>
    </div>
    <div class="col-md-3 form-group">
      <div class="control-label col-md-6">剩余数量：</div>
      <div class="col-md-6 input-group">
      <input id="ISBOR" name="ISBOR" type="number" min="0" class="form-control" >
      <span class="input-group-addon">本</span>
      </div>
    </div>
    <div class="col-md-3 form-group">
      <div class="control-label col-md-6">被借次数：</div>
      <div class="col-md-6 input-group">
      <input name="BORNUM" type="number" min="0" class="form-control" value="0">
      <span class="input-group-addon">次</span>
      </div>
    </div>
    <div class=" col-md-4 form-group">
      <div class="col-md-4 control-label">ISBN：</div>
      <div class="col-md-8">
      <input name="ISBN" type="text" class="form-control" >
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 form-group">
      <div class="control-label col-md-6">豆瓣评分：</div>
      <div class="col-md-6"><input name="BSCORE" type="text" class="form-control"></div>
    </div>
    <div class="col-md-4 form-group">
      <div class="control-label col-md-4">出版社：</div>
      <div class="col-md-8"><input name="PUB" type="text" class="form-control" ></div>
    </div>
    <div class="col-md-5 form-group">
      <div class="control-label col-md-6">出版日期：</div>
      <div class="col-md-6 input-group"><input name="BDATE" type="date" class="form-control" >
      <span class="glyphicon glyphicon-calendar input-group-addon "></span>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="control-label col-md-1">备注：</div>
    <div class="col-md-11"><input name="NOTE" type="text" class="form-control"></div>
  </div>
  <hr>

    <h3>简介</h3>
    <div class="form-group"><textarea name="INFO" class="form-control" rows="5"></textarea></div>


    <div class="form-group">
      <div class="col-md-6 col-xs-6"><button type="submit" formmethod="post" class="btn btn-inverse"  onclick="return confirm('确定要添加吗？')">保存</button></div>
      <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-inverse" onClick="JavaScript:location.href='book.php';">返回</div>
    </div>

    <input type="hidden" name="MM_insert" value="thisform">
  </form>

  </div>

</div>
<?php
 include("copyfoot.php");
?>

</body>
</html>