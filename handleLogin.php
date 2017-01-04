<?php
  // It finds record matching username th verify it first, then find record match both username and pw
  // This can distinguish the user inputting incorrect pw or username
  // also setcookie and return success or err msg
  $conn=mysqli_connect('sophia.cs.hku.hk','cywong','303519Cy') or die ('Failed to Connect '.mysqli_error($conn));

  mysqli_select_db($conn,'cywong') or die ('Failed to Access DB'.mysqli_error($conn));

  $query = "select * from users where name = '".$_GET['username']."'";

  $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

  if (mysqli_num_rows($result) == 0){
    print "Username is incorrect";
  }
  else{
    $query2 = "select * from users where name = '".$_GET['username']."' and password = '".$_GET['password']."'";

    $result2 = mysqli_query($conn, $query2) or die ('Failed to query '.mysqli_error($conn));
    // print "ok";

    if (mysqli_num_rows($result2) == 0){
      print "Password is incorrect";
    }
    else{
      $users = array();
      while($row=mysqli_fetch_array($result2)) {

          $users[]=array('userID'=>$row['userID'],'name'=>$row['name'], 'password'=>$row['password'], 'icon'=>$row['icon']);


      }
      setcookie("userID", $users[0]['userID'], time() + (3600),"/");
      print "login success";
    }




  }


 ?>
