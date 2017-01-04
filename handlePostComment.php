<?php
  // It post new comment to db and retrieve all new comments to client side
  $conn=mysqli_connect('sophia.cs.hku.hk','cywong','303519Cy') or die ('Failed to Connect '.mysqli_error($conn));

  mysqli_select_db($conn,'cywong') or die ('Failed to Access DB'.mysqli_error($conn));

  $query = "select * from comments";

  $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

  $totalNo = mysqli_num_rows($result);

  $totalNo = $totalNo + 1;

  $time = $_POST['month']." ".$_POST['date']." ".$_POST['year'];

  $query2 = "insert into comments (commentID, newsID , userID, content, time) values ('".$totalNo."', '".$_POST['newsID']."','".$_COOKIE['userID']."','".$_POST['comment']."','".$time."')";

  $result2 = mysqli_query($conn, $query2) or die ('Failed to query '.mysqli_error($conn));


  $query3 = "select * from comments where commentID > ".$_POST['commentID']." and newsID = '".$_POST['newsID']."'";

  $result3 = mysqli_query($conn, $query3) or die ('Failed to query '.mysqli_error($conn));

  $newComments = array();
  while($row = mysqli_fetch_assoc($result3)) {

      $query4 = "select * from users where userID = '".$row['userID']."'";
      $result4 = mysqli_query($conn, $query4) or die ('Failed to query '.mysqli_error($conn));
      $icon = "";
      $name = "";
      while($row2 = mysqli_fetch_assoc($result4)){
        $icon = $row2['icon'];
        $name = $row2['name'];
      }
      $newComments[] = array('commentID'=>$row['commentID'], 'newsID'=>$row['newsID'],'userID'=>$row['userID'], 'time'=>$row['time'], 'content'=>$row['content'], 'icon'=>$icon, 'name'=>$name);
  }

  print json_encode(array('newComments'=>$newComments));

 ?>
