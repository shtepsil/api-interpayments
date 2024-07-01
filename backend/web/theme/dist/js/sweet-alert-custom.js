/**
 * Плагин, написан как оболочка на существующий плагин Growl,
 * для удобства использования со своими переменными.
 */
// Wrap class GrowlCustomObject plugin
// ------------------------------
class GrowlCustomObject {
    constructor(message, title, duration, style) {
        this.options = {
            // title: 'Заголовок',
            message: 'Текст оповещения',
            duration: 5000,
            style: 'notice',
        };

        if (typeof message !== 'undefined') {
            this.options.message = message;
        }
        if (typeof duration !== 'undefined') {
            this.options.duration = duration;
        }
        if (typeof title !== 'undefined') {
            if (Number.isInteger(title)) {
                this.options.duration = title;
            } else {
                this.options.title = title;
            }
        }
        if (typeof style !== 'undefined') {
            this.options.style = style;
        }

        return false;
    }

    get() {
        return this.options;
    }
} //Class GrowlCustomObject

const sw = {
    notice: function (message, title, duration) {
        var options = new GrowlCustomObject(
            message,
            title,
            duration,
            'notice'
        ).get();
        $.growl(options);
    },
    warning: function (message, title, duration) {
        var options = new GrowlCustomObject(
            message,
            title,
            duration,
            'warning'
        ).get();
        $.growl(options);
    },
    error: function (message, title, duration) {
        var options = new GrowlCustomObject(
            message,
            title,
            duration,
            'error'
        ).get();
        $.growl(options);
    },
    confirm: function (message, title, btnConfirmText, btnCancelText) {},
};

// === END Toast ===========================================
