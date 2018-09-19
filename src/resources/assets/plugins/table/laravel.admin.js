(function ($) {
    $.extend($.fn.meTables, {
        // 按钮添加修改掉
        buttonsCreate: function (index, data, unique) {
            unique = unique || "";
            var html = "", attr = "data-row=\"" + index + "\"";
            // 添加按钮信息
            if (!$.isEmptyObject(data)) {
                for (var i in data) {
                    if ($.isEmptyObject(data[i])) {
                        continue;
                    }

                    var class_list = [
                        "btn btn-xs",
                        data[i]['className'],
                        data[i]['operationClass'] + "-" + unique
                    ];
                    html += "<button class=\"" + class_list.join(" ") + "\" " + attr + ">" +
                        "                <i class=\"fa " + data[i]['icon'] + "\"></i></button> ";
                }
            }

            return html;
        },
        /**
         * vue 处理
         * @param params
         * @returns {string}
         */
        vueUploadCreate: function (params) {
            params.type = "hidden";
            return '<div id="vue-upload"><el-upload\n' +
                '            :headers="headers" ' +
                '            :limit="limit" ' +
                '            :disabled="disabled" ' +
                '            name="vue_image" ' +
                '            class="upload-demo"\n' +
                '            action="' + getValue(params, 'action') + '"\n' +
                '            :on-remove="remove" ' +
                '            :on-success="success"' +
                '            :file-list="list"\n' +
                '            list-type="picture">\n' +
                '        <el-button size="small" type="primary"> 点击上传 </el-button>\n' +
                '        <div slot="tip" class="el-upload__tip"> 只能上传jpg/png文件，且不超过2M </div>\n' +
                '    </el-upload>' + this.inputCreate(params) + '</div>';
        },

        /**
         * 中间文本搜索
         *
         * @param params
         * @returns {string}
         */
        textSearchMiddleCreate: function (params) {
            params["id"] = "search-" + params.name;
            params.placeholder = params.placeholder || params.title || params.name;
            params.class = params.class || "form-control";
            return '<div class="input-group input-group-sm"> ' + this.inputCreate(params) + ' </div> ';
        },

        /**
         * 中间多选搜索
         *
         * @param params
         * @param value
         * @param defaultObject
         * @returns {string}
         */
        selectSearchMiddleCreate: function (params, value, defaultObject) {
            params["id"] = "search-" + params.name;
            params.class = params.class || "form-control";
            return '<div class="input-group input-group-sm"> ' + this.selectInput(params, value, defaultObject) + '</div> ';
        },

        // 搜索表单
        searchFormCreate: function (html, params) {
            html += '<div class="input-group input-group-sm">\n' +
                '     <div class="input-group-btn">\n' +
                '           <button type="submit" class="btn btn-info">\n' +
                '                <i class="fa fa-search"></i>\n' +
                '                     ' + $.getValue($.fn.meTables.language, "meTables.search") +
                '           </button>\n' +
                '     </div>\n' +
                '</div>\n' +
                '<div class="input-group input-group-sm">\n' +
                '     <div class="input-group-btn">\n' +
                '          <button type="reset" class="btn btn-warning">\n' +
                '               ' + $.getValue($.fn.meTables.language, "meTables.clear") +
                '          </button>\n' +
                '     </div>\n' +
                '</div>';
            return '<form ' + this.handleParams(params) + '>' + html + '</form>';
        },

        /**
         * 覆盖单选按钮
         * @param params
         * @param d
         * @returns {string}
         */
        radioCreate: function (params, d) {
            html = "";
            if (d && this.isObject(d)) {
                params['class'] = "ace valid";
                var c = params.default;
                params = this.handleParams(params);
                for (i in d) {
                    html += '<label class="line-height-1 blue"> ' +
                        '<input type="radio" ' + params + (c == i ? ' checked="checked" ' : "") + ' value="' + i + '"  /> ' +
                        '<span class="lbl"> ' + d[i] + " </span> " +
                        "</label>　 "
                }
            }

            return html;
        },

        /**
         * 覆盖多选
         * @param params
         * @param d
         * @returns {string}
         */
        checkboxCreate: function (params, d) {
            html = '';
            if (d && this.isObject(d)) {
                var o = params.all;
                delete params.all;
                delete params.divClass;
                params["class"] = "ace m-checkbox";
                params = this.handleParams(params);
                if (o) {
                    html += '<div class="checkbox">' +
                        '<label>' +
                        '<input type="checkbox" class="ace checkbox-all" onclick="var isChecked = $(this).prop(\'checked\');$(this).parent().parent().parent().find(\'input[type=checkbox]\').prop(\'checked\', isChecked);" />' +
                        '<span class="lbl"> ' + meTables.fn.getLanguage("sSelectAll") + ' </span>' +
                        '</label>' +
                        '</div>';
                }
                for (i in d) {
                    html += '<div class="checkbox">' +
                        '<label>' +
                        '<input type="checkbox" ' + params + ' value="' + i + '" />' +
                        '<span class="lbl"> ' + d[i] + ' </span>' +
                        '</label>' +
                        '</div>';
                }
            }

            return html;
        },

        /**
         * 颜色选择
         * @param params
         * @returns {string}
         */
        colorCreate: function (params) {
            return '<input type="hidden" name="style" value="#3c8dbc" id="style-input">\
                                <ul class="fc-color-picker color-chooser" id="style-select">\
                                    <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>\
                                </ul>';
        },

        statusRender: function (data) {
            var c = parseInt(data) === 1 ? "green" : "red";
            return '<span style="color:' + c + '">' + (data === 1 ? "启用" : "禁用") + '</span>';
        }
    });

    // 修改默认配置
    $.fn.meTables.defaults.table.dom = "t<'row'<'table-page col-sm-4'li><'col-sm-8'p>>";
    $.fn.meTables.defaults.url.create = 'store';
    $.fn.meTables.defaults.url.delete = 'destroy';
    $.fn.meTables.defaults.number = null;
    $.fn.meTables.defaults.checkbox = null;
    $.extend($.fn.meTables.defaults, {
        /**
         * 搜索表单
         * @param self
         */
        searchMiddleHandle: function (self) {
            var name = self.options.searchForm.replace("#", "").replace(".", "");
            $("#me-table-search-form-" + self.options.unique).append($.fn.meTables.searchFormCreate(self.options.searchHtml, {
                "id": name,
                "name": name,
                "class": "form-inline pull-right"
            }));
        }
    });
})(jQuery);