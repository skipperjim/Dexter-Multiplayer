<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 12/7/14
 * Time: 4:50 PM
 */
?>

<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Dexter's Photo Album</title>
</head>

<body>
<fieldset id="PhotoAlbum_Main" style="border:1px dashed lightgray;">
    <h2 style="text-align: center;">Dexter's Photo Album!</h2>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "42Baseball";
    $dbname = "dexter";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, file_name, path FROM photos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["id"]. " - Name: " . $row["file_name"]. " " . $row["path"]. "<br>";
            echo "<img src='http://127.0.0.1/dexter/${row['path']}/${row['file_name']}' alt='Dexter!!' style='display: inline-block;' width='270px' height='480'>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>


</fieldset>
</body>

</html>