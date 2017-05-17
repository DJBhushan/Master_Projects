$(document).ready(function(){
   /**************** Message LIKE JS **********************/
   $(document).on("click",".submit_like", function(e) {
       var message_id = $(this).val();
       var message_like = "like";
       $('#msg_unlike_' + message_id).removeAttr('disabled');
       $('#new_span_unlike_' + message_id + ', .ajax_msg_span_unlike').remove()
       $('#new_span_' + message_id + ', .ajax_msg_span').remove();
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{messageid: message_id,  message_like: message_like},
          // dataType: 'json',
          success: function(msg){
            var ajaxResponse = JSON.parse(msg);
            console.log(ajaxResponse);
            $('#dialog_box_msg_id_' + message_id).find('#msg_like_' + message_id).html(ajaxResponse.msg_Likes_count);
            $('#dialog_box_msg_id_' + message_id + " .message").append("<span class = 'ajax_msg_span' id = 'new_span_" +message_id + "'>" + ajaxResponse.msg_liked + "</span>");
          }
        });
   return false;
   });
  
   /**************** Message POST JS **********************/
   $(".message_submit").on('click', function(e) {
       var message_submit = $(this).val();
       var message_text = $('#message_text').val();
       console.log(message_text + message_submit );
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{message_submit: message_submit, message_text: message_text},
          // dataType: 'json',
          success: function(msg){
            var ajaxPostMessageResponse = JSON.parse(msg);
            $('#blank_meesage').remove();
            if(ajaxPostMessageResponse.msg_blank === "Message Field is Blank")
              {
                $('.message_field_blank').next().append('<span id = "blank_meesage" style="font-weight: bold; font-size: 20px;">Message Field is Blank</span>');
              }
            else{
              var ajaxPostMessage =  '<div class="container"><div id = "dialog_box_msg_id_' + ajaxPostMessageResponse.MessageID + '" class="dialogbox"><div class="body"><span class="tip tip-up"></span><div class="message"><a href = "Profile.php?userid=' + ajaxPostMessageResponse.UserID + '" ><img width = "100" height = "100" src = "images/' + ajaxPostMessageResponse.image_name + '"></img></a><br>Name : <a href = "Profile.php?userid='+ ajaxPostMessageResponse.UserID + '" ><span>'+ ajaxPostMessageResponse.FirstName + " " + ajaxPostMessageResponse.LastName + '</span></a><br>Message : <a href = "MessageLikes.php?messageid='+ajaxPostMessageResponse.MessageID + '" ><span>'+ ajaxPostMessageResponse.Message + '</span></a><br><span> Date : '+ ajaxPostMessageResponse.Date + '</span><br><span> Time : '+ajaxPostMessageResponse.Time + '</span><br>Likes : <a id = "msg_like_' + ajaxPostMessageResponse.MessageID + '" href = "MessageLikes+php?messageid='+ ajaxPostMessageResponse.MessageID + '" > <span>'+ ajaxPostMessageResponse.Likes + '</span></a><br><button type = "submit" name = "messageid" class="submit_like" value = "'+ ajaxPostMessageResponse.MessageID + '"> Like! </button><button type = "submit" name = "messageid_unlike" id = "msg_unlike_' + ajaxPostMessageResponse.MessageID + '" style="margin-left: 3px;" class="submit_message_unlike" disabled=disabled value = "'+ ajaxPostMessageResponse.MessageID + '"> Unlike! </button><button type = "submit" name = "messageid_delete" class="del_message" id = "msg_del_' + ajaxPostMessageResponse.MessageID + '" style="margin-left: 3px;" value = "' + ajaxPostMessageResponse.MessageID + '"> Delete! </button><br></div></div></div></div>'
              console.log(ajaxPostMessage);
              $('.message_board_main > :last-child').after(ajaxPostMessage);
            }
          }
        });
   return false;
   });
  /**************** Message DELETE JS **********************/
  $(document).on('click', ".submit_message_unlike", function(e) {
       var message_id = $(this).val();
       var message_unlike = "unlike";
       $('#msg_unlike_' + message_id).attr('disabled','disabled');
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{messageid: message_id, message_unlike: message_unlike},
          // dataType: 'json',
          success: function(msg){
            console.log(msg);
            var ajaxResponse = JSON.parse(msg);
            console.log(ajaxResponse);
            $('#new_span_' + message_id + ', .ajax_msg_span').remove();
            $('#new_span_unlike_' + message_id + ', .ajax_msg_span_unlike').remove();
            $('#dialog_box_msg_id_' + message_id).find('#msg_like_' + message_id).html(ajaxResponse.msg_Likes_count);
            $('#dialog_box_msg_id_' + message_id + " .message").append("<span class = 'ajax_msg_span_unlike' id = 'new_span_unlike_" +message_id + "'>" + ajaxResponse.msg_unliked + "</span>");
          }
        });
   return false;
   });
  /**************** Message DELETE JS **********************/
  $(document).on('click', ".del_message", function(e) {
       var message_id = $(this).val();
       var message_delete = "delete";
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{messageid: message_id, message_delete: message_delete},
          // dataType: 'json',
          success: function(msg){
            var ajaxResponse = JSON.parse(msg);
            console.log(ajaxResponse.msg_delete);
            if(ajaxResponse.msg_delete === "Message Deleted...!!!")
              {
                $("#dialog_box_msg_id_" + message_id).remove();
              }
          }
        });
   return false;
   });
});