<?php 

// Page to render json of player match history.
// Query db for all Players join Sets on WinnersFK=id, return players.name matching each sets LosersFK. Put each row (of just a player name) to a Win array.
// Same for all sets LosersFK matching player's id. To a Loss array.
// Push both arrays to Matches array
// Json encode Matches and echo. 
// ex.
//  [
//     ['ASIAN', 'DMDF', 'Pyu', 'ASIAN', 'Pyu', 'Toonmac'],
//     ['ASIAN', 'DMDF', 'GOGO', 'GOGO'], 
//     'Player 9'
//  ];
// 0: wins
// 1: losses
// 2: name

if(isset($_GET["p"])){
    // ------------------------------ //
    $host="localhost";
    $port=3306;
    $socket="MySQL";
    $username="root";
    $password="";
    $dbname="smash";
    // ------------------------------ //
    
    $pID = $_GET["p"];

    $con = new mysqli($host, $username, $password, $dbname, $port, $socket);
    if ($con->connect_errno) {
        die("Failed to connect to MySQL: ($con->connect_errno) $con->connect_error");
    }
    
    $result = $con->query('SELECT * FROM players WHERE id="' . $pID . '"');
    if($row = mysqli_fetch_assoc($result)){
        $matches = array();
        $wins = array();
        $losses = array();
        $player = $row["tag"];
        $isWins = false;
        $isLosses = false;

        unset($result);
        unset($row);
        $result = $con->query('SELECT * FROM matches WHERE winnerFK="' . $pID . '"');
        while($row = mysqli_fetch_assoc($result)){
            $loser = $con->query('SELECT tag FROM players WHERE id="' . $row["loserFK"] . '"');
            if($tag = mysqli_fetch_assoc($loser)){
                array_push($wins, $tag["tag"]);
                $isWins = true;
            }
        }

        unset($result);
        unset($row);
        $result = $con->query('SELECT * FROM matches WHERE loserFK="' . $pID . '"');
        while($row = mysqli_fetch_assoc($result)){
            $winner = $con->query('SELECT tag FROM players WHERE id="' . $row["winnerFK"] . '"');
            if($tag = mysqli_fetch_assoc($winner)){
                array_push($losses, $tag["tag"]);
                $isLosses = true;
            }
        }
        if($isWins){
            array_push($matches, $wins);
        }
        else{
            array_push($matches, ["No Wins"]);
        }
        if($isLosses){
            array_push($matches, $losses);
        }
        else{
            array_push($matches, ["No Losses"]);
        }
        array_push($matches, $player);

        header("Content-type: application/json");
        echo json_encode($matches);
    }
    else{
        die("Player not found!");
    }
}
else{
    die("No player id given!");
}
?>