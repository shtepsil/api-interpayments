const cl = console.log;
function rand(numberCharacters) {
    if (!numberCharacters) numberCharacters = 11;
    return Math.random().toString(36).substr(2).substr(0, numberCharacters); // remove `0.`
}
function createBearerToken() {
    return (
        rand(8) +
        '-' +
        rand(4) +
        '-' +
        rand(4) +
        '-' +
        rand(4) +
        '-' +
        rand(8)
    ).toUpperCase();
}

const loader = {
    element: $('.wrap-loader-global'),
    show(duration) {
        if (!duration) duration = 200;
        this.element.animate({ right: '-32px' }, duration);
    },
    hide(duration) {
        if (!duration) duration = 200;
        this.element.animate({ right: '-200px' }, duration);
    },
};

// Копирование в буфер обмена windows
function copyToClipboard(text) {
    if (typeof text === 'undefined') text = '';
    var copytext2 = document.createElement('textarea');
    copytext2.value = text;
    document.body.appendChild(copytext2);
    copytext2.select();
    document.execCommand('copy');
    document.body.removeChild(copytext2);
}

$(document).ready(() => {
    $('.no-link').on('click', (e) => {
        e.preventDefault();
    });
});
