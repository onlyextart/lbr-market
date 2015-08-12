!function(t){"use strict";var i=t.jCarousel={};i.version="0.3.3";var s=/^([+\-]=)?(.+)$/;i.parseTarget=function(t){var i=!1,e="object"!=typeof t?s.exec(t):null;return e?(t=parseInt(e[2],10)||0,e[1]&&(i=!0,"-="===e[1]&&(t*=-1))):"object"!=typeof t&&(t=parseInt(t,10)||0),{target:t,relative:i}},i.detectCarousel=function(t){for(var i;t.length>0;){if(i=t.filter("[data-jcarousel]"),i.length>0)return i;if(i=t.find("[data-jcarousel]"),i.length>0)return i;t=t.parent()}return null},i.base=function(s){return{version:i.version,_options:{},_element:null,_carousel:null,_init:t.noop,_create:t.noop,_destroy:t.noop,_reload:t.noop,create:function(){return this._element.attr("data-"+s.toLowerCase(),!0).data(s,this),!1===this._trigger("create")?this:(this._create(),this._trigger("createend"),this)},destroy:function(){return!1===this._trigger("destroy")?this:(this._destroy(),this._trigger("destroyend"),this._element.removeData(s).removeAttr("data-"+s.toLowerCase()),this)},reload:function(t){return!1===this._trigger("reload")?this:(t&&this.options(t),this._reload(),this._trigger("reloadend"),this)},element:function(){return this._element},options:function(i,s){if(0===arguments.length)return t.extend({},this._options);if("string"==typeof i){if("undefined"==typeof s)return"undefined"==typeof this._options[i]?null:this._options[i];this._options[i]=s}else this._options=t.extend({},this._options,i);return this},carousel:function(){return this._carousel||(this._carousel=i.detectCarousel(this.options("carousel")||this._element),this._carousel||t.error('Could not detect carousel for plugin "'+s+'"')),this._carousel},_trigger:function(i,e,r){var n,o=!1;return r=[this].concat(r||[]),(e||this._element).each(function(){n=t.Event((s+":"+i).toLowerCase()),t(this).trigger(n,r),n.isDefaultPrevented()&&(o=!0)}),!o}}},i.plugin=function(s,e){var r=t[s]=function(i,s){this._element=t(i),this.options(s),this._init(),this.create()};return r.fn=r.prototype=t.extend({},i.base(s),e),t.fn[s]=function(i){var e=Array.prototype.slice.call(arguments,1),n=this;return this.each("string"==typeof i?function(){var r=t(this).data(s);if(!r)return t.error("Cannot call methods on "+s+' prior to initialization; attempted to call method "'+i+'"');if(!t.isFunction(r[i])||"_"===i.charAt(0))return t.error('No such method "'+i+'" for '+s+" instance");var o=r[i].apply(r,e);return o!==r&&"undefined"!=typeof o?(n=o,!1):void 0}:function(){var e=t(this).data(s);e instanceof r?e.reload(i):new r(this,i)}),n},r}}(jQuery),function(t,i){"use strict";var s=function(t){return parseFloat(t)||0};t.jCarousel.plugin("jcarousel",{animating:!1,tail:0,inTail:!1,resizeTimer:null,lt:null,vertical:!1,rtl:!1,circular:!1,underflow:!1,relative:!1,_options:{list:function(){return this.element().children().eq(0)},items:function(){return this.list().children()},animation:400,transitions:!1,wrap:null,vertical:null,rtl:null,center:!1},_list:null,_items:null,_target:t(),_first:t(),_last:t(),_visible:t(),_fullyvisible:t(),_init:function(){var t=this;return this.onWindowResize=function(){t.resizeTimer&&clearTimeout(t.resizeTimer),t.resizeTimer=setTimeout(function(){t.reload()},100)},this},_create:function(){this._reload(),t(i).on("resize.jcarousel",this.onWindowResize)},_destroy:function(){t(i).off("resize.jcarousel",this.onWindowResize)},_reload:function(){this.vertical=this.options("vertical"),null==this.vertical&&(this.vertical=this.list().height()>this.list().width()),this.rtl=this.options("rtl"),null==this.rtl&&(this.rtl=function(i){if("rtl"===(""+i.attr("dir")).toLowerCase())return!0;var s=!1;return i.parents("[dir]").each(function(){return/rtl/i.test(t(this).attr("dir"))?(s=!0,!1):void 0}),s}(this._element)),this.lt=this.vertical?"top":"left",this.relative="relative"===this.list().css("position"),this._list=null,this._items=null;var i=this.index(this._target)>=0?this._target:this.closest();this.circular="circular"===this.options("wrap"),this.underflow=!1;var s={left:0,top:0};return i.length>0&&(this._prepare(i),this.list().find("[data-jcarousel-clone]").remove(),this._items=null,this.underflow=this._fullyvisible.length>=this.items().length,this.circular=this.circular&&!this.underflow,s[this.lt]=this._position(i)+"px"),this.move(s),this},list:function(){if(null===this._list){var i=this.options("list");this._list=t.isFunction(i)?i.call(this):this._element.find(i)}return this._list},items:function(){if(null===this._items){var i=this.options("items");this._items=(t.isFunction(i)?i.call(this):this.list().find(i)).not("[data-jcarousel-clone]")}return this._items},index:function(t){return this.items().index(t)},closest:function(){var i,e=this,r=this.list().position()[this.lt],n=t(),o=!1,l=this.vertical?"bottom":this.rtl&&!this.relative?"left":"right";return this.rtl&&this.relative&&!this.vertical&&(r+=this.list().width()-this.clipping()),this.items().each(function(){if(n=t(this),o)return!1;var a=e.dimension(n);if(r+=a,r>=0){if(i=a-s(n.css("margin-"+l)),!(Math.abs(r)-a+i/2<=0))return!1;o=!0}}),n},target:function(){return this._target},first:function(){return this._first},last:function(){return this._last},visible:function(){return this._visible},fullyvisible:function(){return this._fullyvisible},hasNext:function(){if(!1===this._trigger("hasnext"))return!0;var t=this.options("wrap"),i=this.items().length-1,s=this.options("center")?this._target:this._last;return i>=0&&!this.underflow&&(t&&"first"!==t||this.index(s)<i||this.tail&&!this.inTail)?!0:!1},hasPrev:function(){if(!1===this._trigger("hasprev"))return!0;var t=this.options("wrap");return this.items().length>0&&!this.underflow&&(t&&"last"!==t||this.index(this._first)>0||this.tail&&this.inTail)?!0:!1},clipping:function(){return this._element["inner"+(this.vertical?"Height":"Width")]()},dimension:function(t){return t["outer"+(this.vertical?"Height":"Width")](!0)},scroll:function(i,s,e){if(this.animating)return this;if(!1===this._trigger("scroll",null,[i,s]))return this;t.isFunction(s)&&(e=s,s=!0);var r=t.jCarousel.parseTarget(i);if(r.relative){var n,o,l,a,h,u,c,f,d=this.items().length-1,_=Math.abs(r.target),p=this.options("wrap");if(r.target>0){var g=this.index(this._last);if(g>=d&&this.tail)this.inTail?"both"===p||"last"===p?this._scroll(0,s,e):t.isFunction(e)&&e.call(this,!1):this._scrollTail(s,e);else if(n=this.index(this._target),this.underflow&&n===d&&("circular"===p||"both"===p||"last"===p)||!this.underflow&&g===d&&("both"===p||"last"===p))this._scroll(0,s,e);else if(l=n+_,this.circular&&l>d){for(f=d,h=this.items().get(-1);f++<l;)h=this.items().eq(0),u=this._visible.index(h)>=0,u&&h.after(h.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(h),u||(c={},c[this.lt]=this.dimension(h),this.moveBy(c)),this._items=null;this._scroll(h,s,e)}else this._scroll(Math.min(l,d),s,e)}else if(this.inTail)this._scroll(Math.max(this.index(this._first)-_+1,0),s,e);else if(o=this.index(this._first),n=this.index(this._target),a=this.underflow?n:o,l=a-_,0>=a&&(this.underflow&&"circular"===p||"both"===p||"first"===p))this._scroll(d,s,e);else if(this.circular&&0>l){for(f=l,h=this.items().get(0);f++<0;){h=this.items().eq(-1),u=this._visible.index(h)>=0,u&&h.after(h.clone(!0).attr("data-jcarousel-clone",!0)),this.list().prepend(h),this._items=null;var m=this.dimension(h);c={},c[this.lt]=-m,this.moveBy(c)}this._scroll(h,s,e)}else this._scroll(Math.max(l,0),s,e)}else this._scroll(r.target,s,e);return this._trigger("scrollend"),this},moveBy:function(t,i){var e=this.list().position(),r=1,n=0;return this.rtl&&!this.vertical&&(r=-1,this.relative&&(n=this.list().width()-this.clipping())),t.left&&(t.left=e.left+n+s(t.left)*r+"px"),t.top&&(t.top=e.top+n+s(t.top)*r+"px"),this.move(t,i)},move:function(i,s){s=s||{};var e=this.options("transitions"),r=!!e,n=!!e.transforms,o=!!e.transforms3d,l=s.duration||0,a=this.list();if(!r&&l>0)return void a.animate(i,s);var h=s.complete||t.noop,u={};if(r){var c={transitionDuration:a.css("transitionDuration"),transitionTimingFunction:a.css("transitionTimingFunction"),transitionProperty:a.css("transitionProperty")},f=h;h=function(){t(this).css(c),f.call(this)},u={transitionDuration:(l>0?l/1e3:0)+"s",transitionTimingFunction:e.easing||s.easing,transitionProperty:l>0?function(){return n||o?"all":i.left?"left":"top"}():"none",transform:"none"}}o?u.transform="translate3d("+(i.left||0)+","+(i.top||0)+",0)":n?u.transform="translate("+(i.left||0)+","+(i.top||0)+")":t.extend(u,i),r&&l>0&&a.one("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd",h),a.css(u),0>=l&&a.each(function(){h.call(this)})},_scroll:function(i,s,e){if(this.animating)return t.isFunction(e)&&e.call(this,!1),this;if("object"!=typeof i?i=this.items().eq(i):"undefined"==typeof i.jquery&&(i=t(i)),0===i.length)return t.isFunction(e)&&e.call(this,!1),this;this.inTail=!1,this._prepare(i);var r=this._position(i),n=this.list().position()[this.lt];if(r===n)return t.isFunction(e)&&e.call(this,!1),this;var o={};return o[this.lt]=r+"px",this._animate(o,s,e),this},_scrollTail:function(i,s){if(this.animating||!this.tail)return t.isFunction(s)&&s.call(this,!1),this;var e=this.list().position()[this.lt];this.rtl&&this.relative&&!this.vertical&&(e+=this.list().width()-this.clipping()),this.rtl&&!this.vertical?e+=this.tail:e-=this.tail,this.inTail=!0;var r={};return r[this.lt]=e+"px",this._update({target:this._target.next(),fullyvisible:this._fullyvisible.slice(1).add(this._visible.last())}),this._animate(r,i,s),this},_animate:function(i,s,e){if(e=e||t.noop,!1===this._trigger("animate"))return e.call(this,!1),this;this.animating=!0;var r=this.options("animation"),n=t.proxy(function(){this.animating=!1;var t=this.list().find("[data-jcarousel-clone]");t.length>0&&(t.remove(),this._reload()),this._trigger("animateend"),e.call(this,!0)},this),o="object"==typeof r?t.extend({},r):{duration:r},l=o.complete||t.noop;return s===!1?o.duration=0:"undefined"!=typeof t.fx.speeds[o.duration]&&(o.duration=t.fx.speeds[o.duration]),o.complete=function(){n(),l.call(this)},this.move(i,o),this},_prepare:function(i){var e,r,n,o,l=this.index(i),a=l,h=this.dimension(i),u=this.clipping(),c=this.vertical?"bottom":this.rtl?"left":"right",f=this.options("center"),d={target:i,first:i,last:i,visible:i,fullyvisible:u>=h?i:t()};if(f&&(h/=2,u/=2),u>h)for(;;){if(e=this.items().eq(++a),0===e.length){if(!this.circular)break;if(e=this.items().eq(0),i.get(0)===e.get(0))break;if(r=this._visible.index(e)>=0,r&&e.after(e.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(e),!r){var _={};_[this.lt]=this.dimension(e),this.moveBy(_)}this._items=null}if(o=this.dimension(e),0===o)break;if(h+=o,d.last=e,d.visible=d.visible.add(e),n=s(e.css("margin-"+c)),u>=h-n&&(d.fullyvisible=d.fullyvisible.add(e)),h>=u)break}if(!this.circular&&!f&&u>h)for(a=l;;){if(--a<0)break;if(e=this.items().eq(a),0===e.length)break;if(o=this.dimension(e),0===o)break;if(h+=o,d.first=e,d.visible=d.visible.add(e),n=s(e.css("margin-"+c)),u>=h-n&&(d.fullyvisible=d.fullyvisible.add(e)),h>=u)break}return this._update(d),this.tail=0,f||"circular"===this.options("wrap")||"custom"===this.options("wrap")||this.index(d.last)!==this.items().length-1||(h-=s(d.last.css("margin-"+c)),h>u&&(this.tail=h-u)),this},_position:function(t){var i=this._first,s=i.position()[this.lt],e=this.options("center"),r=e?this.clipping()/2-this.dimension(i)/2:0;return this.rtl&&!this.vertical?(s-=this.relative?this.list().width()-this.dimension(i):this.clipping()-this.dimension(i),s+=r):s-=r,!e&&(this.index(t)>this.index(i)||this.inTail)&&this.tail?(s=this.rtl&&!this.vertical?s-this.tail:s+this.tail,this.inTail=!0):this.inTail=!1,-s},_update:function(i){var s,e=this,r={target:this._target,first:this._first,last:this._last,visible:this._visible,fullyvisible:this._fullyvisible},n=this.index(i.first||r.first)<this.index(r.first),o=function(s){var o=[],l=[];i[s].each(function(){r[s].index(this)<0&&o.push(this)}),r[s].each(function(){i[s].index(this)<0&&l.push(this)}),n?o=o.reverse():l=l.reverse(),e._trigger(s+"in",t(o)),e._trigger(s+"out",t(l)),e["_"+s]=i[s]};for(s in i)o(s);return this}})}(jQuery,window),function(t){"use strict";t.jcarousel.fn.scrollIntoView=function(i,s,e){var r,n=t.jCarousel.parseTarget(i),o=this.index(this._fullyvisible.first()),l=this.index(this._fullyvisible.last());if(r=n.relative?n.target<0?Math.max(0,o+n.target):l+n.target:"object"!=typeof n.target?n.target:this.index(n.target),o>r)return this.scroll(r,s,e);if(r>=o&&l>=r)return t.isFunction(e)&&e.call(this,!1),this;for(var a,h=this.items(),u=this.clipping(),c=this.vertical?"bottom":this.rtl?"left":"right",f=0;;){if(a=h.eq(r),0===a.length)break;if(f+=this.dimension(a),f>=u){var d=parseFloat(a.css("margin-"+c))||0;f-d!==u&&r++;break}if(0>=r)break;r--}return this.scroll(r,s,e)}}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselControl",{_options:{target:"+=1",event:"click",method:"scroll"},_active:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onReload=t.proxy(this._reload,this),this.onEvent=t.proxy(function(i){i.preventDefault();var s=this.options("method");t.isFunction(s)?s.call(this):this.carousel().jcarousel(this.options("method"),this.options("target"))},this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend jcarousel:scrollend",this.onReload),this._element.on(this.options("event")+".jcarouselcontrol",this.onEvent),this._reload()},_destroy:function(){this._element.off(".jcarouselcontrol",this.onEvent),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend jcarousel:scrollend",this.onReload)},_reload:function(){var i,s=t.jCarousel.parseTarget(this.options("target")),e=this.carousel();if(s.relative)i=e.jcarousel(s.target>0?"hasNext":"hasPrev");else{var r="object"!=typeof s.target?e.jcarousel("items").eq(s.target):s.target;i=e.jcarousel("target").index(r)>=0}return this._active!==i&&(this._trigger(i?"active":"inactive"),this._active=i),this}})}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselPagination",{_options:{perPage:null,item:function(t){return'<a href="#'+t+'">'+t+"</a>"},event:"click",method:"scroll"},_carouselItems:null,_pages:{},_items:{},_currentPage:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onReload=t.proxy(this._reload,this),this.onScroll=t.proxy(this._update,this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend",this.onReload).on("jcarousel:scrollend",this.onScroll),this._reload()},_destroy:function(){this._clear(),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend",this.onReload).off("jcarousel:scrollend",this.onScroll),this._carouselItems=null},_reload:function(){var i=this.options("perPage");if(this._pages={},this._items={},t.isFunction(i)&&(i=i.call(this)),null==i)this._pages=this._calculatePages();else for(var s,e=parseInt(i,10)||0,r=this._getCarouselItems(),n=1,o=0;;){if(s=r.eq(o++),0===s.length)break;this._pages[n]=this._pages[n]?this._pages[n].add(s):s,o%e===0&&n++}this._clear();var l=this,a=this.carousel().data("jcarousel"),h=this._element,u=this.options("item"),c=this._getCarouselItems().length;t.each(this._pages,function(i,s){var e=l._items[i]=t(u.call(l,i,s));e.on(l.options("event")+".jcarouselpagination",t.proxy(function(){var t=s.eq(0);if(a.circular){var e=a.index(a.target()),r=a.index(t);parseFloat(i)>parseFloat(l._currentPage)?e>r&&(t="+="+(c-e+r)):r>e&&(t="-="+(e+(c-r)))}a[this.options("method")](t)},l)),h.append(e)}),this._update()},_update:function(){var i,s=this.carousel().jcarousel("target");t.each(this._pages,function(t,e){return e.each(function(){return s.is(this)?(i=t,!1):void 0}),i?!1:void 0}),this._currentPage!==i&&(this._trigger("inactive",this._items[this._currentPage]),this._trigger("active",this._items[i])),this._currentPage=i},items:function(){return this._items},reloadCarouselItems:function(){return this._carouselItems=null,this},_clear:function(){this._element.empty(),this._currentPage=null},_calculatePages:function(){for(var t,i,s=this.carousel().data("jcarousel"),e=this._getCarouselItems(),r=s.clipping(),n=0,o=0,l=1,a={};;){if(t=e.eq(o++),0===t.length)break;i=s.dimension(t),n+i>r&&(l++,n=0),n+=i,a[l]=a[l]?a[l].add(t):t}return a},_getCarouselItems:function(){return this._carouselItems||(this._carouselItems=this.carousel().jcarousel("items")),this._carouselItems}})}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselAutoscroll",{_options:{target:"+=1",interval:3e3,autostart:!0},_timer:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onAnimateEnd=t.proxy(this.start,this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy),this.options("autostart")&&this.start()},_destroy:function(){this.stop(),this.carousel().off("jcarousel:destroy",this.onDestroy)},start:function(){return this.stop(),this.carousel().one("jcarousel:animateend",this.onAnimateEnd),this._timer=setTimeout(t.proxy(function(){this.carousel().jcarousel("scroll",this.options("target"))},this),this.options("interval")),this},stop:function(){return this._timer&&(this._timer=clearTimeout(this._timer)),this.carousel().off("jcarousel:animateend",this.onAnimateEnd),this}})}(jQuery);;
/*
 *	jQuery carouFredSel 6.0.4
 *	Demo's and documentation:
 *	caroufredsel.frebsite.nl
 *
 *	Copyright (c) 2012 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(C($){8($.1r.1v){G}$.1r.6p=$.1r.1v=C(u,w){8(1k.S==0){18(I,\'6q 54 7P 1j "\'+1k.4l+\'".\');G 1k}8(1k.S>1){G 1k.1W(C(){$(1k).1v(u,w)})}E y=1k,$13=1k[0],55=K;8(y.1m(\'56\')){55=y.1Q(\'3o\',\'4m\');y.R(\'3o\',[\'4n\',I])}y.57=C(o,a,b){o=3R($13,o);o.D=6r($13,o.D);o.1M=6s($13,o.1M);o.M=6t($13,o.M);o.V=59($13,o.V);o.Y=59($13,o.Y);o.1a=6u($13,o.1a);o.1q=6v($13,o.1q);o.1h=6w($13,o.1h);8(a){31=$.1N(I,{},$.1r.1v.5a,o)}7=$.1N(I,{},$.1r.1v.5a,o);7.d=6x(7);z.2b=(7.2b==\'4o\'||7.2b==\'1n\')?\'Y\':\'V\';E c=y.14(),2w=5b($1s,7,\'N\');8(3p(7.25)){7.25=\'7Q\'+F.3S}7.4p=5c(7,2w);7.D=6y(7.D,7,c,b);7[7.d[\'N\']]=6z(7[7.d[\'N\']],7,c);7[7.d[\'1d\']]=6A(7[7.d[\'1d\']],7,c);8(7.2m){8(!3T(7[7.d[\'N\']])){7[7.d[\'N\']]=\'2J%\'}}8(3T(7[7.d[\'N\']])){z.6B=I;z.4q=7[7.d[\'N\']];7[7.d[\'N\']]=4r(2w,z.4q);8(!7.D.L){7.D.T.1c=I}}8(7.2m){7.1R=K;7.1i=[0,0,0,0];7.1A=K;7.D.T.1c=K}O{8(!7.D.L){7=6C(7,2w)}8(!7[7.d[\'N\']]){8(!7.D.T.1c&&Z(7.D[7.d[\'N\']])&&7.D.1t==\'*\'){7[7.d[\'N\']]=7.D.L*7.D[7.d[\'N\']];7.1A=K}O{7[7.d[\'N\']]=\'1c\'}}8(1G(7.1A)){7.1A=(Z(7[7.d[\'N\']]))?\'5d\':K}8(7.D.T.1c){7.D.L=32(c,7,0)}}8(7.D.1t!=\'*\'&&!7.D.T.1c){7.D.T.4s=7.D.L;7.D.L=3U(c,7,0)}7.D.L=2x(7.D.L,7,7.D.T.2c,$13);7.D.T.1Z=7.D.L;8(7.2m){8(!7.D.T.34){7.D.T.34=7.D.L}8(!7.D.T.1X){7.D.T.1X=7.D.L}7=5e(7,c,2w)}O{7.1i=6D(7.1i);8(7.1A==\'3q\'){7.1A=\'1n\'}O 8(7.1A==\'5f\'){7.1A=\'35\'}1B(7.1A){Q\'5d\':Q\'1n\':Q\'35\':8(7[7.d[\'N\']]!=\'1c\'){7=5g(7,c);7.1R=I}16;2y:7.1A=K;7.1R=(7.1i[0]==0&&7.1i[1]==0&&7.1i[2]==0&&7.1i[3]==0)?K:I;16}}8(!Z(7.1M.1C)){7.1M.1C=6E}8(1G(7.1M.D)){7.1M.D=(7.2m||7.D.T.1c||7.D.1t!=\'*\')?\'L\':7.D.L}7.M=$.1N(I,{},7.1M,7.M);7.V=$.1N(I,{},7.1M,7.V);7.Y=$.1N(I,{},7.1M,7.Y);7.1a=$.1N(I,{},7.1M,7.1a);7.M=6F($13,7.M);7.V=5h($13,7.V);7.Y=5h($13,7.Y);7.1a=6G($13,7.1a);7.1q=6H($13,7.1q);7.1h=6I($13,7.1h);8(7.2n){7.2n=5i(7.2n)}8(7.M.5j){7.M.4t=7.M.5j;2K(\'M.5j\',\'M.4t\')}8(7.M.5k){7.M.4u=7.M.5k;2K(\'M.5k\',\'M.4u\')}8(7.M.5l){7.M.4v=7.M.5l;2K(\'M.5l\',\'M.4v\')}8(7.M.5m){7.M.2L=7.M.5m;2K(\'M.5m\',\'M.2L\')}};y.6J=C(){y.1m(\'56\',I);E a=y.14(),3V=5n(y,[\'6K\',\'6L\',\'3W\',\'3q\',\'35\',\'5f\',\'1n\',\'3X\',\'N\',\'1d\',\'6M\',\'1S\',\'5o\',\'6N\']),5p=\'7R\';1B(3V.3W){Q\'6O\':Q\'7S\':5p=3V.3W;16}$1s.X(3V).X({\'7T\':\'3r\',\'3W\':5p});y.1m(\'5q\',3V).X({\'6K\':\'1n\',\'6L\':\'3Y\',\'3W\':\'6O\',\'3q\':0,\'35\':\'M\',\'5f\':\'M\',\'1n\':0,\'6M\':0,\'1S\':0,\'5o\':0,\'6N\':0});4w(a,7);5r(a,7);8(7.2m){5s(7,a)}};y.6P=C(){y.5t();y.12(H(\'5u\',F),C(e,a){e.1f();8(!z.2d){8(7.M.W){7.M.W.36(2z(\'4x\',F))}}z.2d=I;8(7.M.1H){7.M.1H=K;y.R(H(\'3a\',F),a)}G I});y.12(H(\'5v\',F),C(e){e.1f();8(z.20){3Z(U)}G I});y.12(H(\'3a\',F),C(e,a,b){e.1f();1u=3s(1u);8(a&&z.20){U.2d=I;E c=2o()-U.2M;U.1C-=c;8(U.3t){U.3t.1C-=c}8(U.3u){U.3u.1C-=c}3Z(U,K)}8(!z.26&&!z.20){8(b){1u.3v+=2o()-1u.2M}}8(!z.26){8(7.M.W){7.M.W.36(2z(\'6Q\',F))}}z.26=I;8(7.M.4u){E d=7.M.2L-1u.3v,3b=2J-1I.2A(d*2J/7.M.2L);7.M.4u.1g($13,3b,d)}G I});y.12(H(\'1H\',F),C(e,b,c,d){e.1f();1u=3s(1u);E v=[b,c,d],t=[\'2N\',\'27\',\'3c\'],a=3d(v,t);b=a[0];c=a[1];d=a[2];8(b!=\'V\'&&b!=\'Y\'){b=z.2b}8(!Z(c)){c=0}8(!1l(d)){d=K}8(d){z.2d=K;7.M.1H=I}8(!7.M.1H){e.2e();G 18(F,\'3w 4x: 2p 3e.\')}8(z.26){8(7.M.W){7.M.W.2O(2z(\'4x\',F));7.M.W.2O(2z(\'6Q\',F))}}z.26=K;1u.2M=2o();E f=7.M.2L+c;41=f-1u.3v;3b=2J-1I.2A(41*2J/f);8(7.M.1e){1u.1e=7U(C(){E a=2o()-1u.2M+1u.3v,3b=1I.2A(a*2J/f);7.M.1e.4y.1g(7.M.1e.2q[0],3b)},7.M.1e.5w)}1u.M=7V(C(){8(7.M.1e){7.M.1e.4y.1g(7.M.1e.2q[0],2J)}8(7.M.4v){7.M.4v.1g($13,3b,41)}8(z.20){y.R(H(\'1H\',F),b)}O{y.R(H(b,F),7.M)}},41);8(7.M.4t){7.M.4t.1g($13,3b,41)}G I});y.12(H(\'3f\',F),C(e){e.1f();8(U.2d){U.2d=K;z.26=K;z.20=I;U.2M=2o();2P(U)}O{y.R(H(\'1H\',F))}G I});y.12(H(\'V\',F)+\' \'+H(\'Y\',F),C(e,b,f,g,h){e.1f();8(z.2d||y.2f(\':3r\')){e.2e();G 18(F,\'3w 4x 7W 3r: 2p 3e.\')}E i=(Z(7.D.4z))?7.D.4z:7.D.L+1;8(i>J.P){e.2e();G 18(F,\'2p 6R D (\'+J.P+\' P, \'+i+\' 6S): 2p 3e.\')}E v=[b,f,g,h],t=[\'2g\',\'27/2N\',\'C\',\'3c\'],a=3d(v,t);b=a[0];f=a[1];g=a[2];h=a[3];E k=e.5x.17(F.3x.42.S);8(!1D(b)){b={}}8(1o(g)){b.3g=g}8(1l(h)){b.3y=h}b=$.1N(I,{},7[k],b);8(b.5y&&!b.5y.1g($13,k)){e.2e();G 18(F,\'7X "5y" 7Y K.\')}8(!Z(f)){8(7.D.1t!=\'*\'){f=\'L\'}O{E m=[f,b.D,7[k].D];1j(E a=0,l=m.S;a<l;a++){8(Z(m[a])||m[a]==\'6T\'||m[a]==\'L\'){f=m[a];16}}}1B(f){Q\'6T\':e.2e();G y.1Q(H(k+\'7Z\',F),[b,g]);16;Q\'L\':8(!7.D.T.1c&&7.D.1t==\'*\'){f=7.D.L}16}}8(U.2d){y.R(H(\'3f\',F));y.R(H(\'3y\',F),[k,[b,f,g]]);e.2e();G 18(F,\'3w 80 3e.\')}8(b.1C>0){8(z.20){8(b.3y){y.R(H(\'3y\',F),[k,[b,f,g]])}e.2e();G 18(F,\'3w 81 3e.\')}}1u.3v=0;y.R(H(\'6U\'+k,F),[b,f]);8(7.2n){E s=7.2n,c=[b,f];1j(E j=0,l=s.S;j<l;j++){E d=k;8(!s[j][2]){d=(d==\'V\')?\'Y\':\'V\'}8(!s[j][1]){c[0]=s[j][0].1Q(\'3o\',[\'4A\',d])}c[1]=f+s[j][3];s[j][0].R(\'3o\',[\'6U\'+d,c])}}G I});y.12(H(\'82\',F),C(e,b,c){e.1f();E d=y.14();8(!7.1T){8(J.11==0){8(7.3z){y.R(H(\'Y\',F),J.P-1)}G e.2e()}}1U(d,7);8(!Z(c)){8(7.D.T.1c){c=4B(d,7,J.P-1)}O 8(7.D.1t!=\'*\'){E f=(Z(b.D))?b.D:5z(y,7);c=6V(d,7,J.P-1,f)}O{c=7.D.L}c=4C(c,7,b.D,$13)}8(!7.1T){8(J.P-c<J.11){c=J.P-J.11}}7.D.T.1Z=7.D.L;8(7.D.T.1c){E g=2x(32(d,7,J.P-c),7,7.D.T.2c,$13);8(7.D.L+c<=g&&c<J.P){c++;g=2x(32(d,7,J.P-c),7,7.D.T.2c,$13)}7.D.L=g}O 8(7.D.1t!=\'*\'){E g=3U(d,7,J.P-c);7.D.L=2x(g,7,7.D.T.2c,$13)}1U(d,7,I);8(c==0){e.2e();G 18(F,\'0 D 4D 1M: 2p 3e.\')}18(F,\'6W \'+c+\' D 5A.\');J.11+=c;2h(J.11>=J.P){J.11-=J.P}8(!7.1T){8(J.11==0&&b.4E){b.4E.1g($13,\'V\')}8(!7.3z){3A(7,J.11,F)}}y.14().17(J.P-c,J.P).83(y);8(J.P<7.D.L+c){y.14().17(0,(7.D.L+c)-J.P).4F(I).43(y)}E d=y.14(),3h=6X(d,7,c),2i=6Y(d,7),1Y=d.1O(c-1),21=3h.3i(),2r=2i.3i();1U(d,7);E h=0,2B=0;8(7.1A){E p=4G(2i,7);h=p[0];2B=p[1]}E i=(h<0)?7.1i[7.d[3]]:0;E j=K,2Q=$();8(7.D.L<c){2Q=d.17(7.D.T.1Z,c);8(b.1V==\'6Z\'){E k=7.D[7.d[\'N\']];j=2Q;1Y=2r;5B(j);7.D[7.d[\'N\']]=\'1c\'}}E l=K,3B=2R(d.17(0,c),7,\'N\'),2j=4H(4I(2i,7,I),7,!7.1R),3C=0,28={},4J={},2s={},2S={},4K={},2T={},5C={},2U=5D(b,7,c,3B);1B(b.1V){Q\'1J\':Q\'1J-1w\':3C=2R(d.17(0,7.D.L),7,\'N\');16}8(j){7.D[7.d[\'N\']]=k}1U(d,7,I);8(2B>=0){1U(21,7,7.1i[7.d[1]])}8(h>=0){1U(1Y,7,7.1i[7.d[3]])}8(7.1A){7.1i[7.d[1]]=2B;7.1i[7.d[3]]=h}2T[7.d[\'1n\']]=-(3B-i);5C[7.d[\'1n\']]=-(3C-i);4J[7.d[\'1n\']]=2j[7.d[\'N\']];E m=C(){},1P=C(){},1E=C(){},3D=C(){},2C=C(){},5E=C(){},1F=C(){},3E=C(){},1x=C(){},1y=C(){},1K=C(){};1B(b.1V){Q\'3j\':Q\'1J\':Q\'1J-1w\':Q\'22\':Q\'22-1w\':l=y.4F(I).43($1s);16}1B(b.1V){Q\'3j\':Q\'22\':Q\'22-1w\':l.14().17(0,c).2t();l.14().17(7.D.T.1Z).2t();16;Q\'1J\':Q\'1J-1w\':l.14().17(7.D.L).2t();l.X(5C);16}y.X(2T);U=44(2U,b.2k);28[7.d[\'1n\']]=(7.1R)?7.1i[7.d[3]]:0;8(7[7.d[\'N\']]==\'1c\'||7[7.d[\'1d\']]==\'1c\'){m=C(){$1s.X(2j)};1P=C(){U.19.1b([$1s,2j])}}8(7.1R){8(2r.5F(1Y).S){2s[7.d[\'1S\']]=1Y.1m(\'29\');8(h<0){1Y.X(2s)}O{1F=C(){1Y.X(2s)};3E=C(){U.19.1b([1Y,2s])}}}1B(b.1V){Q\'1J\':Q\'1J-1w\':l.14().1O(c-1).X(2s);16}8(2r.5F(21).S){2S[7.d[\'1S\']]=21.1m(\'29\');1E=C(){21.X(2S)};3D=C(){U.19.1b([21,2S])}}8(2B>=0){4K[7.d[\'1S\']]=2r.1m(\'29\')+7.1i[7.d[1]];2C=C(){2r.X(4K)};5E=C(){U.19.1b([2r,4K])}}}1K=C(){y.X(28)};E n=7.D.L+c-J.P;1y=C(){8(n>0){y.14().17(J.P).2t();3h=$(y.14().17(J.P-(7.D.L-n)).3F().70(y.14().17(0,n).3F()))}5G(j);8(7.1R){E a=y.14().1O(7.D.L+c-1);a.X(7.d[\'1S\'],a.1m(\'29\'))}};E o=5H(3h,2Q,2i,c,\'V\',2U,2j);1x=C(){5I(y,l,b);z.20=K;2a.3g=45($13,b,\'3g\',o,2a);2D=5J(y,2D,F);8(!z.26){y.R(H(\'1H\',F))}};z.20=I;1u=3s(1u);2a.3G=45($13,b,\'3G\',o,2a);1B(b.1V){Q\'3Y\':y.X(28);m();1E();2C();1F();1K();1y();1x();16;Q\'1w\':U.19.1b([y,{\'1L\':0},C(){m();1E();2C();1F();1K();1y();U=44(2U,b.2k);U.19.1b([y,{\'1L\':1},1x]);2P(U)}]);16;Q\'3j\':y.X({\'1L\':0});U.19.1b([l,{\'1L\':0}]);U.19.1b([y,{\'1L\':1},1x]);1P();1E();2C();1F();1K();1y();16;Q\'1J\':U.19.1b([l,28,C(){1E();2C();1F();1K();1y();1x()}]);1P();16;Q\'1J-1w\':U.19.1b([y,{\'1L\':0}]);U.19.1b([l,28,C(){y.X({\'1L\':1});1E();2C();1F();1K();1y();1x()}]);1P();16;Q\'22\':U.19.1b([l,4J,1x]);1P();1E();2C();1F();1K();1y();16;Q\'22-1w\':y.X({\'1L\':0});U.19.1b([y,{\'1L\':1}]);U.19.1b([l,4J,1x]);1P();1E();2C();1F();1K();1y();16;2y:U.19.1b([y,28,C(){1y();1x()}]);1P();3D();5E();3E();16}2P(U);5K(7.25,y,F);y.R(H(\'3H\',F),[K,2j]);G I});y.12(H(\'84\',F),C(e,c,d){e.1f();E f=y.14();8(!7.1T){8(J.11==7.D.L){8(7.3z){y.R(H(\'V\',F),J.P-1)}G e.2e()}}1U(f,7);8(!Z(d)){8(7.D.1t!=\'*\'){E g=(Z(c.D))?c.D:5z(y,7);d=71(f,7,0,g)}O{d=7.D.L}d=4C(d,7,c.D,$13)}E h=(J.11==0)?J.P:J.11;8(!7.1T){8(7.D.T.1c){E i=32(f,7,d),g=4B(f,7,h-1)}O{E i=7.D.L,g=7.D.L}8(d+i>h){d=h-g}}7.D.T.1Z=7.D.L;8(7.D.T.1c){E i=2x(5L(f,7,d,h),7,7.D.T.2c,$13);2h(7.D.L-d>=i&&d<J.P){d++;i=2x(5L(f,7,d,h),7,7.D.T.2c,$13)}7.D.L=i}O 8(7.D.1t!=\'*\'){E i=3U(f,7,d);7.D.L=2x(i,7,7.D.T.2c,$13)}1U(f,7,I);8(d==0){e.2e();G 18(F,\'0 D 4D 1M: 2p 3e.\')}18(F,\'6W \'+d+\' D 72.\');J.11-=d;2h(J.11<0){J.11+=J.P}8(!7.1T){8(J.11==7.D.L&&c.4E){c.4E.1g($13,\'Y\')}8(!7.3z){3A(7,J.11,F)}}8(J.P<7.D.L+d){y.14().17(0,(7.D.L+d)-J.P).4F(I).43(y)}E f=y.14(),3h=73(f,7),2i=74(f,7,d),1Y=f.1O(d-1),21=3h.3i(),2r=2i.3i();1U(f,7);E j=0,2B=0;8(7.1A){E p=4G(2i,7);j=p[0];2B=p[1]}E k=K,2Q=$();8(7.D.T.1Z<d){2Q=f.17(7.D.T.1Z,d);8(c.1V==\'6Z\'){E l=7.D[7.d[\'N\']];k=2Q;1Y=21;5B(k);7.D[7.d[\'N\']]=\'1c\'}}E m=K,3B=2R(f.17(0,d),7,\'N\'),2j=4H(4I(2i,7,I),7,!7.1R),3C=0,28={},4L={},2s={},2S={},2T={},2U=5D(c,7,d,3B);1B(c.1V){Q\'22\':Q\'22-1w\':3C=2R(f.17(0,7.D.T.1Z),7,\'N\');16}8(k){7.D[7.d[\'N\']]=l}8(7.1A){8(7.1i[7.d[1]]<0){7.1i[7.d[1]]=0}}1U(f,7,I);1U(21,7,7.1i[7.d[1]]);8(7.1A){7.1i[7.d[1]]=2B;7.1i[7.d[3]]=j}2T[7.d[\'1n\']]=(7.1R)?7.1i[7.d[3]]:0;E n=C(){},1P=C(){},1E=C(){},3D=C(){},1F=C(){},3E=C(){},1x=C(){},1y=C(){},1K=C(){};1B(c.1V){Q\'3j\':Q\'1J\':Q\'1J-1w\':Q\'22\':Q\'22-1w\':m=y.4F(I).43($1s);m.14().17(7.D.T.1Z).2t();16}1B(c.1V){Q\'3j\':Q\'1J\':Q\'1J-1w\':y.X(\'3X\',1);m.X(\'3X\',0);16}U=44(2U,c.2k);28[7.d[\'1n\']]=-3B;4L[7.d[\'1n\']]=-3C;8(j<0){28[7.d[\'1n\']]+=j}8(7[7.d[\'N\']]==\'1c\'||7[7.d[\'1d\']]==\'1c\'){n=C(){$1s.X(2j)};1P=C(){U.19.1b([$1s,2j])}}8(7.1R){E o=2r.1m(\'29\');8(2B>=0){o+=7.1i[7.d[1]]}2r.X(7.d[\'1S\'],o);8(1Y.5F(21).S){2S[7.d[\'1S\']]=21.1m(\'29\')}1E=C(){21.X(2S)};3D=C(){U.19.1b([21,2S])};E q=1Y.1m(\'29\');8(j>0){q+=7.1i[7.d[3]]}2s[7.d[\'1S\']]=q;1F=C(){1Y.X(2s)};3E=C(){U.19.1b([1Y,2s])}}1K=C(){y.X(2T)};E r=7.D.L+d-J.P;1y=C(){8(r>0){y.14().17(J.P).2t()}E a=y.14().17(0,d).43(y).3i();8(r>0){2i=3I(f,7)}5G(k);8(7.1R){8(J.P<7.D.L+d){E b=y.14().1O(7.D.L-1);b.X(7.d[\'1S\'],b.1m(\'29\')+7.1i[7.d[3]])}a.X(7.d[\'1S\'],a.1m(\'29\'))}};E s=5H(3h,2Q,2i,d,\'Y\',2U,2j);1x=C(){y.X(\'3X\',y.1m(\'5q\').3X);5I(y,m,c);z.20=K;2a.3g=45($13,c,\'3g\',s,2a);2D=5J(y,2D,F);8(!z.26){y.R(H(\'1H\',F))}};z.20=I;1u=3s(1u);2a.3G=45($13,c,\'3G\',s,2a);1B(c.1V){Q\'3Y\':y.X(28);n();1E();1F();1K();1y();1x();16;Q\'1w\':U.19.1b([y,{\'1L\':0},C(){n();1E();1F();1K();1y();U=44(2U,c.2k);U.19.1b([y,{\'1L\':1},1x]);2P(U)}]);16;Q\'3j\':y.X({\'1L\':0});U.19.1b([m,{\'1L\':0}]);U.19.1b([y,{\'1L\':1},1x]);1P();1E();1F();1K();1y();16;Q\'1J\':y.X(7.d[\'1n\'],$1s[7.d[\'N\']]());U.19.1b([y,2T,1x]);1P();1E();1F();1y();16;Q\'1J-1w\':y.X(7.d[\'1n\'],$1s[7.d[\'N\']]());U.19.1b([m,{\'1L\':0}]);U.19.1b([y,2T,1x]);1P();1E();1F();1y();16;Q\'22\':U.19.1b([m,4L,1x]);1P();1E();1F();1K();1y();16;Q\'22-1w\':y.X({\'1L\':0});U.19.1b([y,{\'1L\':1}]);U.19.1b([m,4L,1x]);1P();1E();1F();1K();1y();16;2y:U.19.1b([y,28,C(){1K();1y();1x()}]);1P();3D();3E();16}2P(U);5K(7.25,y,F);y.R(H(\'3H\',F),[K,2j]);G I});y.12(H(\'3k\',F),C(e,b,c,d,f,g,h){e.1f();E v=[b,c,d,f,g,h],t=[\'2N/27/2g\',\'27\',\'3c\',\'2g\',\'2N\',\'C\'],a=3d(v,t);f=a[3];g=a[4];h=a[5];b=3J(a[0],a[1],a[2],J,y);8(b==0){G K}8(!1D(f)){f=K}8(z.20){8(!1D(f)||f.1C>0){G K}}8(g!=\'V\'&&g!=\'Y\'){8(7.1T){g=(b<=J.P/2)?\'Y\':\'V\'}O{g=(J.11==0||J.11>b)?\'Y\':\'V\'}}8(g==\'V\'){b=J.P-b}y.R(H(g,F),[f,b,h]);G I});y.12(H(\'85\',F),C(e,a,b){e.1f();E c=y.1Q(H(\'46\',F));G y.1Q(H(\'5M\',F),[c-1,a,\'V\',b])});y.12(H(\'86\',F),C(e,a,b){e.1f();E c=y.1Q(H(\'46\',F));G y.1Q(H(\'5M\',F),[c+1,a,\'Y\',b])});y.12(H(\'5M\',F),C(e,a,b,c,d){e.1f();8(!Z(a)){a=y.1Q(H(\'46\',F))}E f=7.1a.D||7.D.L,1X=1I.2A(J.P/f)-1;8(a<0){a=1X}8(a>1X){a=0}G y.1Q(H(\'3k\',F),[a*f,0,I,b,c,d])});y.12(H(\'75\',F),C(e,s){e.1f();8(s){s=3J(s,0,I,J,y)}O{s=0}s+=J.11;8(s!=0){8(D.P>0){2h(s>J.P){s-=J.P}}y.87(y.14().17(s,J.P))}G I});y.12(H(\'2n\',F),C(e,s){e.1f();8(s){s=5i(s)}O 8(7.2n){s=7.2n}O{G 18(F,\'6q 88 4D 2n.\')}E n=y.1Q(H(\'4m\',F)),x=I;1j(E j=0,l=s.S;j<l;j++){8(!s[j][0].1Q(H(\'3k\',F),[n,s[j][3],I])){x=K}}G x});y.12(H(\'3y\',F),C(e,a,b){e.1f();8(1o(a)){a.1g($13,2D)}O 8(2V(a)){2D=a}O 8(!1G(a)){2D.1b([a,b])}G 2D});y.12(H(\'89\',F),C(e,b,c,d,f){e.1f();E v=[b,c,d,f],t=[\'2N/2g\',\'2N/27/2g\',\'3c\',\'27\'],a=3d(v,t);b=a[0];c=a[1];d=a[2];f=a[3];8(1D(b)&&!2u(b)){b=$(b)}O 8(1p(b)){b=$(b)}8(!2u(b)||b.S==0){G 18(F,\'2p a 5N 2g.\')}8(1G(c)){c=\'4M\'}4w(b,7);5r(b,7);E g=c,47=\'47\';8(c==\'4M\'){8(d){8(J.11==0){c=J.P-1;47=\'76\'}O{c=J.11;J.11+=b.S}8(c<0){c=0}}O{c=J.P-1;47=\'76\'}}O{c=3J(c,f,d,J,y)}8(g!=\'4M\'&&!d){8(c<J.11){J.11+=b.S}}8(J.11>=J.P){J.11-=J.P}E h=y.14().1O(c);8(h.S){h[47](b)}O{y.77(b)}J.P=y.14().S;y.R(H(\'4N\',F));y.R(H(\'5O\',F));G I});y.12(H(\'78\',F),C(e,c,d,f){e.1f();E v=[c,d,f],t=[\'2N/27/2g\',\'3c\',\'27\'],a=3d(v,t);c=a[0];d=a[1];f=a[2];E g=K;8(c 2W $&&c.S>1){h=$();c.1W(C(i,a){E b=y.R(H(\'78\',F),[$(1k),d,f]);8(b)h=h.8a(b)});G h}8(1G(c)||c==\'4M\'){h=y.14().3i()}O{c=3J(c,f,d,J,y);E h=y.14().1O(c);8(h.S){8(c<J.11)J.11-=h.S}}8(h&&h.S){h.8b();J.P=y.14().S;y.R(H(\'4N\',F))}G h});y.12(H(\'3G\',F)+\' \'+H(\'3g\',F),C(e,a){e.1f();E b=e.5x.17(F.3x.42.S);8(2V(a)){2a[b]=a}8(1o(a)){2a[b].1b(a)}G 2a[b]});y.12(H(\'4m\',F),C(e,a){e.1f();8(J.11==0){E b=0}O{E b=J.P-J.11}8(1o(a)){a.1g($13,b)}G b});y.12(H(\'46\',F),C(e,a){e.1f();E b=7.1a.D||7.D.L,1X=1I.2A(J.P/b-1),2l;8(J.11==0){2l=0}O 8(J.11<J.P%b){2l=0}O 8(J.11==b&&!7.1T){2l=1X}O{2l=1I.79((J.P-J.11)/b)}8(2l<0){2l=0}8(2l>1X){2l=1X}8(1o(a)){a.1g($13,2l)}G 2l});y.12(H(\'8c\',F),C(e,a){e.1f();E b=3I(y.14(),7);8(1o(a)){a.1g($13,b)}G b});y.12(H(\'17\',F),C(e,f,l,b){e.1f();8(J.P==0){G K}E v=[f,l,b],t=[\'27\',\'27\',\'C\'],a=3d(v,t);f=(Z(a[0]))?a[0]:0;l=(Z(a[1]))?a[1]:J.P;b=a[2];f+=J.11;l+=J.11;8(D.P>0){2h(f>J.P){f-=J.P}2h(l>J.P){l-=J.P}2h(f<0){f+=J.P}2h(l<0){l+=J.P}}E c=y.14(),$i;8(l>f){$i=c.17(f,l)}O{$i=$(c.17(f,J.P).3F().70(c.17(0,l).3F()))}8(1o(b)){b.1g($13,$i)}G $i});y.12(H(\'26\',F)+\' \'+H(\'2d\',F)+\' \'+H(\'20\',F),C(e,a){e.1f();E b=e.5x.17(F.3x.42.S),5P=z[b];8(1o(a)){a.1g($13,5P)}G 5P});y.12(H(\'4A\',F),C(e,a,b,c){e.1f();E d=K;8(1o(a)){a.1g($13,7)}O 8(1D(a)){31=$.1N(I,{},31,a);8(b!==K)d=I;O 7=$.1N(I,{},7,a)}O 8(!1G(a)){8(1o(b)){E f=4O(\'7.\'+a);8(1G(f)){f=\'\'}b.1g($13,f)}O 8(!1G(b)){8(2X c!==\'3c\')c=I;4O(\'31.\'+a+\' = b\');8(c!==K)d=I;O 4O(\'7.\'+a+\' = b\')}O{G 4O(\'7.\'+a)}}8(d){1U(y.14(),7);y.57(31);y.5Q();E g=4P(y,7);y.R(H(\'3H\',F),[I,g])}G 7});y.12(H(\'5O\',F),C(e,a,b){e.1f();8(1G(a)){a=$(\'8d\')}O 8(1p(a)){a=$(a)}8(!2u(a)||a.S==0){G 18(F,\'2p a 5N 2g.\')}8(!1p(b)){b=\'a.6p\'}a.8e(b).1W(C(){E h=1k.7a||\'\';8(h.S>0&&y.14().7b($(h))!=-1){$(1k).23(\'5R\').5R(C(e){e.2E();y.R(H(\'3k\',F),h)})}});G I});y.12(H(\'3H\',F),C(e,b,c){e.1f();8(!7.1a.1z){G}E d=7.1a.D||7.D.L,4Q=1I.2A(J.P/d);8(b){8(7.1a.3K){7.1a.1z.14().2t();7.1a.1z.1W(C(){1j(E a=0;a<4Q;a++){E i=y.14().1O(3J(a*d,0,I,J,y));$(1k).77(7.1a.3K.1g(i[0],a+1))}})}7.1a.1z.1W(C(){$(1k).14().23(7.1a.3L).1W(C(a){$(1k).12(7.1a.3L,C(e){e.2E();y.R(H(\'3k\',F),[a*d,-7.1a.4R,I,7.1a])})})})}E f=y.1Q(H(\'46\',F))+7.1a.4R;8(f>=4Q){f=0}8(f<0){f=4Q-1}7.1a.1z.1W(C(){$(1k).14().2O(2z(\'7c\',F)).1O(f).36(2z(\'7c\',F))});G I});y.12(H(\'4N\',F),C(e){E a=7.D.L,2F=y.14(),2w=5b($1s,7,\'N\');J.P=2F.S;7.4p=5c(7,2w);8(z.4q){7[7.d[\'N\']]=4r(2w,z.4q)}8(7.2m){7.D.N=7.D.3M.N;7.D.1d=7.D.3M.1d;7=5e(7,2F,2w);a=7.D.L;5s(7,2F)}O 8(7.D.T.1c){a=32(2F,7,0)}O 8(7.D.1t!=\'*\'){a=3U(2F,7,0)}8(!7.1T&&J.11!=0&&a>J.11){8(7.D.T.1c){E b=4B(2F,7,J.11)-J.11}O 8(7.D.1t!=\'*\'){E b=7d(2F,7,J.11)-J.11}O{E b=7.D.L-J.11}18(F,\'8f 8g-1T: 8h \'+b+\' D 5A.\');y.R(H(\'V\',F),b)}7.D.L=2x(a,7,7.D.T.2c,$13);7.D.T.1Z=7.D.L;7=5g(7,2F);E c=4P(y,7);y.R(H(\'3H\',F),[I,c]);4S(7,J.P,F);3A(7,J.11,F);G c});y.12(H(\'4n\',F),C(e,a){e.1f();1u=3s(1u);y.1m(\'56\',K);y.R(H(\'5v\',F));8(a){y.R(H(\'75\',F))}1U(y.14(),7);8(7.2m){y.14().1W(C(){$(1k).X($(1k).1m(\'7e\'))})}y.X(y.1m(\'5q\'));y.5t();y.5S();$1s.8i(y);G I});y.12(H(\'18\',F),C(e){18(F,\'3w N: \'+7.N);18(F,\'3w 1d: \'+7.1d);18(F,\'7f 8j: \'+7.D.N);18(F,\'7f 8k: \'+7.D.1d);18(F,\'48 4a D L: \'+7.D.L);8(7.M.1H){18(F,\'48 4a D 5T 8l: \'+7.M.D)}8(7.V.W){18(F,\'48 4a D 5T 5A: \'+7.V.D)}8(7.Y.W){18(F,\'48 4a D 5T 72: \'+7.Y.D)}G F.18});y.12(\'3o\',C(e,n,o){e.1f();G y.1Q(H(n,F),o)})};y.5t=C(){y.23(H(\'\',F));y.23(H(\'\',F,K));y.23(\'3o\')};y.5Q=C(){y.5S();4S(7,J.P,F);3A(7,J.11,F);8(7.M.2G){E b=3N(7.M.2G);$1s.12(H(\'4T\',F,K),C(){y.R(H(\'3a\',F),b)}).12(H(\'4U\',F,K),C(){y.R(H(\'3f\',F))})}8(7.M.W){7.M.W.12(H(7.M.3L,F,K),C(e){e.2E();E a=K,b=2H;8(z.26){a=\'1H\'}O 8(7.M.4V){a=\'3a\';b=3N(7.M.4V)}8(a){y.R(H(a,F),b)}})}8(7.V.W){7.V.W.12(H(7.V.3L,F,K),C(e){e.2E();y.R(H(\'V\',F))});8(7.V.2G){E b=3N(7.V.2G);7.V.W.12(H(\'4T\',F,K),C(){y.R(H(\'3a\',F),b)}).12(H(\'4U\',F,K),C(){y.R(H(\'3f\',F))})}}8(7.Y.W){7.Y.W.12(H(7.Y.3L,F,K),C(e){e.2E();y.R(H(\'Y\',F))});8(7.Y.2G){E b=3N(7.Y.2G);7.Y.W.12(H(\'4T\',F,K),C(){y.R(H(\'3a\',F),b)}).12(H(\'4U\',F,K),C(){y.R(H(\'3f\',F))})}}8(7.1a.1z){8(7.1a.2G){E b=3N(7.1a.2G);7.1a.1z.12(H(\'4T\',F,K),C(){y.R(H(\'3a\',F),b)}).12(H(\'4U\',F,K),C(){y.R(H(\'3f\',F))})}}8(7.V.2Y||7.Y.2Y){$(4b).12(H(\'7g\',F,K,I,I),C(e){E k=e.7h;8(k==7.Y.2Y){e.2E();y.R(H(\'Y\',F))}8(k==7.V.2Y){e.2E();y.R(H(\'V\',F))}})}8(7.1a.4W){$(4b).12(H(\'7g\',F,K,I,I),C(e){E k=e.7h;8(k>=49&&k<58){k=(k-49)*7.D.L;8(k<=J.P){e.2E();y.R(H(\'3k\',F),[k,0,I,7.1a])}}})}8(7.V.4X||7.Y.4X){2K(\'4c 4d-7i\',\'4c 8m-7i\');8($.1r.4d){E c=(7.V.4X)?C(){y.R(H(\'V\',F))}:2H,4e=(7.Y.4X)?C(){y.R(H(\'Y\',F))}:2H;8(4e||4e){8(!z.4d){z.4d=I;E d={\'8n\':30,\'8o\':30,\'8p\':I};1B(7.2b){Q\'4o\':Q\'5U\':d.8q=c;d.8r=4e;16;2y:d.8s=4e;d.8t=c}$1s.4d(d)}}}}8($.1r.1q){E f=\'8u\'8v 3l;8((f&&7.1q.4f)||(!f&&7.1q.5V)){E g=$.1N(I,{},7.V,7.1q),7j=$.1N(I,{},7.Y,7.1q),5W=C(){y.R(H(\'V\',F),[g])},5X=C(){y.R(H(\'Y\',F),[7j])};1B(7.2b){Q\'4o\':Q\'5U\':7.1q.2I.8w=5X;7.1q.2I.8x=5W;16;2y:7.1q.2I.8y=5X;7.1q.2I.8z=5W}8(z.1q){y.1q(\'4n\')}$1s.1q(7.1q.2I);$1s.X(\'7k\',\'8A\');z.1q=I}}8($.1r.1h){8(7.V.1h){2K(\'7l V.1h 7m\',\'4c 1h 4A 2g\');7.V.1h=2H;7.1h={D:5Y(7.V.1h)}}8(7.Y.1h){2K(\'7l Y.1h 7m\',\'4c 1h 4A 2g\');7.Y.1h=2H;7.1h={D:5Y(7.Y.1h)}}8(7.1h){E h=$.1N(I,{},7.V,7.1h),7n=$.1N(I,{},7.Y,7.1h);8(z.1h){$1s.23(H(\'1h\',F,K))}$1s.12(H(\'1h\',F,K),C(e,a){e.2E();8(a>0){y.R(H(\'V\',F),[h])}O{y.R(H(\'Y\',F),[7n])}});z.1h=I}}8(7.M.1H){y.R(H(\'1H\',F),7.M.5Z)}8(z.6B){E i=$(3l),61=0,62=0;i.12(H(\'8B\',F,K,I,I),C(e){E a=i.N(),63=i.1d();8(a!=61||63!=62){y.R(H(\'5v\',F));8(7.M.64&&!z.26){y.R(H(\'1H\',F))}1U(y.14(),7);y.R(H(\'4N\',F));61=a;62=63}})}};y.5S=C(){E a=H(\'\',F),3O=H(\'\',F,K);65=H(\'\',F,K,I,I);$(4b).23(65);$(3l).23(65);$1s.23(3O);8(7.M.W){7.M.W.23(3O)}8(7.V.W){7.V.W.23(3O)}8(7.Y.W){7.Y.W.23(3O)}8(7.1a.1z){7.1a.1z.23(3O);8(7.1a.3K){7.1a.1z.14().2t()}}8(z.1q){y.1q(\'4n\');$1s.X(\'7k\',\'2y\');z.1q=K}8(z.1h){z.1h=K}4S(7,\'4g\',F);3A(7,\'2O\',F)};8(1l(w)){w={\'18\':w}}E z={\'2b\':\'Y\',\'26\':I,\'20\':K,\'2d\':K,\'1h\':K,\'1q\':K},J={\'P\':y.14().S,\'11\':0},1u={\'M\':2H,\'1e\':2H,\'2M\':2o(),\'3v\':0},U={\'2d\':K,\'1C\':0,\'2M\':0,\'2k\':\'\',\'19\':[]},2a={\'3G\':[],\'3g\':[]},2D=[],F=$.1N(I,{},$.1r.1v.7o,w),7={},31=$.1N(I,{},u),$1s=y.8C(\'<\'+F.66.54+\' 8D="\'+F.66.7p+\'" />\').68();F.4l=y.4l;F.3S=$.1r.1v.3S++;y.57(31,I,55);y.6J();y.6P();y.5Q();8(2V(7.D.3m)){E A=7.D.3m}O{E A=[];8(7.D.3m!=0){A.1b(7.D.3m)}}8(7.25){A.8E(4h(7q(7.25),10))}8(A.S>0){1j(E a=0,l=A.S;a<l;a++){E s=A[a];8(s==0){69}8(s===I){s=3l.8F.7a;8(s.S<1){69}}O 8(s===\'7r\'){s=1I.4i(1I.7r()*J.P)}8(y.1Q(H(\'3k\',F),[s,0,I,{1V:\'3Y\'}])){16}}}E B=4P(y,7),7s=3I(y.14(),7);8(7.7t){7.7t.1g($13,{\'N\':B.N,\'1d\':B.1d,\'D\':7s})}y.R(H(\'3H\',F),[I,B]);y.R(H(\'5O\',F));8(F.18){y.R(H(\'18\',F))}G y};$.1r.1v.3S=1;$.1r.1v.5a={\'2n\':K,\'3z\':I,\'1T\':I,\'2m\':K,\'2b\':\'1n\',\'D\':{\'3m\':0},\'1M\':{\'2k\':\'8G\',\'1C\':6E,\'2G\':K,\'3L\':\'5R\',\'3y\':K}};$.1r.1v.7o={\'18\':K,\'3x\':{\'42\':\'\',\'7u\':\'8H\'},\'66\':{\'54\':\'8I\',\'7p\':\'8J\'},\'6a\':{}};$.1r.1v.7v=C(a){G\'<a 8K="#"><7w>\'+a+\'</7w></a>\'};$.1r.1v.7x=C(a){$(1k).X(\'N\',a+\'%\')};$.1r.1v.25={3F:C(n){n+=\'=\';E b=4b.25.3P(\';\');1j(E a=0,l=b.S;a<l;a++){E c=b[a];2h(c.8L(0)==\' \'){c=c.17(1)}8(c.3Q(n)==0){G c.17(n.S)}}G 0},6b:C(n,v,d){E e="";8(d){E a=6c 7y();a.8M(a.2o()+(d*24*60*60*8N));e="; 8O="+a.8P()}4b.25=n+\'=\'+v+e+\'; 8Q=/\'},2t:C(n){$.1r.1v.25.6b(n,"",-1)}};C 44(d,e){G{19:[],1C:d,8R:d,2k:e,2M:2o()}}C 2P(s){8(1D(s.3t)){2P(s.3t)}1j(E a=0,l=s.19.S;a<l;a++){E b=s.19[a];8(!b){69}8(b[3]){b[0].5u()}b[0].8S(b[1],{8T:b[2],1C:s.1C,2k:s.2k})}8(1D(s.3u)){2P(s.3u)}}C 3Z(s,c){8(!1l(c)){c=I}8(1D(s.3t)){3Z(s.3t,c)}1j(E a=0,l=s.19.S;a<l;a++){E b=s.19[a];b[0].5u(I);8(c){b[0].X(b[1]);8(1o(b[2])){b[2]()}}}8(1D(s.3u)){3Z(s.3u,c)}}C 5I(a,b,o){8(b){b.2t()}1B(o.1V){Q\'1w\':Q\'3j\':Q\'1J-1w\':Q\'22-1w\':a.X(\'1t\',\'\');16}}C 45(d,o,b,a,c){8(o[b]){o[b].1g(d,a)}8(c[b].S){1j(E i=0,l=c[b].S;i<l;i++){c[b][i].1g(d,a)}}G[]}C 5J(a,q,c){8(q.S){a.R(H(q[0][0],c),q[0][1]);q.8U()}G q}C 5B(b){b.1W(C(){E a=$(1k);a.1m(\'7z\',a.2f(\':3r\')).4g()})}C 5G(b){8(b){b.1W(C(){E a=$(1k);8(!a.1m(\'7z\')){a.4j()}})}}C 3s(t){8(t.M){8V(t.M)}8(t.1e){8W(t.1e)}G t}C 5H(a,b,c,d,e,f,g){G{\'N\':g.N,\'1d\':g.1d,\'D\':{\'1Z\':a,\'8X\':b,\'6c\':c,\'L\':c},\'1M\':{\'D\':d,\'2b\':e,\'1C\':f}}}C 5D(a,o,b,c){E d=a.1C;8(a.1V==\'3Y\'){G 0}8(d==\'M\'){d=o.1M.1C/o.1M.D*b}O 8(d<10){d=c/d}8(d<1){G 0}8(a.1V==\'1w\'){d=d/2}G 1I.79(d)}C 4S(o,t,c){E a=(Z(o.D.4z))?o.D.4z:o.D.L+1;8(t==\'4j\'||t==\'4g\'){E f=t}O 8(a>t){18(c,\'2p 6R D (\'+t+\' P, \'+a+\' 6S): 8Y 8Z.\');E f=\'4g\'}O{E f=\'4j\'}E s=(f==\'4j\')?\'2O\':\'36\',h=2z(\'3r\',c);8(o.M.W){o.M.W[f]()[s](h)}8(o.V.W){o.V.W[f]()[s](h)}8(o.Y.W){o.Y.W[f]()[s](h)}8(o.1a.1z){o.1a.1z[f]()[s](h)}}C 3A(o,f,c){8(o.1T||o.3z)G;E a=(f==\'2O\'||f==\'36\')?f:K,4Y=2z(\'90\',c);8(o.M.W&&a){o.M.W[a](4Y)}8(o.V.W){E b=a||(f==0)?\'36\':\'2O\';o.V.W[b](4Y)}8(o.Y.W){E b=a||(f==o.D.L)?\'36\':\'2O\';o.Y.W[b](4Y)}}C 3R(a,b){8(1o(b)){b=b.1g(a)}O 8(1G(b)){b={}}G b}C 6r(a,b){b=3R(a,b);8(Z(b)){b={\'L\':b}}O 8(b==\'1c\'){b={\'L\':b,\'N\':b,\'1d\':b}}O 8(!1D(b)){b={}}G b}C 6s(a,b){b=3R(a,b);8(Z(b)){8(b<=50){b={\'D\':b}}O{b={\'1C\':b}}}O 8(1p(b)){b={\'2k\':b}}O 8(!1D(b)){b={}}G b}C 4Z(a,b){b=3R(a,b);8(1p(b)){E c=6d(b);8(c==-1){b=$(b)}O{b=c}}G b}C 6t(a,b){b=4Z(a,b);8(2u(b)){b={\'W\':b}}O 8(1l(b)){b={\'1H\':b}}O 8(Z(b)){b={\'2L\':b}}8(b.1e){8(1p(b.1e)||2u(b.1e)){b.1e={\'2q\':b.1e}}}G b}C 6F(a,b){8(1o(b.W)){b.W=b.W.1g(a)}8(1p(b.W)){b.W=$(b.W)}8(!1l(b.1H)){b.1H=I}8(!Z(b.5Z)){b.5Z=0}8(1G(b.4V)){b.4V=I}8(!1l(b.64)){b.64=I}8(!Z(b.2L)){b.2L=(b.1C<10)?91:b.1C*5}8(b.1e){8(1o(b.1e.2q)){b.1e.2q=b.1e.2q.1g(a)}8(1p(b.1e.2q)){b.1e.2q=$(b.1e.2q)}8(b.1e.2q){8(!1o(b.1e.4y)){b.1e.4y=$.1r.1v.7x}8(!Z(b.1e.5w)){b.1e.5w=50}}O{b.1e=K}}G b}C 59(a,b){b=4Z(a,b);8(2u(b)){b={\'W\':b}}O 8(Z(b)){b={\'2Y\':b}}G b}C 5h(a,b){8(1o(b.W)){b.W=b.W.1g(a)}8(1p(b.W)){b.W=$(b.W)}8(1p(b.2Y)){b.2Y=6d(b.2Y)}G b}C 6u(a,b){b=4Z(a,b);8(2u(b)){b={\'1z\':b}}O 8(1l(b)){b={\'4W\':b}}G b}C 6G(a,b){8(1o(b.1z)){b.1z=b.1z.1g(a)}8(1p(b.1z)){b.1z=$(b.1z)}8(!Z(b.D)){b.D=K}8(!1l(b.4W)){b.4W=K}8(!1o(b.3K)&&!51(b.3K)){b.3K=$.1r.1v.7v}8(!Z(b.4R)){b.4R=0}G b}C 6v(a,b){8(1o(b)){b=b.1g(a)}8(1G(b)){b={\'4f\':K}}8(3p(b)){b={\'4f\':b}}O 8(Z(b)){b={\'D\':b}}G b}C 6H(a,b){8(!1l(b.4f)){b.4f=I}8(!1l(b.5V)){b.5V=K}8(!1D(b.2I)){b.2I={}}8(!1l(b.2I.7A)){b.2I.7A=K}G b}C 6w(a,b){8(1o(b)){b=b.1g(a)}8(3p(b)){b={}}O 8(Z(b)){b={\'D\':b}}O 8(1G(b)){b=K}G b}C 6I(a,b){G b}C 3J(a,b,c,d,e){8(1p(a)){a=$(a,e)}8(1D(a)){a=$(a,e)}8(2u(a)){a=e.14().7b(a);8(!1l(c)){c=K}}O{8(!1l(c)){c=I}}8(!Z(a)){a=0}8(!Z(b)){b=0}8(c){a+=d.11}a+=b;8(d.P>0){2h(a>=d.P){a-=d.P}2h(a<0){a+=d.P}}G a}C 4B(i,o,s){E t=0,x=0;1j(E a=s;a>=0;a--){E j=i.1O(a);t+=(j.2f(\':L\'))?j[o.d[\'2v\']](I):0;8(t>o.4p){G x}8(a==0){a=i.S}x++}}C 7d(i,o,s){G 6e(i,o.D.1t,o.D.T.4s,s)}C 6V(i,o,s,m){G 6e(i,o.D.1t,m,s)}C 6e(i,f,m,s){E t=0,x=0;1j(E a=s,l=i.S;a>=0;a--){x++;8(x==l){G x}E j=i.1O(a);8(j.2f(f)){t++;8(t==m){G x}}8(a==0){a=l}}}C 5z(a,o){G o.D.T.4s||a.14().17(0,o.D.L).1t(o.D.1t).S}C 32(i,o,s){E t=0,x=0;1j(E a=s,l=i.S-1;a<=l;a++){E j=i.1O(a);t+=(j.2f(\':L\'))?j[o.d[\'2v\']](I):0;8(t>o.4p){G x}x++;8(x==l+1){G x}8(a==l){a=-1}}}C 5L(i,o,s,l){E v=32(i,o,s);8(!o.1T){8(s+v>l){v=l-s}}G v}C 3U(i,o,s){G 6f(i,o.D.1t,o.D.T.4s,s,o.1T)}C 71(i,o,s,m){G 6f(i,o.D.1t,m+1,s,o.1T)-1}C 6f(i,f,m,s,c){E t=0,x=0;1j(E a=s,l=i.S-1;a<=l;a++){x++;8(x>=l){G x}E j=i.1O(a);8(j.2f(f)){t++;8(t==m){G x}}8(a==l){a=-1}}}C 3I(i,o){G i.17(0,o.D.L)}C 6X(i,o,n){G i.17(n,o.D.T.1Z+n)}C 6Y(i,o){G i.17(0,o.D.L)}C 73(i,o){G i.17(0,o.D.T.1Z)}C 74(i,o,n){G i.17(n,o.D.L+n)}C 4w(i,o,d){8(o.1R){8(!1p(d)){d=\'29\'}i.1W(C(){E j=$(1k),m=4h(j.X(o.d[\'1S\']),10);8(!Z(m)){m=0}j.1m(d,m)})}}C 1U(i,o,m){8(o.1R){E x=(1l(m))?m:K;8(!Z(m)){m=0}4w(i,o,\'7B\');i.1W(C(){E j=$(1k);j.X(o.d[\'1S\'],((x)?j.1m(\'7B\'):m+j.1m(\'29\')))})}}C 5r(i,o){8(o.2m){i.1W(C(){E j=$(1k),s=5n(j,[\'N\',\'1d\']);j.1m(\'7e\',s)})}}C 5s(o,b){E c=o.D.L,7C=o.D[o.d[\'N\']],6g=o[o.d[\'1d\']],7D=3T(6g);b.1W(C(){E a=$(1k),6h=7C-7E(a,o,\'92\');a[o.d[\'N\']](6h);8(7D){a[o.d[\'1d\']](4r(6h,6g))}})}C 4P(a,o){E b=a.68(),$i=a.14(),$v=3I($i,o),52=4H(4I($v,o,I),o,K);b.X(52);8(o.1R){E p=o.1i,r=p[o.d[1]];8(o.1A&&r<0){r=0}E c=$v.3i();c.X(o.d[\'1S\'],c.1m(\'29\')+r);a.X(o.d[\'3q\'],p[o.d[0]]);a.X(o.d[\'1n\'],p[o.d[3]])}a.X(o.d[\'N\'],52[o.d[\'N\']]+(2R($i,o,\'N\')*2));a.X(o.d[\'1d\'],6i($i,o,\'1d\'));G 52}C 4I(i,o,a){G[2R(i,o,\'N\',a),6i(i,o,\'1d\',a)]}C 6i(i,o,a,b){8(!1l(b)){b=K}8(Z(o[o.d[a]])&&b){G o[o.d[a]]}8(Z(o.D[o.d[a]])){G o.D[o.d[a]]}a=(a.6j().3Q(\'N\')>-1)?\'2v\':\'3n\';G 4k(i,o,a)}C 4k(i,o,b){E s=0;1j(E a=0,l=i.S;a<l;a++){E j=i.1O(a);E m=(j.2f(\':L\'))?j[o.d[b]](I):0;8(s<m){s=m}}G s}C 2R(i,o,b,c){8(!1l(c)){c=K}8(Z(o[o.d[b]])&&c){G o[o.d[b]]}8(Z(o.D[o.d[b]])){G o.D[o.d[b]]*i.S}E d=(b.6j().3Q(\'N\')>-1)?\'2v\':\'3n\',s=0;1j(E a=0,l=i.S;a<l;a++){E j=i.1O(a);s+=(j.2f(\':L\'))?j[o.d[d]](I):0}G s}C 5b(a,o,d){E b=a.2f(\':L\');8(b){a.4g()}E s=a.68()[o.d[d]]();8(b){a.4j()}G s}C 5c(o,a){G(Z(o[o.d[\'N\']]))?o[o.d[\'N\']]:a}C 6k(i,o,b){E s=K,v=K;1j(E a=0,l=i.S;a<l;a++){E j=i.1O(a);E c=(j.2f(\':L\'))?j[o.d[b]](I):0;8(s===K){s=c}O 8(s!=c){v=I}8(s==0){v=I}}G v}C 7E(i,o,d){G i[o.d[\'93\'+d]](I)-i[o.d[d.6j()]]()}C 4r(s,o){8(3T(o)){o=4h(o.17(0,-1),10);8(!Z(o)){G s}s*=o/2J}G s}C H(n,c,a,b,d){8(!1l(a)){a=I}8(!1l(b)){b=I}8(!1l(d)){d=K}8(a){n=c.3x.42+n}8(b){n=n+\'.\'+c.3x.7u}8(b&&d){n+=c.3S}G n}C 2z(n,c){G(1p(c.6a[n]))?c.6a[n]:n}C 4H(a,o,p){8(!1l(p)){p=I}E b=(o.1R&&p)?o.1i:[0,0,0,0];E c={};c[o.d[\'N\']]=a[0]+b[1]+b[3];c[o.d[\'1d\']]=a[1]+b[0]+b[2];G c}C 3d(c,d){E e=[];1j(E a=0,7F=c.S;a<7F;a++){1j(E b=0,7G=d.S;b<7G;b++){8(d[b].3Q(2X c[a])>-1&&1G(e[b])){e[b]=c[a];16}}}G e}C 6D(p){8(1G(p)){G[0,0,0,0]}8(Z(p)){G[p,p,p,p]}8(1p(p)){p=p.3P(\'94\').7H(\'\').3P(\'95\').7H(\'\').3P(\' \')}8(!2V(p)){G[0,0,0,0]}1j(E i=0;i<4;i++){p[i]=4h(p[i],10)}1B(p.S){Q 0:G[0,0,0,0];Q 1:G[p[0],p[0],p[0],p[0]];Q 2:G[p[0],p[1],p[0],p[1]];Q 3:G[p[0],p[1],p[2],p[1]];2y:G[p[0],p[1],p[2],p[3]]}}C 4G(a,o){E x=(Z(o[o.d[\'N\']]))?1I.2A(o[o.d[\'N\']]-2R(a,o,\'N\')):0;1B(o.1A){Q\'1n\':G[0,x];Q\'35\':G[x,0];Q\'5d\':2y:G[1I.2A(x/2),1I.4i(x/2)]}}C 6x(o){E a=[[\'N\',\'7I\',\'2v\',\'1d\',\'7J\',\'3n\',\'1n\',\'3q\',\'1S\',0,1,2,3],[\'1d\',\'7J\',\'3n\',\'N\',\'7I\',\'2v\',\'3q\',\'1n\',\'5o\',3,2,1,0]];E b=a[0].S,7K=(o.2b==\'35\'||o.2b==\'1n\')?0:1;E c={};1j(E d=0;d<b;d++){c[a[0][d]]=a[7K][d]}G c}C 4C(x,o,a,b){E v=x;8(1o(a)){v=a.1g(b,v)}O 8(1p(a)){E p=a.3P(\'+\'),m=a.3P(\'-\');8(m.S>p.S){E c=I,6l=m[0],2Z=m[1]}O{E c=K,6l=p[0],2Z=p[1]}1B(6l){Q\'96\':v=(x%2==1)?x-1:x;16;Q\'97\':v=(x%2==0)?x-1:x;16;2y:v=x;16}2Z=4h(2Z,10);8(Z(2Z)){8(c){2Z=-2Z}v+=2Z}}8(!Z(v)||v<1){v=1}G v}C 2x(x,o,a,b){G 6m(4C(x,o,a,b),o.D.T)}C 6m(v,i){8(Z(i.34)&&v<i.34){v=i.34}8(Z(i.1X)&&v>i.1X){v=i.1X}8(v<1){v=1}G v}C 5i(s){8(!2V(s)){s=[[s]]}8(!2V(s[0])){s=[s]}1j(E j=0,l=s.S;j<l;j++){8(1p(s[j][0])){s[j][0]=$(s[j][0])}8(!1l(s[j][1])){s[j][1]=I}8(!1l(s[j][2])){s[j][2]=I}8(!Z(s[j][3])){s[j][3]=0}}G s}C 6d(k){8(k==\'35\'){G 39}8(k==\'1n\'){G 37}8(k==\'4o\'){G 38}8(k==\'5U\'){G 40}G-1}C 5K(n,a,c){8(n){E v=a.1Q(H(\'4m\',c));$.1r.1v.25.6b(n,v)}}C 7q(n){E c=$.1r.1v.25.3F(n);G(c==\'\')?0:c}C 5n(a,b){E c={},53;1j(E p=0,l=b.S;p<l;p++){53=b[p];c[53]=a.X(53)}G c}C 6y(a,b,c,d){8(!1D(a.T)){a.T={}}8(!1D(a.3M)){a.3M={}}8(a.3m==0&&Z(d)){a.3m=d}8(1D(a.L)){a.T.34=a.L.34;a.T.1X=a.L.1X;a.L=K}O 8(1p(a.L)){8(a.L==\'1c\'){a.T.1c=I}O{a.T.2c=a.L}a.L=K}O 8(1o(a.L)){a.T.2c=a.L;a.L=K}8(!1p(a.1t)){a.1t=(c.1t(\':3r\').S>0)?\':L\':\'*\'}8(!a[b.d[\'N\']]){8(b.2m){18(I,\'7L a \'+b.d[\'N\']+\' 1j 4c D!\');a[b.d[\'N\']]=4k(c,b,\'2v\')}O{a[b.d[\'N\']]=(6k(c,b,\'2v\'))?\'1c\':c[b.d[\'2v\']](I)}}8(!a[b.d[\'1d\']]){a[b.d[\'1d\']]=(6k(c,b,\'3n\'))?\'1c\':c[b.d[\'3n\']](I)}a.3M.N=a.N;a.3M.1d=a.1d;G a}C 6C(a,b){8(a.D[a.d[\'N\']]==\'1c\'){a.D.T.1c=I}8(!a.D.T.1c){8(Z(a[a.d[\'N\']])){a.D.L=1I.4i(a[a.d[\'N\']]/a.D[a.d[\'N\']])}O{a.D.L=1I.4i(b/a.D[a.d[\'N\']]);a[a.d[\'N\']]=a.D.L*a.D[a.d[\'N\']];8(!a.D.T.2c){a.1A=K}}8(a.D.L==\'98\'||a.D.L<1){18(I,\'2p a 5N 27 4a L D: 7L 4D "1c".\');a.D.T.1c=I}}G a}C 6z(a,b,c){8(a==\'M\'){a=4k(c,b,\'2v\')}G a}C 6A(a,b,c){8(a==\'M\'){a=4k(c,b,\'3n\')}8(!a){a=b.D[b.d[\'1d\']]}G a}C 5g(o,a){E p=4G(3I(a,o),o);o.1i[o.d[1]]=p[1];o.1i[o.d[3]]=p[0];G o}C 5e(o,a,b){E c=6m(1I.2A(o[o.d[\'N\']]/o.D[o.d[\'N\']]),o.D.T);8(c>a.S){c=a.S}E d=1I.4i(o[o.d[\'N\']]/c);o.D.L=c;o.D[o.d[\'N\']]=d;o[o.d[\'N\']]=c*d;G o}C 3N(p){8(1p(p)){E i=(p.3Q(\'99\')>-1)?I:K,r=(p.3Q(\'3f\')>-1)?I:K}O{E i=r=K}G[i,r]}C 5Y(a){G(Z(a))?a:2H}C 6n(a){G(a===2H)}C 1G(a){G(6n(a)||2X a==\'7M\'||a===\'\'||a===\'7M\')}C 2V(a){G(a 2W 9a)}C 2u(a){G(a 2W 7N)}C 1D(a){G((a 2W 9b||2X a==\'2g\')&&!6n(a)&&!2u(a)&&!2V(a))}C Z(a){G((a 2W 48||2X a==\'27\')&&!9c(a))}C 1p(a){G((a 2W 9d||2X a==\'2N\')&&!1G(a)&&!3p(a)&&!51(a))}C 1o(a){G(a 2W 9e||2X a==\'C\')}C 1l(a){G(a 2W 9f||2X a==\'3c\'||3p(a)||51(a))}C 3p(a){G(a===I||a===\'I\')}C 51(a){G(a===K||a===\'K\')}C 3T(x){G(1p(x)&&x.17(-1)==\'%\')}C 2o(){G 6c 7y().2o()}C 2K(o,n){18(I,o+\' 2f 9g, 9h 1j 9i 9j 9k 9l. 9m \'+n+\' 9n.\')}C 18(d,m){8(1D(d)){E s=\' (\'+d.4l+\')\';d=d.18}O{E s=\'\'}8(!d){G K}8(1p(m)){m=\'1v\'+s+\': \'+m}O{m=[\'1v\'+s+\':\',m]}8(3l.6o&&3l.6o.7O){3l.6o.7O(m)}G K}$.1N($.2k,{\'9o\':C(t){E a=t*t;G t*(-a*t+4*a-6*t+4)},\'9p\':C(t){G t*(4*t*t-9*t+6)},\'9q\':C(t){E a=t*t;G t*(33*a*a-9r*a*t+9s*a-67*t+15)}})})(7N);',62,587,'|||||||opts|if||||||||||||||||||||||||||||||function|items|var|conf|return|cf_e|true|itms|false|visible|auto|width|else|total|case|trigger|length|visibleConf|scrl|prev|button|css|next|is_number||first|bind|tt0|children||break|slice|debug|anims|pagination|push|variable|height|progress|stopPropagation|call|mousewheel|padding|for|this|is_boolean|data|left|is_function|is_string|swipe|fn|wrp|filter|tmrs|carouFredSel|fade|_onafter|_moveitems|container|align|switch|duration|is_object|_s_paddingold|_s_paddingcur|is_undefined|play|Math|cover|_position|opacity|scroll|extend|eq|_a_wrapper|triggerHandler|usePadding|marginRight|circular|sz_resetMargin|fx|each|max|i_cur_l|old|isScrolling|i_old_l|uncover|unbind||cookie|isPaused|number|a_cfs|_cfs_origCssMargin|clbk|direction|adjust|isStopped|stopImmediatePropagation|is|object|while|i_new|w_siz|easing|nr|responsive|synchronise|getTime|Not|bar|i_new_l|a_cur|remove|is_jquery|outerWidth|avail_primary|cf_getItemsAdjust|default|cf_c|ceil|pR|_s_paddingnew|queu|preventDefault|a_itm|pauseOnHover|null|options|100|deprecated|timeoutDuration|startTime|string|removeClass|sc_startScroll|i_skp|ms_getTotalSize|a_old|a_lef|a_dur|is_array|instanceof|typeof|key|adj||opts_orig|gn_getVisibleItemsNext||min|right|addClass||||pause|perc|boolean|cf_sortParams|scrolling|resume|onAfter|i_old|last|crossfade|slideTo|window|start|outerHeight|_cfs_triggerEvent|is_true|top|hidden|sc_clearTimers|pre|post|timePassed|Carousel|events|queue|infinite|nv_enableNavi|i_siz|i_siz_vis|_a_paddingold|_a_paddingcur|get|onBefore|updatePageStatus|gi_getCurrentItems|gn_getItemIndex|anchorBuilder|event|sizesConf|bt_pauseOnHoverConfig|ns2|split|indexOf|go_getObject|serialNumber|is_percentage|gn_getVisibleItemsNextFilter|orgCSS|position|zIndex|none|sc_stopScroll||dur2|prefix|appendTo|sc_setScroll|sc_fireCallbacks|currentPage|before|Number||of|document|the|touchwipe|wN|onTouch|hide|parseInt|floor|show|ms_getTrueLargestSize|selector|currentPosition|destroy|up|maxDimension|primarySizePercentage|ms_getPercentage|org|onTimeoutStart|onTimeoutPause|onTimeoutEnd|sz_storeMargin|stopped|updater|minimum|configuration|gn_getVisibleItemsPrev|cf_getAdjust|to|onEnd|clone|cf_getAlignPadding|cf_mapWrapperSizes|ms_getSizes|a_wsz|a_new|a_cfs_vis|end|updateSizes|eval|sz_setSizes|pgs|deviation|nv_showNavi|mouseenter|mouseleave|pauseOnEvent|keys|wipe|di|go_getNaviObject||is_false|sz|prop|element|starting_position|_cfs_isCarousel|_cfs_init||go_getPrevNextObject|defaults|ms_getParentSize|ms_getMaxDimension|center|in_getResponsiveValues|bottom|in_getAlignPadding|go_complementPrevNextObject|cf_getSynchArr|onPauseStart|onPausePause|onPauseEnd|pauseDuration|in_mapCss|marginBottom|newPosition|_cfs_origCss|sz_storeSizes|sz_setResponsiveSizes|_cfs_unbind_events|stop|finish|interval|type|conditions|gn_getVisibleOrg|backward|sc_hideHiddenItems|a_lef_vis|sc_getDuration|_a_paddingnew|not|sc_showHiddenItems|sc_mapCallbackArguments|sc_afterScroll|sc_fireQueue|cf_setCookie|gn_getVisibleItemsNextTestCircular|slideToPage|valid|linkAnchors|value|_cfs_bind_buttons|click|_cfs_unbind_buttons|scrolled|down|onMouse|swP|swN|bt_mousesheelNumber|delay||_windowWidth|_windowHeight|nh|pauseOnResize|ns3|wrapper||parent|continue|classnames|set|new|cf_getKeyCode|gn_getItemsPrevFilter|gn_getItemsNextFilter|seco|nw|ms_getLargestSize|toLowerCase|ms_hasVariableSizes|sta|cf_getItemAdjustMinMax|is_null|console|caroufredsel|No|go_getItemsObject|go_getScrollObject|go_getAutoObject|go_getPaginationObject|go_getSwipeObject|go_getMousewheelObject|cf_getDimensions|in_complementItems|in_complementPrimarySize|in_complementSecondarySize|upDateOnWindowResize|in_complementVisibleItems|cf_getPadding|500|go_complementAutoObject|go_complementPaginationObject|go_complementSwipeObject|go_complementMousewheelObject|_cfs_build|textAlign|float|marginTop|marginLeft|absolute|_cfs_bind_events|paused|enough|needed|page|slide_|gn_getScrollItemsPrevFilter|Scrolling|gi_getOldItemsPrev|gi_getNewItemsPrev|directscroll|concat|gn_getScrollItemsNextFilter|forward|gi_getOldItemsNext|gi_getNewItemsNext|jumpToStart|after|append|removeItem|round|hash|index|selected|gn_getVisibleItemsPrevFilter|_cfs_origCssSizes|Item|keyup|keyCode|plugin|scN|cursor|The|option|mcN|configs|classname|cf_getCookie|random|itm|onCreate|namespace|pageAnchorBuilder|span|progressbarUpdater|Date|_cfs_isHidden|triggerOnTouchEnd|_cfs_tempCssMargin|newS|secp|ms_getPaddingBorderMargin|l1|l2|join|innerWidth|innerHeight|dx|Set|undefined|jQuery|log|found|caroufredsel_cookie_|relative|fixed|overflow|setInterval|setTimeout|or|Callback|returned|Page|resumed|currently|slide_prev|prependTo|slide_next|prevPage|nextPage|prepend|carousel|insertItem|add|detach|currentVisible|body|find|Preventing|non|sliding|replaceWith|widths|heights|automatically|touchSwipe|min_move_x|min_move_y|preventDefaultEvents|wipeUp|wipeDown|wipeLeft|wipeRight|ontouchstart|in|swipeUp|swipeDown|swipeLeft|swipeRight|move|resize|wrap|class|unshift|location|swing|cfs|div|caroufredsel_wrapper|href|charAt|setTime|1000|expires|toGMTString|path|orgDuration|animate|complete|shift|clearTimeout|clearInterval|skipped|Hiding|navigation|disabled|2500|Width|outer|px|em|even|odd|Infinity|immediate|Array|Object|isNaN|String|Function|Boolean|DEPRECATED|support|it|will|be|removed|Use|instead|quadratic|cubic|elastic|106|126'.split('|'),0,{}));
/*mousewheel*/
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);
/*custom scrollbar*/
(function(c){var b={init:function(e){var f={set_width:false,set_height:false,horizontalScroll:false,scrollInertia:950,mouseWheel:true,mouseWheelPixels:"auto",autoDraggerLength:true,autoHideScrollbar:false,alwaysShowScrollbar:false,snapAmount:null,snapOffset:0,scrollButtons:{enable:false,scrollType:"continuous",scrollSpeed:"auto",scrollAmount:40},advanced:{updateOnBrowserResize:true,updateOnContentResize:false,autoExpandHorizontalScroll:false,autoScrollOnFocus:true,normalizeMouseWheelDelta:false},contentTouchScroll:true,callbacks:{onScrollStart:function(){},onScroll:function(){},onTotalScroll:function(){},onTotalScrollBack:function(){},onTotalScrollOffset:0,onTotalScrollBackOffset:0,whileScrolling:function(){}},theme:"light"},e=c.extend(true,f,e);return this.each(function(){var m=c(this);if(e.set_width){m.css("width",e.set_width)}if(e.set_height){m.css("height",e.set_height)}if(!c(document).data("mCustomScrollbar-index")){c(document).data("mCustomScrollbar-index","1")}else{var t=parseInt(c(document).data("mCustomScrollbar-index"));c(document).data("mCustomScrollbar-index",t+1)}m.wrapInner("<div class='mCustomScrollBox mCS-"+e.theme+"' id='mCSB_"+c(document).data("mCustomScrollbar-index")+"' style='position:relative; height:100%; overflow:hidden; max-width:100%;' />").addClass("mCustomScrollbar _mCS_"+c(document).data("mCustomScrollbar-index"));var g=m.children(".mCustomScrollBox");if(e.horizontalScroll){g.addClass("mCSB_horizontal").wrapInner("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />");var k=g.children(".mCSB_h_wrapper");k.wrapInner("<div class='mCSB_container' style='position:absolute; left:0;' />").children(".mCSB_container").css({width:k.children().outerWidth(),position:"relative"}).unwrap()}else{g.wrapInner("<div class='mCSB_container' style='position:relative; top:0;' />")}var o=g.children(".mCSB_container");if(c.support.touch){o.addClass("mCS_touch")}o.after("<div class='mCSB_scrollTools' style='position:absolute;'><div class='mCSB_draggerContainer'><div class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' style='position:relative;'></div></div><div class='mCSB_draggerRail'></div></div></div>");var l=g.children(".mCSB_scrollTools"),h=l.children(".mCSB_draggerContainer"),q=h.children(".mCSB_dragger");if(e.horizontalScroll){q.data("minDraggerWidth",q.width())}else{q.data("minDraggerHeight",q.height())}if(e.scrollButtons.enable){if(e.horizontalScroll){l.prepend("<a class='mCSB_buttonLeft' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonRight' oncontextmenu='return false;'></a>")}else{l.prepend("<a class='mCSB_buttonUp' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonDown' oncontextmenu='return false;'></a>")}}g.bind("scroll",function(){if(!m.is(".mCS_disabled")){g.scrollTop(0).scrollLeft(0)}});m.data({mCS_Init:true,mCustomScrollbarIndex:c(document).data("mCustomScrollbar-index"),horizontalScroll:e.horizontalScroll,scrollInertia:e.scrollInertia,scrollEasing:"mcsEaseOut",mouseWheel:e.mouseWheel,mouseWheelPixels:e.mouseWheelPixels,autoDraggerLength:e.autoDraggerLength,autoHideScrollbar:e.autoHideScrollbar,alwaysShowScrollbar:e.alwaysShowScrollbar,snapAmount:e.snapAmount,snapOffset:e.snapOffset,scrollButtons_enable:e.scrollButtons.enable,scrollButtons_scrollType:e.scrollButtons.scrollType,scrollButtons_scrollSpeed:e.scrollButtons.scrollSpeed,scrollButtons_scrollAmount:e.scrollButtons.scrollAmount,autoExpandHorizontalScroll:e.advanced.autoExpandHorizontalScroll,autoScrollOnFocus:e.advanced.autoScrollOnFocus,normalizeMouseWheelDelta:e.advanced.normalizeMouseWheelDelta,contentTouchScroll:e.contentTouchScroll,onScrollStart_Callback:e.callbacks.onScrollStart,onScroll_Callback:e.callbacks.onScroll,onTotalScroll_Callback:e.callbacks.onTotalScroll,onTotalScrollBack_Callback:e.callbacks.onTotalScrollBack,onTotalScroll_Offset:e.callbacks.onTotalScrollOffset,onTotalScrollBack_Offset:e.callbacks.onTotalScrollBackOffset,whileScrolling_Callback:e.callbacks.whileScrolling,bindEvent_scrollbar_drag:false,bindEvent_content_touch:false,bindEvent_scrollbar_click:false,bindEvent_mousewheel:false,bindEvent_buttonsContinuous_y:false,bindEvent_buttonsContinuous_x:false,bindEvent_buttonsPixels_y:false,bindEvent_buttonsPixels_x:false,bindEvent_focusin:false,bindEvent_autoHideScrollbar:false,mCSB_buttonScrollRight:false,mCSB_buttonScrollLeft:false,mCSB_buttonScrollDown:false,mCSB_buttonScrollUp:false});if(e.horizontalScroll){if(m.css("max-width")!=="none"){if(!e.advanced.updateOnContentResize){e.advanced.updateOnContentResize=true}}}else{if(m.css("max-height")!=="none"){var s=false,r=parseInt(m.css("max-height"));if(m.css("max-height").indexOf("%")>=0){s=r,r=m.parent().height()*s/100}m.css("overflow","hidden");g.css("max-height",r)}}m.mCustomScrollbar("update");if(e.advanced.updateOnBrowserResize){var i,j=c(window).width(),u=c(window).height();c(window).bind("resize."+m.data("mCustomScrollbarIndex"),function(){if(i){clearTimeout(i)}i=setTimeout(function(){if(!m.is(".mCS_disabled")&&!m.is(".mCS_destroyed")){var w=c(window).width(),v=c(window).height();if(j!==w||u!==v){if(m.css("max-height")!=="none"&&s){g.css("max-height",m.parent().height()*s/100)}m.mCustomScrollbar("update");j=w;u=v}}},150)})}if(e.advanced.updateOnContentResize){var p;if(e.horizontalScroll){var n=o.outerWidth()}else{var n=o.outerHeight()}p=setInterval(function(){if(e.horizontalScroll){if(e.advanced.autoExpandHorizontalScroll){o.css({position:"absolute",width:"auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:o.outerWidth(),position:"relative"}).unwrap()}var v=o.outerWidth()}else{var v=o.outerHeight()}if(v!=n){m.mCustomScrollbar("update");n=v}},300)}})},update:function(){var n=c(this),k=n.children(".mCustomScrollBox"),q=k.children(".mCSB_container");q.removeClass("mCS_no_scrollbar");n.removeClass("mCS_disabled mCS_destroyed");k.scrollTop(0).scrollLeft(0);var y=k.children(".mCSB_scrollTools"),o=y.children(".mCSB_draggerContainer"),m=o.children(".mCSB_dragger");if(n.data("horizontalScroll")){var A=y.children(".mCSB_buttonLeft"),t=y.children(".mCSB_buttonRight"),f=k.width();if(n.data("autoExpandHorizontalScroll")){q.css({position:"absolute",width:"auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:q.outerWidth(),position:"relative"}).unwrap()}var z=q.outerWidth()}else{var w=y.children(".mCSB_buttonUp"),g=y.children(".mCSB_buttonDown"),r=k.height(),i=q.outerHeight()}if(i>r&&!n.data("horizontalScroll")){y.css("display","block");var s=o.height();if(n.data("autoDraggerLength")){var u=Math.round(r/i*s),l=m.data("minDraggerHeight");if(u<=l){m.css({height:l})}else{if(u>=s-10){var p=s-10;m.css({height:p})}else{m.css({height:u})}}m.children(".mCSB_dragger_bar").css({"line-height":m.height()+"px"})}var B=m.height(),x=(i-r)/(s-B);n.data("scrollAmount",x).mCustomScrollbar("scrolling",k,q,o,m,w,g,A,t);var D=Math.abs(q.position().top);n.mCustomScrollbar("scrollTo",D,{scrollInertia:0,trigger:"internal"})}else{if(z>f&&n.data("horizontalScroll")){y.css("display","block");var h=o.width();if(n.data("autoDraggerLength")){var j=Math.round(f/z*h),C=m.data("minDraggerWidth");if(j<=C){m.css({width:C})}else{if(j>=h-10){var e=h-10;m.css({width:e})}else{m.css({width:j})}}}var v=m.width(),x=(z-f)/(h-v);n.data("scrollAmount",x).mCustomScrollbar("scrolling",k,q,o,m,w,g,A,t);var D=Math.abs(q.position().left);n.mCustomScrollbar("scrollTo",D,{scrollInertia:0,trigger:"internal"})}else{k.unbind("mousewheel focusin");if(n.data("horizontalScroll")){m.add(q).css("left",0)}else{m.add(q).css("top",0)}if(n.data("alwaysShowScrollbar")){if(!n.data("horizontalScroll")){m.css({height:o.height()})}else{if(n.data("horizontalScroll")){m.css({width:o.width()})}}}else{y.css("display","none");q.addClass("mCS_no_scrollbar")}n.data({bindEvent_mousewheel:false,bindEvent_focusin:false})}}},scrolling:function(i,q,n,k,A,f,D,w){var l=c(this);if(!l.data("bindEvent_scrollbar_drag")){var o,p,C,z,e;if(c.support.pointer){C="pointerdown";z="pointermove";e="pointerup"}else{if(c.support.msPointer){C="MSPointerDown";z="MSPointerMove";e="MSPointerUp"}}if(c.support.pointer||c.support.msPointer){k.bind(C,function(K){K.preventDefault();l.data({on_drag:true});k.addClass("mCSB_dragger_onDrag");var J=c(this),M=J.offset(),I=K.originalEvent.pageX-M.left,L=K.originalEvent.pageY-M.top;if(I<J.width()&&I>0&&L<J.height()&&L>0){o=L;p=I}});c(document).bind(z+"."+l.data("mCustomScrollbarIndex"),function(K){K.preventDefault();if(l.data("on_drag")){var J=k,M=J.offset(),I=K.originalEvent.pageX-M.left,L=K.originalEvent.pageY-M.top;G(o,p,L,I)}}).bind(e+"."+l.data("mCustomScrollbarIndex"),function(x){l.data({on_drag:false});k.removeClass("mCSB_dragger_onDrag")})}else{k.bind("mousedown touchstart",function(K){K.preventDefault();K.stopImmediatePropagation();var J=c(this),N=J.offset(),I,M;if(K.type==="touchstart"){var L=K.originalEvent.touches[0]||K.originalEvent.changedTouches[0];I=L.pageX-N.left;M=L.pageY-N.top}else{l.data({on_drag:true});k.addClass("mCSB_dragger_onDrag");I=K.pageX-N.left;M=K.pageY-N.top}if(I<J.width()&&I>0&&M<J.height()&&M>0){o=M;p=I}}).bind("touchmove",function(K){K.preventDefault();K.stopImmediatePropagation();var N=K.originalEvent.touches[0]||K.originalEvent.changedTouches[0],J=c(this),M=J.offset(),I=N.pageX-M.left,L=N.pageY-M.top;G(o,p,L,I)});c(document).bind("mousemove."+l.data("mCustomScrollbarIndex"),function(K){if(l.data("on_drag")){var J=k,M=J.offset(),I=K.pageX-M.left,L=K.pageY-M.top;G(o,p,L,I)}}).bind("mouseup."+l.data("mCustomScrollbarIndex"),function(x){l.data({on_drag:false});k.removeClass("mCSB_dragger_onDrag")})}l.data({bindEvent_scrollbar_drag:true})}function G(J,K,L,I){if(l.data("horizontalScroll")){l.mCustomScrollbar("scrollTo",(k.position().left-(K))+I,{moveDragger:true,trigger:"internal"})}else{l.mCustomScrollbar("scrollTo",(k.position().top-(J))+L,{moveDragger:true,trigger:"internal"})}}if(c.support.touch&&l.data("contentTouchScroll")){if(!l.data("bindEvent_content_touch")){var m,E,s,t,v,F,H;q.bind("touchstart",function(x){x.stopImmediatePropagation();m=x.originalEvent.touches[0]||x.originalEvent.changedTouches[0];E=c(this);s=E.offset();v=m.pageX-s.left;t=m.pageY-s.top;F=t;H=v});q.bind("touchmove",function(x){x.preventDefault();x.stopImmediatePropagation();m=x.originalEvent.touches[0]||x.originalEvent.changedTouches[0];E=c(this).parent();s=E.offset();v=m.pageX-s.left;t=m.pageY-s.top;if(l.data("horizontalScroll")){l.mCustomScrollbar("scrollTo",H-v,{trigger:"internal"})}else{l.mCustomScrollbar("scrollTo",F-t,{trigger:"internal"})}})}}if(!l.data("bindEvent_scrollbar_click")){n.bind("click",function(I){var x=(I.pageY-n.offset().top)*l.data("scrollAmount"),y=c(I.target);if(l.data("horizontalScroll")){x=(I.pageX-n.offset().left)*l.data("scrollAmount")}if(y.hasClass("mCSB_draggerContainer")||y.hasClass("mCSB_draggerRail")){l.mCustomScrollbar("scrollTo",x,{trigger:"internal",scrollEasing:"draggerRailEase"})}});l.data({bindEvent_scrollbar_click:true})}if(l.data("mouseWheel")){if(!l.data("bindEvent_mousewheel")){i.bind("mousewheel",function(K,M){var J,I=l.data("mouseWheelPixels"),x=Math.abs(q.position().top),L=k.position().top,y=n.height()-k.height();if(l.data("normalizeMouseWheelDelta")){if(M<0){M=-1}else{M=1}}if(I==="auto"){I=100+Math.round(l.data("scrollAmount")/2)}if(l.data("horizontalScroll")){L=k.position().left;y=n.width()-k.width();x=Math.abs(q.position().left)}if((M>0&&L!==0)||(M<0&&L!==y)){K.preventDefault();K.stopImmediatePropagation()}J=x-(M*I);l.mCustomScrollbar("scrollTo",J,{trigger:"internal"})});l.data({bindEvent_mousewheel:true})}}if(l.data("scrollButtons_enable")){if(l.data("scrollButtons_scrollType")==="pixels"){if(l.data("horizontalScroll")){w.add(D).unbind("mousedown touchstart MSPointerDown pointerdown mouseup MSPointerUp pointerup mouseout MSPointerOut pointerout touchend",j,h);l.data({bindEvent_buttonsContinuous_x:false});if(!l.data("bindEvent_buttonsPixels_x")){w.bind("click",function(x){x.preventDefault();r(Math.abs(q.position().left)+l.data("scrollButtons_scrollAmount"))});D.bind("click",function(x){x.preventDefault();r(Math.abs(q.position().left)-l.data("scrollButtons_scrollAmount"))});l.data({bindEvent_buttonsPixels_x:true})}}else{f.add(A).unbind("mousedown touchstart MSPointerDown pointerdown mouseup MSPointerUp pointerup mouseout MSPointerOut pointerout touchend",j,h);l.data({bindEvent_buttonsContinuous_y:false});if(!l.data("bindEvent_buttonsPixels_y")){f.bind("click",function(x){x.preventDefault();r(Math.abs(q.position().top)+l.data("scrollButtons_scrollAmount"))});A.bind("click",function(x){x.preventDefault();r(Math.abs(q.position().top)-l.data("scrollButtons_scrollAmount"))});l.data({bindEvent_buttonsPixels_y:true})}}function r(x){if(!k.data("preventAction")){k.data("preventAction",true);l.mCustomScrollbar("scrollTo",x,{trigger:"internal"})}}}else{if(l.data("horizontalScroll")){w.add(D).unbind("click");l.data({bindEvent_buttonsPixels_x:false});if(!l.data("bindEvent_buttonsContinuous_x")){w.bind("mousedown touchstart MSPointerDown pointerdown",function(y){y.preventDefault();var x=B();l.data({mCSB_buttonScrollRight:setInterval(function(){l.mCustomScrollbar("scrollTo",Math.abs(q.position().left)+x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var j=function(x){x.preventDefault();clearInterval(l.data("mCSB_buttonScrollRight"))};w.bind("mouseup touchend MSPointerUp pointerup mouseout MSPointerOut pointerout",j);D.bind("mousedown touchstart MSPointerDown pointerdown",function(y){y.preventDefault();var x=B();l.data({mCSB_buttonScrollLeft:setInterval(function(){l.mCustomScrollbar("scrollTo",Math.abs(q.position().left)-x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var h=function(x){x.preventDefault();clearInterval(l.data("mCSB_buttonScrollLeft"))};D.bind("mouseup touchend MSPointerUp pointerup mouseout MSPointerOut pointerout",h);l.data({bindEvent_buttonsContinuous_x:true})}}else{f.add(A).unbind("click");l.data({bindEvent_buttonsPixels_y:false});if(!l.data("bindEvent_buttonsContinuous_y")){f.bind("mousedown touchstart MSPointerDown pointerdown",function(y){y.preventDefault();var x=B();l.data({mCSB_buttonScrollDown:setInterval(function(){l.mCustomScrollbar("scrollTo",Math.abs(q.position().top)+x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var u=function(x){x.preventDefault();clearInterval(l.data("mCSB_buttonScrollDown"))};f.bind("mouseup touchend MSPointerUp pointerup mouseout MSPointerOut pointerout",u);A.bind("mousedown touchstart MSPointerDown pointerdown",function(y){y.preventDefault();var x=B();l.data({mCSB_buttonScrollUp:setInterval(function(){l.mCustomScrollbar("scrollTo",Math.abs(q.position().top)-x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var g=function(x){x.preventDefault();clearInterval(l.data("mCSB_buttonScrollUp"))};A.bind("mouseup touchend MSPointerUp pointerup mouseout MSPointerOut pointerout",g);l.data({bindEvent_buttonsContinuous_y:true})}}function B(){var x=l.data("scrollButtons_scrollSpeed");if(l.data("scrollButtons_scrollSpeed")==="auto"){x=Math.round((l.data("scrollInertia")+100)/40)}return x}}}if(l.data("autoScrollOnFocus")){if(!l.data("bindEvent_focusin")){i.bind("focusin",function(){i.scrollTop(0).scrollLeft(0);var x=c(document.activeElement);if(x.is("input,textarea,select,button,a[tabindex],area,object")){var J=q.position().top,y=x.position().top,I=i.height()-x.outerHeight();if(l.data("horizontalScroll")){J=q.position().left;y=x.position().left;I=i.width()-x.outerWidth()}if(J+y<0||J+y>I){l.mCustomScrollbar("scrollTo",y,{trigger:"internal"})}}});l.data({bindEvent_focusin:true})}}if(l.data("autoHideScrollbar")&&!l.data("alwaysShowScrollbar")){if(!l.data("bindEvent_autoHideScrollbar")){i.bind("mouseenter",function(x){i.addClass("mCS-mouse-over");d.showScrollbar.call(i.children(".mCSB_scrollTools"))}).bind("mouseleave touchend",function(x){i.removeClass("mCS-mouse-over");if(x.type==="mouseleave"){d.hideScrollbar.call(i.children(".mCSB_scrollTools"))}});l.data({bindEvent_autoHideScrollbar:true})}}},scrollTo:function(e,f){var i=c(this),o={moveDragger:false,trigger:"external",callbacks:true,scrollInertia:i.data("scrollInertia"),scrollEasing:i.data("scrollEasing")},f=c.extend(o,f),p,g=i.children(".mCustomScrollBox"),k=g.children(".mCSB_container"),r=g.children(".mCSB_scrollTools"),j=r.children(".mCSB_draggerContainer"),h=j.children(".mCSB_dragger"),t=draggerSpeed=f.scrollInertia,q,s,m,l;if(!k.hasClass("mCS_no_scrollbar")){i.data({mCS_trigger:f.trigger});if(i.data("mCS_Init")){f.callbacks=false}if(e||e===0){if(typeof(e)==="number"){if(f.moveDragger){p=e;if(i.data("horizontalScroll")){e=h.position().left*i.data("scrollAmount")}else{e=h.position().top*i.data("scrollAmount")}draggerSpeed=0}else{p=e/i.data("scrollAmount")}}else{if(typeof(e)==="string"){var v;if(e==="top"){v=0}else{if(e==="bottom"&&!i.data("horizontalScroll")){v=k.outerHeight()-g.height()}else{if(e==="left"){v=0}else{if(e==="right"&&i.data("horizontalScroll")){v=k.outerWidth()-g.width()}else{if(e==="first"){v=i.find(".mCSB_container").find(":first")}else{if(e==="last"){v=i.find(".mCSB_container").find(":last")}else{v=i.find(e)}}}}}}if(v.length===1){if(i.data("horizontalScroll")){e=v.position().left}else{e=v.position().top}p=e/i.data("scrollAmount")}else{p=e=v}}}if(i.data("horizontalScroll")){if(i.data("onTotalScrollBack_Offset")){s=-i.data("onTotalScrollBack_Offset")}if(i.data("onTotalScroll_Offset")){l=g.width()-k.outerWidth()+i.data("onTotalScroll_Offset")}if(p<0){p=e=0;clearInterval(i.data("mCSB_buttonScrollLeft"));if(!s){q=true}}else{if(p>=j.width()-h.width()){p=j.width()-h.width();e=g.width()-k.outerWidth();clearInterval(i.data("mCSB_buttonScrollRight"));if(!l){m=true}}else{e=-e}}var n=i.data("snapAmount");if(n){e=Math.round(e/n)*n-i.data("snapOffset")}d.mTweenAxis.call(this,h[0],"left",Math.round(p),draggerSpeed,f.scrollEasing);d.mTweenAxis.call(this,k[0],"left",Math.round(e),t,f.scrollEasing,{onStart:function(){if(f.callbacks&&!i.data("mCS_tweenRunning")){u("onScrollStart")}if(i.data("autoHideScrollbar")&&!i.data("alwaysShowScrollbar")){d.showScrollbar.call(r)}},onUpdate:function(){if(f.callbacks){u("whileScrolling")}},onComplete:function(){if(f.callbacks){u("onScroll");if(q||(s&&k.position().left>=s)){u("onTotalScrollBack")}if(m||(l&&k.position().left<=l)){u("onTotalScroll")}}h.data("preventAction",false);i.data("mCS_tweenRunning",false);if(i.data("autoHideScrollbar")&&!i.data("alwaysShowScrollbar")){if(!g.hasClass("mCS-mouse-over")){d.hideScrollbar.call(r)}}}})}else{if(i.data("onTotalScrollBack_Offset")){s=-i.data("onTotalScrollBack_Offset")}if(i.data("onTotalScroll_Offset")){l=g.height()-k.outerHeight()+i.data("onTotalScroll_Offset")}if(p<0){p=e=0;clearInterval(i.data("mCSB_buttonScrollUp"));if(!s){q=true}}else{if(p>=j.height()-h.height()){p=j.height()-h.height();e=g.height()-k.outerHeight();clearInterval(i.data("mCSB_buttonScrollDown"));if(!l){m=true}}else{e=-e}}var n=i.data("snapAmount");if(n){e=Math.round(e/n)*n-i.data("snapOffset")}d.mTweenAxis.call(this,h[0],"top",Math.round(p),draggerSpeed,f.scrollEasing);d.mTweenAxis.call(this,k[0],"top",Math.round(e),t,f.scrollEasing,{onStart:function(){if(f.callbacks&&!i.data("mCS_tweenRunning")){u("onScrollStart")}if(i.data("autoHideScrollbar")&&!i.data("alwaysShowScrollbar")){d.showScrollbar.call(r)}},onUpdate:function(){if(f.callbacks){u("whileScrolling")}},onComplete:function(){if(f.callbacks){u("onScroll");if(q||(s&&k.position().top>=s)){u("onTotalScrollBack")}if(m||(l&&k.position().top<=l)){u("onTotalScroll")}}h.data("preventAction",false);i.data("mCS_tweenRunning",false);if(i.data("autoHideScrollbar")&&!i.data("alwaysShowScrollbar")){if(!g.hasClass("mCS-mouse-over")){d.hideScrollbar.call(r)}}}})}if(i.data("mCS_Init")){i.data({mCS_Init:false})}}}function u(w){if(i.data("mCustomScrollbarIndex")){this.mcs={top:k.position().top,left:k.position().left,draggerTop:h.position().top,draggerLeft:h.position().left,topPct:Math.round((100*Math.abs(k.position().top))/Math.abs(k.outerHeight()-g.height())),leftPct:Math.round((100*Math.abs(k.position().left))/Math.abs(k.outerWidth()-g.width()))};switch(w){case"onScrollStart":i.data("mCS_tweenRunning",true).data("onScrollStart_Callback").call(i,this.mcs);break;case"whileScrolling":i.data("whileScrolling_Callback").call(i,this.mcs);break;case"onScroll":i.data("onScroll_Callback").call(i,this.mcs);break;case"onTotalScrollBack":i.data("onTotalScrollBack_Callback").call(i,this.mcs);break;case"onTotalScroll":i.data("onTotalScroll_Callback").call(i,this.mcs);break}}}},stop:function(){var g=c(this),e=g.children().children(".mCSB_container"),f=g.children().children().children().children(".mCSB_dragger");d.mTweenAxisStop.call(this,e[0]);d.mTweenAxisStop.call(this,f[0])},disable:function(e){var j=c(this),f=j.children(".mCustomScrollBox"),h=f.children(".mCSB_container"),g=f.children(".mCSB_scrollTools"),i=g.children().children(".mCSB_dragger");f.unbind("mousewheel focusin mouseenter mouseleave touchend");h.unbind("touchstart touchmove");if(e){if(j.data("horizontalScroll")){i.add(h).css("left",0)}else{i.add(h).css("top",0)}}g.css("display","none");h.addClass("mCS_no_scrollbar");j.data({bindEvent_mousewheel:false,bindEvent_focusin:false,bindEvent_content_touch:false,bindEvent_autoHideScrollbar:false}).addClass("mCS_disabled")},destroy:function(){var e=c(this);e.removeClass("mCustomScrollbar _mCS_"+e.data("mCustomScrollbarIndex")).addClass("mCS_destroyed").children().children(".mCSB_container").unwrap().children().unwrap().siblings(".mCSB_scrollTools").remove();c(document).unbind("mousemove."+e.data("mCustomScrollbarIndex")+" mouseup."+e.data("mCustomScrollbarIndex")+" MSPointerMove."+e.data("mCustomScrollbarIndex")+" MSPointerUp."+e.data("mCustomScrollbarIndex"));c(window).unbind("resize."+e.data("mCustomScrollbarIndex"))}},d={showScrollbar:function(){this.stop().animate({opacity:1},"fast")},hideScrollbar:function(){this.stop().animate({opacity:0},"fast")},mTweenAxis:function(g,i,h,f,o,y){var y=y||{},v=y.onStart||function(){},p=y.onUpdate||function(){},w=y.onComplete||function(){};var n=t(),l,j=0,r=g.offsetTop,s=g.style;if(i==="left"){r=g.offsetLeft}var m=h-r;q();e();function t(){if(window.performance&&window.performance.now){return window.performance.now()}else{if(window.performance&&window.performance.webkitNow){return window.performance.webkitNow()}else{if(Date.now){return Date.now()}else{return new Date().getTime()}}}}function x(){if(!j){v.call()}j=t()-n;u();if(j>=g._time){g._time=(j>g._time)?j+l-(j-g._time):j+l-1;if(g._time<j+1){g._time=j+1}}if(g._time<f){g._id=_request(x)}else{w.call()}}function u(){if(f>0){g.currVal=k(g._time,r,m,f,o);s[i]=Math.round(g.currVal)+"px"}else{s[i]=h+"px"}p.call()}function e(){l=1000/60;g._time=j+l;_request=(!window.requestAnimationFrame)?function(z){u();return setTimeout(z,0.01)}:window.requestAnimationFrame;g._id=_request(x)}function q(){if(g._id==null){return}if(!window.requestAnimationFrame){clearTimeout(g._id)}else{window.cancelAnimationFrame(g._id)}g._id=null}function k(B,A,F,E,C){switch(C){case"linear":return F*B/E+A;break;case"easeOutQuad":B/=E;return -F*B*(B-2)+A;break;case"easeInOutQuad":B/=E/2;if(B<1){return F/2*B*B+A}B--;return -F/2*(B*(B-2)-1)+A;break;case"easeOutCubic":B/=E;B--;return F*(B*B*B+1)+A;break;case"easeOutQuart":B/=E;B--;return -F*(B*B*B*B-1)+A;break;case"easeOutQuint":B/=E;B--;return F*(B*B*B*B*B+1)+A;break;case"easeOutCirc":B/=E;B--;return F*Math.sqrt(1-B*B)+A;break;case"easeOutSine":return F*Math.sin(B/E*(Math.PI/2))+A;break;case"easeOutExpo":return F*(-Math.pow(2,-10*B/E)+1)+A;break;case"mcsEaseOut":var D=(B/=E)*B,z=D*B;return A+F*(0.499999999999997*z*D+-2.5*D*D+5.5*z+-6.5*D+4*B);break;case"draggerRailEase":B/=E/2;if(B<1){return F/2*B*B*B+A}B-=2;return F/2*(B*B*B+2)+A;break}}},mTweenAxisStop:function(e){if(e._id==null){return}if(!window.requestAnimationFrame){clearTimeout(e._id)}else{window.cancelAnimationFrame(e._id)}e._id=null},rafPolyfill:function(){var f=["ms","moz","webkit","o"],e=f.length;while(--e>-1&&!window.requestAnimationFrame){window.requestAnimationFrame=window[f[e]+"RequestAnimationFrame"];window.cancelAnimationFrame=window[f[e]+"CancelAnimationFrame"]||window[f[e]+"CancelRequestAnimationFrame"]}}};d.rafPolyfill.call();c.support.touch=!!("ontouchstart" in window);c.support.pointer=window.navigator.pointerEnabled;c.support.msPointer=window.navigator.msPointerEnabled;var a=("https:"==document.location.protocol)?"https:":"http:";c.event.special.mousewheel||document.write('<script src="'+a+'//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js"><\/script>');c.fn.mCustomScrollbar=function(e){if(b[e]){return b[e].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof e==="object"||!e){return b.init.apply(this,arguments)}else{c.error("Method "+e+" does not exist")}}}})(jQuery);;
!function(t,e){"use strict";var n,i=t.document;n=function(){var n,s,o,r,a,l,c,u,f,d,y,p,h,b={},m={},v=!1,g={ENTER:13,ESC:27,SPACE:32},k=[];return m={buttons:{holder:'<nav class="alertify-buttons">{{buttons}}</nav>',submit:'<button type="submit" class="alertify-button alertify-button-ok" id="alertify-ok">{{ok}}</button>',ok:'<button class="alertify-button alertify-button-ok" id="alertify-ok">{{ok}}</button>',cancel:'<button class="alertify-button alertify-button-cancel" id="alertify-cancel">{{cancel}}</button>'},input:'<div class="alertify-text-wrapper"><input type="text" class="alertify-text" id="alertify-text"></div>',message:'<p class="alertify-message">{{message}}</p>',log:'<article class="alertify-log{{class}}">{{message}}</article>'},h=function(){var t,n,s=!1,o=i.createElement("fakeelement"),r={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"};for(t in r)if(o.style[t]!==e){n=r[t],s=!0;break}return{type:n,supported:s}},n=function(t){return i.getElementById(t)},b={labels:{ok:"OK",cancel:"Cancel"},delay:5e3,buttonReverse:!1,buttonFocus:"ok",transition:e,addListeners:function(t){var e,n,l,c,u,f="undefined"!=typeof o,d="undefined"!=typeof s,y="undefined"!=typeof p,h="",b=this;e=function(e){return"undefined"!=typeof e.preventDefault&&e.preventDefault(),l(e),"undefined"!=typeof p&&(h=p.value),"function"==typeof t&&("undefined"!=typeof p?t(!0,h):t(!0)),!1},n=function(e){return"undefined"!=typeof e.preventDefault&&e.preventDefault(),l(e),"function"==typeof t&&t(!1),!1},l=function(){b.hide(),b.unbind(i.body,"keyup",c),b.unbind(r,"focus",u),f&&b.unbind(o,"click",e),d&&b.unbind(s,"click",n)},c=function(t){var i=t.keyCode;(i===g.SPACE&&!y||y&&i===g.ENTER)&&e(t),i===g.ESC&&d&&n(t)},u=function(){y?p.focus():!d||b.buttonReverse?o.focus():s.focus()},this.bind(r,"focus",u),this.bind(a,"focus",u),f&&this.bind(o,"click",e),d&&this.bind(s,"click",n),this.bind(i.body,"keyup",c),this.transition.supported||this.setFocus()},bind:function(t,e,n){"function"==typeof t.addEventListener?t.addEventListener(e,n,!1):t.attachEvent&&t.attachEvent("on"+e,n)},handleErrors:function(){if("undefined"!=typeof t.onerror){var e=this;return t.onerror=function(t,n,i){e.error("["+t+" on line "+i+" of "+n+"]",0)},!0}return!1},appendButtons:function(t,e){return this.buttonReverse?e+t:t+e},build:function(t){var e="",n=t.type,i=t.message,s=t.cssClass||"";switch(e+='<div class="alertify-dialog">',e+='<a id="alertify-resetFocusBack" class="alertify-resetFocus" href="#">Reset Focus</a>',"none"===b.buttonFocus&&(e+='<a href="#" id="alertify-noneFocus" class="alertify-hidden"></a>'),"prompt"===n&&(e+='<div id="alertify-form">'),e+='<article class="alertify-inner">',e+=m.message.replace("{{message}}",i),"prompt"===n&&(e+=m.input),e+=m.buttons.holder,e+="</article>","prompt"===n&&(e+="</div>"),e+='<a id="alertify-resetFocus" class="alertify-resetFocus" href="#">Reset Focus</a>',e+="</div>",n){case"confirm":e=e.replace("{{buttons}}",this.appendButtons(m.buttons.cancel,m.buttons.ok)),e=e.replace("{{ok}}",this.labels.ok).replace("{{cancel}}",this.labels.cancel);break;case"prompt":e=e.replace("{{buttons}}",this.appendButtons(m.buttons.cancel,m.buttons.submit)),e=e.replace("{{ok}}",this.labels.ok).replace("{{cancel}}",this.labels.cancel);break;case"alert":e=e.replace("{{buttons}}",m.buttons.ok),e=e.replace("{{ok}}",this.labels.ok)}return f.className="alertify alertify-"+n+" "+s,u.className="alertify-cover",e},close:function(t,e){var n,i,s=e&&!isNaN(e)?+e:this.delay,o=this;this.bind(t,"click",function(){n(t)}),i=function(t){t.stopPropagation(),o.unbind(this,o.transition.type,i),d.removeChild(this),d.hasChildNodes()||(d.className+=" alertify-logs-hidden")},n=function(t){"undefined"!=typeof t&&t.parentNode===d&&(o.transition.supported?(o.bind(t,o.transition.type,i),t.className+=" alertify-log-hide"):(d.removeChild(t),d.hasChildNodes()||(d.className+=" alertify-logs-hidden")))},0!==e&&setTimeout(function(){n(t)},s)},dialog:function(t,e,n,s,o){c=i.activeElement;var r=function(){d&&null!==d.scrollTop&&u&&null!==u.scrollTop||r()};if("string"!=typeof t)throw new Error("message must be a string");if("string"!=typeof e)throw new Error("type must be a string");if("undefined"!=typeof n&&"function"!=typeof n)throw new Error("fn must be a function");return this.init(),r(),k.push({type:e,message:t,callback:n,placeholder:s,cssClass:o}),v||this.setup(),this},extend:function(t){if("string"!=typeof t)throw new Error("extend method must have exactly one parameter");return function(e,n){return this.log(e,t,n),this}},hide:function(){var t,e=this;k.splice(0,1),k.length>0?this.setup(!0):(v=!1,t=function(n){n.stopPropagation(),e.unbind(f,e.transition.type,t)},this.transition.supported?(this.bind(f,this.transition.type,t),f.className="alertify alertify-hide alertify-hidden"):f.className="alertify alertify-hide alertify-hidden alertify-isHidden",u.className="alertify-cover alertify-cover-hidden",c.focus())},init:function(){i.createElement("nav"),i.createElement("article"),i.createElement("section"),null==n("alertify-cover")&&(u=i.createElement("div"),u.setAttribute("id","alertify-cover"),u.className="alertify-cover alertify-cover-hidden",i.body.appendChild(u)),null==n("alertify")&&(v=!1,k=[],f=i.createElement("section"),f.setAttribute("id","alertify"),f.className="alertify alertify-hidden",i.body.appendChild(f)),null==n("alertify-logs")&&(d=i.createElement("section"),d.setAttribute("id","alertify-logs"),d.className="alertify-logs alertify-logs-hidden",i.body.appendChild(d)),i.body.setAttribute("tabindex","0"),this.transition=h()},log:function(t,e,n,i){var s=function(){d&&null!==d.scrollTop||s()};return this.init(),s(),d.className="alertify-logs",this.notify(t,e,n,i),this},notify:function(t,e,n,s){var o=i.createElement("article");o.className="alertify-log"+("string"==typeof e&&""!==e?" alertify-log-"+e:""),o.innerHTML=t,"function"==typeof s&&this.bind(o,"click",s),d.appendChild(o),setTimeout(function(){o.className=o.className+" alertify-log-show"},50),this.close(o,n)},set:function(t){var e;if("object"!=typeof t&&t instanceof Array)throw new Error("args must be an object");for(e in t)t.hasOwnProperty(e)&&(this[e]=t[e])},setFocus:function(){p?(p.focus(),p.select()):l.focus()},setup:function(t){var i,c=k[0],u=this;v=!0,i=function(t){t.stopPropagation(),u.setFocus(),u.unbind(f,u.transition.type,i)},this.transition.supported&&!t&&this.bind(f,this.transition.type,i),f.innerHTML=this.build(c),r=n("alertify-resetFocus"),a=n("alertify-resetFocusBack"),o=n("alertify-ok")||e,s=n("alertify-cancel")||e,l="cancel"===b.buttonFocus?s:"none"===b.buttonFocus?n("alertify-noneFocus"):o,p=n("alertify-text")||e,y=n("alertify-form")||e,"string"==typeof c.placeholder&&""!==c.placeholder&&(p.value=c.placeholder),t&&this.setFocus(),this.addListeners(c.callback)},unbind:function(t,e,n){"function"==typeof t.removeEventListener?t.removeEventListener(e,n,!1):t.detachEvent&&t.detachEvent("on"+e,n)}},{alert:function(t,e,n){return b.dialog(t,"alert",e,"",n),this},confirm:function(t,e,n){return b.dialog(t,"confirm",e,"",n),this},extend:b.extend,init:b.init,log:function(t,e,n,i){return b.log(t,e,n,i),this},prompt:function(t,e,n,i){return b.dialog(t,"prompt",e,n,i),this},success:function(t,e,n){return b.log(t,"success",e,n),this},error:function(t,e,n){return b.log(t,"error",e,n),this},set:function(t){b.set(t)},labels:b.labels,debug:b.handleErrors}},"function"==typeof define?define([],function(){return new n}):"undefined"==typeof t.alertify&&(t.alertify=new n)}(this);


;
!function(t,e){function n(t,e,n){var r=t.children(),o=!1;t.empty();for(var i=0,d=r.length;d>i;i++){var l=r.eq(i);if(t.append(l),n&&t.append(n),a(t,e)){l.remove(),o=!0;break}n&&n.detach()}return o}function r(e,n,i,d,l){var s=!1,c="a table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, blockquote, select, optgroup, option, textarea, script, style",u="script, .dotdotdot-keep";return e.contents().detach().each(function(){var f=this,h=t(f);if("undefined"==typeof f||3==f.nodeType&&0==t.trim(f.data).length)return!0;if(h.is(u))e.append(h);else{if(s)return!0;e.append(h),!l||h.is(d.after)||h.find(d.after).length||e[e.is(c)?"after":"append"](l),a(i,d)&&(s=3==f.nodeType?o(h,n,i,d,l):r(h,n,i,d,l),s||(h.detach(),s=!0)),s||l&&l.detach()}}),s}function o(e,n,r,o,d){var c=e[0];if(!c)return!1;var f=s(c),h=-1!==f.indexOf(" ")?" ":"　",p="letter"==o.wrap?"":h,g=f.split(p),v=-1,w=-1,y=0,b=g.length-1;for(o.fallbackToLetter&&0==y&&0==b&&(p="",g=f.split(p),b=g.length-1);b>=y&&(0!=y||0!=b);){var m=Math.floor((y+b)/2);if(m==w)break;w=m,l(c,g.slice(0,w+1).join(p)+o.ellipsis),a(r,o)?(b=w,o.fallbackToLetter&&0==y&&0==b&&(p="",g=g[0].split(p),v=-1,w=-1,y=0,b=g.length-1)):(v=w,y=w)}if(-1==v||1==g.length&&0==g[0].length){var T=e.parent();e.detach();var x=d&&d.closest(T).length?d.length:0;T.contents().length>x?c=u(T.contents().eq(-1-x),n):(c=u(T,n,!0),x||T.detach()),c&&(f=i(s(c),o),l(c,f),x&&d&&t(c).parent().append(d))}else f=i(g.slice(0,v+1).join(p),o),l(c,f);return!0}function a(t,e){return t.innerHeight()>e.maxHeight}function i(e,n){for(;t.inArray(e.slice(-1),n.lastCharacter.remove)>-1;)e=e.slice(0,-1);return t.inArray(e.slice(-1),n.lastCharacter.noEllipsis)<0&&(e+=n.ellipsis),e}function d(t){return{width:t.innerWidth(),height:t.innerHeight()}}function l(t,e){t.innerText?t.innerText=e:t.nodeValue?t.nodeValue=e:t.textContent&&(t.textContent=e)}function s(t){return t.innerText?t.innerText:t.nodeValue?t.nodeValue:t.textContent?t.textContent:""}function c(t){do t=t.previousSibling;while(t&&1!==t.nodeType&&3!==t.nodeType);return t}function u(e,n,r){var o,a=e&&e[0];if(a){if(!r){if(3===a.nodeType)return a;if(t.trim(e.text()))return u(e.contents().last(),n)}for(o=c(a);!o;){if(e=e.parent(),e.is(n)||!e.length)return!1;o=c(e[0])}if(o)return u(t(o),n)}return!1}function f(e,n){return e?"string"==typeof e?(e=t(e,n),e.length?e:!1):e.jquery?e:!1:!1}function h(t){for(var e=t.innerHeight(),n=["paddingTop","paddingBottom"],r=0,o=n.length;o>r;r++){var a=parseInt(t.css(n[r]),10);isNaN(a)&&(a=0),e-=a}return e}if(!t.fn.dotdotdot){t.fn.dotdotdot=function(e){if(0==this.length)return t.fn.dotdotdot.debug('No element found for "'+this.selector+'".'),this;if(this.length>1)return this.each(function(){t(this).dotdotdot(e)});var o=this;o.data("dotdotdot")&&o.trigger("destroy.dot"),o.data("dotdotdot-style",o.attr("style")||""),o.css("word-wrap","break-word"),"nowrap"===o.css("white-space")&&o.css("white-space","normal"),o.bind_events=function(){return o.bind("update.dot",function(e,d){e.preventDefault(),e.stopPropagation(),l.maxHeight="number"==typeof l.height?l.height:h(o),l.maxHeight+=l.tolerance,"undefined"!=typeof d&&(("string"==typeof d||"nodeType"in d&&1===d.nodeType)&&(d=t("<div />").append(d).contents()),d instanceof t&&(i=d)),g=o.wrapInner('<div class="dotdotdot" />').children(),g.contents().detach().end().append(i.clone(!0)).find("br").replaceWith("  <br />  ").end().css({height:"auto",width:"auto",border:"none",padding:0,margin:0});var c=!1,u=!1;return s.afterElement&&(c=s.afterElement.clone(!0),c.show(),s.afterElement.detach()),a(g,l)&&(u="children"==l.wrap?n(g,l,c):r(g,o,g,l,c)),g.replaceWith(g.contents()),g=null,t.isFunction(l.callback)&&l.callback.call(o[0],u,i),s.isTruncated=u,u}).bind("isTruncated.dot",function(t,e){return t.preventDefault(),t.stopPropagation(),"function"==typeof e&&e.call(o[0],s.isTruncated),s.isTruncated}).bind("originalContent.dot",function(t,e){return t.preventDefault(),t.stopPropagation(),"function"==typeof e&&e.call(o[0],i),i}).bind("destroy.dot",function(t){t.preventDefault(),t.stopPropagation(),o.unwatch().unbind_events().contents().detach().end().append(i).attr("style",o.data("dotdotdot-style")||"").data("dotdotdot",!1)}),o},o.unbind_events=function(){return o.unbind(".dot"),o},o.watch=function(){if(o.unwatch(),"window"==l.watch){var e=t(window),n=e.width(),r=e.height();e.bind("resize.dot"+s.dotId,function(){n==e.width()&&r==e.height()&&l.windowResizeFix||(n=e.width(),r=e.height(),u&&clearInterval(u),u=setTimeout(function(){o.trigger("update.dot")},100))})}else c=d(o),u=setInterval(function(){if(o.is(":visible")){var t=d(o);(c.width!=t.width||c.height!=t.height)&&(o.trigger("update.dot"),c=t)}},500);return o},o.unwatch=function(){return t(window).unbind("resize.dot"+s.dotId),u&&clearInterval(u),o};var i=o.contents(),l=t.extend(!0,{},t.fn.dotdotdot.defaults,e),s={},c={},u=null,g=null;return l.lastCharacter.remove instanceof Array||(l.lastCharacter.remove=t.fn.dotdotdot.defaultArrays.lastCharacter.remove),l.lastCharacter.noEllipsis instanceof Array||(l.lastCharacter.noEllipsis=t.fn.dotdotdot.defaultArrays.lastCharacter.noEllipsis),s.afterElement=f(l.after,o),s.isTruncated=!1,s.dotId=p++,o.data("dotdotdot",!0).bind_events().trigger("update.dot"),l.watch&&o.watch(),o},t.fn.dotdotdot.defaults={ellipsis:"... ",wrap:"word",fallbackToLetter:!0,lastCharacter:{},tolerance:0,callback:null,after:null,height:null,watch:!1,windowResizeFix:!0},t.fn.dotdotdot.defaultArrays={lastCharacter:{remove:[" ","　",",",";",".","!","?"],noEllipsis:[]}},t.fn.dotdotdot.debug=function(){};var p=1,g=t.fn.html;t.fn.html=function(n){return n!=e&&!t.isFunction(n)&&this.data("dotdotdot")?this.trigger("update",[n]):g.apply(this,arguments)};var v=t.fn.text;t.fn.text=function(n){return n!=e&&!t.isFunction(n)&&this.data("dotdotdot")?(n=t("<div />").text(n).html(),this.trigger("update",[n])):v.apply(this,arguments)}}}(jQuery);;
!function(e){e.fn.dcAccordion=function(a){function i(a,i){var s=e.cookie(a);if(null!=s){var l=s.split(",");e.each(l,function(a,s){var l=e("li:eq("+s+")",i);e("> a",l).addClass(t.classActiveElement);var n=l.parents("li");e("> a",n).addClass(t.classActiveElement)})}}function s(a,i){var s=[];e("li a."+t.classActiveElement,i).each(function(){var a=e(this).parent("li"),t=e("li",i).index(a);s.push(t)}),e.cookie(a,s,{path:"/"})}var t={classParent:"dcjq-parent",classActiveElement:"active",classArrow:"dcjq-icon",classCount:"dcjq-count",classExpand:"dcjq-current-parent",eventType:"click",hoverDelay:300,menuClose:!0,autoClose:!0,autoExpand:!1,speed:"slow",saveState:!0,disableLink:!0,showCount:!1,cookie:"dcjq-accordion"},a=e.extend(t,a);this.each(function(){function a(){$arrow='<span class="'+t.classArrow+'"></span>';var a=t.classParent+"-li";e("> ul",u).show(),e("li",u).each(function(){e("> ul",this).length>0&&(e(this).addClass(a),e("> a",this).addClass(t.classParent).append($arrow))}),e("> ul",u).hide(),1==t.showCount&&e("li."+a,u).each(function(){if(1==t.disableLink)var a=parseInt(e("ul a:not(."+t.classParent+")",this).length);else var a=parseInt(e("ul a",this).length);e("> a",this).append(' <span class="'+t.classCount+'">('+a+")</span>")})}function l(){$activeLi=e(this).parent("li"),$parentsLi=$activeLi.parents("li"),$parentsUl=$activeLi.parents("ul"),1==t.autoClose&&v($parentsLi,$parentsUl),e("> ul",$activeLi).is(":visible")?(e("ul",$activeLi).slideUp(t.speed),e("a",$activeLi).removeClass(t.classActiveElement)):(e(this).siblings("ul").slideToggle(t.speed),e("> a",$activeLi).addClass(t.classActiveElement)),1==t.saveState&&s(t.cookie,u)}function n(){}function c(){}function o(){1==t.menuClose&&(e("ul",u).slideUp(t.speed),e("a",u).removeClass(t.classActiveElement),s(t.cookie,u))}function v(a,i){e("ul",u).not(i).slideUp(t.speed),e("a",u).removeClass(t.classActiveElement),e("> a",a).addClass(t.classActiveElement)}function r(){e("ul",u).hide(),$allActiveLi=e("a."+t.classActiveElement,u),$allActiveLi.siblings("ul").show()}var u=this;if(a(),1==t.saveState&&i(t.cookie,u),1==t.autoExpand&&e("li."+t.classExpand+" > a").addClass(t.classActiveElement),r(),"hover"==t.eventType){var d={sensitivity:2,interval:t.hoverDelay,over:l,timeout:t.hoverDelay,out:n};e("li a",u).hoverIntent(d);var p={sensitivity:2,interval:1e3,over:c,timeout:1e3,out:o};e(u).hoverIntent(p),1==t.disableLink&&e("li a",u).click(function(a){e(this).siblings("ul").length>0&&a.preventDefault()})}else e("li a:not(.thumbnail):not(.info):not(.prodInfo)",u).click(function(a){$activeLi=e(this).parent("li"),$parentsLi=$activeLi.parents("li"),$parentsUl=$activeLi.parents("ul"),1==t.disableLink&&e(this).siblings("ul").length>0&&a.preventDefault(),1==t.autoClose&&v($parentsLi,$parentsUl),e("> ul",$activeLi).is(":visible")?(e("ul",$activeLi).slideUp(t.speed),e("a",$activeLi).removeClass(t.classActiveElement)):(e(this).siblings("ul").slideToggle(t.speed),e("> a",$activeLi).addClass(t.classActiveElement)),1==t.saveState&&s(t.cookie,u)})})}}(jQuery);
;
/*
 * 	Easy Tooltip 1.0 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4380/easy-tooltip--jquery-plugin
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
(function($) {

	$.fn.easyTooltip = function(options){
	  
		// default configuration properties
		var defaults = {	
			xOffset: 10,		
			yOffset: 25,
			tooltipId: "easyTooltip",
			clickRemove: false,
			content: "",
			useElement: ""
		}; 
			
		var options = $.extend(defaults, options);  
		var content;
				
		this.each(function() {  				
			var title = $(this).text() + '<br/>' + $(this).attr("title");	
                        //console.log($(this).text());
			$(this).hover(function(e){											 							   
				content = (options.content != "") ? options.content : title;
				content = (options.useElement != "") ? $("#" + options.useElement).html() : content;
				$(this).attr("title","");									  				
				if (content != "" && content != undefined){			
					//$("body").append("<div id='"+ options.tooltipId +"' class='tip-darkgray tip-arrow-bottom'>"+ content +"</div>");		
		                        
                                        var element = '<div id="'+ options.tooltipId +'" class="easytooltip" style="visibility: inherit; border: 0px none; padding: 0px; background-image: none; background-color: transparent; opacity: 0.95">' +
                                                '<table border="0" cellpadding="0" style="border-spacing: 0">' + 
                                                    '<tbody>' +
                                                        '<tr>' +
                                                            '<td class="tip-top tip-bg-image" colspan="2" style="background-image: url(/images/tip-darkgray.png)"><span></span></td>' +
                                                            '<td class="tip-right tip-bg-image" rowspan="2" style="background-image: url(/images/tip-darkgray.png)"><span></span></td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                            '<td class="tip-left tip-bg-image" rowspan="2" style="background-image: url(/images/tip-darkgray.png)"><span></span></td>' +
                                                            '<td style="widht: 100%">' +
                                                                '<div class="tip-inner tip-bg-image" style="background-image: url(/images/tip-darkgray.png)">' +
                                                                    content +
                                                                '</div>' +
                                                            '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                            '<td class="tip-bottom tip-bg-image" colspan="2" style="background-image: url(/images/tip-darkgray.png)"><span></span></td>' +
                                                        '</tr>' +
                                                    '</tbody>' +
                                                '</table>' + 
                                                '<div class="tip-arrow tip-arrow-bottom" style="visibility: inherit;"><span></span></div>' +
                                            '</div>'
                                        ;
                                        $("body").append(element);		
		                        var element = $("#" + options.tooltipId);
                                        var height = $("#" + options.tooltipId).height();
					element
						.css("position","absolute")
						.css("top",(e.pageY - options.yOffset - height) + "px")
						.css("left",(e.pageX + options.xOffset - 35) + "px")						
						.css("display","none")
						.fadeIn("fast")
				}
			},
			function(){	
				$("#" + options.tooltipId).remove();
				$(this).attr("title",title);
			});	
			$(this).mousemove(function(e){
                                var element = $("#" + options.tooltipId);
                                var height = element.height();
				element
					.css("top",(e.pageY - options.yOffset - height) + "px")
					.css("left",(e.pageX + options.xOffset - 35) + "px")					
			});	
			if(options.clickRemove){
				$(this).mousedown(function(e){
					$("#" + options.tooltipId).remove();
					$(this).attr("title",title);
				});				
			}
		});
	  
	};

})(jQuery);
;
/**
* hoverIntent r5 // 2007.03.27 // jQuery 1.1.2+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne <brian@cherne.net>
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY;};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev]);}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev]);};var handleHover=function(e){var p=(e.type=="mouseover"?e.fromElement:e.toElement)||e.relatedTarget;while(p&&p!=this){try{p=p.parentNode;}catch(e){p=this;}}if(p==this){return false;}var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);}if(e.type=="mouseover"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob);},cfg.timeout);}}};return this.mouseover(handleHover).mouseout(handleHover);};})(jQuery);;
jQuery.cookie=function(e,i,o){if("undefined"==typeof i){var n=null;if(document.cookie&&""!=document.cookie)for(var r=document.cookie.split(";"),t=0;t<r.length;t++){var p=jQuery.trim(r[t]);if(p.substring(0,e.length+1)==e+"="){n=decodeURIComponent(p.substring(e.length+1));break}}return n}o=o||{},null===i&&(i="",o.expires=-1);var u="";if(o.expires&&("number"==typeof o.expires||o.expires.toUTCString)){var s;"number"==typeof o.expires?(s=new Date,s.setTime(s.getTime()+24*o.expires*60*60*1e3)):s=o.expires,u="; expires="+s.toUTCString()}var a=o.path?"; path="+o.path:"",c=o.domain?"; domain="+o.domain:"",m=o.secure?"; secure":"";document.cookie=[e,"=",encodeURIComponent(i),u,a,c,m].join("")};


;
var lbrAnaliticsTimerStart = new Date().getTime();
var lbrAnaliticsSaved = false;
var lbrAnaliticsBlur = false;

$(window).on('beforeunload', function() {
    if ( !$.browser.mozilla ) {
        $(window).off('unload');
    }
    $(window).off('blur');
    saveAnalitics('bu');  
});

$(window).on('unload', function() {
    if ( !$.browser.mozilla ){
        $(window).off('unload');
    }
    $(window).off('blur');
    saveAnalitics('u');
});

$(window).on('blur', function() { // run to another tab
    saveAnalitics('blur');
});

$(window).on('focus', function() { // come back to tab
   lbrAnaliticsTimerStart = new Date().getTime();
   lbrAnaliticsBlur = false;
});

$(document).ready(function($){
    alertify.set({ delay: 5000 }); 
    
    /* choose filial */    
    $('#region').click(function() {
        showRegions();
    });
    
    $('.no-price-label').easyTooltip({content:'Цена будет указана в счет-фактуре'});
    
    $('#confirm-region').click(function() {
        var selector = $('#select-region').find(":selected");
        $.ajax({
            type: 'POST',
            url: '/site/setRegion',
            dataType: 'json',
            data:{
                id: selector.val(),
            },
            success: function() {
                location.reload();
                /*$("#region").text(selector.text());
                if($("#setRegion").dialog("isOpen")) {
                    $("#setRegion").dialog('close');
                }*/
        }});           
    });
    /* end choose filial */
    
    $(".l-menu-wrapper").mCustomScrollbar({
        scrollButtons:{
            enable:true
        }
    });
    
    $(".one_banner h3").dotdotdot({
        ellipsis : '... ',
        wrap	 : 'letter',
    });
    
    $(".one-banner-special h3").dotdotdot({
        ellipsis : '... ',
        wrap	 : 'letter',
    });
    
    $('#accordion-sparepart').dcAccordion({
        eventType: 'click',
        saveState: true,
        disableLink: true,
        speed: 'fast',
        classActive: 'test',
        showCount: false
    });
    
    $('.modelline').dcAccordion({
        eventType: 'click',
        autoClose: true,
        disableLink: true,
        speed: 'fast',
        showCount: false,
        menuClose : true
    });
    
    //Add product to wish list
    $( ".wish-small" ).on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/wishlist/add/',
            dataType: 'json',
            data: {
                id: $(this).parent().attr('elem'),
            },
            success: function(response) {
                if(response.redirect) 
                    window.location = response.redirect;
                else {
                    alertify.success(response.message);
                }
            },
        });
    });
    
    if($('#sale-block ul').length) {
        $('#sale-block ul').carouFredSel({
            prev: '#prev-logo-sale',
            next: '#next-logo-sale',
            items: 3,
            direction: 'down',
            auto: false,
        });
    }
    
    $('.prod-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.product-info .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.model-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.modellines-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    // product page
    if($('.price_link').length) {
       $('.price_link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'}); 
    }
    // end product page
    // model page
    if($('.price-link').length) {
       $('.price-link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'});
    }
    
    $( ".small-cart-button" ).on('click', function() {
        var parent = $(this).parent();
        var cart = parent.find('.cart-quantity');
        var count = parseInt(cart.val());
        if(count > 0) {
            $.ajax({
                type: 'POST',
                url: '/cart/add',
                dataType: 'json',
                data: {
                    id: parent.attr('elem'),
                    count: count,
                },
                success: function(response) { 
                    $('.cart-quantity').val('1');
                    if(response.count){
                        var label = ' товаров';
                        if(response.count == 1) {
                            label = ' товар';
                        } else if(response.count == 2 || response.count == 3 || response.count == 4){
                            label = ' товарa';
                        }
                        $('#cart-count').text(response.count+label);
                    }
                    alertify.success(response.message);
                },
            });
        } else {
            alertify.success('<div class="mes-notify"><span></span><div>Введено неправильное количество</div></div>');
        }
    });
    
    // end model page
    // main page
    if($('#carousel ul').length) {
        $('#carousel ul').carouFredSel({
            pagination: "#pager",
            items: 1,
            scroll: 2000,
        });
    }
    if($('#carousel-logo ul').length) {
        $('#carousel-logo ul').carouFredSel({
            next: '#next-logo',
            prev: '#prev-logo',
            auto: {
                items           : 5,
                fx              :"scroll",
                easing          : "linear",
                duration        : 1000,
                pauseOnHover    : true,
            },
           pagination: "#pager-logo",
           items: 5,
        });
    }
    // end main page
    // seo-text in bottom
    $('.text div *').hide();
    $('.text div *:first-child').show();
    $('.text div *:nth-child(2)').show();
    $('.bottom-more').click(function(){
        if ($(this).hasClass('show-text')){
            $(this).parent().find('div *').hide();
            $(this).parent().find('div *:first-child').show();
            $(this).parent().find('div *:nth-child(2)').show();
            $(this).removeClass('show-text').text('Подробнее...');
        }else{
            $(this).parent().find('div *').show();
            $(this).addClass('show-text').text('Скрыть'); 
        }
    });
    // end seo-text in bottom
});
    
function showRegions(){
    $.ajax({
        url : '/site/getRegions/',
        type : 'POST',
        dataType : "json",
        success:function(data) {
           var temp = [];

           $.each(data['filials'], function(key, value) {
                temp.push({v:value, k: key});
           });

           temp.sort(function(a,b){
               if(a.v > b.v){ return 1}
                if(a.v < b.v){ return -1}
                  return 0;
           });

           $.each(temp, function(key, obj) {
                $('#select-region')
                    .append($("<option></option>")
                    .attr("value", obj.k)
                    .text(obj.v))
                ;
           });

           if(data['active']) {
               $('#select-region').val(data['active']).prop('selected', true);
           }

           $("#setRegion").dialog("open");
        },
        error:function() {
            alert('Ошибка запроса к серверу.');
        }
    });
}

function setCookie(name, value, expires, path, domain, secure) {
    if (!name || !value) return false;
    var str = name + '=' + encodeURIComponent(value);
    var today = new Date();
    today.setTime( today.getTime() );
    if ( expires ) {
            expires = expires * 1000 * 60 * 60 * 24;
    }
    var expires_date = new Date( today.getTime() + (expires) );
    if (expires) str += '; expires=' + expires_date.toGMTString();
    if (path) str += '; path=' + path;
    if (domain) str += '; domain=' + domain;
    if (secure) str += '; secure';

    document.cookie = str;
    return true;
} 

function getCookie(name) {
    var pattern = "(?:; )?" + name + "=([^;]*);?";
    var regexp = new RegExp(pattern);
    if (regexp.test(document.cookie)){
        return decodeURIComponent(RegExp["$1"]);
    }
    return false;
}

function saveAnalitics(p)
{   
    if(!lbrAnaliticsSaved) {
        var url = window.location.pathname;
        var time = (new Date().getTime() - lbrAnaliticsTimerStart)/1000; // in seconds

        $.ajax({
            url: '/analitics/save/',
            type: 'POST',
            dataType: "json",
            data: {
                time: time,
                url: url,
                url_mark: lbrAnaliticsMark
            },
            success: function() {
                if(p == 'blur') lbrAnaliticsBlur = true;
                else lbrAnaliticsSaved = true;
            }
        });
    }
}
;
function AjaxQuickSearch(type){
    var _self = this;
    if(type == 'full'){
        this.Option = {
            url: '/search/index/',
            container: '.full-quick-result',
            input: '#full-search'
        };

        $(_self.Option.input).keyup(function(){
            if($.trim($(this).val()).length > 0) {
                if($('.full-quick-result').hasClass('hide')) {
                    $('.full-quick-result').removeClass('hide');
                    $('.full-quick-result').fadeIn(200);
                }
                $('.full-quick-result').fadeIn(200);
                _self.AjaxRequest($(this).val());
            } else {
                $('.full-quick-result').fadeOut(200);
                $('.full-quick-result').addClass('hide');
            }
        });
    } else {
        this.Option = {
            url: '/search/index/',
            container: '.quick-result',
            input: '#search'
        };

        $(_self.Option.input).keyup(function(){
            if($.trim($(this).val()).length > 0) {
                if($('.quick-result').hasClass('hide')) {
                    $('.quick-result').removeClass('hide');
                    $('.quick-result').fadeIn(200);
                }
                $('.quick-result').fadeIn(200);
                _self.AjaxRequest($(this).val());
            } else {
                $('.quick-result').fadeOut(200);
                $('.quick-result').addClass('hide');
            }
        });
    }
    
    this.AjaxRequest = function(query){
        $.ajax({
            'url': _self.Option.url,
            'data': {q: query, ajax: true},
            'success': function(html){_self.AjaxSuccess(html);}
        });
    };

    this.AjaxSuccess = function(html){
         $(_self.Option.container).html(html);
    };
};
var cart = {
    init : function(){
        $('.cart-quantity').focus(function() {
            var list = $(this).parent().find('.shop-cart-quantity');
            list.addClass('active-list');
            list.show();
        });        

        $('.cart-quantity').bind('input', function() {
            var input = $(this).val();
            if(!isNaN(input) && input.length > 0) {
                $('.shop-cart-quantity li:not(.del-num)').hide();
            } else $('.shop-cart-quantity li:not(.del-num)').show();
        });
        
        
        $('.shop-cart-quantity').mouseleave(function() {
            cart.closeList();
            var element = $(this).parent().find('.cart-quantity');
            element.blur();
        });
        
        $('.small-cart-button').click(function() {
            var quantity = $(this).parent().find('.cart-quantity');
            var val = parseInt(quantity.text());
            if(!isNaN(val)) {  
                $.ajax({
                    type: 'POST',
                    url:  '#',
                    dataType: 'json',
                    data:{
                        productId: quantity.attr('product'),
                        status: status,
                        reason: reason,
                        date: date,
                    },
                    success: function(response) {
                        if(response.message != 'date'){
                            var element = $('img[userid=' + $('#u-block-id').val() + ']');
                            element.parent().parent().parent().find('.u-status').text(response.message);
                            element.attr('status', status);
                            $('.u-block').addClass('hide');
                        } else {
                            $('#u-block-to').css('border-color', 'red');
                            flag = false;
                        }
                }});
            }
        });
        
        $('.shop-cart-quantity div').click(function(event) {
            
            var val = parseInt($(this).text());
            if(isNaN(val)) {
                // удалить из корзины
                $('.active-list').parent().find('.cart-quantity').val('');
            } else {
                $('.active-list').parent().find('.cart-quantity').val(val);
            }
            cart.closeList();
            event.stopPropagation();
            //console.log('click');
        });
        
        /*
        $('.cart-quantity').focusout(function() {
            console.log('focusout');
            setTimeout(function () {cart.closeList()}, 15000);
        });
        */
    },
    closeList : function() {
        var list = $('.active-list');
        list.hide();
        list.removeClass('active-list');
    }
};
