document.addEventListener("DOMContentLoaded", function() {
    const playlistBTN = document.getElementById('showPlaylists');
    const trackBTN = document.getElementById("showTracks");
    const backBTN = document.getElementById("backBTN");

    const playlistTrackCont = document.getElementById('playlistTrackCont');
    const contentCont = document.getElementsByClassName("container-fluid");
    const allTracksCont = document.getElementsByClassName("allTracksContainer");
    const allPlaylistCont = document.getElementsByClassName("allPlaylistContainer");
    const artistCont = document.getElementById("artistContainer"); 

    playlistBTN.addEventListener('click',function (event) {
        playlistTrackCont.classList.add("d-none");
        artistCont.classList.add("d-none");
        contentCont[0].classList.remove("d-none");
        allPlaylistCont[0].classList.remove("d-none");
        allPlaylistCont[0].classList.add("d-grid");
    })

    trackBTN.addEventListener('click',function (event) {
        playlistTrackCont.classList.add("d-none");
        artistCont.classList.add("d-none");
        contentCont[0].classList.remove("d-none");
        allTracksCont[0].classList.remove("d-none");
    })

    backBTN.addEventListener('click', function(event) {
        playlistTrackCont.classList.remove("d-none");
        artistCont.classList.remove("d-none");
        contentCont[0].classList.add("d-none");
        allPlaylistCont[0].classList.add("d-none");
        allTracksCont[0].classList.add("d-none");
    })
});