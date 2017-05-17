<!DOCTYPE html>
<html>
  <head>
    <title>Message Likes</title>
    <link rel = "stylesheet" href = "http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" />
    <link rel = "stylesheet" href = "css/main.css" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="js/main.js"></script> 
  </head>
  <body>
    <div class="container">
     <div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Message Likes Page</h1>
          <hr />
        </div>
        <a href="MessageBoard.php" class="btn btn-primary btn-lg btn-block login-button">MessageBoard</a>
        <a href="ProfileEdit.php" class="btn btn-primary btn-lg btn-block login-button" >Go to Profile Edit</a>
        <a href="logout.php" class="btn btn-primary btn-lg btn-block login-button" >Logout</a>
        <img src="images/load.gif" id="loading-image" style="display:none"/>
      </div>
    </div>
    <?php
        require_once('DBConnect.php');
        session_start();
        $userID = $_SESSION['userID']; 
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
            //echo $_POST['messageid'];
            if($_POST['messageid'] != "")
            {
              $message_id = $_POST['messageid'];
              $sql_insert = "INSERT INTO UserMessageLike(`UserID`, `MessageID`) VALUES ('" .  $userID . "', '" . $message_id . "')";
              if (mysqli_query($conn, $sql_insert))
              {
                $message_liked =  '<div class="container message_liked_exist">  
                                    <div class="dialogbox"> 
                                      <div class="body"> 
                                        <div class="message">
                                          <span style="font-weight: bold;color: #428bca;text-align: center;margin-left: 86px;"> Message Like...!!! </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                //echo $message_liked;
              } 
              else
              {
                $message_liked =  '<div class="container message_liked_exist">  
                                    <div class="dialogbox"> 
                                      <div class="body"> 
                                        <div class="message">
                                          <span style="font-weight: bold;color: #428bca;text-align: center;margin-left: 70px;"> Like Already Exist...!!! </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                //echo $like_exist;
              }
            }
            $message_id = $_GET['messageid'];
            $sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date,               Messages.Time,                                   COUNT(UserMessageLike.UserID) AS Likes
                    FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                    LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                    WHERE Messages.MessageID = $message_id
                    GROUP BY Messages.MessageID
                    ORDER BY COUNT(UserMessageLike.UserID) DESC";
              //echo $sql;
              $result = mysqli_query($conn, $sql);
              //echo $result->num_rows;
              //echo '<div id="message_like_content">' . $message_liked;exit;
              if ($result->num_rows > 0) 
              {
                while ($row = $result->fetch_assoc()) 
                {
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
                              <button type = "submit" name = "messageid" class="submit_message" value = "'. $row['MessageID'] . '"> Like! </button>
                              <button type = "submit" name = "messageid_unlike" class="submit_message_unlike" value = "'. $row['MessageID'] . '"> Unlike! </button><br>
                              </div>
                            </div>
                           </div>
                         </div>
                       </div>';
                }
              }
            $conn->close();
          }
        }
    ?>
  </body>
</html>
