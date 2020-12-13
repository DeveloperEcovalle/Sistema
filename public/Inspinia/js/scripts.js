function isNumber(e) {
    var keynum = (!Window.event) ? e.which : e.keyCode;
    return !((keynum === 8 || keynum === undefined || e.which === 0) ? null : String.fromCharCode(keynum).match(/[^0-9\-]/g));
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
