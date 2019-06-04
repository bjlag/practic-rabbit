'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            let display = document.getElementById('api-display');

            try {
                let data = JSON.parse(xmlHttp.responseText);
                display.innerText = 'Using "' + data['name'] + '", version ' + data['version'];
            } catch (e) {
                console.log(e.message + ' in ' + xmlHttp.responseText);
                display.innerText = 'Unable to connect to API: ' + e.message;
            }
        }
    };

    xmlHttp.open('GET', '/api', true);
    xmlHttp.send();
});