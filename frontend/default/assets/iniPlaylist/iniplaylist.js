

function audioPlaylist(ele, playBox, track, playerID, playlistID) {
    $('#' + playerID).attr('src', track);
    document.getElementById(playerID).play();
    $('#' + playlistID).find('li.active').removeClass('active');
    $(ele).parent().addClass('active'); 
    var trackName = $(ele).find('div.item__title').text();
    $('#'+playBox).find('span.npTitle').text(trackName);
}


function videoPlaylist(ele, playBox, track, playerID, playlistID) {
    $('#' + playerID).attr('src', track);
    document.getElementById(playerID).play();
    $('#' + playlistID).find('li.active').removeClass('active');
    $(ele).parent().addClass('active'); 
}
