(function($) {
	$.fn.MySlider = function(interval, data) {
		var data = traverseArray(data, decode);
		var img = $("#dlimg img"),
			credit = $("#dlw .credit"),
			title = $("#dlw h2 a"),
			description = $("#dlw p").eq(0),
			question = $("#dlw .lede-link a"),
			moreTitle = $("#dlw h3"),
			ul = $("#dlw ul a");
			li1 = ul.eq(0),
			li2 = ul.eq(1),
			li3 = ul.eq(2),
			count = $("#count"),
			btnPly = $("a.dl-play");
		var cnt = 0, total = data.length, status = true;
		$("#dlbBtn").click(function(){
			changeSlide(-1);
			btnPly.removeClass("pause").addClass("play");
			status = false;
			return false;
		});
		$("#dlpBtn").click(function(){
			if(status)
				btnPly.removeClass("pause").addClass("play");
			else {
				btnPly.removeClass("play").addClass("pause");
				setTimeout(run, interval);
			}
			status = !status;
			return false;
		});
		$("#dlfBtn").click(function(){
			changeSlide(1);
			btnPly.removeClass("pause").addClass("play");
			status = false;
			return false;
		});
		function decode(str) {
			alphabet  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			newStr = "";
			key = parseInt(str.charAt(1));
			str = str.substr(2);
			for(i = 0; i < str.length; i++) {
				pos = alphabet.indexOf(str[i]);
				if(pos < 0) {
					newStr += str[i];
					continue;
				}
				newKey = pos - key;
				newStr += ((newKey < 0) ? alphabet[newKey + 61] : alphabet[newKey]);
			}
			return newStr;
		}
		function traverseArray(arr, func) {

			var array = [];
			if(arr instanceof Array) {
				for(var i = 0; i < arr.length; i++) {
					if(arr[i] instanceof Array) {
						array[i] = traverseArray(arr[i], func);
					} else {
						array[i] = func(arr[i]);
					}
				}
			}
			return array;
		}

		function run() {
			if(!status) return false;
			changeSlide(1);
			setTimeout(run, interval);
		}
		function changeSlide(key) {
			img.fadeOut(function(){
				img.attr("src", "media/images/" + data[cnt][0]).fadeIn();
			});
			credit.text(data[cnt][1]);
			title.text(data[cnt][2]);
			description.text(data[cnt][3]);
			question.text(data[cnt][4]);
			moreTitle.text(data[cnt][5]);
			li1.text(data[cnt][6][0]);
			li2.text(data[cnt][6][1]);
			li3.text(data[cnt][6][2]);
			cnt += key;
			cnt = (cnt == -1) ? total - 1 : cnt % total;
			count.text(cnt + 1);
		}
		setTimeout(run, interval);
	};
})(jQuery);

(function($) {
	$.fn.MyHorizontalSlider = function(interval) {
		var clip = $("#slideshow ul"),
			prev = $("#slideshowprev"),
			next = $("#slideshownext"),
			oldId = 0,
			left = 0;
		prev.click(function() {
			var id1 = oldId, id = (--id1 == -1 ? 9 : id1);
			changeClass(id);
			changeSlide(id);
			$(this).blur();
			return false;
		});
		next.click(function(){
			var id1 = oldId, id = (++id1 % 10);
			changeClass(id);
			changeSlide(id);
			$(this).blur();
			return false;
		});
		$("#slideshow .paginate_inner a").click(function() {
			id = parseInt($(this).attr("id").charAt($(this).attr("id").length - 1));
			changeClass(id);
			changeSlide(id);
			return false;
		});
		function changeClass(key) {
			$("#slideshow #i" + oldId).removeClass("sel");
			$("#slideshow #i" + key).addClass("sel");
			oldId = key;
		}
		function changeSlide(newLeft) {
			newLeft *= 302;
			if(left == newLeft) return false;
			if(left < newLeft) {
				clip.animate({"left":"-=" + (newLeft - left) + 'px'});
				left = newLeft;
			} else {
				clip.animate({"left":"+=" + (left - newLeft) + 'px'});
				left -= left - newLeft;
			}
		}
	};
})(jQuery);

(function($) {
	$.fn.MyHorizontalLargeSlider = function(interval, data) {
		//alert(data[0].id);
		var clip    = $("#slideshow-outer-wrapper ul"),
			width   = 0,
			prev    = $("#slideshow-outer-wrapper #slideshowprev"),
			next    = $("#slideshow-outer-wrapper #slideshownext"),
			current = $("#slideshow-outer-wrapper #current_slide"),
			count   = data.length,
			oldId   = 0,
			added   = 0,
			left    = 0,
			slides  = [],
			queue   = [];
		var loading_img = "";
		current.text(1);
		$("#slideshow-outer-wrapper #total_slides").text(data.length);
		createSlideShow(data);
		function createSlideShow(data) {
			clip.css("width", count * 624);
			$(clip).children(0).remove();
			for(var i = 0; i < count; i++) {
				slides[i] = i;
				var li  = $("<li>");
				clip.append(li.append($("<img src='../media/images/loading.gif'>")));
			}
			width = $("#slideshow-wrapper ul li").eq(0).width();
			queue = queue.concat([slides.shift(), slides.shift(), slides.pop()]);
			added = queue.length;
			$("#slideshow-wrapper #caption h3").text(data[0].alt_text);
			$("#slideshow-wrapper #caption p").text(data[0].title);
			addSlides(data);
		}
		function addSlides(data) {
			for(var i = 0; queue.length > 0; i++) {
				var slide = queue.shift();
				if(data[slide].type == "i") {
					$("li", clip).eq(slide).children(0).attr("src", data[slide].src);
				} else {
					$("li", clip).eq(slide).children(0).remove();
					$("li", clip).eq(slide).html("<center>" + data[slide].embed_code + "</center>");
				}
			}
		}
		prev.click(function() {
			if(slides.length > 0) {
				queue = queue.concat([slides.pop(), slides.pop()]);
				addSlides(data);
			}
			var id1 = oldId, id = (--id1 == -1 ? count - 1 : id1);
			current.text(id + 1);
			$("#slideshow-wrapper #caption h3").text(data[id].alt_text);
			$("#slideshow-wrapper #caption p").text(data[id].title);
			changeSlide(id);
			oldId = id;
			$(this).blur();
			return false;
		});
		next.click(function(){
			if(slides.length > 0) {
				queue = queue.concat([slides.shift(), slides.shift()]);
				addSlides(data);
			}
			var id1 = oldId, id = (++id1 % count);
			current.text(id + 1);
			$("#slideshow-wrapper #caption h3").text(data[id].alt_text);
			$("#slideshow-wrapper #caption p").text(data[id].title);
			changeSlide(id);
			oldId = id;
			$(this).blur();
			return false;
		});
		function changeSlide(newLeft) {
			newLeft *= width;
			if(left == newLeft) return false;
			if(left < newLeft) {
				clip.animate({"left" : "-=" + (newLeft - left) + 'px'}, 300);
				left = newLeft;
			} else {
				clip.animate({"left" : "+=" + (left - newLeft) + 'px'}, 300);
				left -= left - newLeft;
			}
		}
	};
})(jQuery);