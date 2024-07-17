const cl = console.log;
window.rand = (numberCharacters) => {
    if (!numberCharacters) numberCharacters = 11;
    return Math.random().toString(36).substr(2).substr(0, numberCharacters); // remove `0.`
};
window.createBearerToken = () => {
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
};

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
window.copyToClipboard = (text) => {
    if (typeof text === 'undefined') text = '';
    const copytext2 = document.createElement('textarea');
    copytext2.value = text;
    document.body.appendChild(copytext2);
    copytext2.select();
    document.execCommand('copy');
    document.body.removeChild(copytext2);
};

window.res = (data) => {
    let result = $('.res');
    result.html('');
    if (typeof data !== 'undefined') {
        if (data == 'result') {
            result.html(data);
        } else {
            result.html('<pre>' + prettyPrintJson.toHtml(data) + '</pre>');
        }
    }
};

$(document).ready(() => {
    $('.no-link').on('click', (e) => {
        e.preventDefault();
    });
});

// Open bootstrap modals
$('[data-bs-target]').on('click', function () {
    const _this = $(this),
        modalTarget = _this.attr('data-bs-target');
    $(modalTarget).modal('show');
});
// Close bootstrap modals
$('[data-dismiss]').on('click', function () {
    const _this = $(this),
        modalTarget = _this.attr('data-dismiss');
    $(modalTarget).modal('hide');
});

// Send
window.send = (url, params, details) => {
    const Params = {},
        param = yii.getCsrfParam(),
        token = yii.getCsrfToken();

    Params[param] = token;
    params = Object.assign(Params, params);
    return new Promise((resolve, reject) => {
        res('result');
        if (url) {
            if (details) {
                cl({ url: url, params: params });
            }
            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'json',
                data: Params,
                beforeSend: function () {
                    loader.show();
                },
            })
                .done(function (data) {
                    resolve(data);
                })
                .fail(function (data) {
                    reject(data);
                })
                .always(function () {
                    loader.hide();
                });
        } else {
            reject('Не передан url');
        }
    });
};
