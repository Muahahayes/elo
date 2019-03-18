var jLength;
var jsonMatches;
$().ready(setClicks);

function setClicks(){
    $('.modal-btn').on('click', modalClick);
    $('.json-btn').on('click', updateClick);
    $('.rivals').on('click', rivalsClick);
}

function rivalsClick(){
    $.getJSON('players.php?players=rivals', function(json){
        var players = json.slice();
        var rivalsStr = ``;
        var i = 0;
        var rivals = [];
        console.log(json);
        while(players.length > 1){
            i = Math.ceil(Math.random() * players.length) - 1;
            rivals[0] = players.splice(i, 1);
            i = Math.ceil(Math.random() * players.length) - 1;
            rivals[1] = players.splice(i, 1);
            rivalsStr += `<tr class="td-row"><td>${rivals[0]}</td><td> VS </td><td>${rivals[1]}</td> </tr>`;
        }
        if(players.length == 1){
            i = Math.ceil(Math.random() * json.length) - 1;
            rivals[0] = players[0];
            rivals[1] = json[i];
            while(rivals[0] == rivals[1]){
                i = Math.ceil(Math.random() * json.length) - 1;
                rivals[1] = json[i];
            }
            rivalsStr += `<tr class="td-row"><td>${rivals[0]}</td><td> VS </td><td>${rivals[1]}</td> </tr>`;
        }
        $('#rivals').html(rivalsStr);
    });
}


//json has w,l,wins,losses
//assign jLength with json's length
//call insertMatch
//do a GET to update.php with match details
//--jLength
//if jLength > 0, call insertMatch again
function updateClick(){
    $.getJSON('matches.json', function(json){
        console.log(json);
        jLength = json.length;
        jsonMatches = json;
        if (jLength > 0) {insertMatch();}
    });
}

function insertMatch(){
    var games = jsonMatches[jLength - 1];
    console.log(`Inserting ${games}`);
    $.post("update.php",games, function(data, status){
        --jLength;
        if(jLength > 0){
            insertMatch();
        }
    })
}

function modalClick(){
    var playerID = $(this).attr('id');
    $.getJSON(`json.php?p=${playerID}`, function(json){
        buildModal(json);
        $('.modal').modal('toggle');
    });
}

function buildModal(json){
    var matches ={};
    for(let player of json[0]){
        if(matches.hasOwnProperty(player)){
            matches[player].wins++;
        }
        else{
            $.extend(matches, { [player] : {'name': player, 'wins': 1, 'losses': 0}});
        }
    }
    for(let player of json[1]){
        if(matches.hasOwnProperty(player)){
            matches[player].losses++;
        }
        else{
            $.extend(matches, { [player] : {'name': player, 'wins': 0, 'losses': 1}});
        }
    }
    console.log(matches);
    var modalStr = `<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${json[2]}'s Match History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">`
    for(let match in matches){
        if(match == "No Wins" || match == "No Losses"){
            modalStr += `<p><b>${match}</b></p>`;
        }
        else{
            modalStr += `<p>${match}: ${matches[match].wins} - ${matches[match].losses}</p>`;
        }
    }
    modalStr +=`</div></div></div></div>`;
    $('#modals').html(modalStr);
}
