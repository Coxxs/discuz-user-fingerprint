/*!
 * User Fingerprint Discuz! X plugin <https://github.com/ganlvtech/discuzx-user-fingerprint>
 * Copyright (C) 2018 Ganlv <https://github.com/ganlvtech>
 * License: GPL 3.0
 */
!function () {
    function send(murmur) {
        var scriptElement = document.createElement('script');
        scriptElement.src = '/plugin.php?id=user_fingerprint&fingerprint=' + murmur;
        document.getElementsByTagName('head')[0].appendChild(scriptElement);
    }

    function fingerprintReport() {
        Fingerprint2.get(function (components) {
            var murmur = Fingerprint2.x64hash128(components.map(function (pair) {
                if (pair.key === 'canvas') {
                    if (pair.value instanceof Array) {
                        pair.value.pop();
                    }
                }
                return pair.value;
            }).join(), 31);
            send(murmur);
        });
    }

    if (window.requestIdleCallback) {
        requestIdleCallback(fingerprintReport);
    } else {
        setTimeout(fingerprintReport, 2000);
    }
}();
