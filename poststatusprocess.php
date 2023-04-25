<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Process post status</title>
</head>
<body>
    <div class="content">
        <?php
            //Function to print links to other pages
            function links(){
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/poststatusform.php'>Post a new status</a>";
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/index.html'>Return to Home Page</a>";
            }

            $host = "cmslamp14.aut.ac.nz";
            $user = "nqg7426";
            $pswd = "";
            $dbnm = "nqg7426";
            $conn = new mysqli($host, $user, $pswd, $dbnm);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //sql for creating table, using the IF NOT EXISTS syntax, to only create the table if it doesn't already exist
            $create_table = "CREATE TABLE IF NOT EXISTS `statuses`(
                `status_code` VARCHAR(5) PRIMARY KEY NOT NULL,
                `status` VARCHAR(40) NOT NULL,
                `share_type` ENUM('Public','Friends','Only Me'),
                `date` DATE NOT NULL,
                `allow_like`BOOLEAN,
                `allow_comment` BOOLEAN,
                `allow_share` BOOLEAN
                );";
            
            $result = $conn->query($create_table);
            
            //Checking to make sure the form inputs are set, and that they are not empty
            if(
                !empty($_POST["statuscode"]) &&
                !empty($_POST["status"]) &&
                !empty($_POST["share"]) &&
                !empty($_POST["date"])
            ){
                $status_code = $_POST["statuscode"];
                $status = $_POST["status"];
                $date = date('d/m/Y', strtotime($_POST["date"]));
                $share = $_POST["share"];
                $allow_like = FALSE;
                $allow_comment = FALSE;
                $allow_share = FALSE;

                if(isset($_POST["allow_like"])){
                    $allow_like = TRUE;
                }
                if(isset($_POST["allow_comments"])){
                    $allow_comment = TRUE;
                }
                if(isset($_POST["allow_share"])){
                    $allow_share = TRUE;
                }

                //sql query to check if any status code exists in the database the same as the one the user is trying to post
                $check_unique = "SELECT * FROM statuses WHERE status_code = '$status_code'";

                $status_dupe_result = $conn->query($check_unique);
                if(!$status_dupe_result){
                    die("<p>Unable to execute the query.</p>"
                    . "<p>Error Code " . $conn->errno
                    . ": " . $conn->error . "</p>");
                }
                //If any rows are returned, then the status code already exists, and the user is prompted to try again
                $num_existing_codes = $status_dupe_result->num_rows;
                    if($num_existing_codes > 0){
                        echo "<p>That status code already exists. Please try another one!</p>";
                        links();
                    }
                    else{
                        //regex to check if the status code is in the correct format e.g. S0001
                        $status_code_pattern = "/^S\d{4}$/";

                        if(!preg_match($status_code_pattern, $status_code)){
                            echo "<p>Wrong format! The status code must start with an \"S\" followed by four digits, like \"S0001\".</p>";
                            links();
                        }
                        else{
                            //If the status code is the correct format, check if the status is in the correct format, i.e no spaces only, no special characters
                            $status_pattern = "/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9,.!? ]+$/";
                            if(!preg_match($status_pattern, $status)){
                                echo "<p>Your status is in a wrong format! The status can only contain
                                        alphanumericals and spaces, comma, period, exclamation point and question mark
                                        and cannot be blank!</p>";
                                links();
                            }
                            else{
                                //Checking the date, to ensure it is valid, that the day is not greater than 31, month is not greater than 12
                                $split_date = explode('/', $date);
                                if(!checkdate($split_date[1], $split_date[0], $split_date[2])){
                                    echo "<p>date is incorrectly formatted, please try again</p>";
                                    links();
                                }
                                else{
                                    //If all the checks are passed, then the status is inserted into the database
                                    //Formatting the date into the required format for MySQL's date datatype
                                    $date = date('Y-m-d', strtotime($_POST["date"]));
                                    $insert_status = "INSERT INTO `statuses` VALUES('$status_code', '$status', '$share', '$date', '$allow_like','$allow_comment','$allow_share')";

                                    //If the query is successful, show the success message, and the links
                                    if($conn->query($insert_status) === TRUE){
                                        echo "<p>Congratulations! The status has been posted!</p>";
                                        links();
                                    }
                                    else{
                                        echo "<p>Error: " . $insert_status . "</p><br>" . $conn->error;  
                                    }
                                }
                                
                            }
                        }
                    }

                }
            else{
                echo "<p>Make sure all fields are filled in</p>";
                links();
            }

            $conn->close();
        ?>
    </div>
</body>
</html>