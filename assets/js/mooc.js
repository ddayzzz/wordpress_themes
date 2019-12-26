jQuery(document).ready(function(jQuery) {
	var __cancel = jQuery('#cancel-comment-reply-link'),
		__cancel_text = __cancel.text(),
		__list = 'commentlist';
	jQuery(document).on("submit", "#commentform", function() {
		jQuery.ajax({
			url: ajaxcomment.ajax_url,
			data: jQuery(this).serialize() + "&action=ajax_comment",
			type: jQuery(this).attr('method'),
			beforeSend: addComment.createButterbar("提交中", "more"),
			error: function(request) {
				var t = addComment;
				t.clearButterbar();
				t.createButterbar(request.responseText, "close");
			},
			success: function(data) {
				// 加载 replay 之后
				jQuery('textarea').each(function() {
					this.value = ''
				});
				var t = addComment,
					cancel = t.I('cancel-comment-reply-link'),
					temp = t.I('wp-temp-form-div'),
					respond = t.I(t.respondId),
					post = t.I('comment_post_ID').value,
					parent = t.I('comment_parent').value;
				if (parent != '0') {
					jQuery('#respond').before('<ul class="children uk-comment-list">' + data + '</ul>');
				} else if (!jQuery('.' + __list ).length) {
					if (ajaxcomment.formpostion == 'bottom') {
						jQuery('#respond').before('<ul class="' + __list + ' uk-comment-list">' + data + '</ul>');
					} else {
						jQuery('#respond').after('<ul class="' + __list + ' uk-comment-list">' + data + '</ul>');
					}

				} else {
					if (ajaxcomment.order == 'asc') {
						jQuery('.' + __list ).append(data);
					} else {
						jQuery('.' + __list ).prepend(data);
					}
				}
				t.clearButterbar();
				t.createButterbar("提交成功", 'check');
				cancel.style.display = 'none';
				cancel.onclick = null;
				t.I('comment_parent').value = '0';
				if (temp && respond) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp)
				}
			}
		});
		return false;
	});
	addComment = {
		moveForm: function(commId, parentId, respondId) {
			var t = this,
				div, comm = t.I(commId),
				respond = t.I(respondId),
				cancel = t.I('cancel-comment-reply-link'),
				parent = t.I('comment_parent'),
				post = t.I('comment_post_ID');
			__cancel.text(__cancel_text);
			t.respondId = respondId;
			if (!t.I('wp-temp-form-div')) {
				div = document.createElement('div');
				div.id = 'wp-temp-form-div';
				div.style.display = 'none';
				respond.parentNode.insertBefore(div, respond)
			}!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
			jQuery("body").animate({
				scrollTop: jQuery('#respond').offset().top - 180
			}, 400);
			parent.value = parentId;
			cancel.style.display = '';
			cancel.onclick = function() {
				var t = addComment,
					temp = t.I('wp-temp-form-div'),
					respond = t.I(t.respondId);
				t.I('comment_parent').value = '0';
				if (temp && respond) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp);
				}
				this.style.display = 'none';
				this.onclick = null;
				return false;
			};
			try {
				t.I('comment').focus();
			} catch (e) {}
			return false;
		},
		I: function(e) {
			return document.getElementById(e);
		},
		clearButterbar: function() {
			UIkit.notification.closeAll()
		},
		createButterbar: function(message, icon) {
			// document.getElementById("sb").click();
			UIkit.notification({message: `<span uk-icon=\'icon: ${icon}\'></span> ${message}`,pos: 'bottom-right'});
		}
	};
	/*
        var RelTitle = document.title;
        var hidden, visibilityChange;
        if (typeof document.hidden !== "undefined") {
          hidden = "hidden";
          visibilityChange = "visibilitychange";
        } else if (typeof document.mozHidden !== "undefined") { // Firefox up to v17
          hidden = "mozHidden";
          visibilityChange = "mozvisibilitychange";
        } else if (typeof document.webkitHidden !== "undefined") { // Chrome up to v32, Android up to v4.4, Blackberry up to v10
          hidden = "webkitHidden";
          visibilityChange = "webkitvisibilitychange";
        }
        function handleVisibilityChange() {
          if (document[hidden]) {
            document.title = ' (●––●) Hi, Mooc!';
          } else {
            document.title = RelTitle;
          }
        }
        if (typeof document.addEventListener !== "undefined" || typeof document[hidden] !== "undefined") {
          document.addEventListener(visibilityChange, handleVisibilityChange, false);
        }
    */

});

jQuery(document).ready(function(){
	jQuery(document).on("click", ".add-smily",function(){
		var myField;
		tag = " " + jQuery(this).data("smilies") + " ";
		if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
			myField = document.getElementById('comment');
		} else {
			return false;
		}
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = tag;
			myField.focus();
		}
		else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			var cursorPos = startPos;
			myField.value = myField.value.substring(0, startPos)
				+ tag
				+ myField.value.substring(endPos, myField.value.length);
			cursorPos += tag.length;
			myField.focus();
			myField.selectionStart = cursorPos;
			myField.selectionEnd = cursorPos;
		}else {
			myField.value += tag;
			myField.focus();
		}
	});


});

jQuery(window).scroll(function() {
	jQuery(this).scrollTop() > 150 ? jQuery("#gotop").css({
		bottom: "32px"
	}) : jQuery("#gotop").css({
		bottom: "-100px"
	})
});
jQuery("#gotop").click(function() {
	return jQuery("body,html").animate({
			scrollTop: 0
		},
		800),
		!1
});

jQuery(document).on("click", "#show-more",
	function() {
		if (jQuery(this).hasClass('is-loading')) {
			return false;
		} else {
			var paged = jQuery(this).data("paged"),
				total = jQuery(this).data("total"),
				category = jQuery(this).data("cate"),
				tag = jQuery(this).data("tag"),
				search = jQuery(this).data("search"),
				author = jQuery(this).data("author");
			var ajax_data = {
				action: "ajax_index_post",
				paged: paged,
				total: total,
				category:category,
				author:author,
				tag:tag,
				search:search
			};
			jQuery(this).addClass('is-loading')
			jQuery.post('wp-admin/admin-ajax.php', ajax_data,
				function(data) {
					jQuery('.loadmore').remove();
					jQuery(".posts").append(data);
				});
			return false;
		}
	});
jQuery("pre").each(function (i, e) {
	hljs.highlightBlock(e);
});
// 增加 uikit 的 class, 这个是通用的
jQuery("select").addClass("uk-select");
jQuery("ul").addClass("uk-list");
jQuery("aside").addClass("uk-margin-medium-bottom");
