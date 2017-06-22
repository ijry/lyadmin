/**
 * bootstrap_tags
 * Bootstrap的input转换成tag形式输入
 *
 * @author      http://github.com/ijry/
 * @copyright   (c) 20161107 jry
 * @license     Apachw2
 * @version     0.0.1
 */
'use strict'
;(function ($) {
    var Tags = function (elems) {
        elems = elems || '';
        if (!elems) {
            return;
        };

        var self = this;
        elems.each(function (key, elem) {
            if ($(elem).parent('div.form-tags')[0]) {
                return;
            };
            $(elem).wrap($('<div class="form-tags"></div>')).hide();
            var input = $('<input class="input-tags form-control" />').insertAfter($(elem));
            self.bind(input);
        });
    };

    Tags.prototype = {
        set: function (elems, values) {
            self = $(elems);
            var Tags = this;
            elems.each(function (key, elem) {
                for (var key in values) {
                    var tag = $('<span class="label label-info">' + values[key] + '</span>');
                    $(elem).after(tag);
                    tag.click(click);
                    Tags.value(tag);
                };
            });
        },
        bind: function (elem) {
            var Tags = this;
            elem.keypress(function (event) {
                self = $(this);
                var keycode = (event.keyCode ? event.keyCode : event.which);
                //enter key
                if (keycode != '13') {
                    return;
                };
                var text = $(this).val();
                if (!text) {
                    return;
                };
                var tag = $('<span class="label label-info">' + text + '</span>');
                self.before(tag).val('');
                tag.click(click);
                Tags.value(elem);

            });
        },
        value: function (elem) {
            var hideInput = elem.prevAll('input');
            var tags = elem.parent().children('span.label');
            var values = [];
            tags.each(function (key, tag) {
                values.push($(tag).text());
            });
            return hideInput.val(values);
        }
    };

    var click = function () {
        var tag = $(this);
        var text = tag.text();
        var input = $('<input class="form-control input-tags" type="text" />');
        tag.after(input).hide();

        input.val(text)
        .focus()
        .blur(function () {
            finish(tag, input);
        })
        .keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                finish(tag, input);
            };
        });
    }
    var finish = function (tag, input) {
        var text = input.val();
        var elem = tag.next('input.input-tags');
        if (text) {
            tag.text(text).show();
        } else {
            tag.remove();
        }
        new Tags().value(elem);
        input.remove();
        elem.focus();
    };

    $.fn.tags = function (values = '') {
        var tags = new Tags(this);
        if (values.length > 0) {
            tags.set(this, values);
        }
        return this;
    };

})(jQuery);

$(function () {
    $('head').append($('<style>.form-tags span, .input-tags {padding: .3em .6em .3em; display: inline-block;font-size: 14px;margin: 4px 4px 0 0;line-height: 26px;font-weight: normal;}.input-tags {width: auto;}</style>'));
    var $tmp = $('[data-toggle="tags"]');
    $tmp.each(function(index, el) {
        var val = $(this).attr('data-value');
        if (val) {
            $(this).tags(val.split(","));
        } else {
            $(this).tags();
        }
    });
});
