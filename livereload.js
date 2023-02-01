function checkForChanges() {
    fetch('/wp-content/plugins/theme_livereload/check.php', {
        cache: 'no-cache'
    }).then(function (response) {
        if (response.status === 200) {
            response.text().then(function (timestamp) {
                if (sessionStorage.livereloadLastTimestamp != timestamp) {
                    console.debug('Change detected, reloading...');
                    sessionStorage.livereloadLastTimestamp = timestamp;
                    location.reload();
                }
            });
        }
    });
}

setInterval(checkForChanges, 1000);
