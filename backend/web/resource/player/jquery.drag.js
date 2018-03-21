/**
 * drag for jQuery
 *
 * author: harry313
 * e-mail: harry313@163.com
 *
 * Version: 1.0.6
 */

(function($) {

	var isie = navigator.userAgent.indexOf('MSIE') > -1;
	var ctarget, copts, dx, dy, moving;

	//默认参数设置
	var defaultOpts = {
		parent: document,			//父级容器
		//handle: null,				//拖拽区域
		onmove: function(e, x, y) {			//拖拽处理函数
			this.css({
				left: x,
				top: y
			});
		},
		onstart: null,			//拖拽开始回调函数
		onfinish: null			//拖拽完成回调函数
	};

	$.fn.drag = function(opts) {
		if (this.data("undrag")) return;

		opts = $.extend({handle: this}, defaultOpts, opts || {});

		//调整位置
		if (this.css("position") == "static") this.css("position", "relative");
		if (isNaN(parseInt(this.css("left")))) this.css("left", 0);
		if (isNaN(parseInt(this.css("top")))) this.css("top", 0);

		//防止拖拽时选中文本
		var h = $(opts.handle);
		var dcursor = h.css("cursor");
		h.css("cursor", "move");

		var ondown = down(opts, this);
		h.mousedown(ondown);

		//取消拖拽函数
		this.data("undrag", function() {
			h.unbind("mousedown", ondown)
				.css("cursor", dcursor);
			this.removeData("undrag");
		});
		return this;
	};

	$.fn.undrag = function() {
		var undrag = this.data("undrag");
		undrag && undrag.call(this);
		return this;
	};
	
	//鼠标按下时记录鼠标相对位置
	function down(opts, target) {
		return function(e) {
			if (moving) return;

			dx = e.clientX - parseInt(target.css("left"));
			dy = e.clientY - parseInt(target.css("top"));

			ctarget = target;
			copts = opts;

			$(opts.parent).mousemove(move);
			$(document).mouseup(up);
			moving = true;

			isie && this.setCapture();
			opts.onstart && opts.onstart.call(target, e);
			return false;
		}
	}

	//鼠标在父级容器上移动时的处理
	function move(e) {
		var x = e.clientX - dx;
		var y = e.clientY - dy;
		copts.onmove.call(ctarget, e, x, y);
	}

	//拖拽结束
	function up(e) {
		$(copts.parent).unbind("mousemove", move);
		$(document).unbind("mouseup", up);
		moving = false;
		isie && $(copts.handle)[0].releaseCapture();
		copts.onfinish && copts.onfinish.call(ctarget, e);
	}

})(jQuery);
