/**
 * Плагин, написан как оболочка на существующий плагин Growl,
 * для удобства использования со своими переменными.
 */
// Wrap class JqueryToast plugin
// ------------------------------
class JqueryToast {
    constructor(message, title, hideAfter, style) {
        this.options = {
            text: 'Текст уведомления',
            icon: 'info',
            loaderBg: '#177ec1',
            position: 'top-right',
            hideAfter: 3000,
            stack: 6,
        };

        if (typeof message !== 'undefined') {
            this.options.text = message;
        }
        if (typeof hideAfter !== 'undefined') {
            this.options.hideAfter = hideAfter;
        }
        if (typeof title !== 'undefined') {
            if (Number.isInteger(title)) {
                this.options.hideAfter = title;
            } else {
                this.options.heading = title;
            }
        }
        if (typeof style !== 'undefined') {
            this.options.icon = style.type;
            this.options.loaderBg = style.bg;
        }

        return false;
    }

    get() {
        return this.options;
    }
} //Class JqueryToast

const t = {
    info: function (message, title, hideAfter) {
        const options = new JqueryToast(message, title, hideAfter, {
            type: 'info',
            bg: '#1EA5FF', // #177ec1 - фон блока
        }).get();
        $.toast(options);
    },
    success: function (message, title, hideAfter) {
        const options = new JqueryToast(message, title, hideAfter, {
            type: 'success',
            bg: '#5FC50B', // #469408 - фон блока
        }).get();
        $.toast(options);
    },
    warning: function (message, title, hideAfter) {
        const options = new JqueryToast(message, title, hideAfter, {
            type: 'warning',
            bg: '#E37A00', // #e69a2a - фон блока
        }).get();
        $.toast(options);
    },
    error: function (message, title, hideAfter) {
        const options = new JqueryToast(message, title, hideAfter, {
            type: 'error',
            bg: '#dc4666',
        }).get();
        $.toast(options);
    },
};

// === END JqueryToast ===========================================
