<?php
require_once("../config/db.php");

$alamat    = $_POST['alamat'];
$nama = $_POST['nama'];
$lat    = $_POST['lat'];
$lng = $_POST['lng'];
$kuota= $_POST['kuota'];
$date = date("Ymdhis");
$password = md5($date);
 if(isset($_POST['alamat']))
 {

  $query  = "INSERT INTO LOCATION (LOCATION_USERNAME,LOCATION_PASSWORD,LOCATION_NAME,LOCATION_LAT,LOCATION_LNG,LOCATION_ADDRESS,LOCATION_KUOTA,LOCATION_TOTAL)
  VALUES ('".$date."','".$password."','".$nama."','".$lat."','".$lng."','".$alamat."','".$kuota."','0')";
  $result = mysqli_query($mysqli,$query) or die(mysqli_error());

  if( $result==true ) {
    echo "ok";
  }else {

    echo "Gagal";

  }
}

?>
