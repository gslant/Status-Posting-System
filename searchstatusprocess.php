<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search Results</title>
</head>
<body>
    <div class="content">
        <?php
            //Functions to give links to other pages - specifically to post status form and home page if the search fails, and to search form and home page if the search succeeds
            function fail_links(){
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/poststatusform.php'>Post a new status</a><";
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/index.html'>Return to Home Page</a>";
            }
            function success_links(){
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/searchstatusform.php'>Search for another status</a>";
                echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/index.html'>Return to Home Page</a>";
            }

            //Regex to check if the search string is whitespace
            $whitespace_check_pattern = "/^\s*$/";

            if(!isset($_GET) ||preg_match($whitespace_check_pattern, $_GET["Search"])){
                echo "<p>The search string is empty. Please enter a keyword to search</p>";
            }
            else{
                $search_string = $_GET["Search"];
                $host = "cmslamp14.aut.ac.nz";
                $user = "nqg7426";
                $pswd = "";
                $dbnm = "nqg7426";

                $conn = new mysqli($host, $user, $pswd, $dbnm);
            
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                //Checking to see if the statuses table exists, and showing an error message if it doesn't
                $check_table_query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbnm' AND TABLE_NAME = 'statuses';";
                $check_table_result = $conn->query($check_table_query);
                if($check_table_result->num_rows == 0){
                    echo "<p>No status found in the system. Please go to the post status page to post one</p></br>";
                    fail_links();
                }
                else{
                    //Searching for the statuses that match the search string, percent signs are used to allow for partial matches
                    $search_query = "SELECT * FROM statuses WHERE status LIKE '%$search_string%';";
                    $search_result = $conn->query($search_query);
                    if($search_result->num_rows == 0){
                        echo "<p>Status not found. Please try a different keyword</p></br>";
                        success_links();
                    }
                    else{
                        //If the search is successful, print out the results in a table, to increase readability
                        echo "<h1> Status Information</h1>";
                        echo "<table>";
                        echo "<tr>
                            <th>Status Code</th>
                            <th>Status</th>
                            <th>Share Type</th>
                            <th>Date</th>
                            <th>Permission</th>
                        </tr>";
                        while($row = $search_result->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $row["status_code"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td>" . $row["share_type"] . "</td>";
                            echo "<td>" . date("F j, Y", strtotime($row["date"])) . "</td>";
                            echo "<td>" . 
                                (($row["allow_like"]) ? " Allow Like," : "") . 
                                (($row["allow_comment"]) ? " Allow Comment," : "") . 
                                (($row["allow_share"]) ? " Allow Share" : "") . 
                            "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        success_links();
                    }
                }

            }
        ?>
    </div>
</body>
</html>