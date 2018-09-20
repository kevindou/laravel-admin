function empty(value) {
    return value === undefined || value === null || value === "";
}

// 设置ajax
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
    }
});

var mixLoading = null;

/**
 * ajax 自定义的ajax 处理
 * @param params
 * @param message
 * @returns {*}
 */
function ajax(params, message) {
    mixLoading = layer.load();
    return $.ajax(params)
        .always(function () {
            layer.close(mixLoading);
        }).fail(function () {
            layer.msg(message, {icon: 5});
        });
}

/**
 * 发送laravel 的请求
 * @param params
 * @param message
 * @returns {*}
 */
function getLaravelRequest(params, message) {
    mixLoading = layer.load();
    return $.ajax(params)
        .always(function () {
            layer.close(mixLoading);
        }).fail(function (response) {
            var html = '';
            if (response.responseJSON) {
                html += response.responseJSON.message + " <br/>";
                for (var i in response.responseJSON.errors) {
                    html += response.responseJSON.errors[i].join(";") + "<br/>";
                }
            } else {
                html = message;
            }

            layer.msg(html, {icon: 5});
        });
}

/**
 * 获取值
 *
 * @return mixed
 * @param arrValue
 * @param key
 * @param defaultValue
 */
function getValue(arrValue, key, defaultValue) {
    if (typeof key === "string") {
        var index = key.lastIndexOf(".");
        if (key.lastIndexOf(".") !== -1) {
            arrValue = $.getValue(arrValue, key.substr(0, index), defaultValue);
            key = key.substr(index + 1);
        }
    }

    if (!arrValue) {
        return defaultValue;
    }

    if (key in arrValue) {
        return arrValue[key];
    }

    return arrValue[key] ? arrValue[key] : defaultValue;
}

function vueUpload(select)
{
    return new Vue({
        el: "#vue-upload",
        data: {
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            list: [],
            limit: 1,
            disabled: false,
        },
        methods: {
            success(response, file) {
                if (response.code === 0) {
                    this.$message.success('上传成功');
                    $(select).val(response.data.url);
                } else {
                    this.$message.error('上传失败：' + response.message);
                    this.$children[0].clearFiles();
                }
            },
            remove: function () {
                $(select).val('');
            }
        }
    });
}
