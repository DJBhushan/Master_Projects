<?php
        require_once('DBConnect.php');
        session_start();
        $userID = $_SESSION['userID']; 
        $return = array();
        $return_msg_del = array();
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
            /**************** Message Like API **********************/
            if($_POST['messageid'] != "")
            {
               $message_id = $_POST['messageid'];
               if($_POST['message_like'] === "like")
               {
                 $sql_insert = "INSERT INTO UserMessageLike(`UserID`, `MessageID`) VALUES ('" .  $userID . "', '" . $message_id . "')";
                  if (mysqli_query($conn, $sql_insert))
                  {
                    $msg_liked = "Message Like...!!!";
                    $return["msg_liked"] = $msg_liked;
                  } 
                  else
                  {
                    $msg_like_exist =  "Like Exist...!!!";
                    $return["msg_liked"] = $msg_like_exist;
                  }
								}
                 /**************** Message UNLIKE API **********************/
                if($_POST['message_unlike'] === "unlike")
                {
                  $sql_delete = "DELETE FROM UserMessageLike WHERE UserID = ". $userID . " AND MessageID = " . $message_id;
                  if (mysqli_query($conn, $sql_delete))
                  {
                    $msg_deleted = "Message Unliked...!!!";
                    $return["msg_unliked"] = $msg_deleted;
                  }
                  else{
                    $msg_deleted = "Message Already Unliked...!!!";
                    $return["msg_unliked"] = $msg_deleted;
                  }
                }
								$sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date,               Messages.Time,                                   COUNT(UserMessageLike.UserID) AS Likes
                        FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                        LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                        WHERE Messages.MessageID = $message_id
                        GROUP BY Messages.MessageID
                        ORDER BY COUNT(UserMessageLike.UserID) DESC";
									//echo $sql;
									$result = mysqli_query($conn, $sql);
									if ($result->num_rows > 0) 
									{
										while ($row = $result->fetch_assoc()) 
										{
											 $return["msg_Likes_count"] = $row['Likes'];
										}
									}
             }
            /**************** Message POST API **********************/
            if($_POST['message_submit'] === "Submit")
            {
              if($_POST['message_text'] != "")
              {
                $email = $_SESSION['email'];
                $sql = "SELECT UserID FROM Users WHERE email = '" .  $email . "'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                $user_id = $row['UserID'];
                $_SESSION['userID'] = $user_id;
                $date = date('Y-m-d');
                $time = date('H:i:s');
                $sql = "INSERT INTO Messages (MessageID, UserID, Message, Date, Time) VALUES ('', '" . $user_id . "', '" . $_POST['message_text'] . "', '" . $date . "', '" . $time . "')";
                if (mysqli_query($conn, $sql)) 
                {
                  $message_added =  "Message created successfully";
                } 
                else 
                {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $sql = "SELECT Users.UserID, Users.FirstName, Users.LastName, Users.image_name, Messages.UserID, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                                                                                  COUNT(UserMessageLike.UserID) AS                 Likes
                    FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                    LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                    GROUP BY Messages.MessageID
                    DESC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0)
                {
                  while ($row = $result->fetch_assoc()) 
                  {
                    $return["MessageID"] = $row["MessageID"];
                    $return["image_name"] = $row["image_name"];
                    $return["UserID"] = $row["UserID"];
                    $return["FirstName"] = $row["FirstName"];
                    $return["LastName"] = $row["LastName"];
                    $return["Message"] = $row["Message"];
                    $return["Date"] = $row["Date"];
                    $return["Time"] = $row["Time"];
                    $return["Likes"] = $row["Likes"];
                  }
                }
              }
              else{
                $blank_field = "Message Field is Blank";
								$return['msg_blank'] = $blank_field;
              }
            }
            /**************** Message DELETE API **********************/
            if($_POST['messageid'] != "")
            {
              if($_POST['message_delete'] === "delete")
              {
								$sql_delete_msg_like_table = "DELETE FROM UserMessageLike WHERE MessageID = " . $message_id;
							  $sql_delete_msg_table = "DELETE FROM Messages WHERE MessageID = " . $message_id;
              
                if (mysqli_query($conn, $sql_delete_msg_like_table))
                {
                  $msg_deleted = "Message Deleted...!!!";
                  $return["msg_delete"] = $msg_deleted;
                }
                if(mysqli_query($conn, $sql_delete_msg_table))
                {
                  $msg_deleted = "Message Deleted...!!!";
                  $return["msg_delete"] = $msg_deleted;
                }
                else{
                  $msg_del_error = "Error in query";
                  $return["msg_delete"] = $msg_del_error;
                }
              }
            }
						$conn->close();
            echo json_encode($return);exit;
          }
        }
    ?>

	