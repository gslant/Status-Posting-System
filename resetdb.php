<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reset Database</title>
</head>
<body>
    <?php       
            require_once("/home/nqg7426/conf/settings.php");
    
            $conn = new mysqli($host, $user, $pswd, $dbnm); 
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if($conn->query("DROP TABLE statuses") === true){
                echo "<p>The database has been dropped</p>";
            }
            echo "<a href='http://nqg7426.cmslamp14.aut.ac.nz/assign1/index.html'>Return to Home Page</a>";
    ?>
</body>
</html>