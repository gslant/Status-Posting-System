<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Post a status</title>
</head>
<body>
    <div class="content">
        <h1>Status Posting System</h1> 
        <form action="poststatusprocess.php" method="post">
            
            <!--Input for status code -->
            <label><b>Status Code </b><input type="text" name="statuscode" required></label></br>
            <label><b>Status </b><input type="text" name="status" required></label></br></br>

            <!--Input for share options -->
            <label><b>Share: </b></label><br><br>
            <input type="radio" id="public" name="share" value="Public">
            <label for="public">Public</label><br>
            <input type="radio" id="friends" name="share" value="Friends">
            <label for="friends">Friends</label><br>
            <input type="radio" id="only_me" name="share" value="Only Me" checked>
            <label for="only_me">Only Me</label><br><br>
            
            <!-- Input for date, using html date picker, and setting the placeholder date using php, formatted to the required html syntax-->
            <label><b>Date </b><input type="date" name="date" value="<?php echo date("Y-m-d") ?>"></label></br></br>

            <!-- Input for permissions-->
            <label><b>Permission: </b></label><br><br>
            <input type="checkbox" id="allow_like" name="allow_like" value="allow_like">
            <label for="allow_like">Allow Like</label><br>
            <input type="checkbox" id="allow_comments" name="allow_comments" value="allow_comments">
            <label for="allow_comments"> Allow Comments</label><br>
            <input type="checkbox" id="allow_share" name="allow_share" value="allow_share">
            <label for="allow_share"> Allow Share</label></br>
            <input type="submit" value="Post">
        </form>

        </br><a href="http://nqg7426.cmslamp14.aut.ac.nz/assign1/index.html">Return to Home Page</a>
    </div>
</body>
</html>