function rssPlayer() {
    let audioPlayers = document.querySelectorAll('.episode-audio');
    audioPlayers.forEach(audioPlayer => {
        audioPlayer.setAttribute('class', 'gap');
        audioPlayer.classList.add('gap-enabled');
        // Remove controls from audio element
        audioPlayer.getElementsByTagName('audio')[0].removeAttribute('controls');
    });
// Init Green Audio Player on all .gap elements
    GreenAudioPlayer.init({
        selector: '.gap-enabled',
        stopOthersOnPlay: true
    });
    audioPlayers.forEach(audioPlayer => {
        audioPlayer.classList.remove('gap-enabled');
    });
}

rssPlayer();
