window.addEventListener("message", receivePlugzMessage, false);
console.log('event listener added.');


function receivePlugzMessage(event) {
    var origin = event.origin || event.originalEvent.origin;
    if (!(origin === "http://www.plugz" || origin === "https://www.plugz.co")) {
        console.log('origin mismach: '+origin);
        return;
    }
    
    $('iframe').css('height', event.data.details.height);
    console.log('received height: '+event.data.details.height);
}