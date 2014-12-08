<!-- <script src="/assets/js/jquery-2.1.1.min.js"></script> -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

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
<fieldset id="PhotoAlbum_Main" class='stitched'>
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
        echo "<center><p>";
        echo "<a href='' id='PrevImg'><< Prev &nbsp;&nbsp;</a>";
        echo "<a href='' id='NextImg'>&nbsp;&nbsp; Next >></a>";
        echo "</p></center>";
        // output data of each row
        $i = 1;
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["id"]. " - Name: " . $row["file_name"]. " " . $row["path"]. "<br>";
            if($i == 1){
                echo "<div id='ImageDiv_${row['id']}' class='photoframe' num='${row['id']}' style='margin:0 auto;'><center>";
            }else{
                echo "<div id='ImageDiv_${row['id']}' class='photoframe' num='${row['id']}' style='display:none;'><center>";
            }
            echo "<img id='Image_${row['id']}' src='http://127.0.0.1/dexter/${row['path']}/${row['file_name']}' alt='Dexter!!' style='display: inline-block;' width='270px' height='480'>";
            echo "</center></div>";
            $i++;
        }
    } else {
        echo "0 results.";
    }
    echo "<center><p>";
    echo "<a href='' id='PrevImg'><< Prev &nbsp;&nbsp;</a>";
    echo "<a href='' id='NextImg'>&nbsp;&nbsp; Next >></a>";
    echo "</p></center>";
    $conn->close();
    ?>


</fieldset>
</body>

</html>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function(){
    console.log("Document ready!");

    jQuery("a#PrevImg, a#NextImg").on("click", function(e){
        navigateImages(jQuery(this).attr("id"));
        e.preventDefault();
    });
});

function navigateImages(direction){
    console.log("Going to "+direction+".");
    var currImgEle = jQuery("div.photoframe:visible");
    var currNum = parseInt(currImgEle.attr("num"));
    var nextNum = (currNum+1);
    var prevNum = (currNum-1);
    console.log("Current Image # is: "+currNum+". Next # is: "+nextNum+". Previous # is: "+prevNum+".");
    if(direction === "PrevImg"){
        var viewImgEle = jQuery("div#ImageDiv_"+prevNum+":hidden");
    }else if(direction === "NextImg"){
        var viewImgEle = jQuery("div#ImageDiv_"+nextNum+":hidden");
    }
    currImgEle.hide();
    viewImgEle.show();
}
</script>

<style>
body {
    width: 960px;
    margin: 0 auto;
    background-color: gray;
}
a#PrevImg, a#NextImg {
    text-decoration: none;
}
.stitched {
    padding: 20px;
    margin: 10px;
    background: darkgray;
    color: #fff;
    font-size: 20px;
    font-weight: bold;
    line-height: 1.3em;
    border: 2px dashed black;
    border-radius: 10px;
    box-shadow: 0 0 0 4px darkgray, 2px 1px 6px 4px rgba(10, 10, 0, 0.5);
    text-shadow: -1px -1px #aa3030;
    font-weight: normal;
}
</style>