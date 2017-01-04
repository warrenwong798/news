<?php
  // It removes cookies to logout
  if (isset($_COOKIE['userID'])){
    unset($_COOKIE['userID']);
    setcookie('userID', null, -1, '/');
    print "logout success";
  }
  else{
    print "User is not logged in yet";
  }
 ?>
