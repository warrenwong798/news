<?php
// This obtain detail info of news and allow user post cm after login
$conn=mysqli_connect('sophia.cs.hku.hk','cywong','303519Cy') or die ('Failed to Connect '.mysqli_error($conn));

mysqli_select_db($conn,'cywong') or die ('Failed to Access DB'.mysqli_error($conn));

$query = "select * from news where newsID = ".$_GET["newsID"];

$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

$news = array();
while($row = mysqli_fetch_assoc($result)) {
    $news[] = array('newsID'=>$row['newsID'],'headline'=>$row['headline'], 'time'=>$row['time'], 'content'=>$row['content']);
}


// $news = json_encode($news);
?>
<!DOCTYPE html>
  <head>
          <link href='bootstrap.min.css' rel='stylesheet'>
          <link href='font-awesome.min.css' rel='stylesheet'>
          <link href='style.css' rel='stylesheet'>
          <script src='jquery-3.1.1.min.js'></script>

    </head>
        <body>
          <div class='col-xs-12'>
            <div class="col-xs-12 text-center">
              <!-- <div class="text-left" style="max-width:100px;"> -->
                <a class="arrow" href="index.html"><i class="fa fa-arrow-left fa-4x"  aria-hidden="true"></i></a>
              <!-- </div> -->
              <?php
                print "<h2>".$news[0]['headline']."</h2>".$news[0]['time'];
               ?>
            </div>
            <div class="col-xs-12">
              <?php
                print "<h4 class='newsContent'>".$news[0]['content']."</h4>";
               ?>
            </div>
            <div class="col-xs-12" id="comments">
              <?php
              $query2 = "select * from comments where newsID = ".$_GET["newsID"]." order by SUBSTRING_INDEX(time, ' ', -1) DESC";
              if (mysqli_connect_errno())
              {
              echo "Failed to connect to MySQL: " . mysqli_connect_error();
              }
              $results = mysqli_query($conn, $query2) or die ('Failed to query '.mysqli_error($conn));
              $comments = array();


              $i = 0;
              while($row3 = mysqli_fetch_assoc($results)) {

                  // $comments[] = array('commentID'=>$row3['commentID'], 'newsID'=>$row3['newsID'],'userID'=>$row3['userID'], 'time'=>$row3['time'], 'content'=>$row3['content']);
                  // $query3 = "select * from users where userID =".$comments[$i]['userID'];
                  $query3 = "select * from users where userID =".$row3['userID'];
                  $userResults = mysqli_query($conn, $query3) or die ('Failed to query '.mysqli_error($conn));
                  $users = array();
                  $j = 0;
                  $name ="";
                  $icon="";


                  while($row2 = mysqli_fetch_assoc($userResults)){
                    $users[] = array('userID'=>$row2['userID'], 'name'=>$row2['name'], 'password'=>$row2['password'], 'icon'=>$row2['icon']);
                    // print "<div class='col-xs-12 comment'><div class='col-xs-2'><img src='".$users[$j]['icon']."'/></div><div class='col-xs-2'><h4>".$users[$j]['name']."</h4></div><div class='col-xs-8 text-center'>".$comments[$i]['time']."<br/>".$comments[$i]['content']."</div></div>";
                    // $j = $j + 1;
                    // $users[] = array('userID'=>$row2['userID'], 'name'=>$row2['name'], 'icon'=>$row2['icon'],'commentID'=>$row3['commentID'], 'newsID'=>$row3['newsID'], 'time'=>$row3['time'], 'content'=>$row3['content']);
                    $name = $row2['name'];
                    $icon = $row2['icon'];
                  }
                  $comments[] = array('commentID'=>$row3['commentID'],'userID'=>$row3['userID'], 'time'=>$row3['time'],'content'=>$row3['content'], 'name'=>$name,'icon'=>$icon);
                  $i = $i + 1;
              }
              ?>
              <script>
                var cm = <?php print json_encode(array('cm'=>$comments));  ?>;
                cm.cm.sort(function(x,y){
                  var xDate = new Date(x.time);
                  var yDate = new Date(y.time);
                  if (xDate > yDate){
                    return -1;
                  }
                  if (xDate < yDate){
                    return 1;
                  }
                  return 0;
                });
                for (var i = 0; i < cm.cm.length; i++){
                  $("#comments").append("<div class='col-xs-12 comment'><div class='col-xs-2'><img src='" + cm.cm[i]['icon'] + "'/></div><div class='col-xs-2'><h4>" + cm.cm[i]['name'] + "</h4></div><div class='col-xs-8 text-center'>" + cm.cm[i]['time'] + "<br/>" + cm.cm[i]['content'] + "</div></div>");
                }
              </script>
            </div>
            <div id="commentArea" class="col-xs-12">
              <div class='col-xs-6 col-xs-offset-2'>
                <input class="col-xs-12" type='text' id='commentBox'/>
              </div>
              <div class='col-xs-4'>
                <a id='loginButton' href="login.php?newsID=<?php echo $_GET['newsID']?>"><button>Login to Comment</button></a>
                <button id='commentButton' onclick="postComment()">Post Comment</button>
              </div>
            </div>

          </div>
          <script>
          function postComment(){
            if ($("#commentBox").val() == ""){
              alert("No comment has been entered");
            }
            else{
              var monthName = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              var time = new Date();
              var month = monthName[time.getMonth()];
              var day = time.getDate();
              var year = time.getFullYear();
              var newsID = <?php echo $_GET['newsID']; ?>;

              if (cm.cm.length == 0){
                var commentID = 0;
              }
              else{
                var commentID = cm.cm[0]['commentID'];
              }
              $.ajax({
                type: "post",
                url: "handlePostComment.php",
                dataType: "json",
                data: {comment: $("#commentBox").val(), newsID: newsID, commentID: commentID, date: day, month: month, year: year},
                success: function (responseText){
                  $("#commentBox").val("");
                  responseText.newComments.sort(function(x,y){
                    var xDate = new Date(x.time);
                    var yDate = new Date(y.time);
                    if (xDate > yDate){
                      return -1;
                    }
                    if (xDate < yDate){
                      return 1;
                    }
                    return 0;
                  });
                  for (var i = 0; i < responseText.newComments.length; i++){
                    $("#comments").prepend("<div class='col-xs-12 comment'><div class='col-xs-2'><img src='" + responseText.newComments[i]['icon'] + "'/></div><div class='col-xs-2'><h4>" + responseText.newComments[i]['name'] + "</h4></div><div class='col-xs-8 text-center'>" + responseText.newComments[i]['time'] + "<br/>" + responseText.newComments[i]['content'] + "</div></div>");
                  }

                }
              });
            }
          }
          function getCookie(cname, callback) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    callback(c.substring(name.length,c.length));
                    return;
                }
            }
            callback("");
          }
            function init(cookie){

              var userID = cookie;
              if (userID != ""){
                $("#loginButton").hide();
                $("#commentButton").show();
                $("#commentBox").prop('disabled', false);

                // console.log("1");
              }
              else{
                $("#loginButton").show();
                $("#commentButton").hide();
                $("#commentBox").prop('disabled', true);
                // console.log("2");
              }
            }
            $(document).ready(getCookie("userID", init));
          </script>
        </body>
