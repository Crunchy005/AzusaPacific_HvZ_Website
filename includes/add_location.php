<?php
  session_start();
  if(isset($_SESSION['username']))
  {
    $location = $_GET['location'];//pull from get array

	include("header.php");

    //make query to input location to DB
    $q = "insert into map (location) values ('".$location."')";
    //$q = "select * from map";
    $result = mysqli_query($q, $con) or die ('error with query ' . mysqli_error());
    /*while($result_array = mysqli_fetch_array($result))
    {
      print_r($result_array);
      echo '<br>';
    }*/

    if($result)
      echo('');
    else
      echo('error');
  }
?>
