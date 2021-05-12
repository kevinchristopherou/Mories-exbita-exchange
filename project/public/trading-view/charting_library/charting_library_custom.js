function getParameterByName(name) {
    "use strict";
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getTzByTimezone() {
    "use strict";
    var timezones = {
        "-600": "Pacific/Honolulu",
        "-420": "America/Los_Angeles",
        "-360": "America/El_Salvador",
        "-300": "America/Bogota",
        "-240": "America/New_York",
        "-180": "America/Sao_Paulo",
        "0": "",
        "60": "Europe/London",
        "120": "Europe/Berlin",
        "180": "Europe/Athens",
        "240": "Asia/Dubai",
        "270": "Asia/Tehran",
        "300": "Asia/Ashkhabad",
        "330": "Asia/Kolkata",
        "360": "Asia/Almaty",
        "420": "Asia/Bangkok",
        "480": "Asia/Singapore",
        "540": "Tokyo",
        "570": "Australia/Adelaide",
        "600": "Australia/Sydney",
        "720": "Pacific/Auckland",
        "765": "Pacific/Chatham",
        "780": "Pacific/Fakaofo",
    };

    var offset = new Date().getTimezoneOffset() * -1;
    var utc = offset.toString();
    return timezones[utc] ? timezones[utc] : '';
}
