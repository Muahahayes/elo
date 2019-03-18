<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" 
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" 
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
    <script src="elo.js"></script>
    <title>Smash Rankings</title>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item active"><a class="nav-link" href="index.php">Player Ratings</a></li>
                <li class="nav-item"><a class="nav-link" href="update.php">Report Sets</a></li>
                <li class="nav-item"><a class="nav-link" href="weekly.html">Weekly Rivals</a></li>
            </ul>
        </div>
    </nav>
    <div class="jumbotron jumbotron-fluid bg-success">
        <div class="container-fluid">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <h1 class="display-4">Player Ratings</h1>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>

<?php 
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
    $result = $con->query('SELECT * FROM players ORDER BY elo desc');

    $players = array();
    while($row = mysqli_fetch_assoc($result)){
        array_push($players, $row);
    }
    ?>
    <br>
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th id="tag">Tag</th>
                    <th id="name">Name</th>
                    <th id="elo">Rating</th>
                    <th id="placement">Placement</th>
                    <th id="matchHistory">Match History</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i= 0;
                $tiers = array(
                    0 => array(
                        "name" => "Black Belt",
                        "elo" => 10000,
                        "range" => "Above 1800"
                    ),
                    1 => array(
                        "name" => "Red Belt",
                        "elo" => 1800,
                        "range" => "Between 1700 and 1800"
                    ),
                    2 => array(
                        "name" => "Purple Belt",
                        "elo" => 1700,
                        "range" => "Between 1600 and 1700"
                    ),
                    3 => array(
                        "name" => "Blue Belt",
                        "elo" => 1600,
                        "range" => "Between 1500 and 1600"
                    ),
                    4 => array(
                        "name" => "Green Belt",
                        "elo" => 1500,
                        "range" => "Between 1400 and 1500"
                    ),
                    5 => array(
                        "name" => "Yellow Belt",
                        "elo" => 1400,
                        "range" => "Between 1300 and 1400"
                    ),
                    6 => array(
                        "name" => "White Belt",
                        "elo" => 1300,
                        "range" => "Below 1300"
                    )
                    );
                    while($players[0]["elo"] < $tiers[$i + 1]["elo"])
                    {
                        $i++;
                    }
                foreach($players as $player){
                    if($i != 7 && $player["elo"] < $tiers[$i]["elo"]){
                        echo('<tr><td><h1>< ' . $tiers[$i]["name"] . 's > <br>#' . $tiers[$i]["range"] . '</h1></td></tr>');
                        $i++;
                    }
                    if ($player["placement"] > 0) {
                        $placement = $player["placement"];
                    }
                    else {
                        $placement = 'Complete';
                    }
                    echo('<tr class="td-row">
                    <td>' . $player["tag"] . '</td>
                    <td>' . $player["name"] . '</td>
                    <td>' . $player["elo"] . '</td>
                    <td>' . $placement . '</td>
                    <td><button type="button" class="modal-btn btn btn-primary" id="' . $player["id"] . '">Match History</button></td>'); 
                }
            ?>
            </tbody>
        </table>
    </div>
    <div id="modals"></div>
</body>
</html>