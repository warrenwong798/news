<!DOCTYPE html>
<head>
  <!-- This page responsible to retrieving login info and send to server for authentication -->
  <link href="bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <script src="jquery-3.1.1.min.js"></script>
  <script type="text/javascript">
    function login(){
      var userName = $("#userName").val();
      var password = $("#password").val();
      if ((userName == "") || (password == "")){
        alert("Please enter username and password");
        return;
      }
      $.ajax({
        url: 'handleLogin.php?username=' + userName + '&password=' + password,
        type: 'get',
        dataType: 'text',
        success: function(responseText){
          if (responseText == "login success"){
            $("#heading").html("You have successfully logged in");
            $("#loginData").hide();
          }
          else{
            $("#heading").html(responseText);
          }
        }
      });


    }

  </script>


</head>
<body>
  <div class="conatiner">
    <div class="col-xs-12 text-center">
      <h3 id="heading">You can log in here</h3>
    </div>
    <div id="loginData">
      <div class="col-xs-8 col-xs-offset-4 input-field input-field-name">
        <div class="col-xs-2">
          User Name:
        </div>
        <div class="col-xs-10">
          <input type="text" value="" id = "userName"/>
        </div>
      </div>
      <div class="col-xs-8 col-xs-offset-4 input-field input-field-pw">
        <div class="col-xs-2">
          Password:
        </div>
        <div class="col-xs-10">
          <input type="text" value="" id = "password"/>
        </div>
      </div>
      <div class="col-xs-12 text-center">
        <button id="submit" onclick="login()">Submit</button>
      </div>
    </div>
    <div id="goBack" class="col-xs-12">


    </div>
  </div>
  <script>
    var goBack = <?php if ($_GET['newsID'] != "0"){
      echo "'displayNewsEntry.php?newsID=".$_GET['newsID']."'";
    }
    else{
      $html = "'index.html'";
      echo $html;
    } ?>;
    $("#goBack").html("<a href = '" + goBack + "'> Go Back</a>");
   </script>
</body>
