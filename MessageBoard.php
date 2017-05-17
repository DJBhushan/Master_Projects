<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Message Board</title>
    <link rel = "stylesheet" href = "http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" />
    <link rel = "stylesheet" href = "css/main.css" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="js/main.js"></script>
  </head>
  <body>
     <div class="container">
     <div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Message Board Page</h1>
          <hr />
        </div>
       <div class="container" style = "margin-left: -34px;">  
        <div class="dialogbox"> 
          <div class="body"> 
            <div class="message">
              <span style="font-weight: bold;color: #428bca;text-align: center;margin-left: 40px;"> Welcome <?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?>...!!!  </span>
            </div>
          </div>
        </div>
      </div>
       <a href="logout.php" class="btn btn-primary btn-lg btn-block login-button" style = "margin-left: -12px;" >Logout</a>
       <img src="images/load.gif" id="loading-image" style="display:none"/>
      </div>
    </div>
<?php
    require_once('DBConnect.php');
    global $user_id;
    if (!isset($_SESSION['userID'])) 
    {
        header('location: SessionExpire.php');
    }
    else
    {
      $now = time(); // Checking the time now when home page starts.
      if ($now > $_SESSION['expire']) 
      {
          unset($_SESSION['userID']);
          session_destroy();
          header('location: SessionExpire.php');
      }
      else
      {
        if($_POST['message'] != "")
        {
          $email = $_SESSION['email'];
          $sql = "SELECT UserID FROM Users WHERE email = '" .  $email . "'";
          $result = mysqli_query($conn, $sql);
          $row = $result->fetch_assoc();
          $user_id = $row['UserID'];
          $_SESSION['userID'] = $user_id;
          $date = date('Y-m-d');
          $time = date('H:i:s');
          $sql = "INSERT INTO Messages (MessageID, UserID, Message, Date, Time) VALUES ('', '" . $user_id . "', '" . $_POST['message'] . "', '" . $date . "', '" . $time . "')";
          if (mysqli_query($conn, $sql_insert)) 
          {
            $message_added =  "Message created successfully";
          } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
        }
        else{
          $blank_field = "<span style = 'font-weight: bold; font-size: 20px;'>Message Field is Blank</span>";
        }
       $sql = "SELECT Users.UserID, Users.FirstName, Users.LastName, Users.image_name, Messages.UserID, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                                 COUNT(UserMessageLike.UserID) AS                 Likes
              FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
              LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
              GROUP BY Messages.MessageID
              ORDER BY COUNT(UserMessageLike.UserID) DESC";
          $result = mysqli_query($conn, $sql);
          if ($result->num_rows > 0)
          {
            echo "<div class = 'message_board_main'>";
            while ($row = $result->fetch_assoc()) 
            {
            if($_SESSION['userID'] == $row['UserID']  ) 
            {
              $delete_button = '<button type = "submit" name = "messageid_delete" class="del_message" id = "msg_del_' . $row['MessageID'] . '" style="margin-left: 3px;" value = "'. $row['MessageID'] . '"> Delete! </button>';
            }
            else
              $delete_button = "";
            echo '<div class="container">  
                    <div id = "dialog_box_msg_id_' . $row['MessageID'] . '" class="dialogbox"> 
                      <div class="body"> 
                        <span class="tip tip-up"></span>
                        <div class="message">
                          <a href = "Profile.php?userid='. $row['UserID'] . '" ><img width = "100" height = "100" src = "images/'. $row['image_name']. '"></img></a><br>
                          Name : <a href = "Profile.php?userid='. $row['UserID'] . '" ><span>'. $row['FirstName'] . " " . $row['LastName'] . '</span></a><br>
                          Message : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" ><span>'. $row['Message']. '</span></a><br>
                          <span> Date : '. $row['Date']. '</span><br>
                          <span> Time : '. $row['Time']. '</span><br>
                          Likes : <a id = "msg_like_' . $row['MessageID'] . '" href = "MessageLikes.php?messageid='. $row['MessageID'] . '" > <span>'. $row['Likes']. '</span></a><br>
                          <button type = "submit" name = "messageid" class="submit_like" value = "'. $row['MessageID'] . '"> Like! </button>
                          <button type = "submit" name = "messageid_unlike" disabled=disabled id = "msg_unlike_' . $row['MessageID'] . '" class="submit_message_unlike" value = "'. $row['MessageID'] . '"> Unlike! </button>' . $delete_button . '
                        <br></div>
                      </div>
                    </div>
                   </div>
                 ';
            }
            echo "</div>";
          }
        $conn->close();
?>
    <form action="MessageBoard.php" method="post">
        <div class="container">
           <div class="row">
            <div class="col-md-6">
              <div class="panel panel-login">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-12 message_field_blank">
                      <div class="form-group">
                        <input type="text" name="message" id="message_text" tabindex="2" class="form-control" placeholder="Message">
                      </div>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-sm-6 col-sm-offset-3">
                            <input type="submit" name="submit" tabindex="4" class="form-control bttn bttn-login message_submit" value="Submit">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         </div>
      </div>
    </form>
  </body>
</html>
<?php        
      }
    }
 ?>