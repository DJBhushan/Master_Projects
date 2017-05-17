<!DOCTYPE html>
<html>
  <head>
    <title>Profile Page</title>
    <link rel = "stylesheet" href = "http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" />
    <link rel = "stylesheet" href = "css/main.css" />
  </head>
  <body>
    <div class="container">
     <div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Profile Page</h1>
          <hr />
        </div>
        <a href="MessageBoard.php" class="btn btn-primary btn-lg btn-block login-button">MessageBoard</a>
        <a href="ProfileEdit.php" class="btn btn-primary btn-lg btn-block login-button" >Go to Profile Edit</a>
        <a href="logout.php" class="btn btn-primary btn-lg btn-block login-button" >Logout</a>
      </div>
    </div>
<?php
    require_once('DBConnect.php');
    session_start();
    $userID = $_GET['userid'];
    //$userID = $_SESSION['userID'];
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
          $sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time, COUNT(UserMessageLike.UserID)                   AS               Likes
                  FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                  LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                  WHERE Users.UserID = " . $userID . "
                  GROUP BY Messages.MessageID
                  ORDER BY COUNT(UserMessageLike.UserID) DESC";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) 
            {
              $message_count = $result->num_rows;
              echo  '<div class="container">  
                                    <div class="dialogbox"> 
                                      <div class="body"> 
                                        <div class="message">
                                          <span style="font-weight: bold;color: #428bca;text-align: center;"> Number of Messages Created By User ' . $message_count . '</span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
              while ($row = $result->fetch_assoc()) 
              {
                echo '<form action="MessageLikes.php?messageid='. $row['MessageID'] . '" method="post"> 
                        <div class="container">  
                          <div class="dialogbox"> 
                            <div class="body"> 
                              <span class="tip tip-up"></span>
                              <div class="message">
                                <a href = "Profile.php?userid='. $row['UserID'] . '" ><img width = "100" height = "100" src = "images/'. $row['image_name']. '"></img></a><br>
                                Name : <a href = "Profile.php?userid='. $row['UserID'] . '" ><span>'. $row['FirstName'] . " " . $row['LastName'] . '</span></a><br>
                                Message : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" ><span>'. $row['Message']. '</span></a><br>
                                <span> Date : '. $row['Date']. '</span><br>
                                <span> Time : '. $row['Time']. '</span><br>
                                Likes : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" > <span>'. $row['Likes']. '</span></a><br>
                              </div>
                            </div>
                          </div>
                        </div>
                     </form>';
              }
            }
            else{
              echo "<span style = 'margin-left: 51em; font-weight: bold;'> Number of Messages Created By User " . $result->num_rows . " </span><br>";
            }
            $sql_likes_by_other = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                               COUNT(UserMessageLike.UserID) AS Likes
                  FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                  LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                  WHERE UserMessageLike.UserID = " . $userID . "
                  GROUP BY Messages.MessageID
                  ORDER BY COUNT(UserMessageLike.UserID) DESC";
            $result = mysqli_query($conn, $sql_likes_by_other);
            if ($result->num_rows > 0) 
            {
              echo  '<div class="container">  
                                    <div class="dialogbox"> 
                                      <div class="body"> 
                                        <div class="message">
                                          <span style="font-weight: bold;color: #428bca;text-align: center;"> Number of Messages Liked By Other Users ' . $result->num_rows . '</span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
              while ($row = $result->fetch_assoc()) 
              {
                echo '<form action="MessageLikes.php?messageid='. $row['MessageID'] . '" method="post"> 
                        <div class="container">  
                          <div class="dialogbox"> 
                            <div class="body"> 
                              <span class="tip tip-up"></span>
                              <div class="message">
                                <a href = "Profile.php?userid='. $row['UserID'] . '" ><img width = "100" height = "100" src = "images/'. $row['image_name']. '"></img></a><br>
                                Name : <a href = "Profile.php?userid='. $row['UserID'] . '" ><span>'. $row['FirstName'] . " " . $row['LastName'] . '</span></a><br>
                                Message : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" ><span>'. $row['Message']. '</span></a><br>
                                <span> Date : '. $row['Date']. '</span><br>
                                <span> Time : '. $row['Time']. '</span><br>
                                <button type = "submit" name = "messageid" value = "'. $row['MessageID'] . '"> Like! </button>
                              </div>
                            </div>
                          </div>
                        </div>
                     </form>';
              }
            }
         else{
              echo "<span style = 'margin-left: 43em; font-weight: bold;'> Number of Messages Liked By Other Users " . $result->num_rows . "                    </span><br>";
            }
          $conn->close();
      }
    }
?>
  </body>
</html>