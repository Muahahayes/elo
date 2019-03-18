<?php 
// Page to render a json array of all the player names in the db
if(isset($_GET["players"])){
    // ------------------------------ //
    $host="localhost";
    $port=3306;
    $socket="MySQL";
    $username="root";
    $password="";
    $dbname="smash";
    // ------------------------------ //
    
    $con = new mysqli($host, $username, $password, $dbname, $port, $socket);
    if ($con->connect_errno) {
        die("Failed to connect to MySQL: ($con->connect_errno) $con->connect_error");
    }

    $result = $con->query('SELECT * FROM players');
    $pList = array();
    if($_GET["players"] == "rivals"){
        while($player = mysqli_fetch_assoc($result)){
            if($player["rival"] == 1){
                $tag = $player["tag"];
                array_push($pList, $tag);
            }
        }
    }
    else{
        while($player = mysqli_fetch_assoc($result)){
                $tag = $player["tag"];
                array_push($pList, $tag); 
        } 
    }

    header("Content-type: application/json");
    echo json_encode($pList);
}

?>