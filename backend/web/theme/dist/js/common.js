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
