<?	session_start();
	include_once ($_SERVER['DOCUMENT_ROOT']."/spaces/appClass.php");
	$appClass = new appClass;
	$landing = "http://myvoffice.me/spaces/login.html";
	if($_SESSION['cid']==""){
	if(isset($_COOKIE['cid']) && ($_COOKIE['token'])){
	$_SESSION["cid"] = $appClass->getClientToken($_COOKIE['cid'],$_COOKIE['token']);
	if($_SESSION["cid"]=="")
	echo "<script>window.location.href = '$landing';</script>";
	}else{
	echo "<script>window.location.href = '$landing';</script>";
		}
	}