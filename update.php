<!DOCTYPE html>
<!-- Page to insert sets
4 text boxes and submit button in a form
[Query db for all player names from players
First 2 boxes have drop down of names]
2 boxes for names, 2 for set results
Submit a POST back to this page with names and results
Query INSERT to put the Set into the db
Query for both players ELO scores
  Do ELO formula for the two players
Query ALTER to update the two players ELO -->
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
    <title>Upload Sets</title>
</head>
<style>
    label {
        font-weight: bold;
    }
    input[type=text], [type=number] {
        width: 30%;
        padding: 10px;
        margin: 6px 0;
        border: 1px solid #aaa;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type=submit] {
        width: 20%;
        background-color: #09f;
        color: white;
        padding: 15px;
        margin: 10px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type=submit]:hover {
        background-color: #07d;
    }
    input[type=button] {
        width: 20%;
        background-color: #09f;
        color: white;
        padding: 15px;
        margin: 10px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type=button]:hover {
        background-color: #07d;
    }
</style>
<body>
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Player Ratings</a></li>
                <li class="nav-item active"><a class="nav-link" href="update.php">Report Sets</a></li>
                <li class="nav-item"><a class="nav-link" href="weekly.html">Weekly Rivals</a></li>
            </ul>
        </div>
    </nav>
    <div class="jumbotron jumbotron-fluid bg-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col"></div>
                    <div class="col">
                        <h1 class="display-8">Upload Set Results</h1>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <form method="POST" action="update.php#w">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-6">
                        <label for="w">Winner's Tag:</label>
                        <input type="text" name="w" id="w" autofocus required title="The TAG of the Winner (Not their human name!)">
                    </div>
                    <div class="col"></div>
                </div>

                <div class="row">
                    <div class="col"></div>
                    <div class="col-6">
                        <label for="l">&nbsp &nbspLoser's Tag:</label>
                        <input type="text" name="l" id="l" required title="The TAG of the Loser (Not their human name!)">
                    </div>
                    <div class="col"></div>
                </div>

                <div class="row">
                    <div class="col"></div>
                    <div class="col-5">
                        <label for="wins">Score:</label>
                        <input type="number" name="wins" id="wins" value=0 min=0 required style="width: 20%;" title="The number of wins for the Winner">
                        <input type="number" name="losses" id="losses" value=0 min=0 required style="width: 20%;" title="The number of wins for the Loser">
                    </div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col-6">
                        <input type="checkbox" name="t" value="true"> Tournament Set<br>
                    </div>
                    <div class="col"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col-6">
                        <input type="checkbox" name="r" value="true"> Rival Set<br>
                    </div>
                    <div class="col"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col-6">
                        <input type="submit" class="btn" value="Upload" class="bg-info">
                    </div>
                    <div class="col"></div>
                </div>
            </form>
            <div class="row">
                <div class="col"></div>
                <div class="col"></div>
                <div class="col-6">
                    <input type="button" class="json-btn btn-info" value="Read JSON">
                </div>
                <div class="col"></div>
            </div>
        </div>
</body>
<?php 
    if (isset($_POST["w"]) || isset($_GET["w"])){
        if(isset($_POST["w"]))
        {
            $winner = (isset($_POST["w"])) ? $_POST["w"] : false;
            $loser = (isset($_POST["l"])) ? $_POST["l"] : false;
            $wins = (isset($_POST["wins"]) || $_POST["wins"] == 0) ? $_POST["wins"] : false;
            $losses = (isset($_POST["losses"]) || $_POST["losses"] == 0) ? $_POST["losses"] : false;
        }

        if(isset($_GET["w"]))
        {
            $winner = (isset($_GET["w"])) ? $_GET["w"] : false;
            $loser = (isset($_GET["l"])) ? $_GET["l"] : false;
            $wins = (isset($_GET["wins"]) || $_GET["wins"] == 0) ? $_GET["wins"] : false;
            $losses = (isset($_GET["losses"]) || $_GET["losses"] == 0) ? $_GET["losses"] : false;
        }

        if(!$winner) die("No winner given!");
        if(!$loser) die("No loser given!");
        if($wins === false) die("No wins given!");
        if($losses === false) die("No losses given!");

        echo("winner = " . $winner . "<br>loser = " . $loser);
        echo("<br>wins = " . $wins . "<br>losses = " . $losses);

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

        $winnerK = 0;
        $loserK = 0;
        $winnerELO = 0;
        $loserELO = 0;

        // Find player id and elo
        $result = $con->query("SELECT id, elo, placement FROM players WHERE tag='" . $winner . "'");
        if ($row = mysqli_fetch_assoc($result)){
            $winnerK = $row["id"];
            $winnerELO = $row["elo"];
            $winnerPlacement = $row["placement"];
        }
        else{
            die("Winner not found!");
        }

        $result = $con->query("SELECT id, elo, placement FROM players WHERE tag='" . $loser . "'");
        if ($row = mysqli_fetch_assoc($result)){
            $loserK = $row["id"];
            $loserELO = $row["elo"];
            $loserPlacement = $row["placement"];
        }
        else{
            die("Loser not found!");
        }

        // INSERT Set
        $query = "INSERT INTO `matches` VALUES (0, '";
        $query2 = $query . $winnerK . "','" . $loserK . "','" . $wins . "','" . $losses ."')";
        if($con->query($query2)){
            echo "<h1>Insert success</h1>";
        }
        else{
            die("<br><h1>Insert Fail: " . $query2 . "<br>" . $con->error . "</h1>");
        }

        // Calculate ELO changes
        $K = 16;
        $R = false;
        

        // Optional modes
        if(isset($_GET["t"]) && $_GET["t"] == "true"){$K = 24;} // Tournaments are worth 1.5x
        if(isset($_POST["t"]) && $_POST["t"] == "true"){$K = 24;}
        if(isset($_POST["r"]) && $_POST["r"] == "true"){$K = 32; $R = true;} // Rivals are worth 2x to win, 1x to lose
        if(isset($_GET["r"]) && $_GET["r"] == "true"){$K = 32; $R = true;}

        // Placement Scaling
        // During placement period, matches are worth double
        $wK = $K;
        $lK = $K;
        if ($winnerPlacement > 0) {
            $wK = $wK + $K;
            $placementQuery = "UPDATE players SET placement=" . ($winnerPlacement - 1) . " WHERE id=" . $winnerK;
            $winnerPlacement--;
            if($con->query($placementQuery)){
                echo("<br><h2>" . $winner . "'s Placement updated!</h2>");
            }
            else{
                die("<br><h2>" . $winner . "'s Placement update failed!</h2>");
            }
        }
        if ($loserPlacement > 0) {
            $lK = $lK * 3;
            $placementQuery = "UPDATE players SET placement=" . ($loserPlacement - 1) . " WHERE id=" . $loserK;
            if($con->query($placementQuery)){
                echo("<br><h2>" . $loser . "'s Placement updated!</h2>");
            }
            else{
                die("<br><h2>" . $loser . "'s Placement update failed!</h2>");
                $fixWinner = "UPDATE players SET placement=" . $winnerPlacement . " WHERE id=" . $winnerK;
                $con->query($fixWinner);
            }
        }

        $ex1 = $winnerELO / 300.0;
        $ex2 = $loserELO / 300.0;
        $r1 = pow(10 , $ex1);
        $r2 = pow(10 , $ex2);

        $e1 = $r1 / ($r1 + $r2);
        $e2 = $r2 / ($r2 + $r1);

        $s1 = $wins - $losses;

        if($loserELO <= 1200){
            $winnerNew = $winnerELO + (2 * $s1);
        }
        else{
            if($winnerELO < 1600){
                $winnerNew = floor($winnerELO + ($wK * (1 - $e1) * 1.1) * $s1);
            }
            else{
                $winnerNew = floor($winnerELO + ($wK * (1 - $e1)) * $s1);
            }
        }

        if($R){$K = 16;} // Rivals are worth 2x to win, 1x to lose
        if($loserELO <= 1200){
            $loserNew = 1200;
        }
        else if($loserELO < 1600){
            $loserNew = ceil($loserELO + ($lK * (0 - $e2) * 0.9) * $s1);
        }
        else{
            $loserNew = ceil($loserELO + ($lK * (0 - $e2)) * $s1);
        }

        echo("<br>winnerELO = " . $winnerELO . " winnerNew = " . $winnerNew . "<br>loserELO = " . $loserELO . " loserNew = " . $loserNew);

        // UPDATE change old elo to new elo
        $winQuery = "UPDATE players SET elo=" . $winnerNew . " WHERE id=" . $winnerK;
        if($con->query($winQuery)){
            echo("<br><h2>" . $winner . "'s ELO updated!</h2>");
        }
        else{
            die("<br><h2>" . $winner . "'s update failed!</h2>");
        }
        
        $loseQuery = "UPDATE players SET elo=" . $loserNew . " WHERE id=" . $loserK;
        if($con->query($loseQuery)){
            echo("<br><h2>" . $loser . "'s ELO updated!</h2>");
        }
        else{
            echo("<br><h2>" . $loser . "'s update failed!</h2>");
            $fixWinner = "UPDATE players SET elo=" . $winnerELO . " WHERE id=" . $winnerK;
            $con->query($fixWinner);
        }

        $con->close();
    }

?>
</html>