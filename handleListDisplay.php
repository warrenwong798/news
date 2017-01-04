<?php
  // Here gets differnt info requested from db and send back to client side
  $conn=mysqli_connect('sophia.cs.hku.hk','cywong','303519Cy') or die ('Failed to Connect '.mysqli_error($conn));

  mysqli_select_db($conn,'cywong') or die ('Failed to Access DB'.mysqli_error($conn));

  $query = "select * from news where headline like '%".$_GET["searchString"]."%' order by SUBSTRING_INDEX(time, ' ', -1) DESC";

  $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

  $totalNo = mysqli_num_rows($result);

  $news = array();

  // while($row=mysqli_fetch_array($result)) {
  //   $news[]=array('newsID'=>$row['newsID'],'headline'=>$row['headline'], 'time'=>$row['time'], 'content'=>array_slice(explode(" ", $row['content']), 0, 10));
  // }
  //
  // print json_encode(array('news' => $news ));

  $loginStatus = 0;

  if (isset($_COOKIE['userID'])){
    $loginStatus = 1;
  }



  while($row=mysqli_fetch_array($result)) {

      $news[]=array('newsID'=>$row['newsID'],'headline'=>$row['headline'], 'time'=>$row['time'], 'content'=>array_slice(explode(" ", $row['content']), 0, 10));


  }

  print json_encode(array('totalNo'=>$totalNo,'news'=>$news, 'loginStatus'=>$loginStatus));


?>
