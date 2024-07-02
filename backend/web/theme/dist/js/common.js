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

$(document).ready(() => {
    $('.no-link').on('click', (e) => {
        e.preventDefault();
    });
});
