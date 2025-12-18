(function ($) {
    // Initialize the htimeline container: bind events and classes
    function initTimeline(target) {
        var options = $.data(target, "htimeline").options;
        $(target).addClass("htimeline-container");

        // Click handler for items
        $(target).off(".htimeline").on("click.htimeline", ".htimeline-item", function () {
            var idx = $(this).index(".htimeline-item");
            options.onClick.call(target, options.data[idx]);
        });

        // Resize handling (EasyUI fluid support)
        $(target)._bind("_resize", function (e, param) {
            if ($(this).hasClass("easyui-fluid") || param) {
                updateSize(target);
            }
            return false;
        });
    }

    // Update sizing for the htimeline container
    function updateSize(target, size) {
        var options = $.data(target, "htimeline").options;
        var $t = $(target);
        if (size) {
            $.extend(options, { width: size.width, height: size.height });
        }
        $t._size(options, $t.parent());
    }

    // Render htimeline items from data
    function renderData(target, data) {
        var options = $(target).htimeline("options");
        options.data = data;
        $(target).empty();

        var ul = $("<ul class=\"htimeline\"></ul>").appendTo(target);
        var hasLabel = false;
        for (var i = 0; i < options.data.length; i++) {
            var row = options.data[i];
            var li = $("<li class=\"htimeline-item\"></li>").appendTo(ul);
            // Optional label
            var labelHtml = options.labelFormatter.call(target, row);
            if (labelHtml) {
                $("<div class=\"htimeline-item-label\"></div>").html(labelHtml).appendTo(li);
                hasLabel = true;
            }
            // Line and dot elements
            $("<div class=\"htimeline-item-line\"></div>").appendTo(li);
            var dot = $("<div class=\"htimeline-item-dot\"></div>").appendTo(li);

            var dotHtml = options.dotFormatter.call(target, row);
            if (dotHtml) {
                dot.addClass("htimeline-item-dot-custom").html(dotHtml);
            }
            // Content
            var content = $("<div class=\"htimeline-item-content\"></div>").appendTo(li);
            var html = options.formatter.call(target, row);
            if(html){
                content.html(html);
            }
        }
        if (hasLabel) {
            ul.addClass("htimeline-label");
        }
    }
    $.fn.htimeline = function (options, parameter) {
        if (typeof options == "string") {
            return $.fn.htimeline.methods[options](this, parameter);
        }
        options = options || {};
        return this.each(function () {
            var domData = $.data(this, "htimeline");
            if (domData) {
                $.extend(domData.options, options);
            } else {
                domData = $.data(this, "htimeline", { options: $.extend({}, $.fn.htimeline.defaults, $.fn.htimeline.parseOptions(this), options) });
                initTimeline(this);
            }
            updateSize(this);
            renderData(this, domData.options.data);
        });
    };
    $.fn.htimeline.methods = {
        options: function (jq) {
            return $.data(jq[0], "htimeline").options;
        }, 
        loadData: function (jq, data) {
            return jq.each(function () {
                renderData(this, data);
            });
        }
    };
    $.fn.htimeline.parseOptions = function (target) {
        var t = $(target);
        return $.extend({}, $.parser.parseOptions(target, []));
    };
    $.fn.htimeline.defaults = {
        width: "auto", 
        height: "auto",
        data: [],
        value: [], 
        dotFormatter: function (row) {
            if(!row.highlighted){
                return null;
            }
            return '<span class="fa fa-circle text-success"></span>';
        }, 
        labelFormatter: function (row) {
            if(!row.highlighted || !row["label"]){
                return row["label"];
            }
            return '<strong>' + row['label'] + '</strong>';
        }, 
        formatter: function (row) {
            if(!row.highlighted || !row["content"]){
                return row["content"];
            }
            return '<strong>' + row['content'] + '</strong>';
        }, 
        onClick: function (row) {
        }
    };
    $.parser.plugins.push('htimeline');
})(jQuery);

