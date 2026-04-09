//utils.js
function getVar(e) {
    for (var n = window.location.search.substring(1).split("&"), t = null, i = 0; i < n.length; i++) if ((t = n[i].split("="))[0] == e) return t[1];
    return !1;
}
function arCheck() {
    var e = document.getElementById("ar_submit_iframe");
    e &&
        !e.done &&
        (e.parentNode.removeChild(e),
        (e.className = "wi_frame"),
        (e.done = !0),
        modal({
            name: "ar_test_modal",
            head: "AR Integration Test",
            body: e,
            foot: {
                done: function () {
                    modal.exit();
                },
            },
            evnt: {
                init: function () {
                    HTMLFormElement.prototype.submit.call(jQuery("#AR-INTEGRATION")[0]);
                },
            },
        }));
}
(window.wait = function (e) {
    var n = null,
        t = setInterval(function () {
            var i;
            if ((n = "string" == typeof (i = e.on) ? document.getElementById(i) : "function" == typeof i ? i() : i) && (clearInterval(t), n)) return e.do(), !0;
        }, 50);
}),
    (window.modal = function (e) {
        modal.exit();
        var n,
            t,
            i = document.createElement("div"),
            a = "";
        (i.className = "wi_mdl_ovl"),
            (a += '<div class="wi_mdl_box">'),
            (a += '<div class="wi_mdl_head">' + e.head + "</div>"),
            (a += '<div class="wi_mdl_body"></div>'),
            (a += '<div class="wi_mdl_foot"></div>'),
            (a += "</div>"),
            (i.innerHTML = a),
            "string" == typeof e.body ? (i.getElementsByClassName("wi_mdl_body")[0].innerHTML = e.body) : i.getElementsByClassName("wi_mdl_body")[0].appendChild(e.body),
            i.getElementsByClassName("wi_mdl_foot")[0].appendChild(
                ((n = document.createElement("div")),
                (t = null),
                e.foot.forEach(function (e, i) {
                    ((t = document.createElement("button")).className = "wi_mdl_btn_auto"), (t.innerHTML = e.name), e.callback && "function" == typeof e.callback && (t.onclick = e.callback), n.appendChild(t);
                }),
                n)
            ),
            document.addEventListener(
                "keydown",
                function (e) {
                    27 == e.keyCode && modal.exit();
                },
                !1
            ),
            document.body.appendChild(i),
            e.evnt && e.evnt.init && e.evnt.init(e);
    }),
    (modal.exit = function () {
        var e = [].slice.call(document.getElementsByClassName("wi_mdl_ovl"));
        for (var n in e) e[n].parentNode.removeChild(e[n]);
    });

/*!
 * Bootstrap v3.4.1 (https://getbootstrap.com/)
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under the MIT license
 */
if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
!(function (t) {
    "use strict";
    var e = jQuery.fn.jquery.split(" ")[0].split(".");
    if ((e[0] < 2 && e[1] < 9) || (1 == e[0] && 9 == e[1] && e[2] < 1) || 3 < e[0]) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4");
})(),
    (function (n) {
        "use strict";
        (n.fn.emulateTransitionEnd = function (t) {
            var e = !1,
                i = this;
            n(this).one("bsTransitionEnd", function () {
                e = !0;
            });
            return (
                setTimeout(function () {
                    e || n(i).trigger(n.support.transition.end);
                }, t),
                this
            );
        }),
            n(function () {
                (n.support.transition = (function o() {
                    var t = document.createElement("bootstrap"),
                        e = { WebkitTransition: "webkitTransitionEnd", MozTransition: "transitionend", OTransition: "oTransitionEnd otransitionend", transition: "transitionend" };
                    for (var i in e) if (t.style[i] !== undefined) return { end: e[i] };
                    return !1;
                })()),
                    n.support.transition &&
                        (n.event.special.bsTransitionEnd = {
                            bindType: n.support.transition.end,
                            delegateType: n.support.transition.end,
                            handle: function (t) {
                                if (n(t.target).is(this)) return t.handleObj.handler.apply(this, arguments);
                            },
                        });
            });
    })(jQuery),
    (function (s) {
        "use strict";
        var e = '[data-dismiss="alert"]',
            a = function (t) {
                s(t).on("click", e, this.close);
            };
        (a.VERSION = "3.4.1"),
            (a.TRANSITION_DURATION = 150),
            (a.prototype.close = function (t) {
                var e = s(this),
                    i = e.attr("data-target");
                i || (i = (i = e.attr("href")) && i.replace(/.*(?=#[^\s]*$)/, "")), (i = "#" === i ? [] : i);
                var o = s(document).find(i);
                function n() {
                    o.detach().trigger("closed.bs.alert").remove();
                }
                t && t.preventDefault(),
                    o.length || (o = e.closest(".alert")),
                    o.trigger((t = s.Event("close.bs.alert"))),
                    t.isDefaultPrevented() || (o.removeClass("in"), s.support.transition && o.hasClass("fade") ? o.one("bsTransitionEnd", n).emulateTransitionEnd(a.TRANSITION_DURATION) : n());
            });
        var t = s.fn.alert;
        (s.fn.alert = function o(i) {
            return this.each(function () {
                var t = s(this),
                    e = t.data("bs.alert");
                e || t.data("bs.alert", (e = new a(this))), "string" == typeof i && e[i].call(t);
            });
        }),
            (s.fn.alert.Constructor = a),
            (s.fn.alert.noConflict = function () {
                return (s.fn.alert = t), this;
            }),
            s(document).on("click.bs.alert.data-api", e, a.prototype.close);
    })(jQuery),
    (function (s) {
        "use strict";
        var n = function (t, e) {
            (this.$element = s(t)), (this.options = s.extend({}, n.DEFAULTS, e)), (this.isLoading = !1);
        };
        function i(o) {
            return this.each(function () {
                var t = s(this),
                    e = t.data("bs.button"),
                    i = "object" == typeof o && o;
                e || t.data("bs.button", (e = new n(this, i))), "toggle" == o ? e.toggle() : o && e.setState(o);
            });
        }
        (n.VERSION = "3.4.1"),
            (n.DEFAULTS = { loadingText: "loading..." }),
            (n.prototype.setState = function (t) {
                var e = "disabled",
                    i = this.$element,
                    o = i.is("input") ? "val" : "html",
                    n = i.data();
                (t += "Text"),
                    null == n.resetText && i.data("resetText", i[o]()),
                    setTimeout(
                        s.proxy(function () {
                            i[o](null == n[t] ? this.options[t] : n[t]),
                                "loadingText" == t ? ((this.isLoading = !0), i.addClass(e).attr(e, e).prop(e, !0)) : this.isLoading && ((this.isLoading = !1), i.removeClass(e).removeAttr(e).prop(e, !1));
                        }, this),
                        0
                    );
            }),
            (n.prototype.toggle = function () {
                var t = !0,
                    e = this.$element.closest('[data-toggle="buttons"]');
                if (e.length) {
                    var i = this.$element.find("input");
                    "radio" == i.prop("type")
                        ? (i.prop("checked") && (t = !1), e.find(".active").removeClass("active"), this.$element.addClass("active"))
                        : "checkbox" == i.prop("type") && (i.prop("checked") !== this.$element.hasClass("active") && (t = !1), this.$element.toggleClass("active")),
                        i.prop("checked", this.$element.hasClass("active")),
                        t && i.trigger("change");
                } else this.$element.attr("aria-pressed", !this.$element.hasClass("active")), this.$element.toggleClass("active");
            });
        var t = s.fn.button;
        (s.fn.button = i),
            (s.fn.button.Constructor = n),
            (s.fn.button.noConflict = function () {
                return (s.fn.button = t), this;
            }),
            s(document)
                .on("click.bs.button.data-api", '[data-toggle^="button"]', function (t) {
                    var e = s(t.target).closest(".btn");
                    i.call(e, "toggle"), s(t.target).is('input[type="radio"], input[type="checkbox"]') || (t.preventDefault(), e.is("input,button") ? e.trigger("focus") : e.find("input:visible,button:visible").first().trigger("focus"));
                })
                .on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function (t) {
                    s(t.target)
                        .closest(".btn")
                        .toggleClass("focus", /^focus(in)?$/.test(t.type));
                });
    })(jQuery),
    (function (p) {
        "use strict";
        var c = function (t, e) {
            (this.$element = p(t)),
                (this.$indicators = this.$element.find(".carousel-indicators")),
                (this.options = e),
                (this.paused = null),
                (this.sliding = null),
                (this.interval = null),
                (this.$active = null),
                (this.$items = null),
                this.options.keyboard && this.$element.on("keydown.bs.carousel", p.proxy(this.keydown, this)),
                "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", p.proxy(this.pause, this)).on("mouseleave.bs.carousel", p.proxy(this.cycle, this));
        };
        function r(n) {
            return this.each(function () {
                var t = p(this),
                    e = t.data("bs.carousel"),
                    i = p.extend({}, c.DEFAULTS, t.data(), "object" == typeof n && n),
                    o = "string" == typeof n ? n : i.slide;
                e || t.data("bs.carousel", (e = new c(this, i))), "number" == typeof n ? e.to(n) : o ? e[o]() : i.interval && e.pause().cycle();
            });
        }
        (c.VERSION = "3.4.1"),
            (c.TRANSITION_DURATION = 600),
            (c.DEFAULTS = { interval: 5e3, pause: "hover", wrap: !0, keyboard: !0 }),
            (c.prototype.keydown = function (t) {
                if (!/input|textarea/i.test(t.target.tagName)) {
                    switch (t.which) {
                        case 37:
                            this.prev();
                            break;
                        case 39:
                            this.next();
                            break;
                        default:
                            return;
                    }
                    t.preventDefault();
                }
            }),
            (c.prototype.cycle = function (t) {
                return t || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(p.proxy(this.next, this), this.options.interval)), this;
            }),
            (c.prototype.getItemIndex = function (t) {
                return (this.$items = t.parent().children(".item")), this.$items.index(t || this.$active);
            }),
            (c.prototype.getItemForDirection = function (t, e) {
                var i = this.getItemIndex(e);
                if ((("prev" == t && 0 === i) || ("next" == t && i == this.$items.length - 1)) && !this.options.wrap) return e;
                var o = (i + ("prev" == t ? -1 : 1)) % this.$items.length;
                return this.$items.eq(o);
            }),
            (c.prototype.to = function (t) {
                var e = this,
                    i = this.getItemIndex((this.$active = this.$element.find(".item.active")));
                if (!(t > this.$items.length - 1 || t < 0))
                    return this.sliding
                        ? this.$element.one("slid.bs.carousel", function () {
                              e.to(t);
                          })
                        : i == t
                        ? this.pause().cycle()
                        : this.slide(i < t ? "next" : "prev", this.$items.eq(t));
            }),
            (c.prototype.pause = function (t) {
                return t || (this.paused = !0), this.$element.find(".next, .prev").length && p.support.transition && (this.$element.trigger(p.support.transition.end), this.cycle(!0)), (this.interval = clearInterval(this.interval)), this;
            }),
            (c.prototype.next = function () {
                if (!this.sliding) return this.slide("next");
            }),
            (c.prototype.prev = function () {
                if (!this.sliding) return this.slide("prev");
            }),
            (c.prototype.slide = function (t, e) {
                var i = this.$element.find(".item.active"),
                    o = e || this.getItemForDirection(t, i),
                    n = this.interval,
                    s = "next" == t ? "left" : "right",
                    a = this;
                if (o.hasClass("active")) return (this.sliding = !1);
                var r = o[0],
                    l = p.Event("slide.bs.carousel", { relatedTarget: r, direction: s });
                if ((this.$element.trigger(l), !l.isDefaultPrevented())) {
                    if (((this.sliding = !0), n && this.pause(), this.$indicators.length)) {
                        this.$indicators.find(".active").removeClass("active");
                        var h = p(this.$indicators.children()[this.getItemIndex(o)]);
                        h && h.addClass("active");
                    }
                    var d = p.Event("slid.bs.carousel", { relatedTarget: r, direction: s });
                    return (
                        p.support.transition && this.$element.hasClass("slide")
                            ? (o.addClass(t),
                              "object" == typeof o && o.length && o[0].offsetWidth,
                              i.addClass(s),
                              o.addClass(s),
                              i
                                  .one("bsTransitionEnd", function () {
                                      o.removeClass([t, s].join(" ")).addClass("active"),
                                          i.removeClass(["active", s].join(" ")),
                                          (a.sliding = !1),
                                          setTimeout(function () {
                                              a.$element.trigger(d);
                                          }, 0);
                                  })
                                  .emulateTransitionEnd(c.TRANSITION_DURATION))
                            : (i.removeClass("active"), o.addClass("active"), (this.sliding = !1), this.$element.trigger(d)),
                        n && this.cycle(),
                        this
                    );
                }
            });
        var t = p.fn.carousel;
        (p.fn.carousel = r),
            (p.fn.carousel.Constructor = c),
            (p.fn.carousel.noConflict = function () {
                return (p.fn.carousel = t), this;
            });
        var e = function (t) {
            var e = p(this),
                i = e.attr("href");
            i && (i = i.replace(/.*(?=#[^\s]+$)/, ""));
            var o = e.attr("data-target") || i,
                n = p(document).find(o);
            if (n.hasClass("carousel")) {
                var s = p.extend({}, n.data(), e.data()),
                    a = e.attr("data-slide-to");
                a && (s.interval = !1), r.call(n, s), a && n.data("bs.carousel").to(a), t.preventDefault();
            }
        };
        p(document).on("click.bs.carousel.data-api", "[data-slide]", e).on("click.bs.carousel.data-api", "[data-slide-to]", e),
            p(window).on("load", function () {
                p('[data-ride="carousel"]').each(function () {
                    var t = p(this);
                    r.call(t, t.data());
                });
            });
    })(jQuery),
    (function (a) {
        "use strict";
        var r = function (t, e) {
            (this.$element = a(t)),
                (this.options = a.extend({}, r.DEFAULTS, e)),
                (this.$trigger = a('[data-toggle="collapse"][href="#' + t.id + '"],[data-toggle="collapse"][data-target="#' + t.id + '"]')),
                (this.transitioning = null),
                this.options.parent ? (this.$parent = this.getParent()) : this.addAriaAndCollapsedClass(this.$element, this.$trigger),
                this.options.toggle && this.toggle();
        };
        function n(t) {
            var e,
                i = t.attr("data-target") || ((e = t.attr("href")) && e.replace(/.*(?=#[^\s]+$)/, ""));
            return a(document).find(i);
        }
        function l(o) {
            return this.each(function () {
                var t = a(this),
                    e = t.data("bs.collapse"),
                    i = a.extend({}, r.DEFAULTS, t.data(), "object" == typeof o && o);
                !e && i.toggle && /show|hide/.test(o) && (i.toggle = !1), e || t.data("bs.collapse", (e = new r(this, i))), "string" == typeof o && e[o]();
            });
        }
        (r.VERSION = "3.4.1"),
            (r.TRANSITION_DURATION = 350),
            (r.DEFAULTS = { toggle: !0 }),
            (r.prototype.dimension = function () {
                return this.$element.hasClass("width") ? "width" : "height";
            }),
            (r.prototype.show = function () {
                if (!this.transitioning && !this.$element.hasClass("in")) {
                    var t,
                        e = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
                    if (!(e && e.length && (t = e.data("bs.collapse")) && t.transitioning)) {
                        var i = a.Event("show.bs.collapse");
                        if ((this.$element.trigger(i), !i.isDefaultPrevented())) {
                            e && e.length && (l.call(e, "hide"), t || e.data("bs.collapse", null));
                            var o = this.dimension();
                            this.$element.removeClass("collapse").addClass("collapsing")[o](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), (this.transitioning = 1);
                            var n = function () {
                                this.$element.removeClass("collapsing").addClass("collapse in")[o](""), (this.transitioning = 0), this.$element.trigger("shown.bs.collapse");
                            };
                            if (!a.support.transition) return n.call(this);
                            var s = a.camelCase(["scroll", o].join("-"));
                            this.$element.one("bsTransitionEnd", a.proxy(n, this)).emulateTransitionEnd(r.TRANSITION_DURATION)[o](this.$element[0][s]);
                        }
                    }
                }
            }),
            (r.prototype.hide = function () {
                if (!this.transitioning && this.$element.hasClass("in")) {
                    var t = a.Event("hide.bs.collapse");
                    if ((this.$element.trigger(t), !t.isDefaultPrevented())) {
                        var e = this.dimension();
                        this.$element[e](this.$element[e]())[0].offsetHeight,
                            this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1),
                            this.$trigger.addClass("collapsed").attr("aria-expanded", !1),
                            (this.transitioning = 1);
                        var i = function () {
                            (this.transitioning = 0), this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse");
                        };
                        if (!a.support.transition) return i.call(this);
                        this.$element[e](0).one("bsTransitionEnd", a.proxy(i, this)).emulateTransitionEnd(r.TRANSITION_DURATION);
                    }
                }
            }),
            (r.prototype.toggle = function () {
                this[this.$element.hasClass("in") ? "hide" : "show"]();
            }),
            (r.prototype.getParent = function () {
                return a(document)
                    .find(this.options.parent)
                    .find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]')
                    .each(
                        a.proxy(function (t, e) {
                            var i = a(e);
                            this.addAriaAndCollapsedClass(n(i), i);
                        }, this)
                    )
                    .end();
            }),
            (r.prototype.addAriaAndCollapsedClass = function (t, e) {
                var i = t.hasClass("in");
                t.attr("aria-expanded", i), e.toggleClass("collapsed", !i).attr("aria-expanded", i);
            });
        var t = a.fn.collapse;
        (a.fn.collapse = l),
            (a.fn.collapse.Constructor = r),
            (a.fn.collapse.noConflict = function () {
                return (a.fn.collapse = t), this;
            }),
            a(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function (t) {
                var e = a(this);
                e.attr("data-target") || t.preventDefault();
                var i = n(e),
                    o = i.data("bs.collapse") ? "toggle" : e.data();
                l.call(i, o);
            });
    })(jQuery),
    (function (a) {
        "use strict";
        var r = '[data-toggle="dropdown"]',
            o = function (t) {
                a(t).on("click.bs.dropdown", this.toggle);
            };
        function l(t) {
            var e = t.attr("data-target");
            e || (e = (e = t.attr("href")) && /#[A-Za-z]/.test(e) && e.replace(/.*(?=#[^\s]*$)/, ""));
            var i = "#" !== e ? a(document).find(e) : null;
            return i && i.length ? i : t.parent();
        }
        function s(o) {
            (o && 3 === o.which) ||
                (a(".dropdown-backdrop").remove(),
                a(r).each(function () {
                    var t = a(this),
                        e = l(t),
                        i = { relatedTarget: this };
                    e.hasClass("open") &&
                        ((o && "click" == o.type && /input|textarea/i.test(o.target.tagName) && a.contains(e[0], o.target)) ||
                            (e.trigger((o = a.Event("hide.bs.dropdown", i))), o.isDefaultPrevented() || (t.attr("aria-expanded", "false"), e.removeClass("open").trigger(a.Event("hidden.bs.dropdown", i)))));
                }));
        }
        (o.VERSION = "3.4.1"),
            (o.prototype.toggle = function (t) {
                var e = a(this);
                if (!e.is(".disabled, :disabled")) {
                    var i = l(e),
                        o = i.hasClass("open");
                    if ((s(), !o)) {
                        "ontouchstart" in document.documentElement && !i.closest(".navbar-nav").length && a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click", s);
                        var n = { relatedTarget: this };
                        if ((i.trigger((t = a.Event("show.bs.dropdown", n))), t.isDefaultPrevented())) return;
                        e.trigger("focus").attr("aria-expanded", "true"), i.toggleClass("open").trigger(a.Event("shown.bs.dropdown", n));
                    }
                    return !1;
                }
            }),
            (o.prototype.keydown = function (t) {
                if (/(38|40|27|32)/.test(t.which) && !/input|textarea/i.test(t.target.tagName)) {
                    var e = a(this);
                    if ((t.preventDefault(), t.stopPropagation(), !e.is(".disabled, :disabled"))) {
                        var i = l(e),
                            o = i.hasClass("open");
                        if ((!o && 27 != t.which) || (o && 27 == t.which)) return 27 == t.which && i.find(r).trigger("focus"), e.trigger("click");
                        var n = i.find(".dropdown-menu li:not(.disabled):visible a");
                        if (n.length) {
                            var s = n.index(t.target);
                            38 == t.which && 0 < s && s--, 40 == t.which && s < n.length - 1 && s++, ~s || (s = 0), n.eq(s).trigger("focus");
                        }
                    }
                }
            });
        var t = a.fn.dropdown;
        (a.fn.dropdown = function e(i) {
            return this.each(function () {
                var t = a(this),
                    e = t.data("bs.dropdown");
                e || t.data("bs.dropdown", (e = new o(this))), "string" == typeof i && e[i].call(t);
            });
        }),
            (a.fn.dropdown.Constructor = o),
            (a.fn.dropdown.noConflict = function () {
                return (a.fn.dropdown = t), this;
            }),
            a(document)
                .on("click.bs.dropdown.data-api", s)
                .on("click.bs.dropdown.data-api", ".dropdown form", function (t) {
                    t.stopPropagation();
                })
                .on("click.bs.dropdown.data-api", r, o.prototype.toggle)
                .on("keydown.bs.dropdown.data-api", r, o.prototype.keydown)
                .on("keydown.bs.dropdown.data-api", ".dropdown-menu", o.prototype.keydown);
    })(jQuery),
    (function (a) {
        "use strict";
        var s = function (t, e) {
            (this.options = e),
                (this.$body = a(document.body)),
                (this.$element = a(t)),
                (this.$dialog = this.$element.find(".modal-dialog")),
                (this.$backdrop = null),
                (this.isShown = null),
                (this.originalBodyPad = null),
                (this.scrollbarWidth = 0),
                (this.ignoreBackdropClick = !1),
                (this.fixedContent = ".navbar-fixed-top, .navbar-fixed-bottom"),
                this.options.remote &&
                    this.$element.find(".modal-content").load(
                        this.options.remote,
                        a.proxy(function () {
                            this.$element.trigger("loaded.bs.modal");
                        }, this)
                    );
        };
        function r(o, n) {
            return this.each(function () {
                var t = a(this),
                    e = t.data("bs.modal"),
                    i = a.extend({}, s.DEFAULTS, t.data(), "object" == typeof o && o);
                e || t.data("bs.modal", (e = new s(this, i))), "string" == typeof o ? e[o](n) : i.show && e.show(n);
            });
        }
        (s.VERSION = "3.4.1"),
            (s.TRANSITION_DURATION = 300),
            (s.BACKDROP_TRANSITION_DURATION = 150),
            (s.DEFAULTS = { backdrop: !0, keyboard: !0, show: !0 }),
            (s.prototype.toggle = function (t) {
                return this.isShown ? this.hide() : this.show(t);
            }),
            (s.prototype.show = function (i) {
                var o = this,
                    t = a.Event("show.bs.modal", { relatedTarget: i });
                this.$element.trigger(t),
                    this.isShown ||
                        t.isDefaultPrevented() ||
                        ((this.isShown = !0),
                        this.checkScrollbar(),
                        this.setScrollbar(),
                        this.$body.addClass("modal-open"),
                        this.escape(),
                        this.resize(),
                        this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', a.proxy(this.hide, this)),
                        this.$dialog.on("mousedown.dismiss.bs.modal", function () {
                            o.$element.one("mouseup.dismiss.bs.modal", function (t) {
                                a(t.target).is(o.$element) && (o.ignoreBackdropClick = !0);
                            });
                        }),
                        this.backdrop(function () {
                            var t = a.support.transition && o.$element.hasClass("fade");
                            o.$element.parent().length || o.$element.appendTo(o.$body), o.$element.show().scrollTop(0), o.adjustDialog(), t && o.$element[0].offsetWidth, o.$element.addClass("in"), o.enforceFocus();
                            var e = a.Event("shown.bs.modal", { relatedTarget: i });
                            t
                                ? o.$dialog
                                      .one("bsTransitionEnd", function () {
                                          o.$element.trigger("focus").trigger(e);
                                      })
                                      .emulateTransitionEnd(s.TRANSITION_DURATION)
                                : o.$element.trigger("focus").trigger(e);
                        }));
            }),
            (s.prototype.hide = function (t) {
                t && t.preventDefault(),
                    (t = a.Event("hide.bs.modal")),
                    this.$element.trigger(t),
                    this.isShown &&
                        !t.isDefaultPrevented() &&
                        ((this.isShown = !1),
                        this.escape(),
                        this.resize(),
                        a(document).off("focusin.bs.modal"),
                        this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),
                        this.$dialog.off("mousedown.dismiss.bs.modal"),
                        a.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", a.proxy(this.hideModal, this)).emulateTransitionEnd(s.TRANSITION_DURATION) : this.hideModal());
            }),
            (s.prototype.enforceFocus = function () {
                a(document)
                    .off("focusin.bs.modal")
                    .on(
                        "focusin.bs.modal",
                        a.proxy(function (t) {
                            document === t.target || this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus");
                        }, this)
                    );
            }),
            (s.prototype.escape = function () {
                this.isShown && this.options.keyboard
                    ? this.$element.on(
                          "keydown.dismiss.bs.modal",
                          a.proxy(function (t) {
                              27 == t.which && this.hide();
                          }, this)
                      )
                    : this.isShown || this.$element.off("keydown.dismiss.bs.modal");
            }),
            (s.prototype.resize = function () {
                this.isShown ? a(window).on("resize.bs.modal", a.proxy(this.handleUpdate, this)) : a(window).off("resize.bs.modal");
            }),
            (s.prototype.hideModal = function () {
                var t = this;
                this.$element.hide(),
                    this.backdrop(function () {
                        t.$body.removeClass("modal-open"), t.resetAdjustments(), t.resetScrollbar(), t.$element.trigger("hidden.bs.modal");
                    });
            }),
            (s.prototype.removeBackdrop = function () {
                this.$backdrop && this.$backdrop.remove(), (this.$backdrop = null);
            }),
            (s.prototype.backdrop = function (t) {
                var e = this,
                    i = this.$element.hasClass("fade") ? "fade" : "";
                if (this.isShown && this.options.backdrop) {
                    var o = a.support.transition && i;
                    if (
                        ((this.$backdrop = a(document.createElement("div"))
                            .addClass("modal-backdrop " + i)
                            .appendTo(this.$body)),
                        this.$element.on(
                            "click.dismiss.bs.modal",
                            a.proxy(function (t) {
                                this.ignoreBackdropClick ? (this.ignoreBackdropClick = !1) : t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide());
                            }, this)
                        ),
                        o && this.$backdrop[0].offsetWidth,
                        this.$backdrop.addClass("in"),
                        !t)
                    )
                        return;
                    o ? this.$backdrop.one("bsTransitionEnd", t).emulateTransitionEnd(s.BACKDROP_TRANSITION_DURATION) : t();
                } else if (!this.isShown && this.$backdrop) {
                    this.$backdrop.removeClass("in");
                    var n = function () {
                        e.removeBackdrop(), t && t();
                    };
                    a.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", n).emulateTransitionEnd(s.BACKDROP_TRANSITION_DURATION) : n();
                } else t && t();
            }),
            (s.prototype.handleUpdate = function () {
                this.adjustDialog();
            }),
            (s.prototype.adjustDialog = function () {
                var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
                this.$element.css({ paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "", paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : "" });
            }),
            (s.prototype.resetAdjustments = function () {
                this.$element.css({ paddingLeft: "", paddingRight: "" });
            }),
            (s.prototype.checkScrollbar = function () {
                var t = window.innerWidth;
                if (!t) {
                    var e = document.documentElement.getBoundingClientRect();
                    t = e.right - Math.abs(e.left);
                }
                (this.bodyIsOverflowing = document.body.clientWidth < t), (this.scrollbarWidth = this.measureScrollbar());
            }),
            (s.prototype.setScrollbar = function () {
                var t = parseInt(this.$body.css("padding-right") || 0, 10);
                this.originalBodyPad = document.body.style.paddingRight || "";
                var n = this.scrollbarWidth;
                this.bodyIsOverflowing &&
                    (this.$body.css("padding-right", t + n),
                    a(this.fixedContent).each(function (t, e) {
                        var i = e.style.paddingRight,
                            o = a(e).css("padding-right");
                        a(e)
                            .data("padding-right", i)
                            .css("padding-right", parseFloat(o) + n + "px");
                    }));
            }),
            (s.prototype.resetScrollbar = function () {
                this.$body.css("padding-right", this.originalBodyPad),
                    a(this.fixedContent).each(function (t, e) {
                        var i = a(e).data("padding-right");
                        a(e).removeData("padding-right"), (e.style.paddingRight = i || "");
                    });
            }),
            (s.prototype.measureScrollbar = function () {
                var t = document.createElement("div");
                (t.className = "modal-scrollbar-measure"), this.$body.append(t);
                var e = t.offsetWidth - t.clientWidth;
                return this.$body[0].removeChild(t), e;
            });
        var t = a.fn.modal;
        (a.fn.modal = r),
            (a.fn.modal.Constructor = s),
            (a.fn.modal.noConflict = function () {
                return (a.fn.modal = t), this;
            }),
            a(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function (t) {
                var e = a(this),
                    i = e.attr("href"),
                    o = e.attr("data-target") || (i && i.replace(/.*(?=#[^\s]+$)/, "")),
                    n = a(document).find(o),
                    s = n.data("bs.modal") ? "toggle" : a.extend({ remote: !/#/.test(i) && i }, n.data(), e.data());
                e.is("a") && t.preventDefault(),
                    n.one("show.bs.modal", function (t) {
                        t.isDefaultPrevented() ||
                            n.one("hidden.bs.modal", function () {
                                e.is(":visible") && e.trigger("focus");
                            });
                    }),
                    r.call(n, s, this);
            });
    })(jQuery),
    (function (g) {
        "use strict";
        var o = ["sanitize", "whiteList", "sanitizeFn"],
            a = ["background", "cite", "href", "itemtype", "longdesc", "poster", "src", "xlink:href"],
            t = {
                "*": ["class", "dir", "id", "lang", "role", /^aria-[\w-]*$/i],
                a: ["target", "href", "title", "rel"],
                area: [],
                b: [],
                br: [],
                col: [],
                code: [],
                div: [],
                em: [],
                hr: [],
                h1: [],
                h2: [],
                h3: [],
                h4: [],
                h5: [],
                h6: [],
                i: [],
                img: ["src", "alt", "title", "width", "height"],
                li: [],
                ol: [],
                p: [],
                pre: [],
                s: [],
                small: [],
                span: [],
                sub: [],
                sup: [],
                strong: [],
                u: [],
                ul: [],
            },
            r = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi,
            l = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i;
        function u(t, e) {
            var i = t.nodeName.toLowerCase();
            if (-1 !== g.inArray(i, e)) return -1 === g.inArray(i, a) || Boolean(t.nodeValue.match(r) || t.nodeValue.match(l));
            for (
                var o = g(e).filter(function (t, e) {
                        return e instanceof RegExp;
                    }),
                    n = 0,
                    s = o.length;
                n < s;
                n++
            )
                if (i.match(o[n])) return !0;
            return !1;
        }
        function n(t, e, i) {
            if (0 === t.length) return t;
            if (i && "function" == typeof i) return i(t);
            if (!document.implementation || !document.implementation.createHTMLDocument) return t;
            var o = document.implementation.createHTMLDocument("sanitization");
            o.body.innerHTML = t;
            for (
                var n = g.map(e, function (t, e) {
                        return e;
                    }),
                    s = g(o.body).find("*"),
                    a = 0,
                    r = s.length;
                a < r;
                a++
            ) {
                var l = s[a],
                    h = l.nodeName.toLowerCase();
                if (-1 !== g.inArray(h, n))
                    for (
                        var d = g.map(l.attributes, function (t) {
                                return t;
                            }),
                            p = [].concat(e["*"] || [], e[h] || []),
                            c = 0,
                            f = d.length;
                        c < f;
                        c++
                    )
                        u(d[c], p) || l.removeAttribute(d[c].nodeName);
                else l.parentNode.removeChild(l);
            }
            return o.body.innerHTML;
        }
        var m = function (t, e) {
            (this.type = null), (this.options = null), (this.enabled = null), (this.timeout = null), (this.hoverState = null), (this.$element = null), (this.inState = null), this.init("tooltip", t, e);
        };
        (m.VERSION = "3.4.1"),
            (m.TRANSITION_DURATION = 150),
            (m.DEFAULTS = {
                animation: !0,
                placement: "top",
                selector: !1,
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                trigger: "hover focus",
                title: "",
                delay: 0,
                html: !1,
                container: !1,
                viewport: { selector: "body", padding: 0 },
                sanitize: !0,
                sanitizeFn: null,
                whiteList: t,
            }),
            (m.prototype.init = function (t, e, i) {
                if (
                    ((this.enabled = !0),
                    (this.type = t),
                    (this.$element = g(e)),
                    (this.options = this.getOptions(i)),
                    (this.$viewport = this.options.viewport && g(document).find(g.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : this.options.viewport.selector || this.options.viewport)),
                    (this.inState = { click: !1, hover: !1, focus: !1 }),
                    this.$element[0] instanceof document.constructor && !this.options.selector)
                )
                    throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
                for (var o = this.options.trigger.split(" "), n = o.length; n--; ) {
                    var s = o[n];
                    if ("click" == s) this.$element.on("click." + this.type, this.options.selector, g.proxy(this.toggle, this));
                    else if ("manual" != s) {
                        var a = "hover" == s ? "mouseenter" : "focusin",
                            r = "hover" == s ? "mouseleave" : "focusout";
                        this.$element.on(a + "." + this.type, this.options.selector, g.proxy(this.enter, this)), this.$element.on(r + "." + this.type, this.options.selector, g.proxy(this.leave, this));
                    }
                }
                this.options.selector ? (this._options = g.extend({}, this.options, { trigger: "manual", selector: "" })) : this.fixTitle();
            }),
            (m.prototype.getDefaults = function () {
                return m.DEFAULTS;
            }),
            (m.prototype.getOptions = function (t) {
                var e = this.$element.data();
                for (var i in e) e.hasOwnProperty(i) && -1 !== g.inArray(i, o) && delete e[i];
                return (t = g.extend({}, this.getDefaults(), e, t)).delay && "number" == typeof t.delay && (t.delay = { show: t.delay, hide: t.delay }), t.sanitize && (t.template = n(t.template, t.whiteList, t.sanitizeFn)), t;
            }),
            (m.prototype.getDelegateOptions = function () {
                var i = {},
                    o = this.getDefaults();
                return (
                    this._options &&
                        g.each(this._options, function (t, e) {
                            o[t] != e && (i[t] = e);
                        }),
                    i
                );
            }),
            (m.prototype.enter = function (t) {
                var e = t instanceof this.constructor ? t : g(t.currentTarget).data("bs." + this.type);
                if (
                    (e || ((e = new this.constructor(t.currentTarget, this.getDelegateOptions())), g(t.currentTarget).data("bs." + this.type, e)),
                    t instanceof g.Event && (e.inState["focusin" == t.type ? "focus" : "hover"] = !0),
                    e.tip().hasClass("in") || "in" == e.hoverState)
                )
                    e.hoverState = "in";
                else {
                    if ((clearTimeout(e.timeout), (e.hoverState = "in"), !e.options.delay || !e.options.delay.show)) return e.show();
                    e.timeout = setTimeout(function () {
                        "in" == e.hoverState && e.show();
                    }, e.options.delay.show);
                }
            }),
            (m.prototype.isInStateTrue = function () {
                for (var t in this.inState) if (this.inState[t]) return !0;
                return !1;
            }),
            (m.prototype.leave = function (t) {
                var e = t instanceof this.constructor ? t : g(t.currentTarget).data("bs." + this.type);
                if (
                    (e || ((e = new this.constructor(t.currentTarget, this.getDelegateOptions())), g(t.currentTarget).data("bs." + this.type, e)),
                    t instanceof g.Event && (e.inState["focusout" == t.type ? "focus" : "hover"] = !1),
                    !e.isInStateTrue())
                ) {
                    if ((clearTimeout(e.timeout), (e.hoverState = "out"), !e.options.delay || !e.options.delay.hide)) return e.hide();
                    e.timeout = setTimeout(function () {
                        "out" == e.hoverState && e.hide();
                    }, e.options.delay.hide);
                }
            }),
            (m.prototype.show = function () {
                var t = g.Event("show.bs." + this.type);
                if (this.hasContent() && this.enabled) {
                    this.$element.trigger(t);
                    var e = g.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
                    if (t.isDefaultPrevented() || !e) return;
                    var i = this,
                        o = this.tip(),
                        n = this.getUID(this.type);
                    this.setContent(), o.attr("id", n), this.$element.attr("aria-describedby", n), this.options.animation && o.addClass("fade");
                    var s = "function" == typeof this.options.placement ? this.options.placement.call(this, o[0], this.$element[0]) : this.options.placement,
                        a = /\s?auto?\s?/i,
                        r = a.test(s);
                    r && (s = s.replace(a, "") || "top"),
                        o
                            .detach()
                            .css({ top: 0, left: 0, display: "block" })
                            .addClass(s)
                            .data("bs." + this.type, this),
                        this.options.container ? o.appendTo(g(document).find(this.options.container)) : o.insertAfter(this.$element),
                        this.$element.trigger("inserted.bs." + this.type);
                    var l = this.getPosition(),
                        h = o[0].offsetWidth,
                        d = o[0].offsetHeight;
                    if (r) {
                        var p = s,
                            c = this.getPosition(this.$viewport);
                        (s = "bottom" == s && l.bottom + d > c.bottom ? "top" : "top" == s && l.top - d < c.top ? "bottom" : "right" == s && l.right + h > c.width ? "left" : "left" == s && l.left - h < c.left ? "right" : s),
                            o.removeClass(p).addClass(s);
                    }
                    var f = this.getCalculatedOffset(s, l, h, d);
                    this.applyPlacement(f, s);
                    var u = function () {
                        var t = i.hoverState;
                        i.$element.trigger("shown.bs." + i.type), (i.hoverState = null), "out" == t && i.leave(i);
                    };
                    g.support.transition && this.$tip.hasClass("fade") ? o.one("bsTransitionEnd", u).emulateTransitionEnd(m.TRANSITION_DURATION) : u();
                }
            }),
            (m.prototype.applyPlacement = function (t, e) {
                var i = this.tip(),
                    o = i[0].offsetWidth,
                    n = i[0].offsetHeight,
                    s = parseInt(i.css("margin-top"), 10),
                    a = parseInt(i.css("margin-left"), 10);
                isNaN(s) && (s = 0),
                    isNaN(a) && (a = 0),
                    (t.top += s),
                    (t.left += a),
                    g.offset.setOffset(
                        i[0],
                        g.extend(
                            {
                                using: function (t) {
                                    i.css({ top: Math.round(t.top), left: Math.round(t.left) });
                                },
                            },
                            t
                        ),
                        0
                    ),
                    i.addClass("in");
                var r = i[0].offsetWidth,
                    l = i[0].offsetHeight;
                "top" == e && l != n && (t.top = t.top + n - l);
                var h = this.getViewportAdjustedDelta(e, t, r, l);
                h.left ? (t.left += h.left) : (t.top += h.top);
                var d = /top|bottom/.test(e),
                    p = d ? 2 * h.left - o + r : 2 * h.top - n + l,
                    c = d ? "offsetWidth" : "offsetHeight";
                i.offset(t), this.replaceArrow(p, i[0][c], d);
            }),
            (m.prototype.replaceArrow = function (t, e, i) {
                this.arrow()
                    .css(i ? "left" : "top", 50 * (1 - t / e) + "%")
                    .css(i ? "top" : "left", "");
            }),
            (m.prototype.setContent = function () {
                var t = this.tip(),
                    e = this.getTitle();
                this.options.html ? (this.options.sanitize && (e = n(e, this.options.whiteList, this.options.sanitizeFn)), t.find(".tooltip-inner").html(e)) : t.find(".tooltip-inner").text(e), t.removeClass("fade in top bottom left right");
            }),
            (m.prototype.hide = function (t) {
                var e = this,
                    i = g(this.$tip),
                    o = g.Event("hide.bs." + this.type);
                function n() {
                    "in" != e.hoverState && i.detach(), e.$element && e.$element.removeAttr("aria-describedby").trigger("hidden.bs." + e.type), t && t();
                }
                if ((this.$element.trigger(o), !o.isDefaultPrevented()))
                    return i.removeClass("in"), g.support.transition && i.hasClass("fade") ? i.one("bsTransitionEnd", n).emulateTransitionEnd(m.TRANSITION_DURATION) : n(), (this.hoverState = null), this;
            }),
            (m.prototype.fixTitle = function () {
                var t = this.$element;
                (t.attr("title") || "string" != typeof t.attr("data-original-title")) && t.attr("data-original-title", t.attr("title") || "").attr("title", "");
            }),
            (m.prototype.hasContent = function () {
                return this.getTitle();
            }),
            (m.prototype.getPosition = function (t) {
                var e = (t = t || this.$element)[0],
                    i = "BODY" == e.tagName,
                    o = e.getBoundingClientRect();
                null == o.width && (o = g.extend({}, o, { width: o.right - o.left, height: o.bottom - o.top }));
                var n = window.SVGElement && e instanceof window.SVGElement,
                    s = i ? { top: 0, left: 0 } : n ? null : t.offset(),
                    a = { scroll: i ? document.documentElement.scrollTop || document.body.scrollTop : t.scrollTop() },
                    r = i ? { width: g(window).width(), height: g(window).height() } : null;
                return g.extend({}, o, a, r, s);
            }),
            (m.prototype.getCalculatedOffset = function (t, e, i, o) {
                return "bottom" == t
                    ? { top: e.top + e.height, left: e.left + e.width / 2 - i / 2 }
                    : "top" == t
                    ? { top: e.top - o, left: e.left + e.width / 2 - i / 2 }
                    : "left" == t
                    ? { top: e.top + e.height / 2 - o / 2, left: e.left - i }
                    : { top: e.top + e.height / 2 - o / 2, left: e.left + e.width };
            }),
            (m.prototype.getViewportAdjustedDelta = function (t, e, i, o) {
                var n = { top: 0, left: 0 };
                if (!this.$viewport) return n;
                var s = (this.options.viewport && this.options.viewport.padding) || 0,
                    a = this.getPosition(this.$viewport);
                if (/right|left/.test(t)) {
                    var r = e.top - s - a.scroll,
                        l = e.top + s - a.scroll + o;
                    r < a.top ? (n.top = a.top - r) : l > a.top + a.height && (n.top = a.top + a.height - l);
                } else {
                    var h = e.left - s,
                        d = e.left + s + i;
                    h < a.left ? (n.left = a.left - h) : d > a.right && (n.left = a.left + a.width - d);
                }
                return n;
            }),
            (m.prototype.getTitle = function () {
                var t = this.$element,
                    e = this.options;
                return t.attr("data-original-title") || ("function" == typeof e.title ? e.title.call(t[0]) : e.title);
            }),
            (m.prototype.getUID = function (t) {
                for (; (t += ~~(1e6 * Math.random())), document.getElementById(t); );
                return t;
            }),
            (m.prototype.tip = function () {
                if (!this.$tip && ((this.$tip = g(this.options.template)), 1 != this.$tip.length)) throw new Error(this.type + " `template` option must consist of exactly 1 top-level element!");
                return this.$tip;
            }),
            (m.prototype.arrow = function () {
                return (this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow"));
            }),
            (m.prototype.enable = function () {
                this.enabled = !0;
            }),
            (m.prototype.disable = function () {
                this.enabled = !1;
            }),
            (m.prototype.toggleEnabled = function () {
                this.enabled = !this.enabled;
            }),
            (m.prototype.toggle = function (t) {
                var e = this;
                t && ((e = g(t.currentTarget).data("bs." + this.type)) || ((e = new this.constructor(t.currentTarget, this.getDelegateOptions())), g(t.currentTarget).data("bs." + this.type, e))),
                    t ? ((e.inState.click = !e.inState.click), e.isInStateTrue() ? e.enter(e) : e.leave(e)) : e.tip().hasClass("in") ? e.leave(e) : e.enter(e);
            }),
            (m.prototype.destroy = function () {
                var t = this;
                clearTimeout(this.timeout),
                    this.hide(function () {
                        t.$element.off("." + t.type).removeData("bs." + t.type), t.$tip && t.$tip.detach(), (t.$tip = null), (t.$arrow = null), (t.$viewport = null), (t.$element = null);
                    });
            }),
            (m.prototype.sanitizeHtml = function (t) {
                return n(t, this.options.whiteList, this.options.sanitizeFn);
            });
        var e = g.fn.tooltip;
        (g.fn.tooltip = function i(o) {
            return this.each(function () {
                var t = g(this),
                    e = t.data("bs.tooltip"),
                    i = "object" == typeof o && o;
                (!e && /destroy|hide/.test(o)) || (e || t.data("bs.tooltip", (e = new m(this, i))), "string" == typeof o && e[o]());
            });
        }),
            (g.fn.tooltip.Constructor = m),
            (g.fn.tooltip.noConflict = function () {
                return (g.fn.tooltip = e), this;
            });
    })(jQuery),
    (function (n) {
        "use strict";
        var s = function (t, e) {
            this.init("popover", t, e);
        };
        if (!n.fn.tooltip) throw new Error("Popover requires tooltip.js");
        (s.VERSION = "3.4.1"),
            (s.DEFAULTS = n.extend({}, n.fn.tooltip.Constructor.DEFAULTS, {
                placement: "right",
                trigger: "click",
                content: "",
                template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
            })),
            (((s.prototype = n.extend({}, n.fn.tooltip.Constructor.prototype)).constructor = s).prototype.getDefaults = function () {
                return s.DEFAULTS;
            }),
            (s.prototype.setContent = function () {
                var t = this.tip(),
                    e = this.getTitle(),
                    i = this.getContent();
                if (this.options.html) {
                    var o = typeof i;
                    this.options.sanitize && ((e = this.sanitizeHtml(e)), "string" === o && (i = this.sanitizeHtml(i))),
                        t.find(".popover-title").html(e),
                        t.find(".popover-content").children().detach().end()["string" === o ? "html" : "append"](i);
                } else t.find(".popover-title").text(e), t.find(".popover-content").children().detach().end().text(i);
                t.removeClass("fade top bottom left right in"), t.find(".popover-title").html() || t.find(".popover-title").hide();
            }),
            (s.prototype.hasContent = function () {
                return this.getTitle() || this.getContent();
            }),
            (s.prototype.getContent = function () {
                var t = this.$element,
                    e = this.options;
                return t.attr("data-content") || ("function" == typeof e.content ? e.content.call(t[0]) : e.content);
            }),
            (s.prototype.arrow = function () {
                return (this.$arrow = this.$arrow || this.tip().find(".arrow"));
            });
        var t = n.fn.popover;
        (n.fn.popover = function e(o) {
            return this.each(function () {
                var t = n(this),
                    e = t.data("bs.popover"),
                    i = "object" == typeof o && o;
                (!e && /destroy|hide/.test(o)) || (e || t.data("bs.popover", (e = new s(this, i))), "string" == typeof o && e[o]());
            });
        }),
            (n.fn.popover.Constructor = s),
            (n.fn.popover.noConflict = function () {
                return (n.fn.popover = t), this;
            });
    })(jQuery),
    (function (s) {
        "use strict";
        function n(t, e) {
            (this.$body = s(document.body)),
                (this.$scrollElement = s(t).is(document.body) ? s(window) : s(t)),
                (this.options = s.extend({}, n.DEFAULTS, e)),
                (this.selector = (this.options.target || "") + " .nav li > a"),
                (this.offsets = []),
                (this.targets = []),
                (this.activeTarget = null),
                (this.scrollHeight = 0),
                this.$scrollElement.on("scroll.bs.scrollspy", s.proxy(this.process, this)),
                this.refresh(),
                this.process();
        }
        function e(o) {
            return this.each(function () {
                var t = s(this),
                    e = t.data("bs.scrollspy"),
                    i = "object" == typeof o && o;
                e || t.data("bs.scrollspy", (e = new n(this, i))), "string" == typeof o && e[o]();
            });
        }
        (n.VERSION = "3.4.1"),
            (n.DEFAULTS = { offset: 10 }),
            (n.prototype.getScrollHeight = function () {
                return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight);
            }),
            (n.prototype.refresh = function () {
                var t = this,
                    o = "offset",
                    n = 0;
                (this.offsets = []),
                    (this.targets = []),
                    (this.scrollHeight = this.getScrollHeight()),
                    s.isWindow(this.$scrollElement[0]) || ((o = "position"), (n = this.$scrollElement.scrollTop())),
                    this.$body
                        .find(this.selector)
                        .map(function () {
                            var t = s(this),
                                e = t.data("target") || t.attr("href"),
                                i = /^#./.test(e) && s(e);
                            return (i && i.length && i.is(":visible") && [[i[o]().top + n, e]]) || null;
                        })
                        .sort(function (t, e) {
                            return t[0] - e[0];
                        })
                        .each(function () {
                            t.offsets.push(this[0]), t.targets.push(this[1]);
                        });
            }),
            (n.prototype.process = function () {
                var t,
                    e = this.$scrollElement.scrollTop() + this.options.offset,
                    i = this.getScrollHeight(),
                    o = this.options.offset + i - this.$scrollElement.height(),
                    n = this.offsets,
                    s = this.targets,
                    a = this.activeTarget;
                if ((this.scrollHeight != i && this.refresh(), o <= e)) return a != (t = s[s.length - 1]) && this.activate(t);
                if (a && e < n[0]) return (this.activeTarget = null), this.clear();
                for (t = n.length; t--; ) a != s[t] && e >= n[t] && (n[t + 1] === undefined || e < n[t + 1]) && this.activate(s[t]);
            }),
            (n.prototype.activate = function (t) {
                (this.activeTarget = t), this.clear();
                var e = this.selector + '[data-target="' + t + '"],' + this.selector + '[href="' + t + '"]',
                    i = s(e).parents("li").addClass("active");
                i.parent(".dropdown-menu").length && (i = i.closest("li.dropdown").addClass("active")), i.trigger("activate.bs.scrollspy");
            }),
            (n.prototype.clear = function () {
                s(this.selector).parentsUntil(this.options.target, ".active").removeClass("active");
            });
        var t = s.fn.scrollspy;
        (s.fn.scrollspy = e),
            (s.fn.scrollspy.Constructor = n),
            (s.fn.scrollspy.noConflict = function () {
                return (s.fn.scrollspy = t), this;
            }),
            s(window).on("load.bs.scrollspy.data-api", function () {
                s('[data-spy="scroll"]').each(function () {
                    var t = s(this);
                    e.call(t, t.data());
                });
            });
    })(jQuery),
    (function (r) {
        "use strict";
        var a = function (t) {
            this.element = r(t);
        };
        function e(i) {
            return this.each(function () {
                var t = r(this),
                    e = t.data("bs.tab");
                e || t.data("bs.tab", (e = new a(this))), "string" == typeof i && e[i]();
            });
        }
        (a.VERSION = "3.4.1"),
            (a.TRANSITION_DURATION = 150),
            (a.prototype.show = function () {
                var t = this.element,
                    e = t.closest("ul:not(.dropdown-menu)"),
                    i = t.data("target");
                if ((i || (i = (i = t.attr("href")) && i.replace(/.*(?=#[^\s]*$)/, "")), !t.parent("li").hasClass("active"))) {
                    var o = e.find(".active:last a"),
                        n = r.Event("hide.bs.tab", { relatedTarget: t[0] }),
                        s = r.Event("show.bs.tab", { relatedTarget: o[0] });
                    if ((o.trigger(n), t.trigger(s), !s.isDefaultPrevented() && !n.isDefaultPrevented())) {
                        var a = r(document).find(i);
                        this.activate(t.closest("li"), e),
                            this.activate(a, a.parent(), function () {
                                o.trigger({ type: "hidden.bs.tab", relatedTarget: t[0] }), t.trigger({ type: "shown.bs.tab", relatedTarget: o[0] });
                            });
                    }
                }
            }),
            (a.prototype.activate = function (t, e, i) {
                var o = e.find("> .active"),
                    n = i && r.support.transition && ((o.length && o.hasClass("fade")) || !!e.find("> .fade").length);
                function s() {
                    o.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1),
                        t.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0),
                        n ? (t[0].offsetWidth, t.addClass("in")) : t.removeClass("fade"),
                        t.parent(".dropdown-menu").length && t.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0),
                        i && i();
                }
                o.length && n ? o.one("bsTransitionEnd", s).emulateTransitionEnd(a.TRANSITION_DURATION) : s(), o.removeClass("in");
            });
        var t = r.fn.tab;
        (r.fn.tab = e),
            (r.fn.tab.Constructor = a),
            (r.fn.tab.noConflict = function () {
                return (r.fn.tab = t), this;
            });
        var i = function (t) {
            t.preventDefault(), e.call(r(this), "show");
        };
        r(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', i).on("click.bs.tab.data-api", '[data-toggle="pill"]', i);
    })(jQuery),
    (function (l) {
        "use strict";
        var h = function (t, e) {
            this.options = l.extend({}, h.DEFAULTS, e);
            var i = this.options.target === h.DEFAULTS.target ? l(this.options.target) : l(document).find(this.options.target);
            (this.$target = i.on("scroll.bs.affix.data-api", l.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", l.proxy(this.checkPositionWithEventLoop, this))),
                (this.$element = l(t)),
                (this.affixed = null),
                (this.unpin = null),
                (this.pinnedOffset = null),
                this.checkPosition();
        };
        function i(o) {
            return this.each(function () {
                var t = l(this),
                    e = t.data("bs.affix"),
                    i = "object" == typeof o && o;
                e || t.data("bs.affix", (e = new h(this, i))), "string" == typeof o && e[o]();
            });
        }
        (h.VERSION = "3.4.1"),
            (h.RESET = "affix affix-top affix-bottom"),
            (h.DEFAULTS = { offset: 0, target: window }),
            (h.prototype.getState = function (t, e, i, o) {
                var n = this.$target.scrollTop(),
                    s = this.$element.offset(),
                    a = this.$target.height();
                if (null != i && "top" == this.affixed) return n < i && "top";
                if ("bottom" == this.affixed) return null != i ? !(n + this.unpin <= s.top) && "bottom" : !(n + a <= t - o) && "bottom";
                var r = null == this.affixed,
                    l = r ? n : s.top;
                return null != i && n <= i ? "top" : null != o && t - o <= l + (r ? a : e) && "bottom";
            }),
            (h.prototype.getPinnedOffset = function () {
                if (this.pinnedOffset) return this.pinnedOffset;
                this.$element.removeClass(h.RESET).addClass("affix");
                var t = this.$target.scrollTop(),
                    e = this.$element.offset();
                return (this.pinnedOffset = e.top - t);
            }),
            (h.prototype.checkPositionWithEventLoop = function () {
                setTimeout(l.proxy(this.checkPosition, this), 1);
            }),
            (h.prototype.checkPosition = function () {
                if (this.$element.is(":visible")) {
                    var t = this.$element.height(),
                        e = this.options.offset,
                        i = e.top,
                        o = e.bottom,
                        n = Math.max(l(document).height(), l(document.body).height());
                    "object" != typeof e && (o = i = e), "function" == typeof i && (i = e.top(this.$element)), "function" == typeof o && (o = e.bottom(this.$element));
                    var s = this.getState(n, t, i, o);
                    if (this.affixed != s) {
                        null != this.unpin && this.$element.css("top", "");
                        var a = "affix" + (s ? "-" + s : ""),
                            r = l.Event(a + ".bs.affix");
                        if ((this.$element.trigger(r), r.isDefaultPrevented())) return;
                        (this.affixed = s),
                            (this.unpin = "bottom" == s ? this.getPinnedOffset() : null),
                            this.$element
                                .removeClass(h.RESET)
                                .addClass(a)
                                .trigger(a.replace("affix", "affixed") + ".bs.affix");
                    }
                    "bottom" == s && this.$element.offset({ top: n - t - o });
                }
            });
        var t = l.fn.affix;
        (l.fn.affix = i),
            (l.fn.affix.Constructor = h),
            (l.fn.affix.noConflict = function () {
                return (l.fn.affix = t), this;
            }),
            l(window).on("load", function () {
                l('[data-spy="affix"]').each(function () {
                    var t = l(this),
                        e = t.data();
                    (e.offset = e.offset || {}), null != e.offsetBottom && (e.offset.bottom = e.offsetBottom), null != e.offsetTop && (e.offset.top = e.offsetTop), i.call(t, e);
                });
            });
    })(jQuery);

/* =========================================================
 * bootstrap-colorpicker.js
 * http://www.eyecon.ro/bootstrap-colorpicker
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!(function (t) {
    var e = function (t) {
        (this.value = { h: 1, s: 1, b: 1, a: 1 }), this.setColor(t);
    };
    e.prototype = {
        constructor: e,
        setColor: function (e) {
            e = e.toLowerCase();
            var o = this;
            t.each(s.stringParsers, function (t, i) {
                var r = i.re.exec(e),
                    n = r && i.parse(r),
                    a = i.space || "rgba";
                if (n) return (o.value = "hsla" === a ? s.RGBtoHSB.apply(null, s.HSLtoRGB.apply(null, n)) : s.RGBtoHSB.apply(null, n)), !1;
            });
        },
        setHue: function (t) {
            this.value.h = 1 - t;
        },
        setSaturation: function (t) {
            this.value.s = t;
        },
        setLightness: function (t) {
            this.value.b = 1 - t;
        },
        setAlpha: function (t) {
            this.value.a = parseInt(100 * (1 - t), 10) / 100;
        },
        toRGB: function (t, e, o, s) {
            var i, r, n, a, l;
            return (
                t || ((t = this.value.h), (e = this.value.s), (o = this.value.b)),
                (t = ((t *= 360) % 360) / 60),
                (i = r = n = o - (l = o * e)),
                (i += [l, (a = l * (1 - Math.abs((t % 2) - 1))), 0, 0, a, l][(t = ~~t)]),
                (r += [a, l, l, a, 0, 0][t]),
                (n += [0, 0, a, l, l, a][t]),
                { r: Math.round(255 * i), g: Math.round(255 * r), b: Math.round(255 * n), a: s || this.value.a }
            );
        },
        toHex: function (t, e, o, s) {
            var i = this.toRGB(t, e, o, s);
            return "#" + ((1 << 24) | (parseInt(i.r) << 16) | (parseInt(i.g) << 8) | parseInt(i.b)).toString(16).substr(1);
        },
        toHSL: function (t, e, o, s) {
            t || ((t = this.value.h), (e = this.value.s), (o = this.value.b));
            var i = (2 - e) * o,
                r = e * o;
            return (r /= i > 0 && i <= 1 ? i : 2 - i) > 1 && (r = 1), { h: t, s: r, l: (i /= 2), a: s || this.value.a };
        },
    };
    var o = function (e, o) {
        this.element = t(e);
        var i = o.format || this.element.data("color-format") || "hex";
        (this.format = s.translateFormats[i]),
            (this.isInput = this.element.is("input")),
            (this.component = !!this.element.is(".color") && this.element.find(".add-on")),
            (this.picker = t(s.template).appendTo("body").on("mousedown", t.proxy(this.mousedown, this))),
            this.isInput
                ? this.element.on({ focus: t.proxy(this.show, this), keyup: t.proxy(this.update, this) })
                : this.component
                ? this.component.on({ click: t.proxy(this.show, this) })
                : this.element.on({ click: t.proxy(this.show, this) }),
            ("rgba" !== i && "hsla" !== i) || (this.picker.addClass("alpha"), (this.alpha = this.picker.find(".colorpicker-alpha")[0].style)),
            this.component ? (this.picker.find(".colorpicker-color").hide(), (this.preview = this.element.find("i")[0].style)) : (this.preview = this.picker.find("div:last")[0].style),
            (this.base = this.picker.find("div:first")[0].style),
            this.update();
    };
    (o.prototype = {
        constructor: o,
        show: function (e) {
            this.picker.show(),
                (this.height = this.component ? this.component.outerHeight() : this.element.outerHeight()),
                this.place(),
                t(window).on("resize", t.proxy(this.place, this)),
                this.isInput || (e && (e.stopPropagation(), e.preventDefault())),
                t(document).on({ mousedown: t.proxy(this.hide, this) }),
                this.element.trigger({ type: "show", color: this.color });
        },
        update: function () {
            (this.color = new e(this.isInput ? this.element.prop("value") : this.element.data("color"))),
                this.picker
                    .find("i")
                    .eq(0)
                    .css({ left: 100 * this.color.value.s, top: 100 - 100 * this.color.value.b })
                    .end()
                    .eq(1)
                    .css("top", 100 * (1 - this.color.value.h))
                    .end()
                    .eq(2)
                    .css("top", 100 * (1 - this.color.value.a)),
                this.previewColor();
        },
        setValue: function (t) {
            (this.color = new e(t)),
                this.picker
                    .find("i")
                    .eq(0)
                    .css({ left: 100 * this.color.value.s, top: 100 - 100 * this.color.value.b })
                    .end()
                    .eq(1)
                    .css("top", 100 * (1 - this.color.value.h))
                    .end()
                    .eq(2)
                    .css("top", 100 * (1 - this.color.value.a)),
                this.previewColor(),
                this.element.trigger({ type: "changeColor", color: this.color });
        },
        hide: function () {
            this.picker.hide(),
                t(window).off("resize", this.place),
                this.isInput
                    ? this.element.prop("value", this.format.call(this))
                    : (t(document).off({ mousedown: this.hide }), this.component && this.element.find("input").prop("value", this.format.call(this)), this.element.data("color", this.format.call(this))),
                this.element.trigger({ type: "hide", color: this.color });
        },
        place: function () {
            var t = this.component ? this.component.offset() : this.element.offset();
            this.picker.css({ top: t.top + this.height, left: t.left });
        },
        previewColor: function () {
            try {
                this.preview.backgroundColor = this.format.call(this);
            } catch (t) {
                this.preview.backgroundColor = this.color.toHex();
            }
            (this.base.backgroundColor = this.color.toHex(this.color.value.h, 1, 1, 1)), this.alpha && (this.alpha.backgroundColor = this.color.toHex());
        },
        pointer: null,
        slider: null,
        mousedown: function (e) {
            e.stopPropagation(), e.preventDefault();
            var o = t(e.target).closest("div");
            if (!o.is(".colorpicker")) {
                if (o.is(".colorpicker-saturation")) this.slider = t.extend({}, s.sliders.saturation);
                else if (o.is(".colorpicker-hue")) this.slider = t.extend({}, s.sliders.hue);
                else {
                    if (!o.is(".colorpicker-alpha")) return !1;
                    this.slider = t.extend({}, s.sliders.alpha);
                }
                var i = o.offset();
                (this.slider.knob = o.find("i")[0].style),
                    (this.slider.left = e.pageX - i.left),
                    (this.slider.top = e.pageY - i.top),
                    (this.pointer = { left: e.pageX, top: e.pageY }),
                    t(document)
                        .on({ mousemove: t.proxy(this.mousemove, this), mouseup: t.proxy(this.mouseup, this) })
                        .trigger("mousemove");
            }
            return !1;
        },
        mousemove: function (t) {
            t.stopPropagation(), t.preventDefault();
            var e = Math.max(0, Math.min(this.slider.maxLeft, this.slider.left + ((t.pageX || this.pointer.left) - this.pointer.left))),
                o = Math.max(0, Math.min(this.slider.maxTop, this.slider.top + ((t.pageY || this.pointer.top) - this.pointer.top)));
            return (
                (this.slider.knob.left = e + "px"),
                (this.slider.knob.top = o + "px"),
                this.slider.callLeft && this.color[this.slider.callLeft].call(this.color, e / 100),
                this.slider.callTop && this.color[this.slider.callTop].call(this.color, o / 100),
                this.previewColor(),
                this.element.trigger({ type: "changeColor", color: this.color }),
                !1
            );
        },
        mouseup: function (e) {
            return e.stopPropagation(), e.preventDefault(), t(document).off({ mousemove: this.mousemove, mouseup: this.mouseup }), !1;
        },
    }),
        (t.fn.colorpicker = function (e) {
            return this.each(function () {
                var s = t(this),
                    i = s.data("colorpicker"),
                    r = "object" == typeof e && e;
                i || s.data("colorpicker", (i = new o(this, t.extend({}, t.fn.colorpicker.defaults, r)))), "string" == typeof e && i[e]();
            });
        }),
        (t.fn.colorpicker.defaults = {}),
        (t.fn.colorpicker.Constructor = o);
    var s = {
        translateFormats: {
            rgb: function () {
                var t = this.color.toRGB();
                return "rgb(" + t.r + "," + t.g + "," + t.b + ")";
            },
            rgba: function () {
                var t = this.color.toRGB();
                return "rgba(" + t.r + "," + t.g + "," + t.b + "," + t.a + ")";
            },
            hsl: function () {
                var t = this.color.toHSL();
                return "hsl(" + Math.round(360 * t.h) + "," + Math.round(100 * t.s) + "%," + Math.round(100 * t.l) + "%)";
            },
            hsla: function () {
                var t = this.color.toHSL();
                return "hsla(" + Math.round(360 * t.h) + "," + Math.round(100 * t.s) + "%," + Math.round(100 * t.l) + "%," + t.a + ")";
            },
            hex: function () {
                return this.color.toHex();
            },
        },
        sliders: {
            saturation: { maxLeft: 100, maxTop: 100, callLeft: "setSaturation", callTop: "setLightness" },
            hue: { maxLeft: 0, maxTop: 100, callLeft: !1, callTop: "setHue" },
            alpha: { maxLeft: 0, maxTop: 100, callLeft: !1, callTop: "setAlpha" },
        },
        RGBtoHSB: function (t, e, o, s) {
            var i, r;
            return (
                (t /= 255),
                (e /= 255),
                (o /= 255),
                { h: ((((0 === (r = (i = Math.max(t, e, o)) - Math.min(t, e, o)) ? null : i == t ? (e - o) / r : i == e ? (o - t) / r + 2 : (t - e) / r + 4) + 360) % 6) * 60) / 360 || 1, s: 0 === r ? 0 : r / i, b: i, a: s || 1 }
            );
        },
        HueToRGB: function (t, e, o) {
            return o < 0 ? (o += 1) : o > 1 && (o -= 1), 6 * o < 1 ? t + (e - t) * o * 6 : 2 * o < 1 ? e : 3 * o < 2 ? t + (e - t) * (2 / 3 - o) * 6 : t;
        },
        HSLtoRGB: function (t, e, o, i) {
            var r;
            e < 0 && (e = 0);
            var n = 2 * o - (r = o <= 0.5 ? o * (1 + e) : o + e - o * e),
                a = t + 1 / 3,
                l = t,
                h = t - 1 / 3;
            return [Math.round(255 * s.HueToRGB(n, r, a)), Math.round(255 * s.HueToRGB(n, r, l)), Math.round(255 * s.HueToRGB(n, r, h)), i || 1];
        },
        stringParsers: [
            {
                re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                parse: function (t) {
                    return [t[1], t[2], t[3], t[4]];
                },
            },
            {
                re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                parse: function (t) {
                    return [2.55 * t[1], 2.55 * t[2], 2.55 * t[3], t[4]];
                },
            },
            {
                re: /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
                parse: function (t) {
                    return [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16)];
                },
            },
            {
                re: /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/,
                parse: function (t) {
                    return [parseInt(t[1] + t[1], 16), parseInt(t[2] + t[2], 16), parseInt(t[3] + t[3], 16)];
                },
            },
            {
                re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                space: "hsla",
                parse: function (t) {
                    return [t[1] / 360, t[2] / 100, t[3] / 100, t[4]];
                },
            },
        ],
        template:
            '<div class="colorpicker dropdown-menu"><div class="colorpicker-saturation"><i><b></b></i></div><div class="colorpicker-hue"><i></i></div><div class="colorpicker-alpha"><i></i></div><div class="colorpicker-color"><div /></div></div>',
    };
})(window.jQuery);

//colorconversion.js
function rgbToHsl(a, e, r) {
    (a /= 255), (e /= 255), (r /= 255);
    var s,
        c,
        t = Math.max(a, e, r),
        n = Math.min(a, e, r),
        b = (t + n) / 2;
    if (t == n) s = c = 0;
    else {
        var i = t - n;
        switch (((c = b > 0.5 ? i / (2 - t - n) : i / (t + n)), t)) {
            case a:
                s = (e - r) / i + (e < r ? 6 : 0);
                break;
            case e:
                s = (r - a) / i + 2;
                break;
            case r:
                s = (a - e) / i + 4;
        }
        s /= 6;
    }
    return [s, c, b];
}
function hslToRgb(a, e, r) {
    var s, c, t;
    if (0 == e) s = c = t = r;
    else {
        function n(a, e, r) {
            return r < 0 && (r += 1), r > 1 && (r -= 1), r < 1 / 6 ? a + 6 * (e - a) * r : r < 0.5 ? e : r < 2 / 3 ? a + (e - a) * (2 / 3 - r) * 6 : a;
        }
        var b = r < 0.5 ? r * (1 + e) : r + e - r * e,
            i = 2 * r - b;
        (s = n(i, b, a + 1 / 3)), (c = n(i, b, a)), (t = n(i, b, a - 1 / 3));
    }
    return [255 * s, 255 * c, 255 * t];
}
function rgbToHsv(a, e, r) {
    (a /= 255), (e /= 255), (r /= 255);
    var s,
        c,
        t = Math.max(a, e, r),
        n = Math.min(a, e, r),
        b = t,
        i = t - n;
    if (((c = 0 == t ? 0 : i / t), t == n)) s = 0;
    else {
        switch (t) {
            case a:
                s = (e - r) / i + (e < r ? 6 : 0);
                break;
            case e:
                s = (r - a) / i + 2;
                break;
            case r:
                s = (a - e) / i + 4;
        }
        s /= 6;
    }
    return [s, c, b];
}
function hsvToRgb(a, e, r) {
    var s,
        c,
        t,
        n = Math.floor(6 * a),
        b = 6 * a - n,
        i = r * (1 - e),
        o = r * (1 - b * e),
        h = r * (1 - (1 - b) * e);
    switch (n % 6) {
        case 0:
            (s = r), (c = h), (t = i);
            break;
        case 1:
            (s = o), (c = r), (t = i);
            break;
        case 2:
            (s = i), (c = r), (t = h);
            break;
        case 3:
            (s = i), (c = o), (t = r);
            break;
        case 4:
            (s = h), (c = i), (t = r);
            break;
        case 5:
            (s = r), (c = i), (t = o);
    }
    return [255 * s, 255 * c, 255 * t];
}

/*!
 * pickadate.js v3.6.4, 2019/05/25
 * By Amsul, http://amsul.ca
 * Hosted on http://amsul.github.io/pickadate.js
 * Licensed under MIT
 */
!(function (a) {
    "function" == typeof define && define.amd ? define("picker", ["jquery"], a) : "object" == typeof exports ? (module.exports = a(require("jquery"))) : "object" == typeof window ? (window.Picker = a(jQuery)) : (this.Picker = a(jQuery));
})(function (a) {
    function b(g, h, j, l) {
        function n() {
            return b._.node("div", b._.node("div", b._.node("div", b._.node("div", B.component.nodes(w.open), y.box), y.wrap), y.frame), y.holder, 'tabindex="-1"');
        }
        function o() {
            z
                .data(h, B)
                .addClass(y.input)
                .val(z.data("value") ? B.get("select", x.format) : g.value)
                .on("focus." + w.id + " click." + w.id, function (a) {
                    a.preventDefault(), B.open();
                })
                .on("mousedown", function () {
                    w.handlingOpen = !0;
                    var b = function () {
                        setTimeout(function () {
                            a(document).off("mouseup", b), (w.handlingOpen = !1);
                        }, 0);
                    };
                    a(document).on("mouseup", b);
                }),
                x.editable || z.on("keydown." + w.id, u),
                f(g, { haspopup: !0, expanded: !1, readonly: !1, owns: g.id + "_root" });
        }
        function p() {
            f(B.$root[0], "hidden", !0);
        }
        function q() {
            B.$holder
                .on({
                    keydown: u,
                    "focus.toOpen": t,
                    blur: function () {
                        z.removeClass(y.target);
                    },
                    focusin: function (a) {
                        B.$root.removeClass(y.focused), a.stopPropagation();
                    },
                    "mousedown click": function (b) {
                        var c = e(b, g);
                        c != B.$holder[0] && (b.stopPropagation(), "mousedown" != b.type || a(c).is("input, select, textarea, button, option") || (b.preventDefault(), B.$holder.eq(0).focus()));
                    },
                })
                .on("click", "[data-pick], [data-nav], [data-clear], [data-close]", function () {
                    var b = a(this),
                        c = b.data(),
                        d = b.hasClass(y.navDisabled) || b.hasClass(y.disabled),
                        e = i();
                    (e = e && (e.type || e.href ? e : null)),
                        (d || (e && !a.contains(B.$root[0], e))) && B.$holder.eq(0).focus(),
                        !d && c.nav
                            ? B.set("highlight", B.component.item.highlight, { nav: c.nav })
                            : !d && "pick" in c
                            ? (B.set("select", c.pick), x.closeOnSelect && B.close(!0))
                            : c.clear
                            ? (B.clear(), x.closeOnClear && B.close(!0))
                            : c.close && B.close(!0);
                });
        }
        function r() {
            var b;
            !0 === x.hiddenName ? ((b = g.name), (g.name = "")) : ((b = ["string" == typeof x.hiddenPrefix ? x.hiddenPrefix : "", "string" == typeof x.hiddenSuffix ? x.hiddenSuffix : "_submit"]), (b = b[0] + g.name + b[1])),
                (B._hidden = a('<input type=hidden name="' + b + '"' + (z.data("value") || g.value ? ' value="' + B.get("select", x.formatSubmit) + '"' : "") + ">")[0]),
                z.on("change." + w.id, function () {
                    B._hidden.value = g.value ? B.get("select", x.formatSubmit) : "";
                });
        }
        function s() {
            v && m
                ? B.$holder.find("." + y.frame).one("transitionend", function () {
                      B.$holder.eq(0).focus();
                  })
                : setTimeout(function () {
                      B.$holder.eq(0).focus();
                  }, 0);
        }
        function t(a) {
            a.stopPropagation(), z.addClass(y.target), B.$root.addClass(y.focused), B.open();
        }
        function u(a) {
            var b = a.keyCode,
                c = /^(8|46)$/.test(b);
            if (27 == b) return B.close(!0), !1;
            (32 == b || c || (!w.open && B.component.key[b])) && (a.preventDefault(), a.stopPropagation(), c ? B.clear().close() : B.open());
        }
        if (!g) return b;
        var v = !1,
            w = { id: g.id || "P" + Math.abs(~~(Math.random() * new Date())), handlingOpen: !1 },
            x = j ? a.extend(!0, {}, j.defaults, l) : l || {},
            y = a.extend({}, b.klasses(), x.klass),
            z = a(g),
            A = function () {
                return this.start();
            },
            B = (A.prototype = {
                constructor: A,
                $node: z,
                start: function () {
                    return w && w.start
                        ? B
                        : ((w.methods = {}),
                          (w.start = !0),
                          (w.open = !1),
                          (w.type = g.type),
                          (g.autofocus = g == i()),
                          (g.readOnly = !x.editable),
                          (g.id = g.id || w.id),
                          "text" != g.type && (g.type = "text"),
                          (B.component = new j(B, x)),
                          (B.$root = a('<div class="' + y.picker + '" id="' + g.id + '_root" />')),
                          p(),
                          (B.$holder = a(n()).appendTo(B.$root)),
                          q(),
                          x.formatSubmit && r(),
                          o(),
                          x.containerHidden ? a(x.containerHidden).append(B._hidden) : z.after(B._hidden),
                          x.container ? a(x.container).append(B.$root) : z.after(B.$root),
                          B.on({ start: B.component.onStart, render: B.component.onRender, stop: B.component.onStop, open: B.component.onOpen, close: B.component.onClose, set: B.component.onSet }).on({
                              start: x.onStart,
                              render: x.onRender,
                              stop: x.onStop,
                              open: x.onOpen,
                              close: x.onClose,
                              set: x.onSet,
                          }),
                          (v = c(B.$holder[0])),
                          g.autofocus && B.open(),
                          B.trigger("start").trigger("render"));
                },
                render: function (b) {
                    return b ? ((B.$holder = a(n())), q(), B.$root.html(B.$holder)) : B.$root.find("." + y.box).html(B.component.nodes(w.open)), B.trigger("render");
                },
                stop: function () {
                    return w.start
                        ? (B.close(),
                          B._hidden && B._hidden.parentNode.removeChild(B._hidden),
                          B.$root.remove(),
                          z.removeClass(y.input).removeData(h),
                          setTimeout(function () {
                              z.off("." + w.id);
                          }, 0),
                          (g.type = w.type),
                          (g.readOnly = !1),
                          B.trigger("stop"),
                          (w.methods = {}),
                          (w.start = !1),
                          B)
                        : B;
                },
                open: function (c) {
                    return w.open
                        ? B
                        : (z.addClass(y.active),
                          f(g, "expanded", !0),
                          setTimeout(function () {
                              B.$root.addClass(y.opened), f(B.$root[0], "hidden", !1);
                          }, 0),
                          !1 !== c &&
                              ((w.open = !0),
                              v &&
                                  a("body")
                                      .css("overflow", "hidden")
                                      .css("padding-right", "+=" + d()),
                              s(),
                              k
                                  .on("click." + w.id + " focusin." + w.id, function (a) {
                                      if (!w.handlingOpen) {
                                          var b = e(a, g);
                                          a.isSimulated || b == g || b == document || 3 == a.which || B.close(b === B.$holder[0]);
                                      }
                                  })
                                  .on("keydown." + w.id, function (c) {
                                      var d = c.keyCode,
                                          f = B.component.key[d],
                                          h = e(c, g);
                                      27 == d
                                          ? B.close(!0)
                                          : h != B.$holder[0] || (!f && 13 != d)
                                          ? a.contains(B.$root[0], h) && 13 == d && (c.preventDefault(), h.click())
                                          : (c.preventDefault(),
                                            f ? b._.trigger(B.component.key.go, B, [b._.trigger(f)]) : B.$root.find("." + y.highlighted).hasClass(y.disabled) || (B.set("select", B.component.item.highlight), x.closeOnSelect && B.close(!0)));
                                  })),
                          B.trigger("open"));
                },
                close: function (b) {
                    return (
                        b &&
                            (x.editable
                                ? g.focus()
                                : (B.$holder.off("focus.toOpen").focus(),
                                  setTimeout(function () {
                                      B.$holder.on("focus.toOpen", t);
                                  }, 0))),
                        z.removeClass(y.active),
                        f(g, "expanded", !1),
                        setTimeout(function () {
                            B.$root.removeClass(y.opened + " " + y.focused), f(B.$root[0], "hidden", !0);
                        }, 0),
                        w.open
                            ? ((w.open = !1),
                              v &&
                                  a("body")
                                      .css("overflow", "")
                                      .css("padding-right", "-=" + d()),
                              k.off("." + w.id),
                              B.trigger("close"))
                            : B
                    );
                },
                clear: function (a) {
                    return B.set("clear", null, a);
                },
                set: function (b, c, d) {
                    var e,
                        f,
                        g = a.isPlainObject(b),
                        h = g ? b : {};
                    if (((d = g && a.isPlainObject(c) ? c : d || {}), b)) {
                        g || (h[b] = c);
                        for (e in h)
                            (f = h[e]), e in B.component.item && (void 0 === f && (f = null), B.component.set(e, f, d)), ("select" != e && "clear" != e) || !x.updateInput || z.val("clear" == e ? "" : B.get(e, x.format)).trigger("change");
                        B.render();
                    }
                    return d.muted ? B : B.trigger("set", h);
                },
                get: function (a, c) {
                    if (((a = a || "value"), null != w[a])) return w[a];
                    if ("valueSubmit" == a) {
                        if (B._hidden) return B._hidden.value;
                        a = "value";
                    }
                    if ("value" == a) return g.value;
                    if (a in B.component.item) {
                        if ("string" == typeof c) {
                            var d = B.component.get(a);
                            return d ? b._.trigger(B.component.formats.toString, B.component, [c, d]) : "";
                        }
                        return B.component.get(a);
                    }
                },
                on: function (b, c, d) {
                    var e,
                        f,
                        g = a.isPlainObject(b),
                        h = g ? b : {};
                    if (b) {
                        g || (h[b] = c);
                        for (e in h) (f = h[e]), d && (e = "_" + e), (w.methods[e] = w.methods[e] || []), w.methods[e].push(f);
                    }
                    return B;
                },
                off: function () {
                    var a,
                        b,
                        c = arguments;
                    for (a = 0, namesCount = c.length; a < namesCount; a += 1) (b = c[a]) in w.methods && delete w.methods[b];
                    return B;
                },
                trigger: function (a, c) {
                    var d = function (a) {
                        var d = w.methods[a];
                        d &&
                            d.map(function (a) {
                                b._.trigger(a, B, [c]);
                            });
                    };
                    return d("_" + a), d(a), B;
                },
            });
        return new A();
    }
    function c(a) {
        var b,
            c = "position";
        return a.currentStyle ? (b = a.currentStyle[c]) : window.getComputedStyle && (b = getComputedStyle(a)[c]), "fixed" == b;
    }
    function d() {
        if (l.height() <= j.height()) return 0;
        var b = a('<div style="visibility:hidden;width:100px" />').appendTo("body"),
            c = b[0].offsetWidth;
        b.css("overflow", "scroll");
        var d = a('<div style="width:100%" />').appendTo(b),
            e = d[0].offsetWidth;
        return b.remove(), c - e;
    }
    function e(a, b) {
        var c = [];
        return a.path && (c = a.path), a.originalEvent && a.originalEvent.path && (c = a.originalEvent.path), c && c.length > 0 ? (b && c.indexOf(b) >= 0 ? b : c[0]) : a.target;
    }
    function f(b, c, d) {
        if (a.isPlainObject(c)) for (var e in c) g(b, e, c[e]);
        else g(b, c, d);
    }
    function g(a, b, c) {
        a.setAttribute(("role" == b ? "" : "aria-") + b, c);
    }
    function h(b, c) {
        a.isPlainObject(b) || (b = { attribute: c }), (c = "");
        for (var d in b) {
            var e = ("role" == d ? "" : "aria-") + d;
            c += null == b[d] ? "" : e + '="' + b[d] + '"';
        }
        return c;
    }
    function i() {
        try {
            return document.activeElement;
        } catch (a) {}
    }
    var j = a(window),
        k = a(document),
        l = a(document.documentElement),
        m = null != document.documentElement.style.transition;
    return (
        (b.klasses = function (a) {
            return (
                (a = a || "picker"),
                {
                    picker: a,
                    opened: a + "--opened",
                    focused: a + "--focused",
                    input: a + "__input",
                    active: a + "__input--active",
                    target: a + "__input--target",
                    holder: a + "__holder",
                    frame: a + "__frame",
                    wrap: a + "__wrap",
                    box: a + "__box",
                }
            );
        }),
        (b._ = {
            group: function (a) {
                for (var c, d = "", e = b._.trigger(a.min, a); e <= b._.trigger(a.max, a, [e]); e += a.i) (c = b._.trigger(a.item, a, [e])), (d += b._.node(a.node, c[0], c[1], c[2]));
                return d;
            },
            node: function (b, c, d, e) {
                return c ? ((c = a.isArray(c) ? c.join("") : c), (d = d ? ' class="' + d + '"' : ""), (e = e ? " " + e : ""), "<" + b + d + e + ">" + c + "</" + b + ">") : "";
            },
            lead: function (a) {
                return (a < 10 ? "0" : "") + a;
            },
            trigger: function (a, b, c) {
                return "function" == typeof a ? a.apply(b, c || []) : a;
            },
            digits: function (a) {
                return /\d/.test(a[1]) ? 2 : 1;
            },
            isDate: function (a) {
                return {}.toString.call(a).indexOf("Date") > -1 && this.isInteger(a.getDate());
            },
            isInteger: function (a) {
                return {}.toString.call(a).indexOf("Number") > -1 && a % 1 == 0;
            },
            ariaAttr: h,
        }),
        (b.extend = function (c, d) {
            (a.fn[c] = function (e, f) {
                var g = this.data(c);
                return "picker" == e
                    ? g
                    : g && "string" == typeof e
                    ? b._.trigger(g[e], g, [f])
                    : this.each(function () {
                          a(this).data(c) || new b(this, c, d, e);
                      });
            }),
                (a.fn[c].defaults = d.defaults);
        }),
        b
    );
});

/*!
 * Date picker for pickadate.js v3.6.4
 * http://amsul.github.io/pickadate.js/date.htm
 */
!(function (a) {
    "function" == typeof define && define.amd ? define(["./picker", "jquery"], a) : "object" == typeof exports ? (module.exports = a(require("./picker.js"), require("jquery"))) : a(Picker, jQuery);
})(function (a, b) {
    function c(a, b) {
        var c = this,
            d = a.$node[0],
            e = d.value,
            f = a.$node.data("value"),
            g = f || e,
            h = f ? b.formatSubmit : b.format,
            i = function () {
                return d.currentStyle ? "rtl" == d.currentStyle.direction : "rtl" == getComputedStyle(a.$root[0]).direction;
            };
        (c.settings = b),
            (c.$node = a.$node),
            (c.queue = {
                min: "measure create",
                max: "measure create",
                now: "now create",
                select: "parse create validate",
                highlight: "parse navigate create validate",
                view: "parse create validate viewset",
                disable: "deactivate",
                enable: "activate",
            }),
            (c.item = {}),
            (c.item.clear = null),
            (c.item.disable = (b.disable || []).slice(0)),
            (c.item.enable = -(function (a) {
                return !0 === a[0] ? a.shift() : -1;
            })(c.item.disable)),
            c.set("min", b.min).set("max", b.max).set("now"),
            g ? c.set("select", g, { format: h, defaultValue: !0 }) : c.set("select", null).set("highlight", c.item.now),
            (c.key = {
                40: 7,
                38: -7,
                39: function () {
                    return i() ? -1 : 1;
                },
                37: function () {
                    return i() ? 1 : -1;
                },
                go: function (a) {
                    var b = c.item.highlight,
                        d = new Date(b.year, b.month, b.date + a);
                    c.set("highlight", d, { interval: a }), this.render();
                },
            }),
            a
                .on(
                    "render",
                    function () {
                        a.$root.find("." + b.klass.selectMonth).on("change", function () {
                            var c = this.value;
                            c && (a.set("highlight", [a.get("view").year, c, a.get("highlight").date]), a.$root.find("." + b.klass.selectMonth).trigger("focus"));
                        }),
                            a.$root.find("." + b.klass.selectYear).on("change", function () {
                                var c = this.value;
                                c && (a.set("highlight", [c, a.get("view").month, a.get("highlight").date]), a.$root.find("." + b.klass.selectYear).trigger("focus"));
                            });
                    },
                    1
                )
                .on(
                    "open",
                    function () {
                        var d = "";
                        c.disabled(c.get("now")) && (d = ":not(." + b.klass.buttonToday + ")"), a.$root.find("button" + d + ", select").attr("disabled", !1);
                    },
                    1
                )
                .on(
                    "close",
                    function () {
                        a.$root.find("button, select").attr("disabled", !0);
                    },
                    1
                );
    }
    var d = 7,
        e = a._;
    (c.prototype.set = function (a, b, c) {
        var d = this,
            e = d.item;
        return null === b
            ? ("clear" == a && (a = "select"), (e[a] = b), d)
            : ((e["enable" == a ? "disable" : "flip" == a ? "enable" : a] = d.queue[a]
                  .split(" ")
                  .map(function (e) {
                      return (b = d[e](a, b, c));
                  })
                  .pop()),
              "select" == a
                  ? d.set("highlight", e.select, c)
                  : "highlight" == a
                  ? d.set("view", e.highlight, c)
                  : a.match(/^(flip|min|max|disable|enable)$/) && (e.select && d.disabled(e.select) && d.set("select", e.select, c), e.highlight && d.disabled(e.highlight) && d.set("highlight", e.highlight, c)),
              d);
    }),
        (c.prototype.get = function (a) {
            return this.item[a];
        }),
        (c.prototype.create = function (a, c, d) {
            var f,
                g = this;
            return (
                (c = void 0 === c ? a : c),
                c == -1 / 0 || c == 1 / 0
                    ? (f = c)
                    : b.isPlainObject(c) && e.isInteger(c.pick)
                    ? (c = c.obj)
                    : b.isArray(c)
                    ? ((c = new Date(c[0], c[1], c[2])), (c = e.isDate(c) ? c : g.create().obj))
                    : (c = e.isInteger(c) || e.isDate(c) ? g.normalize(new Date(c), d) : g.now(a, c, d)),
                { year: f || c.getFullYear(), month: f || c.getMonth(), date: f || c.getDate(), day: f || c.getDay(), obj: f || c, pick: f || c.getTime() }
            );
        }),
        (c.prototype.createRange = function (a, c) {
            var d = this,
                f = function (a) {
                    return !0 === a || b.isArray(a) || e.isDate(a) ? d.create(a) : a;
                };
            return (
                e.isInteger(a) || (a = f(a)),
                e.isInteger(c) || (c = f(c)),
                e.isInteger(a) && b.isPlainObject(c) ? (a = [c.year, c.month, c.date + a]) : e.isInteger(c) && b.isPlainObject(a) && (c = [a.year, a.month, a.date + c]),
                { from: f(a), to: f(c) }
            );
        }),
        (c.prototype.withinRange = function (a, b) {
            return (a = this.createRange(a.from, a.to)), b.pick >= a.from.pick && b.pick <= a.to.pick;
        }),
        (c.prototype.overlapRanges = function (a, b) {
            var c = this;
            return (a = c.createRange(a.from, a.to)), (b = c.createRange(b.from, b.to)), c.withinRange(a, b.from) || c.withinRange(a, b.to) || c.withinRange(b, a.from) || c.withinRange(b, a.to);
        }),
        (c.prototype.now = function (a, b, c) {
            return (b = new Date()), c && c.rel && b.setDate(b.getDate() + c.rel), this.normalize(b, c);
        }),
        (c.prototype.navigate = function (a, c, d) {
            var e,
                f,
                g,
                h,
                i = b.isArray(c),
                j = b.isPlainObject(c),
                k = this.item.view;
            if (i || j) {
                for (
                    j ? ((f = c.year), (g = c.month), (h = c.date)) : ((f = +c[0]), (g = +c[1]), (h = +c[2])),
                        d && d.nav && k && k.month !== g && ((f = k.year), (g = k.month)),
                        e = new Date(f, g + (d && d.nav ? d.nav : 0), 1),
                        f = e.getFullYear(),
                        g = e.getMonth();
                    new Date(f, g, h).getMonth() !== g;

                )
                    h -= 1;
                c = [f, g, h];
            }
            return c;
        }),
        (c.prototype.normalize = function (a) {
            return a.setHours(0, 0, 0, 0), a;
        }),
        (c.prototype.measure = function (a, b) {
            var c = this;
            return e.isInteger(b) ? (b = c.now(a, b, { rel: b })) : b ? "string" == typeof b && (b = c.parse(a, b)) : (b = "min" == a ? -1 / 0 : 1 / 0), b;
        }),
        (c.prototype.viewset = function (a, b) {
            return this.create([b.year, b.month, 1]);
        }),
        (c.prototype.validate = function (a, c, d) {
            var f,
                g,
                h,
                i,
                j = this,
                k = c,
                l = d && d.interval ? d.interval : 1,
                m = -1 === j.item.enable,
                n = j.item.min,
                o = j.item.max,
                p =
                    m &&
                    j.item.disable.filter(function (a) {
                        if (b.isArray(a)) {
                            var d = j.create(a).pick;
                            d < c.pick ? (f = !0) : d > c.pick && (g = !0);
                        }
                        return e.isInteger(a);
                    }).length;
            if ((!d || (!d.nav && !d.defaultValue)) && ((!m && j.disabled(c)) || (m && j.disabled(c) && (p || f || g)) || (!m && (c.pick <= n.pick || c.pick >= o.pick))))
                for (
                    m && !p && ((!g && l > 0) || (!f && l < 0)) && (l *= -1);
                    j.disabled(c) &&
                    (Math.abs(l) > 1 && (c.month < k.month || c.month > k.month) && ((c = k), (l = l > 0 ? 1 : -1)),
                    c.pick <= n.pick
                        ? ((h = !0), (l = 1), (c = j.create([n.year, n.month, n.date + (c.pick === n.pick ? 0 : -1)])))
                        : c.pick >= o.pick && ((i = !0), (l = -1), (c = j.create([o.year, o.month, o.date + (c.pick === o.pick ? 0 : 1)]))),
                    !h || !i);

                )
                    c = j.create([c.year, c.month, c.date + l]);
            return c;
        }),
        (c.prototype.disabled = function (a) {
            var c = this,
                d = c.item.disable.filter(function (d) {
                    return e.isInteger(d) ? a.day === (c.settings.firstDay ? d : d - 1) % 7 : b.isArray(d) || e.isDate(d) ? a.pick === c.create(d).pick : b.isPlainObject(d) ? c.withinRange(d, a) : void 0;
                });
            return (
                (d =
                    d.length &&
                    !d.filter(function (a) {
                        return (b.isArray(a) && "inverted" == a[3]) || (b.isPlainObject(a) && a.inverted);
                    }).length),
                -1 === c.item.enable ? !d : d || a.pick < c.item.min.pick || a.pick > c.item.max.pick
            );
        }),
        (c.prototype.parse = function (a, b, c) {
            var d = this,
                f = {};
            return b && "string" == typeof b
                ? ((c && c.format) || ((c = c || {}), (c.format = d.settings.format)),
                  d.formats.toArray(c.format).map(function (a) {
                      var c = d.formats[a],
                          g = c ? e.trigger(c, d, [b, f]) : a.replace(/^!/, "").length;
                      c && (f[a] = b.substr(0, g)), (b = b.substr(g));
                  }),
                  [f.yyyy || f.yy, +(f.mm || f.m) - 1, f.dd || f.d])
                : b;
        }),
        (c.prototype.formats = (function () {
            function a(a, b, c) {
                var d = a.match(/[^\x00-\x7F]+|\w+/)[0];
                return c.mm || c.m || (c.m = b.indexOf(d) + 1), d.length;
            }
            function b(a) {
                return a.match(/\w+/)[0].length;
            }
            return {
                d: function (a, b) {
                    return a ? e.digits(a) : b.date;
                },
                dd: function (a, b) {
                    return a ? 2 : e.lead(b.date);
                },
                ddd: function (a, c) {
                    return a ? b(a) : this.settings.weekdaysShort[c.day];
                },
                dddd: function (a, c) {
                    return a ? b(a) : this.settings.weekdaysFull[c.day];
                },
                m: function (a, b) {
                    return a ? e.digits(a) : b.month + 1;
                },
                mm: function (a, b) {
                    return a ? 2 : e.lead(b.month + 1);
                },
                mmm: function (b, c) {
                    var d = this.settings.monthsShort;
                    return b ? a(b, d, c) : d[c.month];
                },
                mmmm: function (b, c) {
                    var d = this.settings.monthsFull;
                    return b ? a(b, d, c) : d[c.month];
                },
                yy: function (a, b) {
                    return a ? 2 : ("" + b.year).slice(2);
                },
                yyyy: function (a, b) {
                    return a ? 4 : b.year;
                },
                toArray: function (a) {
                    return a.split(/(d{1,4}|m{1,4}|y{4}|yy|!.)/g);
                },
                toString: function (a, b) {
                    var c = this;
                    return c.formats
                        .toArray(a)
                        .map(function (a) {
                            return e.trigger(c.formats[a], c, [0, b]) || a.replace(/^!/, "");
                        })
                        .join("");
                },
            };
        })()),
        (c.prototype.isDateExact = function (a, c) {
            var d = this;
            return (e.isInteger(a) && e.isInteger(c)) || ("boolean" == typeof a && "boolean" == typeof c)
                ? a === c
                : (e.isDate(a) || b.isArray(a)) && (e.isDate(c) || b.isArray(c))
                ? d.create(a).pick === d.create(c).pick
                : !(!b.isPlainObject(a) || !b.isPlainObject(c)) && d.isDateExact(a.from, c.from) && d.isDateExact(a.to, c.to);
        }),
        (c.prototype.isDateOverlap = function (a, c) {
            var d = this,
                f = d.settings.firstDay ? 1 : 0;
            return e.isInteger(a) && (e.isDate(c) || b.isArray(c))
                ? (a = (a % 7) + f) === d.create(c).day + 1
                : e.isInteger(c) && (e.isDate(a) || b.isArray(a))
                ? (c = (c % 7) + f) === d.create(a).day + 1
                : !(!b.isPlainObject(a) || !b.isPlainObject(c)) && d.overlapRanges(a, c);
        }),
        (c.prototype.flipEnable = function (a) {
            var b = this.item;
            b.enable = a || (-1 == b.enable ? 1 : -1);
        }),
        (c.prototype.deactivate = function (a, c) {
            var d = this,
                f = d.item.disable.slice(0);
            return (
                "flip" == c
                    ? d.flipEnable()
                    : !1 === c
                    ? (d.flipEnable(1), (f = []))
                    : !0 === c
                    ? (d.flipEnable(-1), (f = []))
                    : c.map(function (a) {
                          for (var c, g = 0; g < f.length; g += 1)
                              if (d.isDateExact(a, f[g])) {
                                  c = !0;
                                  break;
                              }
                          c || ((e.isInteger(a) || e.isDate(a) || b.isArray(a) || (b.isPlainObject(a) && a.from && a.to)) && f.push(a));
                      }),
                f
            );
        }),
        (c.prototype.activate = function (a, c) {
            var d = this,
                f = d.item.disable,
                g = f.length;
            return (
                "flip" == c
                    ? d.flipEnable()
                    : !0 === c
                    ? (d.flipEnable(1), (f = []))
                    : !1 === c
                    ? (d.flipEnable(-1), (f = []))
                    : c.map(function (a) {
                          var c, h, i, j;
                          for (i = 0; i < g; i += 1) {
                              if (((h = f[i]), d.isDateExact(h, a))) {
                                  (c = f[i] = null), (j = !0);
                                  break;
                              }
                              if (d.isDateOverlap(h, a)) {
                                  b.isPlainObject(a) ? ((a.inverted = !0), (c = a)) : b.isArray(a) ? ((c = a), c[3] || c.push("inverted")) : e.isDate(a) && (c = [a.getFullYear(), a.getMonth(), a.getDate(), "inverted"]);
                                  break;
                              }
                          }
                          if (c)
                              for (i = 0; i < g; i += 1)
                                  if (d.isDateExact(f[i], a)) {
                                      f[i] = null;
                                      break;
                                  }
                          if (j)
                              for (i = 0; i < g; i += 1)
                                  if (d.isDateOverlap(f[i], a)) {
                                      f[i] = null;
                                      break;
                                  }
                          c && f.push(c);
                      }),
                f.filter(function (a) {
                    return null != a;
                })
            );
        }),
        (c.prototype.nodes = function (a) {
            var b = this,
                c = b.settings,
                f = b.item,
                g = f.now,
                h = f.select,
                i = f.highlight,
                j = f.view,
                k = f.disable,
                l = f.min,
                m = f.max,
                n = (function (a, b) {
                    return (
                        c.firstDay && (a.push(a.shift()), b.push(b.shift())),
                        e.node(
                            "thead",
                            e.node(
                                "tr",
                                e.group({
                                    min: 0,
                                    max: d - 1,
                                    i: 1,
                                    node: "th",
                                    item: function (d) {
                                        return [a[d], c.klass.weekdays, 'scope=col title="' + b[d] + '"'];
                                    },
                                })
                            )
                        )
                    );
                })((c.showWeekdaysFull ? c.weekdaysFull : c.weekdaysShort).slice(0), c.weekdaysFull.slice(0)),
                o = function (a) {
                    return e.node(
                        "div",
                        " ",
                        c.klass["nav" + (a ? "Next" : "Prev")] + ((a && j.year >= m.year && j.month >= m.month) || (!a && j.year <= l.year && j.month <= l.month) ? " " + c.klass.navDisabled : ""),
                        "data-nav=" + (a || -1) + " " + e.ariaAttr({ role: "button", controls: b.$node[0].id + "_table" }) + ' title="' + (a ? c.labelMonthNext : c.labelMonthPrev) + '"'
                    );
                },
                p = function () {
                    var d = c.showMonthsShort ? c.monthsShort : c.monthsFull;
                    return c.selectMonths
                        ? e.node(
                              "select",
                              e.group({
                                  min: 0,
                                  max: 11,
                                  i: 1,
                                  node: "option",
                                  item: function (a) {
                                      return [d[a], 0, "value=" + a + (j.month == a ? " selected" : "") + ((j.year == l.year && a < l.month) || (j.year == m.year && a > m.month) ? " disabled" : "")];
                                  },
                              }),
                              c.klass.selectMonth,
                              (a ? "" : "disabled") + " " + e.ariaAttr({ controls: b.$node[0].id + "_table" }) + ' title="' + c.labelMonthSelect + '"'
                          )
                        : e.node("div", d[j.month], c.klass.month);
                },
                q = function () {
                    var d = j.year,
                        f = !0 === c.selectYears ? 5 : ~~(c.selectYears / 2);
                    if (f) {
                        var g = l.year,
                            h = m.year,
                            i = d - f,
                            k = d + f;
                        if ((g > i && ((k += g - i), (i = g)), h < k)) {
                            var n = i - g,
                                o = k - h;
                            (i -= n > o ? o : n), (k = h);
                        }
                        return e.node(
                            "select",
                            e.group({
                                min: i,
                                max: k,
                                i: 1,
                                node: "option",
                                item: function (a) {
                                    return [a, 0, "value=" + a + (d == a ? " selected" : "")];
                                },
                            }),
                            c.klass.selectYear,
                            (a ? "" : "disabled") + " " + e.ariaAttr({ controls: b.$node[0].id + "_table" }) + ' title="' + c.labelYearSelect + '"'
                        );
                    }
                    return e.node("div", d, c.klass.year);
                };
            return (
                e.node("div", (c.selectYears ? q() + p() : p() + q()) + o() + o(1), c.klass.header) +
                e.node(
                    "table",
                    n +
                        e.node(
                            "tbody",
                            e.group({
                                min: 0,
                                max: 5,
                                i: 1,
                                node: "tr",
                                item: function (a) {
                                    var f = c.firstDay && 0 === b.create([j.year, j.month, 1]).day ? -7 : 0;
                                    return [
                                        e.group({
                                            min: d * a - j.day + f + 1,
                                            max: function () {
                                                return this.min + d - 1;
                                            },
                                            i: 1,
                                            node: "td",
                                            item: function (a) {
                                                a = b.create([j.year, j.month, a + (c.firstDay ? 1 : 0)]);
                                                var d = h && h.pick == a.pick,
                                                    f = i && i.pick == a.pick,
                                                    n = (k && b.disabled(a)) || a.pick < l.pick || a.pick > m.pick,
                                                    o = e.trigger(b.formats.toString, b, [c.format, a]);
                                                return [
                                                    e.node(
                                                        "div",
                                                        a.date,
                                                        (function (b) {
                                                            return (
                                                                b.push(j.month == a.month ? c.klass.infocus : c.klass.outfocus),
                                                                g.pick == a.pick && b.push(c.klass.now),
                                                                d && b.push(c.klass.selected),
                                                                f && b.push(c.klass.highlighted),
                                                                n && b.push(c.klass.disabled),
                                                                b.join(" ")
                                                            );
                                                        })([c.klass.day]),
                                                        "data-pick=" + a.pick + " " + e.ariaAttr({ role: "gridcell", label: o, selected: !(!d || b.$node.val() !== o) || null, activedescendant: !!f || null, disabled: !!n || null })
                                                    ),
                                                    "",
                                                    e.ariaAttr({ role: "presentation" }),
                                                ];
                                            },
                                        }),
                                    ];
                                },
                            })
                        ),
                    c.klass.table,
                    'id="' + b.$node[0].id + '_table" ' + e.ariaAttr({ role: "grid", controls: b.$node[0].id, readonly: !0 })
                ) +
                e.node(
                    "div",
                    e.node("button", c.today, c.klass.buttonToday, "type=button data-pick=" + g.pick + (a && !b.disabled(g) ? "" : " disabled") + " " + e.ariaAttr({ controls: b.$node[0].id })) +
                        e.node("button", c.clear, c.klass.buttonClear, "type=button data-clear=1" + (a ? "" : " disabled") + " " + e.ariaAttr({ controls: b.$node[0].id })) +
                        e.node("button", c.close, c.klass.buttonClose, "type=button data-close=true " + (a ? "" : " disabled") + " " + e.ariaAttr({ controls: b.$node[0].id })),
                    c.klass.footer
                )
            );
        }),
        (c.defaults = (function (a) {
            return {
                labelMonthNext: "Next month",
                labelMonthPrev: "Previous month",
                labelMonthSelect: "Select a month",
                labelYearSelect: "Select a year",
                monthsFull: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                weekdaysFull: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                weekdaysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                today: "Today",
                clear: "Clear",
                close: "Close",
                closeOnSelect: !0,
                closeOnClear: !0,
                updateInput: !0,
                format: "d mmmm, yyyy",
                klass: {
                    table: a + "table",
                    header: a + "header",
                    navPrev: a + "nav--prev",
                    navNext: a + "nav--next",
                    navDisabled: a + "nav--disabled",
                    month: a + "month",
                    year: a + "year",
                    selectMonth: a + "select--month",
                    selectYear: a + "select--year",
                    weekdays: a + "weekday",
                    day: a + "day",
                    disabled: a + "day--disabled",
                    selected: a + "day--selected",
                    highlighted: a + "day--highlighted",
                    now: a + "day--today",
                    infocus: a + "day--infocus",
                    outfocus: a + "day--outfocus",
                    footer: a + "footer",
                    buttonClear: a + "button--clear",
                    buttonToday: a + "button--today",
                    buttonClose: a + "button--close",
                },
            };
        })(a.klasses().picker + "__")),
        a.extend("pickadate", c);
});

/*!
 * Time picker for pickadate.js v3.6.4
 * http://amsul.github.io/pickadate.js/time.htm
 */
!(function (a) {
    "function" == typeof define && define.amd ? define(["./picker", "jquery"], a) : "object" == typeof exports ? (module.exports = a(require("./picker.js"), require("jquery"))) : a(Picker, jQuery);
})(function (a, b) {
    function c(a, b) {
        var c = this,
            d = a.$node[0].value,
            e = a.$node.data("value"),
            f = e || d,
            g = e ? b.formatSubmit : b.format;
        (c.settings = b),
            (c.$node = a.$node),
            (c.queue = {
                interval: "i",
                min: "measure create",
                max: "measure create",
                now: "now create",
                select: "parse create validate",
                highlight: "parse create validate",
                view: "parse create validate",
                disable: "deactivate",
                enable: "activate",
            }),
            (c.item = {}),
            (c.item.clear = null),
            (c.item.interval = b.interval || 30),
            (c.item.disable = (b.disable || []).slice(0)),
            (c.item.enable = -(function (a) {
                return !0 === a[0] ? a.shift() : -1;
            })(c.item.disable)),
            c.set("min", b.min).set("max", b.max).set("now"),
            f ? c.set("select", f, { format: g }) : c.set("select", null).set("highlight", c.item.now),
            (c.key = {
                40: 1,
                38: -1,
                39: 1,
                37: -1,
                go: function (a) {
                    c.set("highlight", c.item.highlight.pick + a * c.item.interval, { interval: a * c.item.interval }), this.render();
                },
            }),
            a
                .on(
                    "render",
                    function () {
                        var c = a.$root.children(),
                            d = c.find("." + b.klass.viewset),
                            e = function (a) {
                                return ["webkit", "moz", "ms", "o", ""].map(function (b) {
                                    return (b ? "-" + b + "-" : "") + a;
                                });
                            },
                            f = function (a, b) {
                                e("transform").map(function (c) {
                                    a.css(c, b);
                                }),
                                    e("transition").map(function (c) {
                                        a.css(c, b);
                                    });
                            };
                        d.length && (f(c, "none"), (c[0].scrollTop = ~~d.position().top - 2 * d[0].clientHeight), f(c, ""));
                    },
                    1
                )
                .on(
                    "open",
                    function () {
                        a.$root.find("button").attr("disabled", !1);
                    },
                    1
                )
                .on(
                    "close",
                    function () {
                        a.$root.find("button").attr("disabled", !0);
                    },
                    1
                );
    }
    var d = 24,
        e = 60,
        f = 12,
        g = d * e,
        h = a._;
    (c.prototype.set = function (a, b, c) {
        var d = this,
            e = d.item;
        return null === b
            ? ("clear" == a && (a = "select"), (e[a] = b), d)
            : ((e["enable" == a ? "disable" : "flip" == a ? "enable" : a] = d.queue[a]
                  .split(" ")
                  .map(function (e) {
                      return (b = d[e](a, b, c));
                  })
                  .pop()),
              "select" == a
                  ? d.set("highlight", e.select, c)
                  : "highlight" == a
                  ? d.set("view", e.highlight, c)
                  : "interval" == a
                  ? d.set("min", e.min, c).set("max", e.max, c)
                  : a.match(/^(flip|min|max|disable|enable)$/) && (e.select && d.disabled(e.select) && d.set("select", b, c), e.highlight && d.disabled(e.highlight) && d.set("highlight", b, c), "min" == a && d.set("max", e.max, c)),
              d);
    }),
        (c.prototype.get = function (a) {
            return this.item[a];
        }),
        (c.prototype.create = function (a, c, f) {
            var i = this;
            return (
                (c = void 0 === c ? a : c),
                h.isDate(c) && (c = [c.getHours(), c.getMinutes()]),
                b.isPlainObject(c) && h.isInteger(c.pick) ? (c = c.pick) : b.isArray(c) ? (c = +c[0] * e + +c[1]) : h.isInteger(c) || (c = i.now(a, c, f)),
                "max" == a && c < i.item.min.pick && (c += g),
                "min" != a && "max" != a && (c - i.item.min.pick) % i.item.interval != 0 && (c += i.item.interval),
                (c = i.normalize(a, c, f)),
                { hour: ~~(d + c / e) % d, mins: (e + (c % e)) % e, time: (g + c) % g, pick: c % g }
            );
        }),
        (c.prototype.createRange = function (a, c) {
            var d = this,
                e = function (a) {
                    return !0 === a || b.isArray(a) || h.isDate(a) ? d.create(a) : a;
                };
            return (
                h.isInteger(a) || (a = e(a)),
                h.isInteger(c) || (c = e(c)),
                h.isInteger(a) && b.isPlainObject(c) ? (a = [c.hour, c.mins + a * d.settings.interval]) : h.isInteger(c) && b.isPlainObject(a) && (c = [a.hour, a.mins + c * d.settings.interval]),
                { from: e(a), to: e(c) }
            );
        }),
        (c.prototype.withinRange = function (a, b) {
            return (a = this.createRange(a.from, a.to)), b.pick >= a.from.pick && b.pick <= a.to.pick;
        }),
        (c.prototype.overlapRanges = function (a, b) {
            var c = this;
            return (a = c.createRange(a.from, a.to)), (b = c.createRange(b.from, b.to)), c.withinRange(a, b.from) || c.withinRange(a, b.to) || c.withinRange(b, a.from) || c.withinRange(b, a.to);
        }),
        (c.prototype.now = function (a, b) {
            var c,
                d = this.item.interval,
                f = new Date(),
                g = f.getHours() * e + f.getMinutes(),
                i = h.isInteger(b);
            return (g -= g % d), (c = b < 0 && d * b + g <= -d), (g += "min" == a && c ? 0 : d), i && (g += d * (c && "max" != a ? b + 1 : b)), g;
        }),
        (c.prototype.normalize = function (a, b) {
            var c = this.item.interval,
                d = (this.item.min && this.item.min.pick) || 0;
            return (b -= "min" == a ? 0 : (b - d) % c);
        }),
        (c.prototype.measure = function (a, c, f) {
            var g = this;
            return (
                c || (c = "min" == a ? [0, 0] : [d - 1, e - 1]),
                "string" == typeof c ? (c = g.parse(a, c)) : !0 === c || h.isInteger(c) ? (c = g.now(a, c, f)) : b.isPlainObject(c) && h.isInteger(c.pick) && (c = g.normalize(a, c.pick, f)),
                c
            );
        }),
        (c.prototype.validate = function (a, b, c) {
            var d = this,
                e = c && c.interval ? c.interval : d.item.interval;
            return d.disabled(b) && (b = d.shift(b, e)), (b = d.scope(b)), d.disabled(b) && (b = d.shift(b, -1 * e)), b;
        }),
        (c.prototype.disabled = function (a) {
            var c = this,
                d = c.item.disable.filter(function (d) {
                    return h.isInteger(d) ? a.hour == d : b.isArray(d) || h.isDate(d) ? a.pick == c.create(d).pick : b.isPlainObject(d) ? c.withinRange(d, a) : void 0;
                });
            return (
                (d =
                    d.length &&
                    !d.filter(function (a) {
                        return (b.isArray(a) && "inverted" == a[2]) || (b.isPlainObject(a) && a.inverted);
                    }).length),
                -1 === c.item.enable ? !d : d || a.pick < c.item.min.pick || a.pick > c.item.max.pick
            );
        }),
        (c.prototype.shift = function (a, b) {
            var c = this,
                d = c.item.min.pick,
                e = c.item.max.pick;
            for (b = b || c.item.interval; c.disabled(a) && ((a = c.create((a.pick += b))), !(a.pick <= d || a.pick >= e)); );
            return a;
        }),
        (c.prototype.scope = function (a) {
            var b = this.item.min.pick,
                c = this.item.max.pick;
            return this.create(a.pick > c ? c : a.pick < b ? b : a);
        }),
        (c.prototype.parse = function (a, b, c) {
            var d,
                f,
                g,
                i,
                j,
                k = this,
                l = {};
            if (!b || "string" != typeof b) return b;
            (c && c.format) || ((c = c || {}), (c.format = k.settings.format)),
                k.formats.toArray(c.format).map(function (a) {
                    var c,
                        d = k.formats[a],
                        e = d ? h.trigger(d, k, [b, l]) : a.replace(/^!/, "").length;
                    d && ((c = b.substr(0, e)), (l[a] = c.match(/^\d+$/) ? +c : c)), (b = b.substr(e));
                });
            for (i in l) (j = l[i]), h.isInteger(j) ? (i.match(/^(h|hh)$/i) ? ((d = j), ("h" != i && "hh" != i) || (d %= 12)) : "i" == i && (f = j)) : i.match(/^a$/i) && j.match(/^p/i) && ("h" in l || "hh" in l) && (g = !0);
            return (g ? d + 12 : d) * e + f;
        }),
        (c.prototype.formats = {
            h: function (a, b) {
                return a ? h.digits(a) : b.hour % f || f;
            },
            hh: function (a, b) {
                return a ? 2 : h.lead(b.hour % f || f);
            },
            H: function (a, b) {
                return a ? h.digits(a) : "" + (b.hour % 24);
            },
            HH: function (a, b) {
                return a ? h.digits(a) : h.lead(b.hour % 24);
            },
            i: function (a, b) {
                return a ? 2 : h.lead(b.mins);
            },
            a: function (a, b) {
                return a ? 4 : g / 2 > b.time % g ? "a.m." : "p.m.";
            },
            A: function (a, b) {
                return a ? 2 : g / 2 > b.time % g ? "AM" : "PM";
            },
            toArray: function (a) {
                return a.split(/(h{1,2}|H{1,2}|i|a|A|!.)/g);
            },
            toString: function (a, b) {
                var c = this;
                return c.formats
                    .toArray(a)
                    .map(function (a) {
                        return h.trigger(c.formats[a], c, [0, b]) || a.replace(/^!/, "");
                    })
                    .join("");
            },
        }),
        (c.prototype.isTimeExact = function (a, c) {
            var d = this;
            return (h.isInteger(a) && h.isInteger(c)) || ("boolean" == typeof a && "boolean" == typeof c)
                ? a === c
                : (h.isDate(a) || b.isArray(a)) && (h.isDate(c) || b.isArray(c))
                ? d.create(a).pick === d.create(c).pick
                : !(!b.isPlainObject(a) || !b.isPlainObject(c)) && d.isTimeExact(a.from, c.from) && d.isTimeExact(a.to, c.to);
        }),
        (c.prototype.isTimeOverlap = function (a, c) {
            var d = this;
            return h.isInteger(a) && (h.isDate(c) || b.isArray(c)) ? a === d.create(c).hour : h.isInteger(c) && (h.isDate(a) || b.isArray(a)) ? c === d.create(a).hour : !(!b.isPlainObject(a) || !b.isPlainObject(c)) && d.overlapRanges(a, c);
        }),
        (c.prototype.flipEnable = function (a) {
            var b = this.item;
            b.enable = a || (-1 == b.enable ? 1 : -1);
        }),
        (c.prototype.deactivate = function (a, c) {
            var d = this,
                e = d.item.disable.slice(0);
            return (
                "flip" == c
                    ? d.flipEnable()
                    : !1 === c
                    ? (d.flipEnable(1), (e = []))
                    : !0 === c
                    ? (d.flipEnable(-1), (e = []))
                    : c.map(function (a) {
                          for (var c, f = 0; f < e.length; f += 1)
                              if (d.isTimeExact(a, e[f])) {
                                  c = !0;
                                  break;
                              }
                          c || ((h.isInteger(a) || h.isDate(a) || b.isArray(a) || (b.isPlainObject(a) && a.from && a.to)) && e.push(a));
                      }),
                e
            );
        }),
        (c.prototype.activate = function (a, c) {
            var d = this,
                e = d.item.disable,
                f = e.length;
            return (
                "flip" == c
                    ? d.flipEnable()
                    : !0 === c
                    ? (d.flipEnable(1), (e = []))
                    : !1 === c
                    ? (d.flipEnable(-1), (e = []))
                    : c.map(function (a) {
                          var c, g, i, j;
                          for (i = 0; i < f; i += 1) {
                              if (((g = e[i]), d.isTimeExact(g, a))) {
                                  (c = e[i] = null), (j = !0);
                                  break;
                              }
                              if (d.isTimeOverlap(g, a)) {
                                  b.isPlainObject(a) ? ((a.inverted = !0), (c = a)) : b.isArray(a) ? ((c = a), c[2] || c.push("inverted")) : h.isDate(a) && (c = [a.getFullYear(), a.getMonth(), a.getDate(), "inverted"]);
                                  break;
                              }
                          }
                          if (c)
                              for (i = 0; i < f; i += 1)
                                  if (d.isTimeExact(e[i], a)) {
                                      e[i] = null;
                                      break;
                                  }
                          if (j)
                              for (i = 0; i < f; i += 1)
                                  if (d.isTimeOverlap(e[i], a)) {
                                      e[i] = null;
                                      break;
                                  }
                          c && e.push(c);
                      }),
                e.filter(function (a) {
                    return null != a;
                })
            );
        }),
        (c.prototype.i = function (a, b) {
            return h.isInteger(b) && b > 0 ? b : this.item.interval;
        }),
        (c.prototype.nodes = function (a) {
            var b = this,
                c = b.settings,
                d = b.item.select,
                e = b.item.highlight,
                f = b.item.view,
                g = b.item.disable;
            return h.node(
                "ul",
                h.group({
                    min: b.item.min.pick,
                    max: b.item.max.pick,
                    i: b.item.interval,
                    node: "li",
                    item: function (a) {
                        a = b.create(a);
                        var i = a.pick,
                            j = d && d.pick == i,
                            k = e && e.pick == i,
                            l = g && b.disabled(a),
                            m = h.trigger(b.formats.toString, b, [c.format, a]);
                        return [
                            h.trigger(b.formats.toString, b, [h.trigger(c.formatLabel, b, [a]) || c.format, a]),
                            (function (a) {
                                return j && a.push(c.klass.selected), k && a.push(c.klass.highlighted), f && f.pick == i && a.push(c.klass.viewset), l && a.push(c.klass.disabled), a.join(" ");
                            })([c.klass.listItem]),
                            "data-pick=" + a.pick + " " + h.ariaAttr({ role: "option", label: m, selected: !(!j || b.$node.val() !== m) || null, activedescendant: !!k || null, disabled: !!l || null }),
                        ];
                    },
                }) + h.node("li", h.node("button", c.clear, c.klass.buttonClear, "type=button data-clear=1" + (a ? "" : " disabled") + " " + h.ariaAttr({ controls: b.$node[0].id })), "", h.ariaAttr({ role: "presentation" })),
                c.klass.list,
                h.ariaAttr({ role: "listbox", controls: b.$node[0].id })
            );
        }),
        (c.defaults = (function (a) {
            return {
                clear: "Clear",
                format: "h:i A",
                interval: 30,
                closeOnSelect: !0,
                closeOnClear: !0,
                updateInput: !0,
                klass: {
                    picker: a + " " + a + "--time",
                    holder: a + "__holder",
                    list: a + "__list",
                    listItem: a + "__list-item",
                    disabled: a + "__list-item--disabled",
                    selected: a + "__list-item--selected",
                    highlighted: a + "__list-item--highlighted",
                    viewset: a + "__list-item--viewset",
                    now: a + "__list-item--now",
                    buttonClear: a + "__button--clear",
                },
            };
        })(a.klasses().picker)),
        a.extend("pickatime", c);
});

/*!
 * Legacy browser support
 */
[].map ||
    (Array.prototype.map = function (a, b) {
        for (var c = this, d = c.length, e = new Array(d), f = 0; d > f; f++) f in c && (e[f] = a.call(b, c[f], f, c));
        return e;
    }),
    [].filter ||
        (Array.prototype.filter = function (a) {
            if (null == this) throw new TypeError();
            var b = Object(this),
                c = b.length >>> 0;
            if ("function" != typeof a) throw new TypeError();
            for (var d = [], e = arguments[1], f = 0; c > f; f++)
                if (f in b) {
                    var g = b[f];
                    a.call(e, g, f, b) && d.push(g);
                }
            return d;
        }),
    [].indexOf ||
        (Array.prototype.indexOf = function (a) {
            if (null == this) throw new TypeError();
            var b = Object(this),
                c = b.length >>> 0;
            if (0 === c) return -1;
            var d = 0;
            if ((arguments.length > 1 && ((d = Number(arguments[1])), d != d ? (d = 0) : 0 !== d && 1 / 0 != d && d != -1 / 0 && (d = (d > 0 || -1) * Math.floor(Math.abs(d)))), d >= c)) return -1;
            for (var e = d >= 0 ? d : Math.max(c - Math.abs(d), 0); c > e; e++) if (e in b && b[e] === a) return e;
            return -1;
        });
/*!
 * Cross-Browser Split 1.1.1
 * Copyright 2007-2012 Steven Levithan <stevenlevithan.com>
 * Available under the MIT License
 * http://blog.stevenlevithan.com/archives/cross-browser-split
 */
var nativeSplit = String.prototype.split,
    compliantExecNpcg = void 0 === /()??/.exec("")[1];
String.prototype.split = function (a, b) {
    var c = this;
    if ("[object RegExp]" !== Object.prototype.toString.call(a)) return nativeSplit.call(c, a, b);
    var d,
        e,
        f,
        g,
        h = [],
        i = (a.ignoreCase ? "i" : "") + (a.multiline ? "m" : "") + (a.extended ? "x" : "") + (a.sticky ? "y" : ""),
        j = 0;
    for (
        a = new RegExp(a.source, i + "g"), c += "", compliantExecNpcg || (d = new RegExp("^" + a.source + "$(?!\\s)", i)), b = void 0 === b ? -1 >>> 0 : b >>> 0;
        (e = a.exec(c)) &&
        ((f = e.index + e[0].length),
        !(
            f > j &&
            (h.push(c.slice(j, e.index)),
            !compliantExecNpcg &&
                e.length > 1 &&
                e[0].replace(d, function () {
                    for (var a = 1; a < arguments.length - 2; a++) void 0 === arguments[a] && (e[a] = void 0);
                }),
            e.length > 1 && e.index < c.length && Array.prototype.push.apply(h, e.slice(1)),
            (g = e[0].length),
            (j = f),
            h.length >= b)
        ));

    )
        a.lastIndex === e.index && a.lastIndex++;
    return j === c.length ? (g || !a.test("")) && h.push("") : h.push(c.slice(j)), h.length > b ? h.slice(0, b) : h;
};

//tz.js
var jstz = {
    HEMISPHERE_SOUTH: "SOUTH",
    HEMISPHERE_NORTH: "NORTH",
    HEMISPHERE_UNKNOWN: "N/A",
    olson: {},
    TimeZone: function (e, a, i) {
        (this.utc_offset = e), (this.olson_tz = a), (this.uses_dst = i);
    },
};
(jstz.TimeZone.prototype.display = function () {
    this.ambiguity_check();
    var e = "<b>UTC-offset</b>: " + this.utc_offset + "<br/>";
    return (e += "<b>Zoneinfo key</b>: " + this.olson_tz + "<br/>"), (e += "<b>Zone uses DST</b>: " + (this.uses_dst ? "yes" : "no") + "<br/>");
}),
    (jstz.TimeZone.prototype.ambiguity_check = function () {
        var e, a, i, n;
        if (void 0 !== (e = jstz.olson.ambiguity_list[this.olson_tz])) for (a = e.length, i = 0; i < a; i += 1) if (((n = e[i]), jstz.date_is_dst(jstz.olson.dst_start_dates[n]))) return void (this.olson_tz = n);
    }),
    (jstz.date_is_dst = function (e) {
        return (e.getMonth() > 5 ? jstz.get_june_offset() : jstz.get_january_offset()) - jstz.get_date_offset(e) != 0;
    }),
    (jstz.get_date_offset = function (e) {
        return -e.getTimezoneOffset();
    }),
    (jstz.get_timezone_info = function () {
        var e, a, i;
        return (i = (e = jstz.get_january_offset()) - (a = jstz.get_june_offset())) < 0
            ? { utc_offset: e, dst: 1, hemisphere: jstz.HEMISPHERE_NORTH }
            : i > 0
            ? { utc_offset: a, dst: 1, hemisphere: jstz.HEMISPHERE_SOUTH }
            : { utc_offset: e, dst: 0, hemisphere: jstz.HEMISPHERE_UNKNOWN };
    }),
    (jstz.get_january_offset = function () {
        return jstz.get_date_offset(new Date(2011, 0, 1, 0, 0, 0, 0));
    }),
    (jstz.get_june_offset = function () {
        return jstz.get_date_offset(new Date(2011, 5, 1, 0, 0, 0, 0));
    }),
    (jstz.determine_timezone = function () {
        var e, a, i;
        return (a = ""), (e = jstz.get_timezone_info()).hemisphere === jstz.HEMISPHERE_SOUTH && (a = ",s"), (i = e.utc_offset + "," + e.dst + a), { timezone: jstz.olson.timezones[i], key: i };
    }),
    (jstz.olson.timezones = {
        "-720,0": new jstz.TimeZone("-12", "Etc/GMT+12", !1),
        "-660,0": new jstz.TimeZone("-11", "Pacific/Pago_Pago", !1),
        "-600,1": new jstz.TimeZone("-11", "America/Adak", !0),
        "-660,1,s": new jstz.TimeZone("-11", "Pacific/Apia", !0),
        "-600,0": new jstz.TimeZone("-10", "Pacific/Honolulu", !1),
        "-570,0": new jstz.TimeZone("-10:30", "Pacific/Marquesas", !1),
        "-540,0": new jstz.TimeZone("-9", "Pacific/Gambier", !1),
        "-540,1": new jstz.TimeZone("-9", "America/Anchorage", !0),
        "-480,1": new jstz.TimeZone("-8", "America/Los_Angeles", !0),
        "-480,0": new jstz.TimeZone("-8", "Pacific/Pitcairn", !1),
        "-420,0": new jstz.TimeZone("-7", "America/Phoenix", !1),
        "-420,1": new jstz.TimeZone("-7", "America/Denver", !0),
        "-360,0": new jstz.TimeZone("-6", "America/Guatemala", !1),
        "-360,1": new jstz.TimeZone("-6", "America/Chicago", !0),
        "-360,1,s": new jstz.TimeZone("-6", "Pacific/Easter", !0),
        "-300,0": new jstz.TimeZone("-5", "America/Bogota", !1),
        "-300,1": new jstz.TimeZone("-5", "America/New_York", !0),
        "-270,0": new jstz.TimeZone("-430", "America/Caracas", !1),
        "-240,1": new jstz.TimeZone("-4", "America/Halifax", !0),
        "-240,0": new jstz.TimeZone("-4", "America/Santo_Domingo", !1),
        "-240,1,s": new jstz.TimeZone("-4", "America/Asuncion", !0),
        "-210,1": new jstz.TimeZone("-330", "America/St_Johns", !0),
        "-180,1": new jstz.TimeZone("-3", "America/Godthab", !0),
        "-180,0": new jstz.TimeZone("-3", "America/Argentina/Buenos_Aires", !1),
        "-180,1,s": new jstz.TimeZone("-3", "America/Montevideo", !0),
        "-120,0": new jstz.TimeZone("-2", "America/Noronha", !1),
        "-120,1": new jstz.TimeZone("-2", "Etc/GMT+2", !0),
        "-60,1": new jstz.TimeZone("-1", "Atlantic/Azores", !0),
        "-60,0": new jstz.TimeZone("-1", "Atlantic/Cape_Verde", !1),
        "0,0": new jstz.TimeZone("0", "Etc/UTC", !1),
        "0,1": new jstz.TimeZone("0", "Europe/London", !0),
        "60,1": new jstz.TimeZone("+1", "Europe/Berlin", !0),
        "60,0": new jstz.TimeZone("+1", "Africa/Lagos", !1),
        "60,1,s": new jstz.TimeZone("+1", "Africa/Windhoek", !0),
        "120,1": new jstz.TimeZone("+2", "Asia/Beirut", !0),
        "120,0": new jstz.TimeZone("+2", "Africa/Johannesburg", !1),
        "180,1": new jstz.TimeZone("+3", "Europe/Moscow", !0),
        "180,0": new jstz.TimeZone("+3", "Asia/Baghdad", !1),
        "210,1": new jstz.TimeZone("+330", "Asia/Tehran", !0),
        "240,0": new jstz.TimeZone("+4", "Asia/Dubai", !1),
        "240,1": new jstz.TimeZone("+4", "Asia/Yerevan", !0),
        "270,0": new jstz.TimeZone("+430", "Asia/Kabul", !1),
        "300,1": new jstz.TimeZone("+5", "Asia/Yekaterinburg", !0),
        "300,0": new jstz.TimeZone("+5", "Asia/Karachi", !1),
        "330,0": new jstz.TimeZone("+530", "Asia/Kolkata", !1),
        "345,0": new jstz.TimeZone("+545", "Asia/Kathmandu", !1),
        "360,0": new jstz.TimeZone("+6", "Asia/Dhaka", !1),
        "360,1": new jstz.TimeZone("+6", "Asia/Omsk", !0),
        "390,0": new jstz.TimeZone("+630", "Asia/Rangoon", !1),
        "420,1": new jstz.TimeZone("+7", "Asia/Krasnoyarsk", !0),
        "420,0": new jstz.TimeZone("+7", "Asia/Jakarta", !1),
        "480,0": new jstz.TimeZone("+8", "Asia/Shanghai", !1),
        "480,1": new jstz.TimeZone("+8", "Asia/Irkutsk", !0),
        "525,0": new jstz.TimeZone("+845", "Australia/Eucla", !0),
        "525,1,s": new jstz.TimeZone("+845", "Australia/Eucla", !0),
        "540,1": new jstz.TimeZone("+9", "Asia/Yakutsk", !0),
        "540,0": new jstz.TimeZone("+9", "Asia/Tokyo", !1),
        "570,0": new jstz.TimeZone("+930", "Australia/Darwin", !1),
        "570,1,s": new jstz.TimeZone("+930", "Australia/Adelaide", !0),
        "600,0": new jstz.TimeZone("+10", "Australia/Brisbane", !1),
        "600,1": new jstz.TimeZone("+10", "Asia/Vladivostok", !0),
        "600,1,s": new jstz.TimeZone("+10", "Australia/Sydney", !0),
        "630,1,s": new jstz.TimeZone("+1030", "Australia/Lord_Howe", !0),
        "660,1": new jstz.TimeZone("+11", "Asia/Kamchatka", !0),
        "660,0": new jstz.TimeZone("+11", "Pacific/Noumea", !1),
        "690,0": new jstz.TimeZone("+1130", "Pacific/Norfolk", !1),
        "720,1,s": new jstz.TimeZone("+12", "Pacific/Auckland", !0),
        "720,0": new jstz.TimeZone("+12", "Pacific/Tarawa", !1),
        "765,1,s": new jstz.TimeZone("+1245", "Pacific/Chatham", !0),
        "780,0": new jstz.TimeZone("+13", "Pacific/Tongatapu", !1),
        "840,0": new jstz.TimeZone("+14", "Pacific/Kiritimati", !1),
    }),
    (jstz.olson.dst_start_dates = {
        "America/Denver": new Date(2011, 2, 13, 3, 0, 0, 0),
        "America/Mazatlan": new Date(2011, 3, 3, 3, 0, 0, 0),
        "America/Chicago": new Date(2011, 2, 13, 3, 0, 0, 0),
        "America/Mexico_City": new Date(2011, 3, 3, 3, 0, 0, 0),
        "Atlantic/Stanley": new Date(2011, 8, 4, 7, 0, 0, 0),
        "America/Asuncion": new Date(2011, 9, 2, 3, 0, 0, 0),
        "America/Santiago": new Date(2011, 9, 9, 3, 0, 0, 0),
        "America/Campo_Grande": new Date(2011, 9, 16, 5, 0, 0, 0),
        "America/Montevideo": new Date(2011, 9, 2, 3, 0, 0, 0),
        "America/Sao_Paulo": new Date(2011, 9, 16, 5, 0, 0, 0),
        "America/Los_Angeles": new Date(2011, 2, 13, 8, 0, 0, 0),
        "America/Santa_Isabel": new Date(2011, 3, 5, 8, 0, 0, 0),
        "America/Havana": new Date(2011, 2, 13, 2, 0, 0, 0),
        "America/New_York": new Date(2011, 2, 13, 7, 0, 0, 0),
        "Asia/Gaza": new Date(2011, 2, 26, 23, 0, 0, 0),
        "Asia/Beirut": new Date(2011, 2, 27, 1, 0, 0, 0),
        "Europe/Minsk": new Date(2011, 2, 27, 3, 0, 0, 0),
        "Europe/Istanbul": new Date(2011, 2, 27, 7, 0, 0, 0),
        "Asia/Damascus": new Date(2011, 3, 1, 2, 0, 0, 0),
        "Asia/Jerusalem": new Date(2011, 3, 1, 6, 0, 0, 0),
        "Africa/Cairo": new Date(2011, 3, 29, 4, 0, 0, 0),
        "Asia/Yerevan": new Date(2011, 2, 27, 4, 0, 0, 0),
        "Asia/Baku": new Date(2011, 2, 27, 8, 0, 0, 0),
        "Pacific/Auckland": new Date(2011, 8, 26, 7, 0, 0, 0),
        "Pacific/Fiji": new Date(2010, 11, 29, 23, 0, 0, 0),
        "America/Halifax": new Date(2011, 2, 13, 6, 0, 0, 0),
        "America/Goose_Bay": new Date(2011, 2, 13, 2, 1, 0, 0),
        "America/Miquelon": new Date(2011, 2, 13, 5, 0, 0, 0),
        "America/Godthab": new Date(2011, 2, 27, 1, 0, 0, 0),
    }),
    (jstz.olson.ambiguity_list = {
        "America/Denver": ["America/Denver", "America/Mazatlan"],
        "America/Chicago": ["America/Chicago", "America/Mexico_City"],
        "America/Asuncion": ["Atlantic/Stanley", "America/Asuncion", "America/Santiago", "America/Campo_Grande"],
        "America/Montevideo": ["America/Montevideo", "America/Sao_Paulo"],
        "Asia/Beirut": ["Asia/Gaza", "Asia/Beirut", "Europe/Minsk", "Europe/Istanbul", "Asia/Damascus", "Asia/Jerusalem", "Africa/Cairo"],
        "Asia/Yerevan": ["Asia/Yerevan", "Asia/Baku"],
        "Pacific/Auckland": ["Pacific/Auckland", "Pacific/Fiji"],
        "America/Los_Angeles": ["America/Los_Angeles", "America/Santa_Isabel"],
        "America/New_York": ["America/Havana", "America/New_York"],
        "America/Halifax": ["America/Goose_Bay", "America/Halifax"],
        "America/Godthab": ["America/Miquelon", "America/Godthab"],
    });

//datables.jquery.min

var $jscomp = $jscomp || {};
$jscomp.scope = {};
$jscomp.findInternal = function (k, y, z) {
    k instanceof String && (k = String(k));
    for (var q = k.length, G = 0; G < q; G++) {
        var O = k[G];
        if (y.call(z, O, G, k)) return { i: G, v: O };
    }
    return { i: -1, v: void 0 };
};
$jscomp.ASSUME_ES5 = !1;
$jscomp.ASSUME_NO_NATIVE_MAP = !1;
$jscomp.ASSUME_NO_NATIVE_SET = !1;
$jscomp.SIMPLE_FROUND_POLYFILL = !1;
$jscomp.ISOLATE_POLYFILLS = !1;
$jscomp.defineProperty =
    $jscomp.ASSUME_ES5 || "function" == typeof Object.defineProperties
        ? Object.defineProperty
        : function (k, y, z) {
              if (k == Array.prototype || k == Object.prototype) return k;
              k[y] = z.value;
              return k;
          };
$jscomp.getGlobal = function (k) {
    k = ["object" == typeof globalThis && globalThis, k, "object" == typeof window && window, "object" == typeof self && self, "object" == typeof global && global];
    for (var y = 0; y < k.length; ++y) {
        var z = k[y];
        if (z && z.Math == Math) return z;
    }
    throw Error("Cannot find global object");
};
$jscomp.global = $jscomp.getGlobal(this);
$jscomp.IS_SYMBOL_NATIVE = "function" === typeof Symbol && "symbol" === typeof Symbol("x");
$jscomp.TRUST_ES6_POLYFILLS = !$jscomp.ISOLATE_POLYFILLS || $jscomp.IS_SYMBOL_NATIVE;
$jscomp.polyfills = {};
$jscomp.propertyToPolyfillSymbol = {};
$jscomp.POLYFILL_PREFIX = "$jscp$";
var $jscomp$lookupPolyfilledValue = function (k, y) {
    var z = $jscomp.propertyToPolyfillSymbol[y];
    if (null == z) return k[y];
    z = k[z];
    return void 0 !== z ? z : k[y];
};
$jscomp.polyfill = function (k, y, z, q) {
    y && ($jscomp.ISOLATE_POLYFILLS ? $jscomp.polyfillIsolated(k, y, z, q) : $jscomp.polyfillUnisolated(k, y, z, q));
};
$jscomp.polyfillUnisolated = function (k, y, z, q) {
    z = $jscomp.global;
    k = k.split(".");
    for (q = 0; q < k.length - 1; q++) {
        var G = k[q];
        if (!(G in z)) return;
        z = z[G];
    }
    k = k[k.length - 1];
    q = z[k];
    y = y(q);
    y != q && null != y && $jscomp.defineProperty(z, k, { configurable: !0, writable: !0, value: y });
};
$jscomp.polyfillIsolated = function (k, y, z, q) {
    var G = k.split(".");
    k = 1 === G.length;
    q = G[0];
    q = !k && q in $jscomp.polyfills ? $jscomp.polyfills : $jscomp.global;
    for (var O = 0; O < G.length - 1; O++) {
        var ma = G[O];
        if (!(ma in q)) return;
        q = q[ma];
    }
    G = G[G.length - 1];
    z = $jscomp.IS_SYMBOL_NATIVE && "es6" === z ? q[G] : null;
    y = y(z);
    null != y &&
        (k
            ? $jscomp.defineProperty($jscomp.polyfills, G, { configurable: !0, writable: !0, value: y })
            : y !== z &&
              (($jscomp.propertyToPolyfillSymbol[G] = $jscomp.IS_SYMBOL_NATIVE ? $jscomp.global.Symbol(G) : $jscomp.POLYFILL_PREFIX + G),
              (G = $jscomp.propertyToPolyfillSymbol[G]),
              $jscomp.defineProperty(q, G, { configurable: !0, writable: !0, value: y })));
};
$jscomp.polyfill(
    "Array.prototype.find",
    function (k) {
        return k
            ? k
            : function (y, z) {
                  return $jscomp.findInternal(this, y, z).v;
              };
    },
    "es6",
    "es3"
);
(function (k) {
    "function" === typeof define && define.amd
        ? define(["jquery"], function (y) {
              return k(y, window, document);
          })
        : "object" === typeof exports
        ? (module.exports = function (y, z) {
              y || (y = window);
              z || (z = "undefined" !== typeof window ? require("jquery") : require("jquery")(y));
              return k(z, y, y.document);
          })
        : k(jQuery, window, document);
})(function (k, y, z, q) {
    function G(a) {
        var b,
            c,
            d = {};
        k.each(a, function (e, f) {
            (b = e.match(/^([^A-Z]+?)([A-Z])/)) && -1 !== "a aa ai ao as b fn i m o s ".indexOf(b[1] + " ") && ((c = e.replace(b[0], b[2].toLowerCase())), (d[c] = e), "o" === b[1] && G(a[e]));
        });
        a._hungarianMap = d;
    }
    function O(a, b, c) {
        a._hungarianMap || G(a);
        var d;
        k.each(b, function (e, f) {
            d = a._hungarianMap[e];
            d === q || (!c && b[d] !== q) || ("o" === d.charAt(0) ? (b[d] || (b[d] = {}), k.extend(!0, b[d], b[e]), O(a[d], b[d], c)) : (b[d] = b[e]));
        });
    }
    function ma(a) {
        var b = u.defaults.oLanguage,
            c = b.sDecimal;
        c && Va(c);
        if (a) {
            var d = a.sZeroRecords;
            !a.sEmptyTable && d && "No data available in table" === b.sEmptyTable && V(a, a, "sZeroRecords", "sEmptyTable");
            !a.sLoadingRecords && d && "Loading..." === b.sLoadingRecords && V(a, a, "sZeroRecords", "sLoadingRecords");
            a.sInfoThousands && (a.sThousands = a.sInfoThousands);
            (a = a.sDecimal) && c !== a && Va(a);
        }
    }
    function yb(a) {
        R(a, "ordering", "bSort");
        R(a, "orderMulti", "bSortMulti");
        R(a, "orderClasses", "bSortClasses");
        R(a, "orderCellsTop", "bSortCellsTop");
        R(a, "order", "aaSorting");
        R(a, "orderFixed", "aaSortingFixed");
        R(a, "paging", "bPaginate");
        R(a, "pagingType", "sPaginationType");
        R(a, "pageLength", "iDisplayLength");
        R(a, "searching", "bFilter");
        "boolean" === typeof a.sScrollX && (a.sScrollX = a.sScrollX ? "100%" : "");
        "boolean" === typeof a.scrollX && (a.scrollX = a.scrollX ? "100%" : "");
        if ((a = a.aoSearchCols)) for (var b = 0, c = a.length; b < c; b++) a[b] && O(u.models.oSearch, a[b]);
    }
    function zb(a) {
        R(a, "orderable", "bSortable");
        R(a, "orderData", "aDataSort");
        R(a, "orderSequence", "asSorting");
        R(a, "orderDataType", "sortDataType");
        var b = a.aDataSort;
        "number" !== typeof b || Array.isArray(b) || (a.aDataSort = [b]);
    }
    function Ab(a) {
        if (!u.__browser) {
            var b = {};
            u.__browser = b;
            var c = k("<div/>")
                    .css({ position: "fixed", top: 0, left: -1 * k(y).scrollLeft(), height: 1, width: 1, overflow: "hidden" })
                    .append(
                        k("<div/>")
                            .css({ position: "absolute", top: 1, left: 1, width: 100, overflow: "scroll" })
                            .append(k("<div/>").css({ width: "100%", height: 10 }))
                    )
                    .appendTo("body"),
                d = c.children(),
                e = d.children();
            b.barWidth = d[0].offsetWidth - d[0].clientWidth;
            b.bScrollOversize = 100 === e[0].offsetWidth && 100 !== d[0].clientWidth;
            b.bScrollbarLeft = 1 !== Math.round(e.offset().left);
            b.bBounding = c[0].getBoundingClientRect().width ? !0 : !1;
            c.remove();
        }
        k.extend(a.oBrowser, u.__browser);
        a.oScroll.iBarWidth = u.__browser.barWidth;
    }
    function Bb(a, b, c, d, e, f) {
        var g = !1;
        if (c !== q) {
            var h = c;
            g = !0;
        }
        for (; d !== e; ) a.hasOwnProperty(d) && ((h = g ? b(h, a[d], d, a) : a[d]), (g = !0), (d += f));
        return h;
    }
    function Wa(a, b) {
        var c = u.defaults.column,
            d = a.aoColumns.length;
        c = k.extend({}, u.models.oColumn, c, { nTh: b ? b : z.createElement("th"), sTitle: c.sTitle ? c.sTitle : b ? b.innerHTML : "", aDataSort: c.aDataSort ? c.aDataSort : [d], mData: c.mData ? c.mData : d, idx: d });
        a.aoColumns.push(c);
        c = a.aoPreSearchCols;
        c[d] = k.extend({}, u.models.oSearch, c[d]);
        Da(a, d, k(b).data());
    }
    function Da(a, b, c) {
        b = a.aoColumns[b];
        var d = a.oClasses,
            e = k(b.nTh);
        if (!b.sWidthOrig) {
            b.sWidthOrig = e.attr("width") || null;
            var f = (e.attr("style") || "").match(/width:\s*(\d+[pxem%]+)/);
            f && (b.sWidthOrig = f[1]);
        }
        c !== q &&
            null !== c &&
            (zb(c),
            O(u.defaults.column, c, !0),
            c.mDataProp === q || c.mData || (c.mData = c.mDataProp),
            c.sType && (b._sManualType = c.sType),
            c.className && !c.sClass && (c.sClass = c.className),
            c.sClass && e.addClass(c.sClass),
            k.extend(b, c),
            V(b, c, "sWidth", "sWidthOrig"),
            c.iDataSort !== q && (b.aDataSort = [c.iDataSort]),
            V(b, c, "aDataSort"));
        var g = b.mData,
            h = ia(g),
            l = b.mRender ? ia(b.mRender) : null;
        c = function (n) {
            return "string" === typeof n && -1 !== n.indexOf("@");
        };
        b._bAttrSrc = k.isPlainObject(g) && (c(g.sort) || c(g.type) || c(g.filter));
        b._setter = null;
        b.fnGetData = function (n, m, p) {
            var t = h(n, m, q, p);
            return l && m ? l(t, m, n, p) : t;
        };
        b.fnSetData = function (n, m, p) {
            return da(g)(n, m, p);
        };
        "number" !== typeof g && (a._rowReadObject = !0);
        a.oFeatures.bSort || ((b.bSortable = !1), e.addClass(d.sSortableNone));
        a = -1 !== k.inArray("asc", b.asSorting);
        c = -1 !== k.inArray("desc", b.asSorting);
        b.bSortable && (a || c)
            ? a && !c
                ? ((b.sSortingClass = d.sSortableAsc), (b.sSortingClassJUI = d.sSortJUIAscAllowed))
                : !a && c
                ? ((b.sSortingClass = d.sSortableDesc), (b.sSortingClassJUI = d.sSortJUIDescAllowed))
                : ((b.sSortingClass = d.sSortable), (b.sSortingClassJUI = d.sSortJUI))
            : ((b.sSortingClass = d.sSortableNone), (b.sSortingClassJUI = ""));
    }
    function ra(a) {
        if (!1 !== a.oFeatures.bAutoWidth) {
            var b = a.aoColumns;
            Xa(a);
            for (var c = 0, d = b.length; c < d; c++) b[c].nTh.style.width = b[c].sWidth;
        }
        b = a.oScroll;
        ("" === b.sY && "" === b.sX) || Ea(a);
        H(a, null, "column-sizing", [a]);
    }
    function sa(a, b) {
        a = Fa(a, "bVisible");
        return "number" === typeof a[b] ? a[b] : null;
    }
    function ta(a, b) {
        a = Fa(a, "bVisible");
        b = k.inArray(b, a);
        return -1 !== b ? b : null;
    }
    function na(a) {
        var b = 0;
        k.each(a.aoColumns, function (c, d) {
            d.bVisible && "none" !== k(d.nTh).css("display") && b++;
        });
        return b;
    }
    function Fa(a, b) {
        var c = [];
        k.map(a.aoColumns, function (d, e) {
            d[b] && c.push(e);
        });
        return c;
    }
    function Ya(a) {
        var b = a.aoColumns,
            c = a.aoData,
            d = u.ext.type.detect,
            e,
            f,
            g;
        var h = 0;
        for (e = b.length; h < e; h++) {
            var l = b[h];
            var n = [];
            if (!l.sType && l._sManualType) l.sType = l._sManualType;
            else if (!l.sType) {
                var m = 0;
                for (f = d.length; m < f; m++) {
                    var p = 0;
                    for (g = c.length; p < g; p++) {
                        n[p] === q && (n[p] = S(a, p, h, "type"));
                        var t = d[m](n[p], a);
                        if (!t && m !== d.length - 1) break;
                        if ("html" === t) break;
                    }
                    if (t) {
                        l.sType = t;
                        break;
                    }
                }
                l.sType || (l.sType = "string");
            }
        }
    }
    function Cb(a, b, c, d) {
        var e,
            f,
            g,
            h = a.aoColumns;
        if (b)
            for (e = b.length - 1; 0 <= e; e--) {
                var l = b[e];
                var n = l.targets !== q ? l.targets : l.aTargets;
                Array.isArray(n) || (n = [n]);
                var m = 0;
                for (f = n.length; m < f; m++)
                    if ("number" === typeof n[m] && 0 <= n[m]) {
                        for (; h.length <= n[m]; ) Wa(a);
                        d(n[m], l);
                    } else if ("number" === typeof n[m] && 0 > n[m]) d(h.length + n[m], l);
                    else if ("string" === typeof n[m]) {
                        var p = 0;
                        for (g = h.length; p < g; p++) ("_all" == n[m] || k(h[p].nTh).hasClass(n[m])) && d(p, l);
                    }
            }
        if (c) for (e = 0, a = c.length; e < a; e++) d(e, c[e]);
    }
    function ea(a, b, c, d) {
        var e = a.aoData.length,
            f = k.extend(!0, {}, u.models.oRow, { src: c ? "dom" : "data", idx: e });
        f._aData = b;
        a.aoData.push(f);
        for (var g = a.aoColumns, h = 0, l = g.length; h < l; h++) g[h].sType = null;
        a.aiDisplayMaster.push(e);
        b = a.rowIdFn(b);
        b !== q && (a.aIds[b] = f);
        (!c && a.oFeatures.bDeferRender) || Za(a, e, c, d);
        return e;
    }
    function Ga(a, b) {
        var c;
        b instanceof k || (b = k(b));
        return b.map(function (d, e) {
            c = $a(a, e);
            return ea(a, c.data, e, c.cells);
        });
    }
    function S(a, b, c, d) {
        var e = a.iDraw,
            f = a.aoColumns[c],
            g = a.aoData[b]._aData,
            h = f.sDefaultContent,
            l = f.fnGetData(g, d, { settings: a, row: b, col: c });
        if (l === q) return a.iDrawError != e && null === h && (aa(a, 0, "Requested unknown parameter " + ("function" == typeof f.mData ? "{function}" : "'" + f.mData + "'") + " for row " + b + ", column " + c, 4), (a.iDrawError = e)), h;
        if ((l === g || null === l) && null !== h && d !== q) l = h;
        else if ("function" === typeof l) return l.call(g);
        return null === l && "display" == d ? "" : l;
    }
    function Db(a, b, c, d) {
        a.aoColumns[c].fnSetData(a.aoData[b]._aData, d, { settings: a, row: b, col: c });
    }
    function ab(a) {
        return k.map(a.match(/(\\.|[^\.])+/g) || [""], function (b) {
            return b.replace(/\\\./g, ".");
        });
    }
    function ia(a) {
        if (k.isPlainObject(a)) {
            var b = {};
            k.each(a, function (d, e) {
                e && (b[d] = ia(e));
            });
            return function (d, e, f, g) {
                var h = b[e] || b._;
                return h !== q ? h(d, e, f, g) : d;
            };
        }
        if (null === a)
            return function (d) {
                return d;
            };
        if ("function" === typeof a)
            return function (d, e, f, g) {
                return a(d, e, f, g);
            };
        if ("string" !== typeof a || (-1 === a.indexOf(".") && -1 === a.indexOf("[") && -1 === a.indexOf("(")))
            return function (d, e) {
                return d[a];
            };
        var c = function (d, e, f) {
            if ("" !== f) {
                var g = ab(f);
                for (var h = 0, l = g.length; h < l; h++) {
                    f = g[h].match(ua);
                    var n = g[h].match(oa);
                    if (f) {
                        g[h] = g[h].replace(ua, "");
                        "" !== g[h] && (d = d[g[h]]);
                        n = [];
                        g.splice(0, h + 1);
                        g = g.join(".");
                        if (Array.isArray(d)) for (h = 0, l = d.length; h < l; h++) n.push(c(d[h], e, g));
                        d = f[0].substring(1, f[0].length - 1);
                        d = "" === d ? n : n.join(d);
                        break;
                    } else if (n) {
                        g[h] = g[h].replace(oa, "");
                        d = d[g[h]]();
                        continue;
                    }
                    if (null === d || d[g[h]] === q) return q;
                    d = d[g[h]];
                }
            }
            return d;
        };
        return function (d, e) {
            return c(d, e, a);
        };
    }
    function da(a) {
        if (k.isPlainObject(a)) return da(a._);
        if (null === a) return function () {};
        if ("function" === typeof a)
            return function (c, d, e) {
                a(c, "set", d, e);
            };
        if ("string" !== typeof a || (-1 === a.indexOf(".") && -1 === a.indexOf("[") && -1 === a.indexOf("(")))
            return function (c, d) {
                c[a] = d;
            };
        var b = function (c, d, e) {
            e = ab(e);
            var f = e[e.length - 1];
            for (var g, h, l = 0, n = e.length - 1; l < n; l++) {
                if ("__proto__" === e[l] || "constructor" === e[l]) throw Error("Cannot set prototype values");
                g = e[l].match(ua);
                h = e[l].match(oa);
                if (g) {
                    e[l] = e[l].replace(ua, "");
                    c[e[l]] = [];
                    f = e.slice();
                    f.splice(0, l + 1);
                    g = f.join(".");
                    if (Array.isArray(d)) for (h = 0, n = d.length; h < n; h++) (f = {}), b(f, d[h], g), c[e[l]].push(f);
                    else c[e[l]] = d;
                    return;
                }
                h && ((e[l] = e[l].replace(oa, "")), (c = c[e[l]](d)));
                if (null === c[e[l]] || c[e[l]] === q) c[e[l]] = {};
                c = c[e[l]];
            }
            if (f.match(oa)) c[f.replace(oa, "")](d);
            else c[f.replace(ua, "")] = d;
        };
        return function (c, d) {
            return b(c, d, a);
        };
    }
    function bb(a) {
        return T(a.aoData, "_aData");
    }
    function Ha(a) {
        a.aoData.length = 0;
        a.aiDisplayMaster.length = 0;
        a.aiDisplay.length = 0;
        a.aIds = {};
    }
    function Ia(a, b, c) {
        for (var d = -1, e = 0, f = a.length; e < f; e++) a[e] == b ? (d = e) : a[e] > b && a[e]--;
        -1 != d && c === q && a.splice(d, 1);
    }
    function va(a, b, c, d) {
        var e = a.aoData[b],
            f,
            g = function (l, n) {
                for (; l.childNodes.length; ) l.removeChild(l.firstChild);
                l.innerHTML = S(a, b, n, "display");
            };
        if ("dom" !== c && ((c && "auto" !== c) || "dom" !== e.src)) {
            var h = e.anCells;
            if (h)
                if (d !== q) g(h[d], d);
                else for (c = 0, f = h.length; c < f; c++) g(h[c], c);
        } else e._aData = $a(a, e, d, d === q ? q : e._aData).data;
        e._aSortData = null;
        e._aFilterData = null;
        g = a.aoColumns;
        if (d !== q) g[d].sType = null;
        else {
            c = 0;
            for (f = g.length; c < f; c++) g[c].sType = null;
            cb(a, e);
        }
    }
    function $a(a, b, c, d) {
        var e = [],
            f = b.firstChild,
            g,
            h = 0,
            l,
            n = a.aoColumns,
            m = a._rowReadObject;
        d = d !== q ? d : m ? {} : [];
        var p = function (x, r) {
                if ("string" === typeof x) {
                    var A = x.indexOf("@");
                    -1 !== A && ((A = x.substring(A + 1)), da(x)(d, r.getAttribute(A)));
                }
            },
            t = function (x) {
                if (c === q || c === h)
                    (g = n[h]), (l = x.innerHTML.trim()), g && g._bAttrSrc ? (da(g.mData._)(d, l), p(g.mData.sort, x), p(g.mData.type, x), p(g.mData.filter, x)) : m ? (g._setter || (g._setter = da(g.mData)), g._setter(d, l)) : (d[h] = l);
                h++;
            };
        if (f)
            for (; f; ) {
                var v = f.nodeName.toUpperCase();
                if ("TD" == v || "TH" == v) t(f), e.push(f);
                f = f.nextSibling;
            }
        else for (e = b.anCells, f = 0, v = e.length; f < v; f++) t(e[f]);
        (b = b.firstChild ? b : b.nTr) && (b = b.getAttribute("id")) && da(a.rowId)(d, b);
        return { data: d, cells: e };
    }
    function Za(a, b, c, d) {
        var e = a.aoData[b],
            f = e._aData,
            g = [],
            h,
            l;
        if (null === e.nTr) {
            var n = c || z.createElement("tr");
            e.nTr = n;
            e.anCells = g;
            n._DT_RowIndex = b;
            cb(a, e);
            var m = 0;
            for (h = a.aoColumns.length; m < h; m++) {
                var p = a.aoColumns[m];
                e = (l = c ? !1 : !0) ? z.createElement(p.sCellType) : d[m];
                e._DT_CellIndex = { row: b, column: m };
                g.push(e);
                if (l || !((!p.mRender && p.mData === m) || (k.isPlainObject(p.mData) && p.mData._ === m + ".display"))) e.innerHTML = S(a, b, m, "display");
                p.sClass && (e.className += " " + p.sClass);
                p.bVisible && !c ? n.appendChild(e) : !p.bVisible && c && e.parentNode.removeChild(e);
                p.fnCreatedCell && p.fnCreatedCell.call(a.oInstance, e, S(a, b, m), f, b, m);
            }
            H(a, "aoRowCreatedCallback", null, [n, f, b, g]);
        }
    }
    function cb(a, b) {
        var c = b.nTr,
            d = b._aData;
        if (c) {
            if ((a = a.rowIdFn(d))) c.id = a;
            d.DT_RowClass && ((a = d.DT_RowClass.split(" ")), (b.__rowc = b.__rowc ? Ja(b.__rowc.concat(a)) : a), k(c).removeClass(b.__rowc.join(" ")).addClass(d.DT_RowClass));
            d.DT_RowAttr && k(c).attr(d.DT_RowAttr);
            d.DT_RowData && k(c).data(d.DT_RowData);
        }
    }
    function Eb(a) {
        var b,
            c,
            d = a.nTHead,
            e = a.nTFoot,
            f = 0 === k("th, td", d).length,
            g = a.oClasses,
            h = a.aoColumns;
        f && (c = k("<tr/>").appendTo(d));
        var l = 0;
        for (b = h.length; l < b; l++) {
            var n = h[l];
            var m = k(n.nTh).addClass(n.sClass);
            f && m.appendTo(c);
            a.oFeatures.bSort && (m.addClass(n.sSortingClass), !1 !== n.bSortable && (m.attr("tabindex", a.iTabIndex).attr("aria-controls", a.sTableId), db(a, n.nTh, l)));
            n.sTitle != m[0].innerHTML && m.html(n.sTitle);
            eb(a, "header")(a, m, n, g);
        }
        f && wa(a.aoHeader, d);
        k(d).children("tr").attr("role", "row");
        k(d).children("tr").children("th, td").addClass(g.sHeaderTH);
        k(e).children("tr").children("th, td").addClass(g.sFooterTH);
        if (null !== e) for (a = a.aoFooter[0], l = 0, b = a.length; l < b; l++) (n = h[l]), (n.nTf = a[l].cell), n.sClass && k(n.nTf).addClass(n.sClass);
    }
    function xa(a, b, c) {
        var d,
            e,
            f = [],
            g = [],
            h = a.aoColumns.length;
        if (b) {
            c === q && (c = !1);
            var l = 0;
            for (d = b.length; l < d; l++) {
                f[l] = b[l].slice();
                f[l].nTr = b[l].nTr;
                for (e = h - 1; 0 <= e; e--) a.aoColumns[e].bVisible || c || f[l].splice(e, 1);
                g.push([]);
            }
            l = 0;
            for (d = f.length; l < d; l++) {
                if ((a = f[l].nTr)) for (; (e = a.firstChild); ) a.removeChild(e);
                e = 0;
                for (b = f[l].length; e < b; e++) {
                    var n = (h = 1);
                    if (g[l][e] === q) {
                        a.appendChild(f[l][e].cell);
                        for (g[l][e] = 1; f[l + h] !== q && f[l][e].cell == f[l + h][e].cell; ) (g[l + h][e] = 1), h++;
                        for (; f[l][e + n] !== q && f[l][e].cell == f[l][e + n].cell; ) {
                            for (c = 0; c < h; c++) g[l + c][e + n] = 1;
                            n++;
                        }
                        k(f[l][e].cell).attr("rowspan", h).attr("colspan", n);
                    }
                }
            }
        }
    }
    function fa(a) {
        var b = H(a, "aoPreDrawCallback", "preDraw", [a]);
        if (-1 !== k.inArray(!1, b)) U(a, !1);
        else {
            b = [];
            var c = 0,
                d = a.asStripeClasses,
                e = d.length,
                f = a.oLanguage,
                g = a.iInitDisplayStart,
                h = "ssp" == P(a),
                l = a.aiDisplay;
            a.bDrawing = !0;
            g !== q && -1 !== g && ((a._iDisplayStart = h ? g : g >= a.fnRecordsDisplay() ? 0 : g), (a.iInitDisplayStart = -1));
            g = a._iDisplayStart;
            var n = a.fnDisplayEnd();
            if (a.bDeferLoading) (a.bDeferLoading = !1), a.iDraw++, U(a, !1);
            else if (!h) a.iDraw++;
            else if (!a.bDestroying && !Fb(a)) return;
            if (0 !== l.length)
                for (f = h ? a.aoData.length : n, h = h ? 0 : g; h < f; h++) {
                    var m = l[h],
                        p = a.aoData[m];
                    null === p.nTr && Za(a, m);
                    var t = p.nTr;
                    if (0 !== e) {
                        var v = d[c % e];
                        p._sRowStripe != v && (k(t).removeClass(p._sRowStripe).addClass(v), (p._sRowStripe = v));
                    }
                    H(a, "aoRowCallback", null, [t, p._aData, c, h, m]);
                    b.push(t);
                    c++;
                }
            else
                (c = f.sZeroRecords),
                    1 == a.iDraw && "ajax" == P(a) ? (c = f.sLoadingRecords) : f.sEmptyTable && 0 === a.fnRecordsTotal() && (c = f.sEmptyTable),
                    (b[0] = k("<tr/>", { class: e ? d[0] : "" }).append(k("<td />", { valign: "top", colSpan: na(a), class: a.oClasses.sRowEmpty }).html(c))[0]);
            H(a, "aoHeaderCallback", "header", [k(a.nTHead).children("tr")[0], bb(a), g, n, l]);
            H(a, "aoFooterCallback", "footer", [k(a.nTFoot).children("tr")[0], bb(a), g, n, l]);
            d = k(a.nTBody);
            d.children().detach();
            d.append(k(b));
            H(a, "aoDrawCallback", "draw", [a]);
            a.bSorted = !1;
            a.bFiltered = !1;
            a.bDrawing = !1;
        }
    }
    function ja(a, b) {
        var c = a.oFeatures,
            d = c.bFilter;
        c.bSort && Gb(a);
        d ? ya(a, a.oPreviousSearch) : (a.aiDisplay = a.aiDisplayMaster.slice());
        !0 !== b && (a._iDisplayStart = 0);
        a._drawHold = b;
        fa(a);
        a._drawHold = !1;
    }
    function Hb(a) {
        var b = a.oClasses,
            c = k(a.nTable);
        c = k("<div/>").insertBefore(c);
        var d = a.oFeatures,
            e = k("<div/>", { id: a.sTableId + "_wrapper", class: b.sWrapper + (a.nTFoot ? "" : " " + b.sNoFooter) });
        a.nHolding = c[0];
        a.nTableWrapper = e[0];
        a.nTableReinsertBefore = a.nTable.nextSibling;
        for (var f = a.sDom.split(""), g, h, l, n, m, p, t = 0; t < f.length; t++) {
            g = null;
            h = f[t];
            if ("<" == h) {
                l = k("<div/>")[0];
                n = f[t + 1];
                if ("'" == n || '"' == n) {
                    m = "";
                    for (p = 2; f[t + p] != n; ) (m += f[t + p]), p++;
                    "H" == m ? (m = b.sJUIHeader) : "F" == m && (m = b.sJUIFooter);
                    -1 != m.indexOf(".") ? ((n = m.split(".")), (l.id = n[0].substr(1, n[0].length - 1)), (l.className = n[1])) : "#" == m.charAt(0) ? (l.id = m.substr(1, m.length - 1)) : (l.className = m);
                    t += p;
                }
                e.append(l);
                e = k(l);
            } else if (">" == h) e = e.parent();
            else if ("l" == h && d.bPaginate && d.bLengthChange) g = Ib(a);
            else if ("f" == h && d.bFilter) g = Jb(a);
            else if ("r" == h && d.bProcessing) g = Kb(a);
            else if ("t" == h) g = Lb(a);
            else if ("i" == h && d.bInfo) g = Mb(a);
            else if ("p" == h && d.bPaginate) g = Nb(a);
            else if (0 !== u.ext.feature.length)
                for (l = u.ext.feature, p = 0, n = l.length; p < n; p++)
                    if (h == l[p].cFeature) {
                        g = l[p].fnInit(a);
                        break;
                    }
            g && ((l = a.aanFeatures), l[h] || (l[h] = []), l[h].push(g), e.append(g));
        }
        c.replaceWith(e);
        a.nHolding = null;
    }
    function wa(a, b) {
        b = k(b).children("tr");
        var c, d, e;
        a.splice(0, a.length);
        var f = 0;
        for (e = b.length; f < e; f++) a.push([]);
        f = 0;
        for (e = b.length; f < e; f++) {
            var g = b[f];
            for (c = g.firstChild; c; ) {
                if ("TD" == c.nodeName.toUpperCase() || "TH" == c.nodeName.toUpperCase()) {
                    var h = 1 * c.getAttribute("colspan");
                    var l = 1 * c.getAttribute("rowspan");
                    h = h && 0 !== h && 1 !== h ? h : 1;
                    l = l && 0 !== l && 1 !== l ? l : 1;
                    var n = 0;
                    for (d = a[f]; d[n]; ) n++;
                    var m = n;
                    var p = 1 === h ? !0 : !1;
                    for (d = 0; d < h; d++) for (n = 0; n < l; n++) (a[f + n][m + d] = { cell: c, unique: p }), (a[f + n].nTr = g);
                }
                c = c.nextSibling;
            }
        }
    }
    function Ka(a, b, c) {
        var d = [];
        c || ((c = a.aoHeader), b && ((c = []), wa(c, b)));
        b = 0;
        for (var e = c.length; b < e; b++) for (var f = 0, g = c[b].length; f < g; f++) !c[b][f].unique || (d[f] && a.bSortCellsTop) || (d[f] = c[b][f].cell);
        return d;
    }
    function La(a, b, c) {
        H(a, "aoServerParams", "serverParams", [b]);
        if (b && Array.isArray(b)) {
            var d = {},
                e = /(.*?)\[\]$/;
            k.each(b, function (m, p) {
                (m = p.name.match(e)) ? ((m = m[0]), d[m] || (d[m] = []), d[m].push(p.value)) : (d[p.name] = p.value);
            });
            b = d;
        }
        var f = a.ajax,
            g = a.oInstance,
            h = function (m) {
                H(a, null, "xhr", [a, m, a.jqXHR]);
                c(m);
            };
        if (k.isPlainObject(f) && f.data) {
            var l = f.data;
            var n = "function" === typeof l ? l(b, a) : l;
            b = "function" === typeof l && n ? n : k.extend(!0, b, n);
            delete f.data;
        }
        n = {
            data: b,
            success: function (m) {
                var p = m.error || m.sError;
                p && aa(a, 0, p);
                a.json = m;
                h(m);
            },
            dataType: "json",
            cache: !1,
            type: a.sServerMethod,
            error: function (m, p, t) {
                t = H(a, null, "xhr", [a, null, a.jqXHR]);
                -1 === k.inArray(!0, t) && ("parsererror" == p ? aa(a, 0, "Invalid JSON response", 1) : 4 === m.readyState && aa(a, 0, "Ajax error", 7));
                U(a, !1);
            },
        };
        a.oAjaxData = b;
        H(a, null, "preXhr", [a, b]);
        a.fnServerData
            ? a.fnServerData.call(
                  g,
                  a.sAjaxSource,
                  k.map(b, function (m, p) {
                      return { name: p, value: m };
                  }),
                  h,
                  a
              )
            : a.sAjaxSource || "string" === typeof f
            ? (a.jqXHR = k.ajax(k.extend(n, { url: f || a.sAjaxSource })))
            : "function" === typeof f
            ? (a.jqXHR = f.call(g, b, h, a))
            : ((a.jqXHR = k.ajax(k.extend(n, f))), (f.data = l));
    }
    function Fb(a) {
        return a.bAjaxDataGet
            ? (a.iDraw++,
              U(a, !0),
              La(a, Ob(a), function (b) {
                  Pb(a, b);
              }),
              !1)
            : !0;
    }
    function Ob(a) {
        var b = a.aoColumns,
            c = b.length,
            d = a.oFeatures,
            e = a.oPreviousSearch,
            f = a.aoPreSearchCols,
            g = [],
            h = pa(a);
        var l = a._iDisplayStart;
        var n = !1 !== d.bPaginate ? a._iDisplayLength : -1;
        var m = function (x, r) {
            g.push({ name: x, value: r });
        };
        m("sEcho", a.iDraw);
        m("iColumns", c);
        m("sColumns", T(b, "sName").join(","));
        m("iDisplayStart", l);
        m("iDisplayLength", n);
        var p = { draw: a.iDraw, columns: [], order: [], start: l, length: n, search: { value: e.sSearch, regex: e.bRegex } };
        for (l = 0; l < c; l++) {
            var t = b[l];
            var v = f[l];
            n = "function" == typeof t.mData ? "function" : t.mData;
            p.columns.push({ data: n, name: t.sName, searchable: t.bSearchable, orderable: t.bSortable, search: { value: v.sSearch, regex: v.bRegex } });
            m("mDataProp_" + l, n);
            d.bFilter && (m("sSearch_" + l, v.sSearch), m("bRegex_" + l, v.bRegex), m("bSearchable_" + l, t.bSearchable));
            d.bSort && m("bSortable_" + l, t.bSortable);
        }
        d.bFilter && (m("sSearch", e.sSearch), m("bRegex", e.bRegex));
        d.bSort &&
            (k.each(h, function (x, r) {
                p.order.push({ column: r.col, dir: r.dir });
                m("iSortCol_" + x, r.col);
                m("sSortDir_" + x, r.dir);
            }),
            m("iSortingCols", h.length));
        b = u.ext.legacy.ajax;
        return null === b ? (a.sAjaxSource ? g : p) : b ? g : p;
    }
    function Pb(a, b) {
        var c = function (g, h) {
                return b[g] !== q ? b[g] : b[h];
            },
            d = Ma(a, b),
            e = c("sEcho", "draw"),
            f = c("iTotalRecords", "recordsTotal");
        c = c("iTotalDisplayRecords", "recordsFiltered");
        if (e !== q) {
            if (1 * e < a.iDraw) return;
            a.iDraw = 1 * e;
        }
        Ha(a);
        a._iRecordsTotal = parseInt(f, 10);
        a._iRecordsDisplay = parseInt(c, 10);
        e = 0;
        for (f = d.length; e < f; e++) ea(a, d[e]);
        a.aiDisplay = a.aiDisplayMaster.slice();
        a.bAjaxDataGet = !1;
        fa(a);
        a._bInitComplete || Na(a, b);
        a.bAjaxDataGet = !0;
        U(a, !1);
    }
    function Ma(a, b) {
        a = k.isPlainObject(a.ajax) && a.ajax.dataSrc !== q ? a.ajax.dataSrc : a.sAjaxDataProp;
        return "data" === a ? b.aaData || b[a] : "" !== a ? ia(a)(b) : b;
    }
    function Jb(a) {
        var b = a.oClasses,
            c = a.sTableId,
            d = a.oLanguage,
            e = a.oPreviousSearch,
            f = a.aanFeatures,
            g = '<input type="search" class="' + b.sFilterInput + '"/>',
            h = d.sSearch;
        h = h.match(/_INPUT_/) ? h.replace("_INPUT_", g) : h + g;
        b = k("<div/>", { id: f.f ? null : c + "_filter", class: b.sFilter }).append(k("<label/>").append(h));
        var l = function () {
            var m = this.value ? this.value : "";
            m != e.sSearch && (ya(a, { sSearch: m, bRegex: e.bRegex, bSmart: e.bSmart, bCaseInsensitive: e.bCaseInsensitive }), (a._iDisplayStart = 0), fa(a));
        };
        f = null !== a.searchDelay ? a.searchDelay : "ssp" === P(a) ? 400 : 0;
        var n = k("input", b)
            .val(e.sSearch)
            .attr("placeholder", d.sSearchPlaceholder)
            .on("keyup.DT search.DT input.DT paste.DT cut.DT", f ? fb(l, f) : l)
            .on("mouseup", function (m) {
                setTimeout(function () {
                    l.call(n[0]);
                }, 10);
            })
            .on("keypress.DT", function (m) {
                if (13 == m.keyCode) return !1;
            })
            .attr("aria-controls", c);
        k(a.nTable).on("search.dt.DT", function (m, p) {
            if (a === p)
                try {
                    n[0] !== z.activeElement && n.val(e.sSearch);
                } catch (t) {}
        });
        return b[0];
    }
    function ya(a, b, c) {
        var d = a.oPreviousSearch,
            e = a.aoPreSearchCols,
            f = function (h) {
                d.sSearch = h.sSearch;
                d.bRegex = h.bRegex;
                d.bSmart = h.bSmart;
                d.bCaseInsensitive = h.bCaseInsensitive;
            },
            g = function (h) {
                return h.bEscapeRegex !== q ? !h.bEscapeRegex : h.bRegex;
            };
        Ya(a);
        if ("ssp" != P(a)) {
            Qb(a, b.sSearch, c, g(b), b.bSmart, b.bCaseInsensitive);
            f(b);
            for (b = 0; b < e.length; b++) Rb(a, e[b].sSearch, b, g(e[b]), e[b].bSmart, e[b].bCaseInsensitive);
            Sb(a);
        } else f(b);
        a.bFiltered = !0;
        H(a, null, "search", [a]);
    }
    function Sb(a) {
        for (var b = u.ext.search, c = a.aiDisplay, d, e, f = 0, g = b.length; f < g; f++) {
            for (var h = [], l = 0, n = c.length; l < n; l++) (e = c[l]), (d = a.aoData[e]), b[f](a, d._aFilterData, e, d._aData, l) && h.push(e);
            c.length = 0;
            k.merge(c, h);
        }
    }
    function Rb(a, b, c, d, e, f) {
        if ("" !== b) {
            var g = [],
                h = a.aiDisplay;
            d = gb(b, d, e, f);
            for (e = 0; e < h.length; e++) (b = a.aoData[h[e]]._aFilterData[c]), d.test(b) && g.push(h[e]);
            a.aiDisplay = g;
        }
    }
    function Qb(a, b, c, d, e, f) {
        e = gb(b, d, e, f);
        var g = a.oPreviousSearch.sSearch,
            h = a.aiDisplayMaster;
        f = [];
        0 !== u.ext.search.length && (c = !0);
        var l = Tb(a);
        if (0 >= b.length) a.aiDisplay = h.slice();
        else {
            if (l || c || d || g.length > b.length || 0 !== b.indexOf(g) || a.bSorted) a.aiDisplay = h.slice();
            b = a.aiDisplay;
            for (c = 0; c < b.length; c++) e.test(a.aoData[b[c]]._sFilterRow) && f.push(b[c]);
            a.aiDisplay = f;
        }
    }
    function gb(a, b, c, d) {
        a = b ? a : hb(a);
        c &&
            (a =
                "^(?=.*?" +
                k
                    .map(a.match(/"[^"]+"|[^ ]+/g) || [""], function (e) {
                        if ('"' === e.charAt(0)) {
                            var f = e.match(/^"(.*)"$/);
                            e = f ? f[1] : e;
                        }
                        return e.replace('"', "");
                    })
                    .join(")(?=.*?") +
                ").*$");
        return new RegExp(a, d ? "i" : "");
    }
    function Tb(a) {
        var b = a.aoColumns,
            c,
            d,
            e = u.ext.type.search;
        var f = !1;
        var g = 0;
        for (c = a.aoData.length; g < c; g++) {
            var h = a.aoData[g];
            if (!h._aFilterData) {
                var l = [];
                var n = 0;
                for (d = b.length; n < d; n++) {
                    f = b[n];
                    if (f.bSearchable) {
                        var m = S(a, g, n, "filter");
                        e[f.sType] && (m = e[f.sType](m));
                        null === m && (m = "");
                        "string" !== typeof m && m.toString && (m = m.toString());
                    } else m = "";
                    m.indexOf && -1 !== m.indexOf("&") && ((Oa.innerHTML = m), (m = rc ? Oa.textContent : Oa.innerText));
                    m.replace && (m = m.replace(/[\r\n\u2028]/g, ""));
                    l.push(m);
                }
                h._aFilterData = l;
                h._sFilterRow = l.join("  ");
                f = !0;
            }
        }
        return f;
    }
    function Ub(a) {
        return { search: a.sSearch, smart: a.bSmart, regex: a.bRegex, caseInsensitive: a.bCaseInsensitive };
    }
    function Vb(a) {
        return { sSearch: a.search, bSmart: a.smart, bRegex: a.regex, bCaseInsensitive: a.caseInsensitive };
    }
    function Mb(a) {
        var b = a.sTableId,
            c = a.aanFeatures.i,
            d = k("<div/>", { class: a.oClasses.sInfo, id: c ? null : b + "_info" });
        c || (a.aoDrawCallback.push({ fn: Wb, sName: "information" }), d.attr("role", "status").attr("aria-live", "polite"), k(a.nTable).attr("aria-describedby", b + "_info"));
        return d[0];
    }
    function Wb(a) {
        var b = a.aanFeatures.i;
        if (0 !== b.length) {
            var c = a.oLanguage,
                d = a._iDisplayStart + 1,
                e = a.fnDisplayEnd(),
                f = a.fnRecordsTotal(),
                g = a.fnRecordsDisplay(),
                h = g ? c.sInfo : c.sInfoEmpty;
            g !== f && (h += " " + c.sInfoFiltered);
            h += c.sInfoPostFix;
            h = Xb(a, h);
            c = c.fnInfoCallback;
            null !== c && (h = c.call(a.oInstance, a, d, e, f, g, h));
            k(b).html(h);
        }
    }
    function Xb(a, b) {
        var c = a.fnFormatNumber,
            d = a._iDisplayStart + 1,
            e = a._iDisplayLength,
            f = a.fnRecordsDisplay(),
            g = -1 === e;
        return b
            .replace(/_START_/g, c.call(a, d))
            .replace(/_END_/g, c.call(a, a.fnDisplayEnd()))
            .replace(/_MAX_/g, c.call(a, a.fnRecordsTotal()))
            .replace(/_TOTAL_/g, c.call(a, f))
            .replace(/_PAGE_/g, c.call(a, g ? 1 : Math.ceil(d / e)))
            .replace(/_PAGES_/g, c.call(a, g ? 1 : Math.ceil(f / e)));
    }
    function za(a) {
        var b = a.iInitDisplayStart,
            c = a.aoColumns;
        var d = a.oFeatures;
        var e = a.bDeferLoading;
        if (a.bInitialised) {
            Hb(a);
            Eb(a);
            xa(a, a.aoHeader);
            xa(a, a.aoFooter);
            U(a, !0);
            d.bAutoWidth && Xa(a);
            var f = 0;
            for (d = c.length; f < d; f++) {
                var g = c[f];
                g.sWidth && (g.nTh.style.width = K(g.sWidth));
            }
            H(a, null, "preInit", [a]);
            ja(a);
            c = P(a);
            if ("ssp" != c || e)
                "ajax" == c
                    ? La(
                          a,
                          [],
                          function (h) {
                              var l = Ma(a, h);
                              for (f = 0; f < l.length; f++) ea(a, l[f]);
                              a.iInitDisplayStart = b;
                              ja(a);
                              U(a, !1);
                              Na(a, h);
                          },
                          a
                      )
                    : (U(a, !1), Na(a));
        } else
            setTimeout(function () {
                za(a);
            }, 200);
    }
    function Na(a, b) {
        a._bInitComplete = !0;
        (b || a.oInit.aaData) && ra(a);
        H(a, null, "plugin-init", [a, b]);
        H(a, "aoInitComplete", "init", [a, b]);
    }
    function ib(a, b) {
        b = parseInt(b, 10);
        a._iDisplayLength = b;
        jb(a);
        H(a, null, "length", [a, b]);
    }
    function Ib(a) {
        var b = a.oClasses,
            c = a.sTableId,
            d = a.aLengthMenu,
            e = Array.isArray(d[0]),
            f = e ? d[0] : d;
        d = e ? d[1] : d;
        e = k("<select/>", { name: c + "_length", "aria-controls": c, class: b.sLengthSelect });
        for (var g = 0, h = f.length; g < h; g++) e[0][g] = new Option("number" === typeof d[g] ? a.fnFormatNumber(d[g]) : d[g], f[g]);
        var l = k("<div><label/></div>").addClass(b.sLength);
        a.aanFeatures.l || (l[0].id = c + "_length");
        l.children().append(a.oLanguage.sLengthMenu.replace("_MENU_", e[0].outerHTML));
        k("select", l)
            .val(a._iDisplayLength)
            .on("change.DT", function (n) {
                ib(a, k(this).val());
                fa(a);
            });
        k(a.nTable).on("length.dt.DT", function (n, m, p) {
            a === m && k("select", l).val(p);
        });
        return l[0];
    }
    function Nb(a) {
        var b = a.sPaginationType,
            c = u.ext.pager[b],
            d = "function" === typeof c,
            e = function (g) {
                fa(g);
            };
        b = k("<div/>").addClass(a.oClasses.sPaging + b)[0];
        var f = a.aanFeatures;
        d || c.fnInit(a, b, e);
        f.p ||
            ((b.id = a.sTableId + "_paginate"),
            a.aoDrawCallback.push({
                fn: function (g) {
                    if (d) {
                        var h = g._iDisplayStart,
                            l = g._iDisplayLength,
                            n = g.fnRecordsDisplay(),
                            m = -1 === l;
                        h = m ? 0 : Math.ceil(h / l);
                        l = m ? 1 : Math.ceil(n / l);
                        n = c(h, l);
                        var p;
                        m = 0;
                        for (p = f.p.length; m < p; m++) eb(g, "pageButton")(g, f.p[m], m, n, h, l);
                    } else c.fnUpdate(g, e);
                },
                sName: "pagination",
            }));
        return b;
    }
    function kb(a, b, c) {
        var d = a._iDisplayStart,
            e = a._iDisplayLength,
            f = a.fnRecordsDisplay();
        0 === f || -1 === e
            ? (d = 0)
            : "number" === typeof b
            ? ((d = b * e), d > f && (d = 0))
            : "first" == b
            ? (d = 0)
            : "previous" == b
            ? ((d = 0 <= e ? d - e : 0), 0 > d && (d = 0))
            : "next" == b
            ? d + e < f && (d += e)
            : "last" == b
            ? (d = Math.floor((f - 1) / e) * e)
            : aa(a, 0, "Unknown paging action: " + b, 5);
        b = a._iDisplayStart !== d;
        a._iDisplayStart = d;
        b && (H(a, null, "page", [a]), c && fa(a));
        return b;
    }
    function Kb(a) {
        return k("<div/>", { id: a.aanFeatures.r ? null : a.sTableId + "_processing", class: a.oClasses.sProcessing })
            .html(a.oLanguage.sProcessing)
            .insertBefore(a.nTable)[0];
    }
    function U(a, b) {
        a.oFeatures.bProcessing && k(a.aanFeatures.r).css("display", b ? "block" : "none");
        H(a, null, "processing", [a, b]);
    }
    function Lb(a) {
        var b = k(a.nTable);
        b.attr("role", "grid");
        var c = a.oScroll;
        if ("" === c.sX && "" === c.sY) return a.nTable;
        var d = c.sX,
            e = c.sY,
            f = a.oClasses,
            g = b.children("caption"),
            h = g.length ? g[0]._captionSide : null,
            l = k(b[0].cloneNode(!1)),
            n = k(b[0].cloneNode(!1)),
            m = b.children("tfoot");
        m.length || (m = null);
        l = k("<div/>", { class: f.sScrollWrapper })
            .append(
                k("<div/>", { class: f.sScrollHead })
                    .css({ overflow: "hidden", position: "relative", border: 0, width: d ? (d ? K(d) : null) : "100%" })
                    .append(
                        k("<div/>", { class: f.sScrollHeadInner })
                            .css({ "box-sizing": "content-box", width: c.sXInner || "100%" })
                            .append(
                                l
                                    .removeAttr("id")
                                    .css("margin-left", 0)
                                    .append("top" === h ? g : null)
                                    .append(b.children("thead"))
                            )
                    )
            )
            .append(
                k("<div/>", { class: f.sScrollBody })
                    .css({ position: "relative", overflow: "auto", width: d ? K(d) : null })
                    .append(b)
            );
        m &&
            l.append(
                k("<div/>", { class: f.sScrollFoot })
                    .css({ overflow: "hidden", border: 0, width: d ? (d ? K(d) : null) : "100%" })
                    .append(
                        k("<div/>", { class: f.sScrollFootInner }).append(
                            n
                                .removeAttr("id")
                                .css("margin-left", 0)
                                .append("bottom" === h ? g : null)
                                .append(b.children("tfoot"))
                        )
                    )
            );
        b = l.children();
        var p = b[0];
        f = b[1];
        var t = m ? b[2] : null;
        if (d)
            k(f).on("scroll.DT", function (v) {
                v = this.scrollLeft;
                p.scrollLeft = v;
                m && (t.scrollLeft = v);
            });
        k(f).css("max-height", e);
        c.bCollapse || k(f).css("height", e);
        a.nScrollHead = p;
        a.nScrollBody = f;
        a.nScrollFoot = t;
        a.aoDrawCallback.push({ fn: Ea, sName: "scrolling" });
        return l[0];
    }
    function Ea(a) {
        var b = a.oScroll,
            c = b.sX,
            d = b.sXInner,
            e = b.sY;
        b = b.iBarWidth;
        var f = k(a.nScrollHead),
            g = f[0].style,
            h = f.children("div"),
            l = h[0].style,
            n = h.children("table");
        h = a.nScrollBody;
        var m = k(h),
            p = h.style,
            t = k(a.nScrollFoot).children("div"),
            v = t.children("table"),
            x = k(a.nTHead),
            r = k(a.nTable),
            A = r[0],
            E = A.style,
            I = a.nTFoot ? k(a.nTFoot) : null,
            W = a.oBrowser,
            M = W.bScrollOversize,
            C = T(a.aoColumns, "nTh"),
            B = [],
            ba = [],
            X = [],
            lb = [],
            Aa,
            Yb = function (F) {
                F = F.style;
                F.paddingTop = "0";
                F.paddingBottom = "0";
                F.borderTopWidth = "0";
                F.borderBottomWidth = "0";
                F.height = 0;
            };
        var ha = h.scrollHeight > h.clientHeight;
        if (a.scrollBarVis !== ha && a.scrollBarVis !== q) (a.scrollBarVis = ha), ra(a);
        else {
            a.scrollBarVis = ha;
            r.children("thead, tfoot").remove();
            if (I) {
                var ka = I.clone().prependTo(r);
                var la = I.find("tr");
                ka = ka.find("tr");
            }
            var mb = x.clone().prependTo(r);
            x = x.find("tr");
            ha = mb.find("tr");
            mb.find("th, td").removeAttr("tabindex");
            c || ((p.width = "100%"), (f[0].style.width = "100%"));
            k.each(Ka(a, mb), function (F, Y) {
                Aa = sa(a, F);
                Y.style.width = a.aoColumns[Aa].sWidth;
            });
            I &&
                Z(function (F) {
                    F.style.width = "";
                }, ka);
            f = r.outerWidth();
            "" === c
                ? ((E.width = "100%"), M && (r.find("tbody").height() > h.offsetHeight || "scroll" == m.css("overflow-y")) && (E.width = K(r.outerWidth() - b)), (f = r.outerWidth()))
                : "" !== d && ((E.width = K(d)), (f = r.outerWidth()));
            Z(Yb, ha);
            Z(function (F) {
                X.push(F.innerHTML);
                B.push(K(k(F).css("width")));
            }, ha);
            Z(function (F, Y) {
                -1 !== k.inArray(F, C) && (F.style.width = B[Y]);
            }, x);
            k(ha).height(0);
            I &&
                (Z(Yb, ka),
                Z(function (F) {
                    lb.push(F.innerHTML);
                    ba.push(K(k(F).css("width")));
                }, ka),
                Z(function (F, Y) {
                    F.style.width = ba[Y];
                }, la),
                k(ka).height(0));
            Z(function (F, Y) {
                F.innerHTML = '<div class="dataTables_sizing">' + X[Y] + "</div>";
                F.childNodes[0].style.height = "0";
                F.childNodes[0].style.overflow = "hidden";
                F.style.width = B[Y];
            }, ha);
            I &&
                Z(function (F, Y) {
                    F.innerHTML = '<div class="dataTables_sizing">' + lb[Y] + "</div>";
                    F.childNodes[0].style.height = "0";
                    F.childNodes[0].style.overflow = "hidden";
                    F.style.width = ba[Y];
                }, ka);
            r.outerWidth() < f
                ? ((la = h.scrollHeight > h.offsetHeight || "scroll" == m.css("overflow-y") ? f + b : f),
                  M && (h.scrollHeight > h.offsetHeight || "scroll" == m.css("overflow-y")) && (E.width = K(la - b)),
                  ("" !== c && "" === d) || aa(a, 1, "Possible column misalignment", 6))
                : (la = "100%");
            p.width = K(la);
            g.width = K(la);
            I && (a.nScrollFoot.style.width = K(la));
            !e && M && (p.height = K(A.offsetHeight + b));
            c = r.outerWidth();
            n[0].style.width = K(c);
            l.width = K(c);
            d = r.height() > h.clientHeight || "scroll" == m.css("overflow-y");
            e = "padding" + (W.bScrollbarLeft ? "Left" : "Right");
            l[e] = d ? b + "px" : "0px";
            I && ((v[0].style.width = K(c)), (t[0].style.width = K(c)), (t[0].style[e] = d ? b + "px" : "0px"));
            r.children("colgroup").insertBefore(r.children("thead"));
            m.trigger("scroll");
            (!a.bSorted && !a.bFiltered) || a._drawHold || (h.scrollTop = 0);
        }
    }
    function Z(a, b, c) {
        for (var d = 0, e = 0, f = b.length, g, h; e < f; ) {
            g = b[e].firstChild;
            for (h = c ? c[e].firstChild : null; g; ) 1 === g.nodeType && (c ? a(g, h, d) : a(g, d), d++), (g = g.nextSibling), (h = c ? h.nextSibling : null);
            e++;
        }
    }
    function Xa(a) {
        var b = a.nTable,
            c = a.aoColumns,
            d = a.oScroll,
            e = d.sY,
            f = d.sX,
            g = d.sXInner,
            h = c.length,
            l = Fa(a, "bVisible"),
            n = k("th", a.nTHead),
            m = b.getAttribute("width"),
            p = b.parentNode,
            t = !1,
            v,
            x = a.oBrowser;
        d = x.bScrollOversize;
        (v = b.style.width) && -1 !== v.indexOf("%") && (m = v);
        for (v = 0; v < l.length; v++) {
            var r = c[l[v]];
            null !== r.sWidth && ((r.sWidth = Zb(r.sWidthOrig, p)), (t = !0));
        }
        if (d || (!t && !f && !e && h == na(a) && h == n.length)) for (v = 0; v < h; v++) (l = sa(a, v)), null !== l && (c[l].sWidth = K(n.eq(v).width()));
        else {
            h = k(b).clone().css("visibility", "hidden").removeAttr("id");
            h.find("tbody tr").remove();
            var A = k("<tr/>").appendTo(h.find("tbody"));
            h.find("thead, tfoot").remove();
            h.append(k(a.nTHead).clone()).append(k(a.nTFoot).clone());
            h.find("tfoot th, tfoot td").css("width", "");
            n = Ka(a, h.find("thead")[0]);
            for (v = 0; v < l.length; v++)
                (r = c[l[v]]),
                    (n[v].style.width = null !== r.sWidthOrig && "" !== r.sWidthOrig ? K(r.sWidthOrig) : ""),
                    r.sWidthOrig && f && k(n[v]).append(k("<div/>").css({ width: r.sWidthOrig, margin: 0, padding: 0, border: 0, height: 1 }));
            if (a.aoData.length) for (v = 0; v < l.length; v++) (t = l[v]), (r = c[t]), k($b(a, t)).clone(!1).append(r.sContentPadding).appendTo(A);
            k("[name]", h).removeAttr("name");
            r = k("<div/>")
                .css(f || e ? { position: "absolute", top: 0, left: 0, height: 1, right: 0, overflow: "hidden" } : {})
                .append(h)
                .appendTo(p);
            f && g ? h.width(g) : f ? (h.css("width", "auto"), h.removeAttr("width"), h.width() < p.clientWidth && m && h.width(p.clientWidth)) : e ? h.width(p.clientWidth) : m && h.width(m);
            for (v = e = 0; v < l.length; v++) (p = k(n[v])), (g = p.outerWidth() - p.width()), (p = x.bBounding ? Math.ceil(n[v].getBoundingClientRect().width) : p.outerWidth()), (e += p), (c[l[v]].sWidth = K(p - g));
            b.style.width = K(e);
            r.remove();
        }
        m && (b.style.width = K(m));
        (!m && !f) ||
            a._reszEvt ||
            ((b = function () {
                k(y).on(
                    "resize.DT-" + a.sInstance,
                    fb(function () {
                        ra(a);
                    })
                );
            }),
            d ? setTimeout(b, 1e3) : b(),
            (a._reszEvt = !0));
    }
    function Zb(a, b) {
        if (!a) return 0;
        a = k("<div/>")
            .css("width", K(a))
            .appendTo(b || z.body);
        b = a[0].offsetWidth;
        a.remove();
        return b;
    }
    function $b(a, b) {
        var c = ac(a, b);
        if (0 > c) return null;
        var d = a.aoData[c];
        return d.nTr ? d.anCells[b] : k("<td/>").html(S(a, c, b, "display"))[0];
    }
    function ac(a, b) {
        for (var c, d = -1, e = -1, f = 0, g = a.aoData.length; f < g; f++) (c = S(a, f, b, "display") + ""), (c = c.replace(sc, "")), (c = c.replace(/&nbsp;/g, " ")), c.length > d && ((d = c.length), (e = f));
        return e;
    }
    function K(a) {
        return null === a ? "0px" : "number" == typeof a ? (0 > a ? "0px" : a + "px") : a.match(/\d$/) ? a + "px" : a;
    }
    function pa(a) {
        var b = [],
            c = a.aoColumns;
        var d = a.aaSortingFixed;
        var e = k.isPlainObject(d);
        var f = [];
        var g = function (m) {
            m.length && !Array.isArray(m[0]) ? f.push(m) : k.merge(f, m);
        };
        Array.isArray(d) && g(d);
        e && d.pre && g(d.pre);
        g(a.aaSorting);
        e && d.post && g(d.post);
        for (a = 0; a < f.length; a++) {
            var h = f[a][0];
            g = c[h].aDataSort;
            d = 0;
            for (e = g.length; d < e; d++) {
                var l = g[d];
                var n = c[l].sType || "string";
                f[a]._idx === q && (f[a]._idx = k.inArray(f[a][1], c[l].asSorting));
                b.push({ src: h, col: l, dir: f[a][1], index: f[a]._idx, type: n, formatter: u.ext.type.order[n + "-pre"] });
            }
        }
        return b;
    }
    function Gb(a) {
        var b,
            c = [],
            d = u.ext.type.order,
            e = a.aoData,
            f = 0,
            g = a.aiDisplayMaster;
        Ya(a);
        var h = pa(a);
        var l = 0;
        for (b = h.length; l < b; l++) {
            var n = h[l];
            n.formatter && f++;
            bc(a, n.col);
        }
        if ("ssp" != P(a) && 0 !== h.length) {
            l = 0;
            for (b = g.length; l < b; l++) c[g[l]] = l;
            f === h.length
                ? g.sort(function (m, p) {
                      var t,
                          v = h.length,
                          x = e[m]._aSortData,
                          r = e[p]._aSortData;
                      for (t = 0; t < v; t++) {
                          var A = h[t];
                          var E = x[A.col];
                          var I = r[A.col];
                          E = E < I ? -1 : E > I ? 1 : 0;
                          if (0 !== E) return "asc" === A.dir ? E : -E;
                      }
                      E = c[m];
                      I = c[p];
                      return E < I ? -1 : E > I ? 1 : 0;
                  })
                : g.sort(function (m, p) {
                      var t,
                          v = h.length,
                          x = e[m]._aSortData,
                          r = e[p]._aSortData;
                      for (t = 0; t < v; t++) {
                          var A = h[t];
                          var E = x[A.col];
                          var I = r[A.col];
                          A = d[A.type + "-" + A.dir] || d["string-" + A.dir];
                          E = A(E, I);
                          if (0 !== E) return E;
                      }
                      E = c[m];
                      I = c[p];
                      return E < I ? -1 : E > I ? 1 : 0;
                  });
        }
        a.bSorted = !0;
    }
    function cc(a) {
        var b = a.aoColumns,
            c = pa(a);
        a = a.oLanguage.oAria;
        for (var d = 0, e = b.length; d < e; d++) {
            var f = b[d];
            var g = f.asSorting;
            var h = f.sTitle.replace(/<.*?>/g, "");
            var l = f.nTh;
            l.removeAttribute("aria-sort");
            f.bSortable &&
                (0 < c.length && c[0].col == d ? (l.setAttribute("aria-sort", "asc" == c[0].dir ? "ascending" : "descending"), (f = g[c[0].index + 1] || g[0])) : (f = g[0]), (h += "asc" === f ? a.sSortAscending : a.sSortDescending));
            l.setAttribute("aria-label", h);
        }
    }
    function nb(a, b, c, d) {
        var e = a.aaSorting,
            f = a.aoColumns[b].asSorting,
            g = function (h, l) {
                var n = h._idx;
                n === q && (n = k.inArray(h[1], f));
                return n + 1 < f.length ? n + 1 : l ? null : 0;
            };
        "number" === typeof e[0] && (e = a.aaSorting = [e]);
        c && a.oFeatures.bSortMulti
            ? ((c = k.inArray(b, T(e, "0"))), -1 !== c ? ((b = g(e[c], !0)), null === b && 1 === e.length && (b = 0), null === b ? e.splice(c, 1) : ((e[c][1] = f[b]), (e[c]._idx = b))) : (e.push([b, f[0], 0]), (e[e.length - 1]._idx = 0)))
            : e.length && e[0][0] == b
            ? ((b = g(e[0])), (e.length = 1), (e[0][1] = f[b]), (e[0]._idx = b))
            : ((e.length = 0), e.push([b, f[0]]), (e[0]._idx = 0));
        ja(a);
        "function" == typeof d && d(a);
    }
    function db(a, b, c, d) {
        var e = a.aoColumns[c];
        ob(b, {}, function (f) {
            !1 !== e.bSortable &&
                (a.oFeatures.bProcessing
                    ? (U(a, !0),
                      setTimeout(function () {
                          nb(a, c, f.shiftKey, d);
                          "ssp" !== P(a) && U(a, !1);
                      }, 0))
                    : nb(a, c, f.shiftKey, d));
        });
    }
    function Pa(a) {
        var b = a.aLastSort,
            c = a.oClasses.sSortColumn,
            d = pa(a),
            e = a.oFeatures,
            f;
        if (e.bSort && e.bSortClasses) {
            e = 0;
            for (f = b.length; e < f; e++) {
                var g = b[e].src;
                k(T(a.aoData, "anCells", g)).removeClass(c + (2 > e ? e + 1 : 3));
            }
            e = 0;
            for (f = d.length; e < f; e++) (g = d[e].src), k(T(a.aoData, "anCells", g)).addClass(c + (2 > e ? e + 1 : 3));
        }
        a.aLastSort = d;
    }
    function bc(a, b) {
        var c = a.aoColumns[b],
            d = u.ext.order[c.sSortDataType],
            e;
        d && (e = d.call(a.oInstance, a, b, ta(a, b)));
        for (var f, g = u.ext.type.order[c.sType + "-pre"], h = 0, l = a.aoData.length; h < l; h++)
            if (((c = a.aoData[h]), c._aSortData || (c._aSortData = []), !c._aSortData[b] || d)) (f = d ? e[h] : S(a, h, b, "sort")), (c._aSortData[b] = g ? g(f) : f);
    }
    function Qa(a) {
        if (a.oFeatures.bStateSave && !a.bDestroying) {
            var b = {
                time: +new Date(),
                start: a._iDisplayStart,
                length: a._iDisplayLength,
                order: k.extend(!0, [], a.aaSorting),
                search: Ub(a.oPreviousSearch),
                columns: k.map(a.aoColumns, function (c, d) {
                    return { visible: c.bVisible, search: Ub(a.aoPreSearchCols[d]) };
                }),
            };
            H(a, "aoStateSaveParams", "stateSaveParams", [a, b]);
            a.oSavedState = b;
            a.fnStateSaveCallback.call(a.oInstance, a, b);
        }
    }
    function dc(a, b, c) {
        var d,
            e,
            f = a.aoColumns;
        b = function (h) {
            if (h && h.time) {
                var l = H(a, "aoStateLoadParams", "stateLoadParams", [a, h]);
                if (-1 === k.inArray(!1, l) && ((l = a.iStateDuration), !((0 < l && h.time < +new Date() - 1e3 * l) || (h.columns && f.length !== h.columns.length)))) {
                    a.oLoadedState = k.extend(!0, {}, h);
                    h.start !== q && ((a._iDisplayStart = h.start), (a.iInitDisplayStart = h.start));
                    h.length !== q && (a._iDisplayLength = h.length);
                    h.order !== q &&
                        ((a.aaSorting = []),
                        k.each(h.order, function (n, m) {
                            a.aaSorting.push(m[0] >= f.length ? [0, m[1]] : m);
                        }));
                    h.search !== q && k.extend(a.oPreviousSearch, Vb(h.search));
                    if (h.columns) for (d = 0, e = h.columns.length; d < e; d++) (l = h.columns[d]), l.visible !== q && (f[d].bVisible = l.visible), l.search !== q && k.extend(a.aoPreSearchCols[d], Vb(l.search));
                    H(a, "aoStateLoaded", "stateLoaded", [a, h]);
                }
            }
            c();
        };
        if (a.oFeatures.bStateSave) {
            var g = a.fnStateLoadCallback.call(a.oInstance, a, b);
            g !== q && b(g);
        } else c();
    }
    function Ra(a) {
        var b = u.settings;
        a = k.inArray(a, T(b, "nTable"));
        return -1 !== a ? b[a] : null;
    }
    function aa(a, b, c, d) {
        c = "DataTables warning: " + (a ? "table id=" + a.sTableId + " - " : "") + c;
        d && (c += ". For more information about this error, please see http://datatables.net/tn/" + d);
        if (b) y.console && console.log && console.log(c);
        else if (((b = u.ext), (b = b.sErrMode || b.errMode), a && H(a, null, "error", [a, d, c]), "alert" == b)) alert(c);
        else {
            if ("throw" == b) throw Error(c);
            "function" == typeof b && b(a, d, c);
        }
    }
    function V(a, b, c, d) {
        Array.isArray(c)
            ? k.each(c, function (e, f) {
                  Array.isArray(f) ? V(a, b, f[0], f[1]) : V(a, b, f);
              })
            : (d === q && (d = c), b[c] !== q && (a[d] = b[c]));
    }
    function pb(a, b, c) {
        var d;
        for (d in b)
            if (b.hasOwnProperty(d)) {
                var e = b[d];
                k.isPlainObject(e) ? (k.isPlainObject(a[d]) || (a[d] = {}), k.extend(!0, a[d], e)) : c && "data" !== d && "aaData" !== d && Array.isArray(e) ? (a[d] = e.slice()) : (a[d] = e);
            }
        return a;
    }
    function ob(a, b, c) {
        k(a)
            .on("click.DT", b, function (d) {
                k(a).trigger("blur");
                c(d);
            })
            .on("keypress.DT", b, function (d) {
                13 === d.which && (d.preventDefault(), c(d));
            })
            .on("selectstart.DT", function () {
                return !1;
            });
    }
    function Q(a, b, c, d) {
        c && a[b].push({ fn: c, sName: d });
    }
    function H(a, b, c, d) {
        var e = [];
        b &&
            (e = k.map(a[b].slice().reverse(), function (f, g) {
                return f.fn.apply(a.oInstance, d);
            }));
        null !== c && ((b = k.Event(c + ".dt")), k(a.nTable).trigger(b, d), e.push(b.result));
        return e;
    }
    function jb(a) {
        var b = a._iDisplayStart,
            c = a.fnDisplayEnd(),
            d = a._iDisplayLength;
        b >= c && (b = c - d);
        b -= b % d;
        if (-1 === d || 0 > b) b = 0;
        a._iDisplayStart = b;
    }
    function eb(a, b) {
        a = a.renderer;
        var c = u.ext.renderer[b];
        return k.isPlainObject(a) && a[b] ? c[a[b]] || c._ : "string" === typeof a ? c[a] || c._ : c._;
    }
    function P(a) {
        return a.oFeatures.bServerSide ? "ssp" : a.ajax || a.sAjaxSource ? "ajax" : "dom";
    }
    function Ba(a, b) {
        var c = ec.numbers_length,
            d = Math.floor(c / 2);
        b <= c
            ? (a = qa(0, b))
            : a <= d
            ? ((a = qa(0, c - 2)), a.push("ellipsis"), a.push(b - 1))
            : (a >= b - 1 - d ? (a = qa(b - (c - 2), b)) : ((a = qa(a - d + 2, a + d - 1)), a.push("ellipsis"), a.push(b - 1)), a.splice(0, 0, "ellipsis"), a.splice(0, 0, 0));
        a.DT_el = "span";
        return a;
    }
    function Va(a) {
        k.each(
            {
                num: function (b) {
                    return Sa(b, a);
                },
                "num-fmt": function (b) {
                    return Sa(b, a, qb);
                },
                "html-num": function (b) {
                    return Sa(b, a, Ta);
                },
                "html-num-fmt": function (b) {
                    return Sa(b, a, Ta, qb);
                },
            },
            function (b, c) {
                L.type.order[b + a + "-pre"] = c;
                b.match(/^html\-/) && (L.type.search[b + a] = L.type.search.html);
            }
        );
    }
    function fc(a) {
        return function () {
            var b = [Ra(this[u.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));
            return u.ext.internal[a].apply(this, b);
        };
    }
    var u = function (a) {
            this.$ = function (f, g) {
                return this.api(!0).$(f, g);
            };
            this._ = function (f, g) {
                return this.api(!0).rows(f, g).data();
            };
            this.api = function (f) {
                return f ? new D(Ra(this[L.iApiIndex])) : new D(this);
            };
            this.fnAddData = function (f, g) {
                var h = this.api(!0);
                f = Array.isArray(f) && (Array.isArray(f[0]) || k.isPlainObject(f[0])) ? h.rows.add(f) : h.row.add(f);
                (g === q || g) && h.draw();
                return f.flatten().toArray();
            };
            this.fnAdjustColumnSizing = function (f) {
                var g = this.api(!0).columns.adjust(),
                    h = g.settings()[0],
                    l = h.oScroll;
                f === q || f ? g.draw(!1) : ("" !== l.sX || "" !== l.sY) && Ea(h);
            };
            this.fnClearTable = function (f) {
                var g = this.api(!0).clear();
                (f === q || f) && g.draw();
            };
            this.fnClose = function (f) {
                this.api(!0).row(f).child.hide();
            };
            this.fnDeleteRow = function (f, g, h) {
                var l = this.api(!0);
                f = l.rows(f);
                var n = f.settings()[0],
                    m = n.aoData[f[0][0]];
                f.remove();
                g && g.call(this, n, m);
                (h === q || h) && l.draw();
                return m;
            };
            this.fnDestroy = function (f) {
                this.api(!0).destroy(f);
            };
            this.fnDraw = function (f) {
                this.api(!0).draw(f);
            };
            this.fnFilter = function (f, g, h, l, n, m) {
                n = this.api(!0);
                null === g || g === q ? n.search(f, h, l, m) : n.column(g).search(f, h, l, m);
                n.draw();
            };
            this.fnGetData = function (f, g) {
                var h = this.api(!0);
                if (f !== q) {
                    var l = f.nodeName ? f.nodeName.toLowerCase() : "";
                    return g !== q || "td" == l || "th" == l ? h.cell(f, g).data() : h.row(f).data() || null;
                }
                return h.data().toArray();
            };
            this.fnGetNodes = function (f) {
                var g = this.api(!0);
                return f !== q ? g.row(f).node() : g.rows().nodes().flatten().toArray();
            };
            this.fnGetPosition = function (f) {
                var g = this.api(!0),
                    h = f.nodeName.toUpperCase();
                return "TR" == h ? g.row(f).index() : "TD" == h || "TH" == h ? ((f = g.cell(f).index()), [f.row, f.columnVisible, f.column]) : null;
            };
            this.fnIsOpen = function (f) {
                return this.api(!0).row(f).child.isShown();
            };
            this.fnOpen = function (f, g, h) {
                return this.api(!0).row(f).child(g, h).show().child()[0];
            };
            this.fnPageChange = function (f, g) {
                f = this.api(!0).page(f);
                (g === q || g) && f.draw(!1);
            };
            this.fnSetColumnVis = function (f, g, h) {
                f = this.api(!0).column(f).visible(g);
                (h === q || h) && f.columns.adjust().draw();
            };
            this.fnSettings = function () {
                return Ra(this[L.iApiIndex]);
            };
            this.fnSort = function (f) {
                this.api(!0).order(f).draw();
            };
            this.fnSortListener = function (f, g, h) {
                this.api(!0).order.listener(f, g, h);
            };
            this.fnUpdate = function (f, g, h, l, n) {
                var m = this.api(!0);
                h === q || null === h ? m.row(g).data(f) : m.cell(g, h).data(f);
                (n === q || n) && m.columns.adjust();
                (l === q || l) && m.draw();
                return 0;
            };
            this.fnVersionCheck = L.fnVersionCheck;
            var b = this,
                c = a === q,
                d = this.length;
            c && (a = {});
            this.oApi = this.internal = L.internal;
            for (var e in u.ext.internal) e && (this[e] = fc(e));
            this.each(function () {
                var f = {},
                    g = 1 < d ? pb(f, a, !0) : a,
                    h = 0,
                    l;
                f = this.getAttribute("id");
                var n = !1,
                    m = u.defaults,
                    p = k(this);
                if ("table" != this.nodeName.toLowerCase()) aa(null, 0, "Non-table node initialisation (" + this.nodeName + ")", 2);
                else {
                    yb(m);
                    zb(m.column);
                    O(m, m, !0);
                    O(m.column, m.column, !0);
                    O(m, k.extend(g, p.data()), !0);
                    var t = u.settings;
                    h = 0;
                    for (l = t.length; h < l; h++) {
                        var v = t[h];
                        if (v.nTable == this || (v.nTHead && v.nTHead.parentNode == this) || (v.nTFoot && v.nTFoot.parentNode == this)) {
                            var x = g.bRetrieve !== q ? g.bRetrieve : m.bRetrieve;
                            if (c || x) return v.oInstance;
                            if (g.bDestroy !== q ? g.bDestroy : m.bDestroy) {
                                v.oInstance.fnDestroy();
                                break;
                            } else {
                                aa(v, 0, "Cannot reinitialise DataTable", 3);
                                return;
                            }
                        }
                        if (v.sTableId == this.id) {
                            t.splice(h, 1);
                            break;
                        }
                    }
                    if (null === f || "" === f) this.id = f = "DataTables_Table_" + u.ext._unique++;
                    var r = k.extend(!0, {}, u.models.oSettings, { sDestroyWidth: p[0].style.width, sInstance: f, sTableId: f });
                    r.nTable = this;
                    r.oApi = b.internal;
                    r.oInit = g;
                    t.push(r);
                    r.oInstance = 1 === b.length ? b : p.dataTable();
                    yb(g);
                    ma(g.oLanguage);
                    g.aLengthMenu && !g.iDisplayLength && (g.iDisplayLength = Array.isArray(g.aLengthMenu[0]) ? g.aLengthMenu[0][0] : g.aLengthMenu[0]);
                    g = pb(k.extend(!0, {}, m), g);
                    V(r.oFeatures, g, "bPaginate bLengthChange bFilter bSort bSortMulti bInfo bProcessing bAutoWidth bSortClasses bServerSide bDeferRender".split(" "));
                    V(r, g, [
                        "asStripeClasses",
                        "ajax",
                        "fnServerData",
                        "fnFormatNumber",
                        "sServerMethod",
                        "aaSorting",
                        "aaSortingFixed",
                        "aLengthMenu",
                        "sPaginationType",
                        "sAjaxSource",
                        "sAjaxDataProp",
                        "iStateDuration",
                        "sDom",
                        "bSortCellsTop",
                        "iTabIndex",
                        "fnStateLoadCallback",
                        "fnStateSaveCallback",
                        "renderer",
                        "searchDelay",
                        "rowId",
                        ["iCookieDuration", "iStateDuration"],
                        ["oSearch", "oPreviousSearch"],
                        ["aoSearchCols", "aoPreSearchCols"],
                        ["iDisplayLength", "_iDisplayLength"],
                    ]);
                    V(r.oScroll, g, [
                        ["sScrollX", "sX"],
                        ["sScrollXInner", "sXInner"],
                        ["sScrollY", "sY"],
                        ["bScrollCollapse", "bCollapse"],
                    ]);
                    V(r.oLanguage, g, "fnInfoCallback");
                    Q(r, "aoDrawCallback", g.fnDrawCallback, "user");
                    Q(r, "aoServerParams", g.fnServerParams, "user");
                    Q(r, "aoStateSaveParams", g.fnStateSaveParams, "user");
                    Q(r, "aoStateLoadParams", g.fnStateLoadParams, "user");
                    Q(r, "aoStateLoaded", g.fnStateLoaded, "user");
                    Q(r, "aoRowCallback", g.fnRowCallback, "user");
                    Q(r, "aoRowCreatedCallback", g.fnCreatedRow, "user");
                    Q(r, "aoHeaderCallback", g.fnHeaderCallback, "user");
                    Q(r, "aoFooterCallback", g.fnFooterCallback, "user");
                    Q(r, "aoInitComplete", g.fnInitComplete, "user");
                    Q(r, "aoPreDrawCallback", g.fnPreDrawCallback, "user");
                    r.rowIdFn = ia(g.rowId);
                    Ab(r);
                    var A = r.oClasses;
                    k.extend(A, u.ext.classes, g.oClasses);
                    p.addClass(A.sTable);
                    r.iInitDisplayStart === q && ((r.iInitDisplayStart = g.iDisplayStart), (r._iDisplayStart = g.iDisplayStart));
                    null !== g.iDeferLoading && ((r.bDeferLoading = !0), (f = Array.isArray(g.iDeferLoading)), (r._iRecordsDisplay = f ? g.iDeferLoading[0] : g.iDeferLoading), (r._iRecordsTotal = f ? g.iDeferLoading[1] : g.iDeferLoading));
                    var E = r.oLanguage;
                    k.extend(!0, E, g.oLanguage);
                    E.sUrl
                        ? (k.ajax({
                              dataType: "json",
                              url: E.sUrl,
                              success: function (C) {
                                  ma(C);
                                  O(m.oLanguage, C);
                                  k.extend(!0, E, C);
                                  H(r, null, "i18n", [r]);
                                  za(r);
                              },
                              error: function () {
                                  za(r);
                              },
                          }),
                          (n = !0))
                        : H(r, null, "i18n", [r]);
                    null === g.asStripeClasses && (r.asStripeClasses = [A.sStripeOdd, A.sStripeEven]);
                    f = r.asStripeClasses;
                    var I = p.children("tbody").find("tr").eq(0);
                    -1 !==
                        k.inArray(
                            !0,
                            k.map(f, function (C, B) {
                                return I.hasClass(C);
                            })
                        ) && (k("tbody tr", this).removeClass(f.join(" ")), (r.asDestroyStripes = f.slice()));
                    f = [];
                    t = this.getElementsByTagName("thead");
                    0 !== t.length && (wa(r.aoHeader, t[0]), (f = Ka(r)));
                    if (null === g.aoColumns) for (t = [], h = 0, l = f.length; h < l; h++) t.push(null);
                    else t = g.aoColumns;
                    h = 0;
                    for (l = t.length; h < l; h++) Wa(r, f ? f[h] : null);
                    Cb(r, g.aoColumnDefs, t, function (C, B) {
                        Da(r, C, B);
                    });
                    if (I.length) {
                        var W = function (C, B) {
                            return null !== C.getAttribute("data-" + B) ? B : null;
                        };
                        k(I[0])
                            .children("th, td")
                            .each(function (C, B) {
                                var ba = r.aoColumns[C];
                                if (ba.mData === C) {
                                    var X = W(B, "sort") || W(B, "order");
                                    B = W(B, "filter") || W(B, "search");
                                    if (null !== X || null !== B) (ba.mData = { _: C + ".display", sort: null !== X ? C + ".@data-" + X : q, type: null !== X ? C + ".@data-" + X : q, filter: null !== B ? C + ".@data-" + B : q }), Da(r, C);
                                }
                            });
                    }
                    var M = r.oFeatures;
                    f = function () {
                        if (g.aaSorting === q) {
                            var C = r.aaSorting;
                            h = 0;
                            for (l = C.length; h < l; h++) C[h][1] = r.aoColumns[h].asSorting[0];
                        }
                        Pa(r);
                        M.bSort &&
                            Q(r, "aoDrawCallback", function () {
                                if (r.bSorted) {
                                    var ba = pa(r),
                                        X = {};
                                    k.each(ba, function (lb, Aa) {
                                        X[Aa.src] = Aa.dir;
                                    });
                                    H(r, null, "order", [r, ba, X]);
                                    cc(r);
                                }
                            });
                        Q(
                            r,
                            "aoDrawCallback",
                            function () {
                                (r.bSorted || "ssp" === P(r) || M.bDeferRender) && Pa(r);
                            },
                            "sc"
                        );
                        C = p.children("caption").each(function () {
                            this._captionSide = k(this).css("caption-side");
                        });
                        var B = p.children("thead");
                        0 === B.length && (B = k("<thead/>").appendTo(p));
                        r.nTHead = B[0];
                        B = p.children("tbody");
                        0 === B.length && (B = k("<tbody/>").appendTo(p));
                        r.nTBody = B[0];
                        B = p.children("tfoot");
                        0 === B.length && 0 < C.length && ("" !== r.oScroll.sX || "" !== r.oScroll.sY) && (B = k("<tfoot/>").appendTo(p));
                        0 === B.length || 0 === B.children().length ? p.addClass(A.sNoFooter) : 0 < B.length && ((r.nTFoot = B[0]), wa(r.aoFooter, r.nTFoot));
                        if (g.aaData) for (h = 0; h < g.aaData.length; h++) ea(r, g.aaData[h]);
                        else (r.bDeferLoading || "dom" == P(r)) && Ga(r, k(r.nTBody).children("tr"));
                        r.aiDisplay = r.aiDisplayMaster.slice();
                        r.bInitialised = !0;
                        !1 === n && za(r);
                    };
                    g.bStateSave ? ((M.bStateSave = !0), Q(r, "aoDrawCallback", Qa, "state_save"), dc(r, g, f)) : f();
                }
            });
            b = null;
            return this;
        },
        L,
        w,
        J,
        rb = {},
        gc = /[\r\n\u2028]/g,
        Ta = /<.*?>/g,
        tc = /^\d{2,4}[\.\/\-]\d{1,2}[\.\/\-]\d{1,2}([T ]{1}\d{1,2}[:\.]\d{2}([\.:]\d{2})?)?$/,
        uc = /(\/|\.|\*|\+|\?|\||\(|\)|\[|\]|\{|\}|\\|\$|\^|\-)/g,
        qb = /['\u00A0,$£€¥%\u2009\u202F\u20BD\u20a9\u20BArfkɃΞ]/gi,
        ca = function (a) {
            return a && !0 !== a && "-" !== a ? !1 : !0;
        },
        hc = function (a) {
            var b = parseInt(a, 10);
            return !isNaN(b) && isFinite(a) ? b : null;
        },
        ic = function (a, b) {
            rb[b] || (rb[b] = new RegExp(hb(b), "g"));
            return "string" === typeof a && "." !== b ? a.replace(/\./g, "").replace(rb[b], ".") : a;
        },
        sb = function (a, b, c) {
            var d = "string" === typeof a;
            if (ca(a)) return !0;
            b && d && (a = ic(a, b));
            c && d && (a = a.replace(qb, ""));
            return !isNaN(parseFloat(a)) && isFinite(a);
        },
        jc = function (a, b, c) {
            return ca(a) ? !0 : ca(a) || "string" === typeof a ? (sb(a.replace(Ta, ""), b, c) ? !0 : null) : null;
        },
        T = function (a, b, c) {
            var d = [],
                e = 0,
                f = a.length;
            if (c !== q) for (; e < f; e++) a[e] && a[e][b] && d.push(a[e][b][c]);
            else for (; e < f; e++) a[e] && d.push(a[e][b]);
            return d;
        },
        Ca = function (a, b, c, d) {
            var e = [],
                f = 0,
                g = b.length;
            if (d !== q) for (; f < g; f++) a[b[f]][c] && e.push(a[b[f]][c][d]);
            else for (; f < g; f++) e.push(a[b[f]][c]);
            return e;
        },
        qa = function (a, b) {
            var c = [];
            if (b === q) {
                b = 0;
                var d = a;
            } else (d = b), (b = a);
            for (a = b; a < d; a++) c.push(a);
            return c;
        },
        kc = function (a) {
            for (var b = [], c = 0, d = a.length; c < d; c++) a[c] && b.push(a[c]);
            return b;
        },
        Ja = function (a) {
            a: {
                if (!(2 > a.length)) {
                    var b = a.slice().sort();
                    for (var c = b[0], d = 1, e = b.length; d < e; d++) {
                        if (b[d] === c) {
                            b = !1;
                            break a;
                        }
                        c = b[d];
                    }
                }
                b = !0;
            }
            if (b) return a.slice();
            b = [];
            e = a.length;
            var f,
                g = 0;
            d = 0;
            a: for (; d < e; d++) {
                c = a[d];
                for (f = 0; f < g; f++) if (b[f] === c) continue a;
                b.push(c);
                g++;
            }
            return b;
        },
        lc = function (a, b) {
            if (Array.isArray(b)) for (var c = 0; c < b.length; c++) lc(a, b[c]);
            else a.push(b);
            return a;
        };
    Array.isArray ||
        (Array.isArray = function (a) {
            return "[object Array]" === Object.prototype.toString.call(a);
        });
    String.prototype.trim ||
        (String.prototype.trim = function () {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
        });
    u.util = {
        throttle: function (a, b) {
            var c = b !== q ? b : 200,
                d,
                e;
            return function () {
                var f = this,
                    g = +new Date(),
                    h = arguments;
                d && g < d + c
                    ? (clearTimeout(e),
                      (e = setTimeout(function () {
                          d = q;
                          a.apply(f, h);
                      }, c)))
                    : ((d = g), a.apply(f, h));
            };
        },
        escapeRegex: function (a) {
            return a.replace(uc, "\\$1");
        },
    };
    var R = function (a, b, c) {
            a[b] !== q && (a[c] = a[b]);
        },
        ua = /\[.*?\]$/,
        oa = /\(\)$/,
        hb = u.util.escapeRegex,
        Oa = k("<div>")[0],
        rc = Oa.textContent !== q,
        sc = /<.*?>/g,
        fb = u.util.throttle,
        mc = [],
        N = Array.prototype,
        vc = function (a) {
            var b,
                c = u.settings,
                d = k.map(c, function (f, g) {
                    return f.nTable;
                });
            if (a) {
                if (a.nTable && a.oApi) return [a];
                if (a.nodeName && "table" === a.nodeName.toLowerCase()) {
                    var e = k.inArray(a, d);
                    return -1 !== e ? [c[e]] : null;
                }
                if (a && "function" === typeof a.settings) return a.settings().toArray();
                "string" === typeof a ? (b = k(a)) : a instanceof k && (b = a);
            } else return [];
            if (b)
                return b
                    .map(function (f) {
                        e = k.inArray(this, d);
                        return -1 !== e ? c[e] : null;
                    })
                    .toArray();
        };
    var D = function (a, b) {
        if (!(this instanceof D)) return new D(a, b);
        var c = [],
            d = function (g) {
                (g = vc(g)) && c.push.apply(c, g);
            };
        if (Array.isArray(a)) for (var e = 0, f = a.length; e < f; e++) d(a[e]);
        else d(a);
        this.context = Ja(c);
        b && k.merge(this, b);
        this.selector = { rows: null, cols: null, opts: null };
        D.extend(this, this, mc);
    };
    u.Api = D;
    k.extend(D.prototype, {
        any: function () {
            return 0 !== this.count();
        },
        concat: N.concat,
        context: [],
        count: function () {
            return this.flatten().length;
        },
        each: function (a) {
            for (var b = 0, c = this.length; b < c; b++) a.call(this, this[b], b, this);
            return this;
        },
        eq: function (a) {
            var b = this.context;
            return b.length > a ? new D(b[a], this[a]) : null;
        },
        filter: function (a) {
            var b = [];
            if (N.filter) b = N.filter.call(this, a, this);
            else for (var c = 0, d = this.length; c < d; c++) a.call(this, this[c], c, this) && b.push(this[c]);
            return new D(this.context, b);
        },
        flatten: function () {
            var a = [];
            return new D(this.context, a.concat.apply(a, this.toArray()));
        },
        join: N.join,
        indexOf:
            N.indexOf ||
            function (a, b) {
                b = b || 0;
                for (var c = this.length; b < c; b++) if (this[b] === a) return b;
                return -1;
            },
        iterator: function (a, b, c, d) {
            var e = [],
                f,
                g,
                h = this.context,
                l,
                n = this.selector;
            "string" === typeof a && ((d = c), (c = b), (b = a), (a = !1));
            var m = 0;
            for (f = h.length; m < f; m++) {
                var p = new D(h[m]);
                if ("table" === b) {
                    var t = c.call(p, h[m], m);
                    t !== q && e.push(t);
                } else if ("columns" === b || "rows" === b) (t = c.call(p, h[m], this[m], m)), t !== q && e.push(t);
                else if ("column" === b || "column-rows" === b || "row" === b || "cell" === b) {
                    var v = this[m];
                    "column-rows" === b && (l = Ua(h[m], n.opts));
                    var x = 0;
                    for (g = v.length; x < g; x++) (t = v[x]), (t = "cell" === b ? c.call(p, h[m], t.row, t.column, m, x) : c.call(p, h[m], t, m, x, l)), t !== q && e.push(t);
                }
            }
            return e.length || d ? ((a = new D(h, a ? e.concat.apply([], e) : e)), (b = a.selector), (b.rows = n.rows), (b.cols = n.cols), (b.opts = n.opts), a) : this;
        },
        lastIndexOf:
            N.lastIndexOf ||
            function (a, b) {
                return this.indexOf.apply(this.toArray.reverse(), arguments);
            },
        length: 0,
        map: function (a) {
            var b = [];
            if (N.map) b = N.map.call(this, a, this);
            else for (var c = 0, d = this.length; c < d; c++) b.push(a.call(this, this[c], c));
            return new D(this.context, b);
        },
        pluck: function (a) {
            return this.map(function (b) {
                return b[a];
            });
        },
        pop: N.pop,
        push: N.push,
        reduce:
            N.reduce ||
            function (a, b) {
                return Bb(this, a, b, 0, this.length, 1);
            },
        reduceRight:
            N.reduceRight ||
            function (a, b) {
                return Bb(this, a, b, this.length - 1, -1, -1);
            },
        reverse: N.reverse,
        selector: null,
        shift: N.shift,
        slice: function () {
            return new D(this.context, this);
        },
        sort: N.sort,
        splice: N.splice,
        toArray: function () {
            return N.slice.call(this);
        },
        to$: function () {
            return k(this);
        },
        toJQuery: function () {
            return k(this);
        },
        unique: function () {
            return new D(this.context, Ja(this));
        },
        unshift: N.unshift,
    });
    D.extend = function (a, b, c) {
        if (c.length && b && (b instanceof D || b.__dt_wrapper)) {
            var d,
                e = function (h, l, n) {
                    return function () {
                        var m = l.apply(h, arguments);
                        D.extend(m, m, n.methodExt);
                        return m;
                    };
                };
            var f = 0;
            for (d = c.length; f < d; f++) {
                var g = c[f];
                b[g.name] = "function" === g.type ? e(a, g.val, g) : "object" === g.type ? {} : g.val;
                b[g.name].__dt_wrapper = !0;
                D.extend(a, b[g.name], g.propExt);
            }
        }
    };
    D.register = w = function (a, b) {
        if (Array.isArray(a)) for (var c = 0, d = a.length; c < d; c++) D.register(a[c], b);
        else {
            d = a.split(".");
            var e = mc,
                f;
            a = 0;
            for (c = d.length; a < c; a++) {
                var g = (f = -1 !== d[a].indexOf("()")) ? d[a].replace("()", "") : d[a];
                a: {
                    var h = 0;
                    for (var l = e.length; h < l; h++)
                        if (e[h].name === g) {
                            h = e[h];
                            break a;
                        }
                    h = null;
                }
                h || ((h = { name: g, val: {}, methodExt: [], propExt: [], type: "object" }), e.push(h));
                a === c - 1 ? ((h.val = b), (h.type = "function" === typeof b ? "function" : k.isPlainObject(b) ? "object" : "other")) : (e = f ? h.methodExt : h.propExt);
            }
        }
    };
    D.registerPlural = J = function (a, b, c) {
        D.register(a, c);
        D.register(b, function () {
            var d = c.apply(this, arguments);
            return d === this ? this : d instanceof D ? (d.length ? (Array.isArray(d[0]) ? new D(d.context, d[0]) : d[0]) : q) : d;
        });
    };
    var nc = function (a, b) {
        if (Array.isArray(a))
            return k.map(a, function (d) {
                return nc(d, b);
            });
        if ("number" === typeof a) return [b[a]];
        var c = k.map(b, function (d, e) {
            return d.nTable;
        });
        return k(c)
            .filter(a)
            .map(function (d) {
                d = k.inArray(this, c);
                return b[d];
            })
            .toArray();
    };
    w("tables()", function (a) {
        return a !== q && null !== a ? new D(nc(a, this.context)) : this;
    });
    w("table()", function (a) {
        a = this.tables(a);
        var b = a.context;
        return b.length ? new D(b[0]) : a;
    });
    J("tables().nodes()", "table().node()", function () {
        return this.iterator(
            "table",
            function (a) {
                return a.nTable;
            },
            1
        );
    });
    J("tables().body()", "table().body()", function () {
        return this.iterator(
            "table",
            function (a) {
                return a.nTBody;
            },
            1
        );
    });
    J("tables().header()", "table().header()", function () {
        return this.iterator(
            "table",
            function (a) {
                return a.nTHead;
            },
            1
        );
    });
    J("tables().footer()", "table().footer()", function () {
        return this.iterator(
            "table",
            function (a) {
                return a.nTFoot;
            },
            1
        );
    });
    J("tables().containers()", "table().container()", function () {
        return this.iterator(
            "table",
            function (a) {
                return a.nTableWrapper;
            },
            1
        );
    });
    w("draw()", function (a) {
        return this.iterator("table", function (b) {
            "page" === a ? fa(b) : ("string" === typeof a && (a = "full-hold" === a ? !1 : !0), ja(b, !1 === a));
        });
    });
    w("page()", function (a) {
        return a === q
            ? this.page.info().page
            : this.iterator("table", function (b) {
                  kb(b, a);
              });
    });
    w("page.info()", function (a) {
        if (0 === this.context.length) return q;
        a = this.context[0];
        var b = a._iDisplayStart,
            c = a.oFeatures.bPaginate ? a._iDisplayLength : -1,
            d = a.fnRecordsDisplay(),
            e = -1 === c;
        return { page: e ? 0 : Math.floor(b / c), pages: e ? 1 : Math.ceil(d / c), start: b, end: a.fnDisplayEnd(), length: c, recordsTotal: a.fnRecordsTotal(), recordsDisplay: d, serverSide: "ssp" === P(a) };
    });
    w("page.len()", function (a) {
        return a === q
            ? 0 !== this.context.length
                ? this.context[0]._iDisplayLength
                : q
            : this.iterator("table", function (b) {
                  ib(b, a);
              });
    });
    var oc = function (a, b, c) {
        if (c) {
            var d = new D(a);
            d.one("draw", function () {
                c(d.ajax.json());
            });
        }
        if ("ssp" == P(a)) ja(a, b);
        else {
            U(a, !0);
            var e = a.jqXHR;
            e && 4 !== e.readyState && e.abort();
            La(a, [], function (f) {
                Ha(a);
                f = Ma(a, f);
                for (var g = 0, h = f.length; g < h; g++) ea(a, f[g]);
                ja(a, b);
                U(a, !1);
            });
        }
    };
    w("ajax.json()", function () {
        var a = this.context;
        if (0 < a.length) return a[0].json;
    });
    w("ajax.params()", function () {
        var a = this.context;
        if (0 < a.length) return a[0].oAjaxData;
    });
    w("ajax.reload()", function (a, b) {
        return this.iterator("table", function (c) {
            oc(c, !1 === b, a);
        });
    });
    w("ajax.url()", function (a) {
        var b = this.context;
        if (a === q) {
            if (0 === b.length) return q;
            b = b[0];
            return b.ajax ? (k.isPlainObject(b.ajax) ? b.ajax.url : b.ajax) : b.sAjaxSource;
        }
        return this.iterator("table", function (c) {
            k.isPlainObject(c.ajax) ? (c.ajax.url = a) : (c.ajax = a);
        });
    });
    w("ajax.url().load()", function (a, b) {
        return this.iterator("table", function (c) {
            oc(c, !1 === b, a);
        });
    });
    var tb = function (a, b, c, d, e) {
            var f = [],
                g,
                h,
                l;
            var n = typeof b;
            (b && "string" !== n && "function" !== n && b.length !== q) || (b = [b]);
            n = 0;
            for (h = b.length; n < h; n++) {
                var m = b[n] && b[n].split && !b[n].match(/[\[\(:]/) ? b[n].split(",") : [b[n]];
                var p = 0;
                for (l = m.length; p < l; p++) (g = c("string" === typeof m[p] ? m[p].trim() : m[p])) && g.length && (f = f.concat(g));
            }
            a = L.selector[a];
            if (a.length) for (n = 0, h = a.length; n < h; n++) f = a[n](d, e, f);
            return Ja(f);
        },
        ub = function (a) {
            a || (a = {});
            a.filter && a.search === q && (a.search = a.filter);
            return k.extend({ search: "none", order: "current", page: "all" }, a);
        },
        vb = function (a) {
            for (var b = 0, c = a.length; b < c; b++) if (0 < a[b].length) return (a[0] = a[b]), (a[0].length = 1), (a.length = 1), (a.context = [a.context[b]]), a;
            a.length = 0;
            return a;
        },
        Ua = function (a, b) {
            var c = [],
                d = a.aiDisplay;
            var e = a.aiDisplayMaster;
            var f = b.search;
            var g = b.order;
            b = b.page;
            if ("ssp" == P(a)) return "removed" === f ? [] : qa(0, e.length);
            if ("current" == b) for (g = a._iDisplayStart, a = a.fnDisplayEnd(); g < a; g++) c.push(d[g]);
            else if ("current" == g || "applied" == g)
                if ("none" == f) c = e.slice();
                else if ("applied" == f) c = d.slice();
                else {
                    if ("removed" == f) {
                        var h = {};
                        g = 0;
                        for (a = d.length; g < a; g++) h[d[g]] = null;
                        c = k.map(e, function (l) {
                            return h.hasOwnProperty(l) ? null : l;
                        });
                    }
                }
            else if ("index" == g || "original" == g) for (g = 0, a = a.aoData.length; g < a; g++) "none" == f ? c.push(g) : ((e = k.inArray(g, d)), ((-1 === e && "removed" == f) || (0 <= e && "applied" == f)) && c.push(g));
            return c;
        },
        wc = function (a, b, c) {
            var d;
            return tb(
                "row",
                b,
                function (e) {
                    var f = hc(e),
                        g = a.aoData;
                    if (null !== f && !c) return [f];
                    d || (d = Ua(a, c));
                    if (null !== f && -1 !== k.inArray(f, d)) return [f];
                    if (null === e || e === q || "" === e) return d;
                    if ("function" === typeof e)
                        return k.map(d, function (l) {
                            var n = g[l];
                            return e(l, n._aData, n.nTr) ? l : null;
                        });
                    if (e.nodeName) {
                        f = e._DT_RowIndex;
                        var h = e._DT_CellIndex;
                        if (f !== q) return g[f] && g[f].nTr === e ? [f] : [];
                        if (h) return g[h.row] && g[h.row].nTr === e.parentNode ? [h.row] : [];
                        f = k(e).closest("*[data-dt-row]");
                        return f.length ? [f.data("dt-row")] : [];
                    }
                    if ("string" === typeof e && "#" === e.charAt(0) && ((f = a.aIds[e.replace(/^#/, "")]), f !== q)) return [f.idx];
                    f = kc(Ca(a.aoData, d, "nTr"));
                    return k(f)
                        .filter(e)
                        .map(function () {
                            return this._DT_RowIndex;
                        })
                        .toArray();
                },
                a,
                c
            );
        };
    w("rows()", function (a, b) {
        a === q ? (a = "") : k.isPlainObject(a) && ((b = a), (a = ""));
        b = ub(b);
        var c = this.iterator(
            "table",
            function (d) {
                return wc(d, a, b);
            },
            1
        );
        c.selector.rows = a;
        c.selector.opts = b;
        return c;
    });
    w("rows().nodes()", function () {
        return this.iterator(
            "row",
            function (a, b) {
                return a.aoData[b].nTr || q;
            },
            1
        );
    });
    w("rows().data()", function () {
        return this.iterator(
            !0,
            "rows",
            function (a, b) {
                return Ca(a.aoData, b, "_aData");
            },
            1
        );
    });
    J("rows().cache()", "row().cache()", function (a) {
        return this.iterator(
            "row",
            function (b, c) {
                b = b.aoData[c];
                return "search" === a ? b._aFilterData : b._aSortData;
            },
            1
        );
    });
    J("rows().invalidate()", "row().invalidate()", function (a) {
        return this.iterator("row", function (b, c) {
            va(b, c, a);
        });
    });
    J("rows().indexes()", "row().index()", function () {
        return this.iterator(
            "row",
            function (a, b) {
                return b;
            },
            1
        );
    });
    J("rows().ids()", "row().id()", function (a) {
        for (var b = [], c = this.context, d = 0, e = c.length; d < e; d++)
            for (var f = 0, g = this[d].length; f < g; f++) {
                var h = c[d].rowIdFn(c[d].aoData[this[d][f]]._aData);
                b.push((!0 === a ? "#" : "") + h);
            }
        return new D(c, b);
    });
    J("rows().remove()", "row().remove()", function () {
        var a = this;
        this.iterator("row", function (b, c, d) {
            var e = b.aoData,
                f = e[c],
                g,
                h;
            e.splice(c, 1);
            var l = 0;
            for (g = e.length; l < g; l++) {
                var n = e[l];
                var m = n.anCells;
                null !== n.nTr && (n.nTr._DT_RowIndex = l);
                if (null !== m) for (n = 0, h = m.length; n < h; n++) m[n]._DT_CellIndex.row = l;
            }
            Ia(b.aiDisplayMaster, c);
            Ia(b.aiDisplay, c);
            Ia(a[d], c, !1);
            0 < b._iRecordsDisplay && b._iRecordsDisplay--;
            jb(b);
            c = b.rowIdFn(f._aData);
            c !== q && delete b.aIds[c];
        });
        this.iterator("table", function (b) {
            for (var c = 0, d = b.aoData.length; c < d; c++) b.aoData[c].idx = c;
        });
        return this;
    });
    w("rows.add()", function (a) {
        var b = this.iterator(
                "table",
                function (d) {
                    var e,
                        f = [];
                    var g = 0;
                    for (e = a.length; g < e; g++) {
                        var h = a[g];
                        h.nodeName && "TR" === h.nodeName.toUpperCase() ? f.push(Ga(d, h)[0]) : f.push(ea(d, h));
                    }
                    return f;
                },
                1
            ),
            c = this.rows(-1);
        c.pop();
        k.merge(c, b);
        return c;
    });
    w("row()", function (a, b) {
        return vb(this.rows(a, b));
    });
    w("row().data()", function (a) {
        var b = this.context;
        if (a === q) return b.length && this.length ? b[0].aoData[this[0]]._aData : q;
        var c = b[0].aoData[this[0]];
        c._aData = a;
        Array.isArray(a) && c.nTr && c.nTr.id && da(b[0].rowId)(a, c.nTr.id);
        va(b[0], this[0], "data");
        return this;
    });
    w("row().node()", function () {
        var a = this.context;
        return a.length && this.length ? a[0].aoData[this[0]].nTr || null : null;
    });
    w("row.add()", function (a) {
        a instanceof k && a.length && (a = a[0]);
        var b = this.iterator("table", function (c) {
            return a.nodeName && "TR" === a.nodeName.toUpperCase() ? Ga(c, a)[0] : ea(c, a);
        });
        return this.row(b[0]);
    });
    var xc = function (a, b, c, d) {
            var e = [],
                f = function (g, h) {
                    if (Array.isArray(g) || g instanceof k) for (var l = 0, n = g.length; l < n; l++) f(g[l], h);
                    else g.nodeName && "tr" === g.nodeName.toLowerCase() ? e.push(g) : ((l = k("<tr><td></td></tr>").addClass(h)), (k("td", l).addClass(h).html(g)[0].colSpan = na(a)), e.push(l[0]));
                };
            f(c, d);
            b._details && b._details.detach();
            b._details = k(e);
            b._detailsShow && b._details.insertAfter(b.nTr);
        },
        wb = function (a, b) {
            var c = a.context;
            c.length && (a = c[0].aoData[b !== q ? b : a[0]]) && a._details && (a._details.remove(), (a._detailsShow = q), (a._details = q));
        },
        pc = function (a, b) {
            var c = a.context;
            c.length && a.length && ((a = c[0].aoData[a[0]]), a._details && ((a._detailsShow = b) ? a._details.insertAfter(a.nTr) : a._details.detach(), yc(c[0])));
        },
        yc = function (a) {
            var b = new D(a),
                c = a.aoData;
            b.off("draw.dt.DT_details column-visibility.dt.DT_details destroy.dt.DT_details");
            0 < T(c, "_details").length &&
                (b.on("draw.dt.DT_details", function (d, e) {
                    a === e &&
                        b
                            .rows({ page: "current" })
                            .eq(0)
                            .each(function (f) {
                                f = c[f];
                                f._detailsShow && f._details.insertAfter(f.nTr);
                            });
                }),
                b.on("column-visibility.dt.DT_details", function (d, e, f, g) {
                    if (a === e) for (e = na(e), f = 0, g = c.length; f < g; f++) (d = c[f]), d._details && d._details.children("td[colspan]").attr("colspan", e);
                }),
                b.on("destroy.dt.DT_details", function (d, e) {
                    if (a === e) for (d = 0, e = c.length; d < e; d++) c[d]._details && wb(b, d);
                }));
        };
    w("row().child()", function (a, b) {
        var c = this.context;
        if (a === q) return c.length && this.length ? c[0].aoData[this[0]]._details : q;
        !0 === a ? this.child.show() : !1 === a ? wb(this) : c.length && this.length && xc(c[0], c[0].aoData[this[0]], a, b);
        return this;
    });
    w(["row().child.show()", "row().child().show()"], function (a) {
        pc(this, !0);
        return this;
    });
    w(["row().child.hide()", "row().child().hide()"], function () {
        pc(this, !1);
        return this;
    });
    w(["row().child.remove()", "row().child().remove()"], function () {
        wb(this);
        return this;
    });
    w("row().child.isShown()", function () {
        var a = this.context;
        return a.length && this.length ? a[0].aoData[this[0]]._detailsShow || !1 : !1;
    });
    var zc = /^([^:]+):(name|visIdx|visible)$/,
        qc = function (a, b, c, d, e) {
            c = [];
            d = 0;
            for (var f = e.length; d < f; d++) c.push(S(a, e[d], b));
            return c;
        },
        Ac = function (a, b, c) {
            var d = a.aoColumns,
                e = T(d, "sName"),
                f = T(d, "nTh");
            return tb(
                "column",
                b,
                function (g) {
                    var h = hc(g);
                    if ("" === g) return qa(d.length);
                    if (null !== h) return [0 <= h ? h : d.length + h];
                    if ("function" === typeof g) {
                        var l = Ua(a, c);
                        return k.map(d, function (p, t) {
                            return g(t, qc(a, t, 0, 0, l), f[t]) ? t : null;
                        });
                    }
                    var n = "string" === typeof g ? g.match(zc) : "";
                    if (n)
                        switch (n[2]) {
                            case "visIdx":
                            case "visible":
                                h = parseInt(n[1], 10);
                                if (0 > h) {
                                    var m = k.map(d, function (p, t) {
                                        return p.bVisible ? t : null;
                                    });
                                    return [m[m.length + h]];
                                }
                                return [sa(a, h)];
                            case "name":
                                return k.map(e, function (p, t) {
                                    return p === n[1] ? t : null;
                                });
                            default:
                                return [];
                        }
                    if (g.nodeName && g._DT_CellIndex) return [g._DT_CellIndex.column];
                    h = k(f)
                        .filter(g)
                        .map(function () {
                            return k.inArray(this, f);
                        })
                        .toArray();
                    if (h.length || !g.nodeName) return h;
                    h = k(g).closest("*[data-dt-column]");
                    return h.length ? [h.data("dt-column")] : [];
                },
                a,
                c
            );
        };
    w("columns()", function (a, b) {
        a === q ? (a = "") : k.isPlainObject(a) && ((b = a), (a = ""));
        b = ub(b);
        var c = this.iterator(
            "table",
            function (d) {
                return Ac(d, a, b);
            },
            1
        );
        c.selector.cols = a;
        c.selector.opts = b;
        return c;
    });
    J("columns().header()", "column().header()", function (a, b) {
        return this.iterator(
            "column",
            function (c, d) {
                return c.aoColumns[d].nTh;
            },
            1
        );
    });
    J("columns().footer()", "column().footer()", function (a, b) {
        return this.iterator(
            "column",
            function (c, d) {
                return c.aoColumns[d].nTf;
            },
            1
        );
    });
    J("columns().data()", "column().data()", function () {
        return this.iterator("column-rows", qc, 1);
    });
    J("columns().dataSrc()", "column().dataSrc()", function () {
        return this.iterator(
            "column",
            function (a, b) {
                return a.aoColumns[b].mData;
            },
            1
        );
    });
    J("columns().cache()", "column().cache()", function (a) {
        return this.iterator(
            "column-rows",
            function (b, c, d, e, f) {
                return Ca(b.aoData, f, "search" === a ? "_aFilterData" : "_aSortData", c);
            },
            1
        );
    });
    J("columns().nodes()", "column().nodes()", function () {
        return this.iterator(
            "column-rows",
            function (a, b, c, d, e) {
                return Ca(a.aoData, e, "anCells", b);
            },
            1
        );
    });
    J("columns().visible()", "column().visible()", function (a, b) {
        var c = this,
            d = this.iterator("column", function (e, f) {
                if (a === q) return e.aoColumns[f].bVisible;
                var g = e.aoColumns,
                    h = g[f],
                    l = e.aoData,
                    n;
                if (a !== q && h.bVisible !== a) {
                    if (a) {
                        var m = k.inArray(!0, T(g, "bVisible"), f + 1);
                        g = 0;
                        for (n = l.length; g < n; g++) {
                            var p = l[g].nTr;
                            e = l[g].anCells;
                            p && p.insertBefore(e[f], e[m] || null);
                        }
                    } else k(T(e.aoData, "anCells", f)).detach();
                    h.bVisible = a;
                }
            });
        a !== q &&
            this.iterator("table", function (e) {
                xa(e, e.aoHeader);
                xa(e, e.aoFooter);
                e.aiDisplay.length || k(e.nTBody).find("td[colspan]").attr("colspan", na(e));
                Qa(e);
                c.iterator("column", function (f, g) {
                    H(f, null, "column-visibility", [f, g, a, b]);
                });
                (b === q || b) && c.columns.adjust();
            });
        return d;
    });
    J("columns().indexes()", "column().index()", function (a) {
        return this.iterator(
            "column",
            function (b, c) {
                return "visible" === a ? ta(b, c) : c;
            },
            1
        );
    });
    w("columns.adjust()", function () {
        return this.iterator(
            "table",
            function (a) {
                ra(a);
            },
            1
        );
    });
    w("column.index()", function (a, b) {
        if (0 !== this.context.length) {
            var c = this.context[0];
            if ("fromVisible" === a || "toData" === a) return sa(c, b);
            if ("fromData" === a || "toVisible" === a) return ta(c, b);
        }
    });
    w("column()", function (a, b) {
        return vb(this.columns(a, b));
    });
    var Bc = function (a, b, c) {
        var d = a.aoData,
            e = Ua(a, c),
            f = kc(Ca(d, e, "anCells")),
            g = k(lc([], f)),
            h,
            l = a.aoColumns.length,
            n,
            m,
            p,
            t,
            v,
            x;
        return tb(
            "cell",
            b,
            function (r) {
                var A = "function" === typeof r;
                if (null === r || r === q || A) {
                    n = [];
                    m = 0;
                    for (p = e.length; m < p; m++) for (h = e[m], t = 0; t < l; t++) (v = { row: h, column: t }), A ? ((x = d[h]), r(v, S(a, h, t), x.anCells ? x.anCells[t] : null) && n.push(v)) : n.push(v);
                    return n;
                }
                if (k.isPlainObject(r)) return r.column !== q && r.row !== q && -1 !== k.inArray(r.row, e) ? [r] : [];
                A = g
                    .filter(r)
                    .map(function (E, I) {
                        return { row: I._DT_CellIndex.row, column: I._DT_CellIndex.column };
                    })
                    .toArray();
                if (A.length || !r.nodeName) return A;
                x = k(r).closest("*[data-dt-row]");
                return x.length ? [{ row: x.data("dt-row"), column: x.data("dt-column") }] : [];
            },
            a,
            c
        );
    };
    w("cells()", function (a, b, c) {
        k.isPlainObject(a) && (a.row === q ? ((c = a), (a = null)) : ((c = b), (b = null)));
        k.isPlainObject(b) && ((c = b), (b = null));
        if (null === b || b === q)
            return this.iterator("table", function (m) {
                return Bc(m, a, ub(c));
            });
        var d = c ? { page: c.page, order: c.order, search: c.search } : {},
            e = this.columns(b, d),
            f = this.rows(a, d),
            g,
            h,
            l,
            n;
        d = this.iterator(
            "table",
            function (m, p) {
                m = [];
                g = 0;
                for (h = f[p].length; g < h; g++) for (l = 0, n = e[p].length; l < n; l++) m.push({ row: f[p][g], column: e[p][l] });
                return m;
            },
            1
        );
        d = c && c.selected ? this.cells(d, c) : d;
        k.extend(d.selector, { cols: b, rows: a, opts: c });
        return d;
    });
    J("cells().nodes()", "cell().node()", function () {
        return this.iterator(
            "cell",
            function (a, b, c) {
                return (a = a.aoData[b]) && a.anCells ? a.anCells[c] : q;
            },
            1
        );
    });
    w("cells().data()", function () {
        return this.iterator(
            "cell",
            function (a, b, c) {
                return S(a, b, c);
            },
            1
        );
    });
    J("cells().cache()", "cell().cache()", function (a) {
        a = "search" === a ? "_aFilterData" : "_aSortData";
        return this.iterator(
            "cell",
            function (b, c, d) {
                return b.aoData[c][a][d];
            },
            1
        );
    });
    J("cells().render()", "cell().render()", function (a) {
        return this.iterator(
            "cell",
            function (b, c, d) {
                return S(b, c, d, a);
            },
            1
        );
    });
    J("cells().indexes()", "cell().index()", function () {
        return this.iterator(
            "cell",
            function (a, b, c) {
                return { row: b, column: c, columnVisible: ta(a, c) };
            },
            1
        );
    });
    J("cells().invalidate()", "cell().invalidate()", function (a) {
        return this.iterator("cell", function (b, c, d) {
            va(b, c, a, d);
        });
    });
    w("cell()", function (a, b, c) {
        return vb(this.cells(a, b, c));
    });
    w("cell().data()", function (a) {
        var b = this.context,
            c = this[0];
        if (a === q) return b.length && c.length ? S(b[0], c[0].row, c[0].column) : q;
        Db(b[0], c[0].row, c[0].column, a);
        va(b[0], c[0].row, "data", c[0].column);
        return this;
    });
    w("order()", function (a, b) {
        var c = this.context;
        if (a === q) return 0 !== c.length ? c[0].aaSorting : q;
        "number" === typeof a ? (a = [[a, b]]) : a.length && !Array.isArray(a[0]) && (a = Array.prototype.slice.call(arguments));
        return this.iterator("table", function (d) {
            d.aaSorting = a.slice();
        });
    });
    w("order.listener()", function (a, b, c) {
        return this.iterator("table", function (d) {
            db(d, a, b, c);
        });
    });
    w("order.fixed()", function (a) {
        if (!a) {
            var b = this.context;
            b = b.length ? b[0].aaSortingFixed : q;
            return Array.isArray(b) ? { pre: b } : b;
        }
        return this.iterator("table", function (c) {
            c.aaSortingFixed = k.extend(!0, {}, a);
        });
    });
    w(["columns().order()", "column().order()"], function (a) {
        var b = this;
        return this.iterator("table", function (c, d) {
            var e = [];
            k.each(b[d], function (f, g) {
                e.push([g, a]);
            });
            c.aaSorting = e;
        });
    });
    w("search()", function (a, b, c, d) {
        var e = this.context;
        return a === q
            ? 0 !== e.length
                ? e[0].oPreviousSearch.sSearch
                : q
            : this.iterator("table", function (f) {
                  f.oFeatures.bFilter && ya(f, k.extend({}, f.oPreviousSearch, { sSearch: a + "", bRegex: null === b ? !1 : b, bSmart: null === c ? !0 : c, bCaseInsensitive: null === d ? !0 : d }), 1);
              });
    });
    J("columns().search()", "column().search()", function (a, b, c, d) {
        return this.iterator("column", function (e, f) {
            var g = e.aoPreSearchCols;
            if (a === q) return g[f].sSearch;
            e.oFeatures.bFilter && (k.extend(g[f], { sSearch: a + "", bRegex: null === b ? !1 : b, bSmart: null === c ? !0 : c, bCaseInsensitive: null === d ? !0 : d }), ya(e, e.oPreviousSearch, 1));
        });
    });
    w("state()", function () {
        return this.context.length ? this.context[0].oSavedState : null;
    });
    w("state.clear()", function () {
        return this.iterator("table", function (a) {
            a.fnStateSaveCallback.call(a.oInstance, a, {});
        });
    });
    w("state.loaded()", function () {
        return this.context.length ? this.context[0].oLoadedState : null;
    });
    w("state.save()", function () {
        return this.iterator("table", function (a) {
            Qa(a);
        });
    });
    u.versionCheck = u.fnVersionCheck = function (a) {
        var b = u.version.split(".");
        a = a.split(".");
        for (var c, d, e = 0, f = a.length; e < f; e++) if (((c = parseInt(b[e], 10) || 0), (d = parseInt(a[e], 10) || 0), c !== d)) return c > d;
        return !0;
    };
    u.isDataTable = u.fnIsDataTable = function (a) {
        var b = k(a).get(0),
            c = !1;
        if (a instanceof u.Api) return !0;
        k.each(u.settings, function (d, e) {
            d = e.nScrollHead ? k("table", e.nScrollHead)[0] : null;
            var f = e.nScrollFoot ? k("table", e.nScrollFoot)[0] : null;
            if (e.nTable === b || d === b || f === b) c = !0;
        });
        return c;
    };
    u.tables = u.fnTables = function (a) {
        var b = !1;
        k.isPlainObject(a) && ((b = a.api), (a = a.visible));
        var c = k.map(u.settings, function (d) {
            if (!a || (a && k(d.nTable).is(":visible"))) return d.nTable;
        });
        return b ? new D(c) : c;
    };
    u.camelToHungarian = O;
    w("$()", function (a, b) {
        b = this.rows(b).nodes();
        b = k(b);
        return k([].concat(b.filter(a).toArray(), b.find(a).toArray()));
    });
    k.each(["on", "one", "off"], function (a, b) {
        w(b + "()", function () {
            var c = Array.prototype.slice.call(arguments);
            c[0] = k
                .map(c[0].split(/\s/), function (e) {
                    return e.match(/\.dt\b/) ? e : e + ".dt";
                })
                .join(" ");
            var d = k(this.tables().nodes());
            d[b].apply(d, c);
            return this;
        });
    });
    w("clear()", function () {
        return this.iterator("table", function (a) {
            Ha(a);
        });
    });
    w("settings()", function () {
        return new D(this.context, this.context);
    });
    w("init()", function () {
        var a = this.context;
        return a.length ? a[0].oInit : null;
    });
    w("data()", function () {
        return this.iterator("table", function (a) {
            return T(a.aoData, "_aData");
        }).flatten();
    });
    w("destroy()", function (a) {
        a = a || !1;
        return this.iterator("table", function (b) {
            var c = b.nTableWrapper.parentNode,
                d = b.oClasses,
                e = b.nTable,
                f = b.nTBody,
                g = b.nTHead,
                h = b.nTFoot,
                l = k(e);
            f = k(f);
            var n = k(b.nTableWrapper),
                m = k.map(b.aoData, function (t) {
                    return t.nTr;
                }),
                p;
            b.bDestroying = !0;
            H(b, "aoDestroyCallback", "destroy", [b]);
            a || new D(b).columns().visible(!0);
            n.off(".DT").find(":not(tbody *)").off(".DT");
            k(y).off(".DT-" + b.sInstance);
            e != g.parentNode && (l.children("thead").detach(), l.append(g));
            h && e != h.parentNode && (l.children("tfoot").detach(), l.append(h));
            b.aaSorting = [];
            b.aaSortingFixed = [];
            Pa(b);
            k(m).removeClass(b.asStripeClasses.join(" "));
            k("th, td", g).removeClass(d.sSortable + " " + d.sSortableAsc + " " + d.sSortableDesc + " " + d.sSortableNone);
            f.children().detach();
            f.append(m);
            g = a ? "remove" : "detach";
            l[g]();
            n[g]();
            !a &&
                c &&
                (c.insertBefore(e, b.nTableReinsertBefore),
                l.css("width", b.sDestroyWidth).removeClass(d.sTable),
                (p = b.asDestroyStripes.length) &&
                    f.children().each(function (t) {
                        k(this).addClass(b.asDestroyStripes[t % p]);
                    }));
            c = k.inArray(b, u.settings);
            -1 !== c && u.settings.splice(c, 1);
        });
    });
    k.each(["column", "row", "cell"], function (a, b) {
        w(b + "s().every()", function (c) {
            var d = this.selector.opts,
                e = this;
            return this.iterator(b, function (f, g, h, l, n) {
                c.call(e[b](g, "cell" === b ? h : d, "cell" === b ? d : q), g, h, l, n);
            });
        });
    });
    w("i18n()", function (a, b, c) {
        var d = this.context[0];
        a = ia(a)(d.oLanguage);
        a === q && (a = b);
        c !== q && k.isPlainObject(a) && (a = a[c] !== q ? a[c] : a._);
        return a.replace("%d", c);
    });
    u.version = "1.10.24";
    u.settings = [];
    u.models = {};
    u.models.oSearch = { bCaseInsensitive: !0, sSearch: "", bRegex: !1, bSmart: !0 };
    u.models.oRow = { nTr: null, anCells: null, _aData: [], _aSortData: null, _aFilterData: null, _sFilterRow: null, _sRowStripe: "", src: null, idx: -1 };
    u.models.oColumn = {
        idx: null,
        aDataSort: null,
        asSorting: null,
        bSearchable: null,
        bSortable: null,
        bVisible: null,
        _sManualType: null,
        _bAttrSrc: !1,
        fnCreatedCell: null,
        fnGetData: null,
        fnSetData: null,
        mData: null,
        mRender: null,
        nTh: null,
        nTf: null,
        sClass: null,
        sContentPadding: null,
        sDefaultContent: null,
        sName: null,
        sSortDataType: "std",
        sSortingClass: null,
        sSortingClassJUI: null,
        sTitle: null,
        sType: null,
        sWidth: null,
        sWidthOrig: null,
    };
    u.defaults = {
        aaData: null,
        aaSorting: [[0, "asc"]],
        aaSortingFixed: [],
        ajax: null,
        aLengthMenu: [10, 25, 50, 100],
        aoColumns: null,
        aoColumnDefs: null,
        aoSearchCols: [],
        asStripeClasses: null,
        bAutoWidth: !0,
        bDeferRender: !1,
        bDestroy: !1,
        bFilter: !0,
        bInfo: !0,
        bLengthChange: !0,
        bPaginate: !0,
        bProcessing: !1,
        bRetrieve: !1,
        bScrollCollapse: !1,
        bServerSide: !1,
        bSort: !0,
        bSortMulti: !0,
        bSortCellsTop: !1,
        bSortClasses: !0,
        bStateSave: !1,
        fnCreatedRow: null,
        fnDrawCallback: null,
        fnFooterCallback: null,
        fnFormatNumber: function (a) {
            return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g, this.oLanguage.sThousands);
        },
        fnHeaderCallback: null,
        fnInfoCallback: null,
        fnInitComplete: null,
        fnPreDrawCallback: null,
        fnRowCallback: null,
        fnServerData: null,
        fnServerParams: null,
        fnStateLoadCallback: function (a) {
            try {
                return JSON.parse((-1 === a.iStateDuration ? sessionStorage : localStorage).getItem("DataTables_" + a.sInstance + "_" + location.pathname));
            } catch (b) {
                return {};
            }
        },
        fnStateLoadParams: null,
        fnStateLoaded: null,
        fnStateSaveCallback: function (a, b) {
            try {
                (-1 === a.iStateDuration ? sessionStorage : localStorage).setItem("DataTables_" + a.sInstance + "_" + location.pathname, JSON.stringify(b));
            } catch (c) {}
        },
        fnStateSaveParams: null,
        iStateDuration: 7200,
        iDeferLoading: null,
        iDisplayLength: 10,
        iDisplayStart: 0,
        iTabIndex: 0,
        oClasses: {},
        oLanguage: {
            oAria: { sSortAscending: ": activate to sort column ascending", sSortDescending: ": activate to sort column descending" },
            oPaginate: { sFirst: "First", sLast: "Last", sNext: "Next", sPrevious: "Previous" },
            sEmptyTable: "No data available in table",
            sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
            sInfoEmpty: "Showing 0 to 0 of 0 entries",
            sInfoFiltered: "(filtered from _MAX_ total entries)",
            sInfoPostFix: "",
            sDecimal: "",
            sThousands: ",",
            sLengthMenu: "Show _MENU_ entries",
            sLoadingRecords: "Loading...",
            sProcessing: "Processing...",
            sSearch: "Search:",
            sSearchPlaceholder: "",
            sUrl: "",
            sZeroRecords: "No matching records found",
        },
        oSearch: k.extend({}, u.models.oSearch),
        sAjaxDataProp: "data",
        sAjaxSource: null,
        sDom: "lfrtip",
        searchDelay: null,
        sPaginationType: "simple_numbers",
        sScrollX: "",
        sScrollXInner: "",
        sScrollY: "",
        sServerMethod: "GET",
        renderer: null,
        rowId: "DT_RowId",
    };
    G(u.defaults);
    u.defaults.column = {
        aDataSort: null,
        iDataSort: -1,
        asSorting: ["asc", "desc"],
        bSearchable: !0,
        bSortable: !0,
        bVisible: !0,
        fnCreatedCell: null,
        mData: null,
        mRender: null,
        sCellType: "td",
        sClass: "",
        sContentPadding: "",
        sDefaultContent: null,
        sName: "",
        sSortDataType: "std",
        sTitle: null,
        sType: null,
        sWidth: null,
    };
    G(u.defaults.column);
    u.models.oSettings = {
        oFeatures: { bAutoWidth: null, bDeferRender: null, bFilter: null, bInfo: null, bLengthChange: null, bPaginate: null, bProcessing: null, bServerSide: null, bSort: null, bSortMulti: null, bSortClasses: null, bStateSave: null },
        oScroll: { bCollapse: null, iBarWidth: 0, sX: null, sXInner: null, sY: null },
        oLanguage: { fnInfoCallback: null },
        oBrowser: { bScrollOversize: !1, bScrollbarLeft: !1, bBounding: !1, barWidth: 0 },
        ajax: null,
        aanFeatures: [],
        aoData: [],
        aiDisplay: [],
        aiDisplayMaster: [],
        aIds: {},
        aoColumns: [],
        aoHeader: [],
        aoFooter: [],
        oPreviousSearch: {},
        aoPreSearchCols: [],
        aaSorting: null,
        aaSortingFixed: [],
        asStripeClasses: null,
        asDestroyStripes: [],
        sDestroyWidth: 0,
        aoRowCallback: [],
        aoHeaderCallback: [],
        aoFooterCallback: [],
        aoDrawCallback: [],
        aoRowCreatedCallback: [],
        aoPreDrawCallback: [],
        aoInitComplete: [],
        aoStateSaveParams: [],
        aoStateLoadParams: [],
        aoStateLoaded: [],
        sTableId: "",
        nTable: null,
        nTHead: null,
        nTFoot: null,
        nTBody: null,
        nTableWrapper: null,
        bDeferLoading: !1,
        bInitialised: !1,
        aoOpenRows: [],
        sDom: null,
        searchDelay: null,
        sPaginationType: "two_button",
        iStateDuration: 0,
        aoStateSave: [],
        aoStateLoad: [],
        oSavedState: null,
        oLoadedState: null,
        sAjaxSource: null,
        sAjaxDataProp: null,
        bAjaxDataGet: !0,
        jqXHR: null,
        json: q,
        oAjaxData: q,
        fnServerData: null,
        aoServerParams: [],
        sServerMethod: null,
        fnFormatNumber: null,
        aLengthMenu: null,
        iDraw: 0,
        bDrawing: !1,
        iDrawError: -1,
        _iDisplayLength: 10,
        _iDisplayStart: 0,
        _iRecordsTotal: 0,
        _iRecordsDisplay: 0,
        oClasses: {},
        bFiltered: !1,
        bSorted: !1,
        bSortCellsTop: null,
        oInit: null,
        aoDestroyCallback: [],
        fnRecordsTotal: function () {
            return "ssp" == P(this) ? 1 * this._iRecordsTotal : this.aiDisplayMaster.length;
        },
        fnRecordsDisplay: function () {
            return "ssp" == P(this) ? 1 * this._iRecordsDisplay : this.aiDisplay.length;
        },
        fnDisplayEnd: function () {
            var a = this._iDisplayLength,
                b = this._iDisplayStart,
                c = b + a,
                d = this.aiDisplay.length,
                e = this.oFeatures,
                f = e.bPaginate;
            return e.bServerSide ? (!1 === f || -1 === a ? b + d : Math.min(b + a, this._iRecordsDisplay)) : !f || c > d || -1 === a ? d : c;
        },
        oInstance: null,
        sInstance: null,
        iTabIndex: 0,
        nScrollHead: null,
        nScrollFoot: null,
        aLastSort: [],
        oPlugins: {},
        rowIdFn: null,
        rowId: null,
    };
    u.ext = L = {
        buttons: {},
        classes: {},
        builder: "-source-",
        errMode: "alert",
        feature: [],
        search: [],
        selector: { cell: [], column: [], row: [] },
        internal: {},
        legacy: { ajax: null },
        pager: {},
        renderer: { pageButton: {}, header: {} },
        order: {},
        type: { detect: [], search: {}, order: {} },
        _unique: 0,
        fnVersionCheck: u.fnVersionCheck,
        iApiIndex: 0,
        oJUIClasses: {},
        sVersion: u.version,
    };
    k.extend(L, { afnFiltering: L.search, aTypes: L.type.detect, ofnSearch: L.type.search, oSort: L.type.order, afnSortData: L.order, aoFeatures: L.feature, oApi: L.internal, oStdClasses: L.classes, oPagination: L.pager });
    k.extend(u.ext.classes, {
        sTable: "dataTable",
        sNoFooter: "no-footer",
        sPageButton: "paginate_button",
        sPageButtonActive: "current",
        sPageButtonDisabled: "disabled",
        sStripeOdd: "odd",
        sStripeEven: "even",
        sRowEmpty: "dataTables_empty",
        sWrapper: "dataTables_wrapper",
        sFilter: "dataTables_filter",
        sInfo: "dataTables_info",
        sPaging: "dataTables_paginate paging_",
        sLength: "dataTables_length",
        sProcessing: "dataTables_processing",
        sSortAsc: "sorting_asc",
        sSortDesc: "sorting_desc",
        sSortable: "sorting",
        sSortableAsc: "sorting_desc_disabled",
        sSortableDesc: "sorting_asc_disabled",
        sSortableNone: "sorting_disabled",
        sSortColumn: "sorting_",
        sFilterInput: "",
        sLengthSelect: "",
        sScrollWrapper: "dataTables_scroll",
        sScrollHead: "dataTables_scrollHead",
        sScrollHeadInner: "dataTables_scrollHeadInner",
        sScrollBody: "dataTables_scrollBody",
        sScrollFoot: "dataTables_scrollFoot",
        sScrollFootInner: "dataTables_scrollFootInner",
        sHeaderTH: "",
        sFooterTH: "",
        sSortJUIAsc: "",
        sSortJUIDesc: "",
        sSortJUI: "",
        sSortJUIAscAllowed: "",
        sSortJUIDescAllowed: "",
        sSortJUIWrapper: "",
        sSortIcon: "",
        sJUIHeader: "",
        sJUIFooter: "",
    });
    var ec = u.ext.pager;
    k.extend(ec, {
        simple: function (a, b) {
            return ["previous", "next"];
        },
        full: function (a, b) {
            return ["first", "previous", "next", "last"];
        },
        numbers: function (a, b) {
            return [Ba(a, b)];
        },
        simple_numbers: function (a, b) {
            return ["previous", Ba(a, b), "next"];
        },
        full_numbers: function (a, b) {
            return ["first", "previous", Ba(a, b), "next", "last"];
        },
        first_last_numbers: function (a, b) {
            return ["first", Ba(a, b), "last"];
        },
        _numbers: Ba,
        numbers_length: 7,
    });
    k.extend(!0, u.ext.renderer, {
        pageButton: {
            _: function (a, b, c, d, e, f) {
                var g = a.oClasses,
                    h = a.oLanguage.oPaginate,
                    l = a.oLanguage.oAria.paginate || {},
                    n,
                    m,
                    p = 0,
                    t = function (x, r) {
                        var A,
                            E = g.sPageButtonDisabled,
                            I = function (B) {
                                kb(a, B.data.action, !0);
                            };
                        var W = 0;
                        for (A = r.length; W < A; W++) {
                            var M = r[W];
                            if (Array.isArray(M)) {
                                var C = k("<" + (M.DT_el || "div") + "/>").appendTo(x);
                                t(C, M);
                            } else {
                                n = null;
                                m = M;
                                C = a.iTabIndex;
                                switch (M) {
                                    case "ellipsis":
                                        x.append('<span class="ellipsis">&#x2026;</span>');
                                        break;
                                    case "first":
                                        n = h.sFirst;
                                        0 === e && ((C = -1), (m += " " + E));
                                        break;
                                    case "previous":
                                        n = h.sPrevious;
                                        0 === e && ((C = -1), (m += " " + E));
                                        break;
                                    case "next":
                                        n = h.sNext;
                                        if (0 === f || e === f - 1) (C = -1), (m += " " + E);
                                        break;
                                    case "last":
                                        n = h.sLast;
                                        if (0 === f || e === f - 1) (C = -1), (m += " " + E);
                                        break;
                                    default:
                                        (n = a.fnFormatNumber(M + 1)), (m = e === M ? g.sPageButtonActive : "");
                                }
                                null !== n &&
                                    ((C = k("<a>", { class: g.sPageButton + " " + m, "aria-controls": a.sTableId, "aria-label": l[M], "data-dt-idx": p, tabindex: C, id: 0 === c && "string" === typeof M ? a.sTableId + "_" + M : null })
                                        .html(n)
                                        .appendTo(x)),
                                    ob(C, { action: M }, I),
                                    p++);
                            }
                        }
                    };
                try {
                    var v = k(b).find(z.activeElement).data("dt-idx");
                } catch (x) {}
                t(k(b).empty(), d);
                v !== q &&
                    k(b)
                        .find("[data-dt-idx=" + v + "]")
                        .trigger("focus");
            },
        },
    });
    k.extend(u.ext.type.detect, [
        function (a, b) {
            b = b.oLanguage.sDecimal;
            return sb(a, b) ? "num" + b : null;
        },
        function (a, b) {
            if (a && !(a instanceof Date) && !tc.test(a)) return null;
            b = Date.parse(a);
            return (null !== b && !isNaN(b)) || ca(a) ? "date" : null;
        },
        function (a, b) {
            b = b.oLanguage.sDecimal;
            return sb(a, b, !0) ? "num-fmt" + b : null;
        },
        function (a, b) {
            b = b.oLanguage.sDecimal;
            return jc(a, b) ? "html-num" + b : null;
        },
        function (a, b) {
            b = b.oLanguage.sDecimal;
            return jc(a, b, !0) ? "html-num-fmt" + b : null;
        },
        function (a, b) {
            return ca(a) || ("string" === typeof a && -1 !== a.indexOf("<")) ? "html" : null;
        },
    ]);
    k.extend(u.ext.type.search, {
        html: function (a) {
            return ca(a) ? a : "string" === typeof a ? a.replace(gc, " ").replace(Ta, "") : "";
        },
        string: function (a) {
            return ca(a) ? a : "string" === typeof a ? a.replace(gc, " ") : a;
        },
    });
    var Sa = function (a, b, c, d) {
        if (0 !== a && (!a || "-" === a)) return -Infinity;
        b && (a = ic(a, b));
        a.replace && (c && (a = a.replace(c, "")), d && (a = a.replace(d, "")));
        return 1 * a;
    };
    k.extend(L.type.order, {
        "date-pre": function (a) {
            a = Date.parse(a);
            return isNaN(a) ? -Infinity : a;
        },
        "html-pre": function (a) {
            return ca(a) ? "" : a.replace ? a.replace(/<.*?>/g, "").toLowerCase() : a + "";
        },
        "string-pre": function (a) {
            return ca(a) ? "" : "string" === typeof a ? a.toLowerCase() : a.toString ? a.toString() : "";
        },
        "string-asc": function (a, b) {
            return a < b ? -1 : a > b ? 1 : 0;
        },
        "string-desc": function (a, b) {
            return a < b ? 1 : a > b ? -1 : 0;
        },
    });
    Va("");
    k.extend(!0, u.ext.renderer, {
        header: {
            _: function (a, b, c, d) {
                k(a.nTable).on("order.dt.DT", function (e, f, g, h) {
                    a === f && ((e = c.idx), b.removeClass(d.sSortAsc + " " + d.sSortDesc).addClass("asc" == h[e] ? d.sSortAsc : "desc" == h[e] ? d.sSortDesc : c.sSortingClass));
                });
            },
            jqueryui: function (a, b, c, d) {
                k("<div/>")
                    .addClass(d.sSortJUIWrapper)
                    .append(b.contents())
                    .append(k("<span/>").addClass(d.sSortIcon + " " + c.sSortingClassJUI))
                    .appendTo(b);
                k(a.nTable).on("order.dt.DT", function (e, f, g, h) {
                    a === f &&
                        ((e = c.idx),
                        b.removeClass(d.sSortAsc + " " + d.sSortDesc).addClass("asc" == h[e] ? d.sSortAsc : "desc" == h[e] ? d.sSortDesc : c.sSortingClass),
                        b
                            .find("span." + d.sSortIcon)
                            .removeClass(d.sSortJUIAsc + " " + d.sSortJUIDesc + " " + d.sSortJUI + " " + d.sSortJUIAscAllowed + " " + d.sSortJUIDescAllowed)
                            .addClass("asc" == h[e] ? d.sSortJUIAsc : "desc" == h[e] ? d.sSortJUIDesc : c.sSortingClassJUI));
                });
            },
        },
    });
    var xb = function (a) {
        return "string" === typeof a ? a.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;") : a;
    };
    u.render = {
        number: function (a, b, c, d, e) {
            return {
                display: function (f) {
                    if ("number" !== typeof f && "string" !== typeof f) return f;
                    var g = 0 > f ? "-" : "",
                        h = parseFloat(f);
                    if (isNaN(h)) return xb(f);
                    h = h.toFixed(c);
                    f = Math.abs(h);
                    h = parseInt(f, 10);
                    f = c ? b + (f - h).toFixed(c).substring(2) : "";
                    return g + (d || "") + h.toString().replace(/\B(?=(\d{3})+(?!\d))/g, a) + f + (e || "");
                },
            };
        },
        text: function () {
            return { display: xb, filter: xb };
        },
    };
    k.extend(u.ext.internal, {
        _fnExternApiFunc: fc,
        _fnBuildAjax: La,
        _fnAjaxUpdate: Fb,
        _fnAjaxParameters: Ob,
        _fnAjaxUpdateDraw: Pb,
        _fnAjaxDataSrc: Ma,
        _fnAddColumn: Wa,
        _fnColumnOptions: Da,
        _fnAdjustColumnSizing: ra,
        _fnVisibleToColumnIndex: sa,
        _fnColumnIndexToVisible: ta,
        _fnVisbleColumns: na,
        _fnGetColumns: Fa,
        _fnColumnTypes: Ya,
        _fnApplyColumnDefs: Cb,
        _fnHungarianMap: G,
        _fnCamelToHungarian: O,
        _fnLanguageCompat: ma,
        _fnBrowserDetect: Ab,
        _fnAddData: ea,
        _fnAddTr: Ga,
        _fnNodeToDataIndex: function (a, b) {
            return b._DT_RowIndex !== q ? b._DT_RowIndex : null;
        },
        _fnNodeToColumnIndex: function (a, b, c) {
            return k.inArray(c, a.aoData[b].anCells);
        },
        _fnGetCellData: S,
        _fnSetCellData: Db,
        _fnSplitObjNotation: ab,
        _fnGetObjectDataFn: ia,
        _fnSetObjectDataFn: da,
        _fnGetDataMaster: bb,
        _fnClearTable: Ha,
        _fnDeleteIndex: Ia,
        _fnInvalidate: va,
        _fnGetRowElements: $a,
        _fnCreateTr: Za,
        _fnBuildHead: Eb,
        _fnDrawHead: xa,
        _fnDraw: fa,
        _fnReDraw: ja,
        _fnAddOptionsHtml: Hb,
        _fnDetectHeader: wa,
        _fnGetUniqueThs: Ka,
        _fnFeatureHtmlFilter: Jb,
        _fnFilterComplete: ya,
        _fnFilterCustom: Sb,
        _fnFilterColumn: Rb,
        _fnFilter: Qb,
        _fnFilterCreateSearch: gb,
        _fnEscapeRegex: hb,
        _fnFilterData: Tb,
        _fnFeatureHtmlInfo: Mb,
        _fnUpdateInfo: Wb,
        _fnInfoMacros: Xb,
        _fnInitialise: za,
        _fnInitComplete: Na,
        _fnLengthChange: ib,
        _fnFeatureHtmlLength: Ib,
        _fnFeatureHtmlPaginate: Nb,
        _fnPageChange: kb,
        _fnFeatureHtmlProcessing: Kb,
        _fnProcessingDisplay: U,
        _fnFeatureHtmlTable: Lb,
        _fnScrollDraw: Ea,
        _fnApplyToChildren: Z,
        _fnCalculateColumnWidths: Xa,
        _fnThrottle: fb,
        _fnConvertToWidth: Zb,
        _fnGetWidestNode: $b,
        _fnGetMaxLenString: ac,
        _fnStringToCss: K,
        _fnSortFlatten: pa,
        _fnSort: Gb,
        _fnSortAria: cc,
        _fnSortListener: nb,
        _fnSortAttachListener: db,
        _fnSortingClasses: Pa,
        _fnSortData: bc,
        _fnSaveState: Qa,
        _fnLoadState: dc,
        _fnSettingsFromNode: Ra,
        _fnLog: aa,
        _fnMap: V,
        _fnBindAction: ob,
        _fnCallbackReg: Q,
        _fnCallbackFire: H,
        _fnLengthOverflow: jb,
        _fnRenderer: eb,
        _fnDataSource: P,
        _fnRowAttributes: cb,
        _fnExtend: pb,
        _fnCalculateEnd: function () {},
    });
    k.fn.dataTable = u;
    u.$ = k;
    k.fn.dataTableSettings = u.settings;
    k.fn.dataTableExt = u.ext;
    k.fn.DataTable = function (a) {
        return k(this).dataTable(a).api();
    };
    k.each(u, function (a, b) {
        k.fn.DataTable[a] = b;
    });
    return k.fn.dataTable;
});

//ar_integration.js

jQuery(document).ready(function ($) {
    var $ar_settings = $("#ar-settings");
    var $hidden_fields_container = $(".wi-form-fields--hidden .fieldblock__content");
    var $wi_available_fields = $("#wi-available-fields");
    var $ar_code = $("#ar_code");
    var $form_builder = $("#wi-form-builder");
    var $form_builder_invisible = $("#wi-form-builder-invisible");
    var $ar_available_mappings = $("#ar_available_mappings");
    var $extracted_form_fields = $(".extracted-form_fields");

    wait({
        on: "wi-form-builder",
        do: function () {
            $ar_code.on("change", function () {
                var $fields = get_form_fields($(this));
                $wi_available_fields.html("");
                $hidden_fields_container.html("");
                $ar_available_mappings.removeData("mappings");
                $fields.each(function () {
                    if ($.inArray($(this).attr("type"), ["text", "email", "hidden", "checkbox"]) >= 0 || ($(this).context && $.inArray($(this).context.tagName, ["textarea", "select"]) >= 0)) {
                        if (!$ar_available_mappings.data("mappings")) {
                            var mappings = [];
                        } else {
                            var mappings = $ar_available_mappings.data("mappings");
                        }
                        mappings.push($(this).attr("name"));
                        $ar_available_mappings.data("mappings", mappings);
                    } else {
                        //store hidden fields to add them later
                        ar_add_form_field_hidden($(this).attr("name"), $(this).val());
                    }
                });
                //hide fields that are used already
                hide_used_fields();
            });

            // when page is loaded, populate the form builder area
            $ar_code.change();
            // up to here

            function hide_used_fields() {
                $extracted_form_fields.find(".wi-form-field").each(function () {
                    if ($(this).children(".wi-field-add").length > 0) {
                        var field_name = $(this).children(".wi-field-add").data("name");
                        if (field_name) {
                            if (is_field_name_in_form_builder(field_name)) $(this).hide();
                            else $(this).show();
                        }
                    }
                });
            }

			$(".wi-field-set").on("click", function () {
				var inputValue = $ar_code.val();
				var action_url = "";
				var form_method = "post";
			
				try {
					// Check if inputValue is a valid URL
					new URL(inputValue);
					// If valid URL, use it directly
					action_url = inputValue;
				} catch (e) {
					// If not a valid URL, treat it as HTML and try to find a form
					var $form = jDOM(inputValue).find("form");
					if ($form.length > 0) {
						action_url = $form.attr("action") || "";
						form_method = $form.attr("method") || "post";
					} else {
						console.warn("No form found in the input.");
					}
				}
			
				// Set the URL and method values in the respective fields
				$("#ar_url").val(action_url);
				$("#ar_method").val(form_method.toLowerCase());
			});
            $(".extracted-form_fields").on("click", ".wi-field-add", function () {
                if ($(this).data("hidden") === "true") {
                    var field_names = $(this).data("names");
                    $hidden_fields_container.html("");
                    for (i in field_names) {
                        ar_add_form_field_hidden(i, field_names[i]);
                    }
                } else {
                    var field_ar_name = $(this).data("name");
                    if (is_field_name_in_form_builder(field_ar_name)) {
                        return false;
                    }
                    ar_add_form_field(field_ar_name);
                }
                // after adding new field, refresh states
                refresh_form_builder();
                hide_used_fields();
            });

            function is_field_name_in_form_builder(field_name) {
                var $_fields = $form_builder.find("input.field__ar-name");
                for (var i = 0; i < $_fields.length; i++) {
                    if ($_fields[i].value === field_name) return true;
                }
                return false;
            }

            function ar_add_form_field_hidden(field_name, field_value) {
                var obtmp = $("#ar_templates .form-builder-hidden-field .field-block--table").clone();
                obtmp.find(".fieldblock__name").val(field_name);
                obtmp.find(".fieldblock__value").val(field_value);
                obtmp.appendTo($hidden_fields_container);
            }

            function ar_label_by_name(ar_name) {
                return $("#ar_templates .labels")
                    .find("." + ar_name)
                    .val();
            }
            function ar_placeholder_by_name(ar_name) {
                return $("#ar_templates .labels")
                    .find("." + ar_name)
                    .attr("data-placeholder");
            }

            function ar_label_name_by_name(ar_name) {
                return $("#ar_templates .label_names")
                    .find("." + ar_name)
                    .val();
            }

            function ar_add_form_field(field_ar_name, field_label, field_mapping) {
                console.log("field_ar_name",field_ar_name);
                console.log("field_label",field_label);
                console.log("field_mapping",field_mapping);

                // Code for required checkboxes
                var required_fields = $ar_settings.find('[name^="ar_required_fields"]'),
                    required_fields_array = [];

                required_fields.each(function () {
                    var required_field_name = $(this).val();
                    required_fields_array.push(required_field_name);
                });


                // Code for show in new row checkboxes
                // var new_row_fields = $ar_settings.find('[name^="ar_required_fields"]'),
                //     new_row_fields_array = [];
            
                // new_row_fields.each(function () {
                //     var new_row_field_name = $(this).val();
                //     new_row_fields_array.push(new_row_field_name);
                // });

                // console.log("new_row_fields_array",new_row_fields_array);

                var newField;
                var obtmp;
                var gdprHeading = $("#form-builder-gdpr-heading");
                var hiddenFields = ["ar_webinar_title", "ar_webinar_host", "ar_webinar_url", "ar_webinar_date", "ar_webinar_time", "ar_webinar_timezone", "ar_webinar_registration_date", "ar_webinar_registration_time", "ar_utm_source", "ar_webinar_date_time",];

                if ($.inArray(field_ar_name, hiddenFields) >= 0) {
                    obtmp = $("#ar_templates .form-builder .wi-form-fieldblock.wi-form-fieldblock-invisible").clone().insertBefore(gdprHeading);
                } else if ($.inArray(field_ar_name, ["ar_privacy_policy", "ar_terms_and_conditions", "ar_mailing_list"]) >= 0) {
                    obtmp = $("#ar_templates .form-builder .wi-form-fieldblock.wi-form-fieldblock-visible").clone().insertAfter($("#wi-form-builder li:last-child"));
                    if ($.inArray(field_ar_name, required_fields_array) >= 0) {
                        obtmp.find(".required_ar").prop("checked", true);
                    }
                    gdprHeading.show();
                } else {
                    if (
                        field_ar_name == "ar_custom_8" ||
                        field_ar_name == "ar_custom_9" ||
                        field_ar_name == "ar_custom_10" ||
                        field_ar_name == "ar_custom_11" ||
                        field_ar_name == "ar_custom_12" ||
                        field_ar_name == "ar_custom_13" ||
                        field_ar_name == "ar_custom_14"
                    ) {
                        obtmp = $("#ar_templates .form-builder .wi-form-fieldblock.wi-form-fieldblock-custom").clone();
                        obtmp.insertBefore(gdprHeading);
                    } else if (field_ar_name == "ar_custom_15" || field_ar_name == "ar_custom_16" || field_ar_name == "ar_custom_17" || field_ar_name == "ar_custom_18") {
                        obtmp = $("#ar_templates .form-builder .wi-form-fieldblock.wi-form-fieldblock-select").clone();

                        if ($.inArray(field_ar_name, required_fields_array) >= 0) {
                            obtmp.find(".required_ar").prop("checked", true);
                        }
                        obtmp.insertBefore(gdprHeading);
                    } else {
                        obtmp = $("#ar_templates .form-builder .wi-form-fieldblock.wi-form-fieldblock-visible").clone();

                        if ($.inArray(field_ar_name, required_fields_array) >= 0) {
                            obtmp.find(".required_ar").prop("checked", true);

                            if (field_ar_name === "ar_email") {
                                obtmp.find(".required_ar").prop("disabled", true);
                            }
                        }
                        obtmp.insertBefore(gdprHeading);
                    }
                }

                if (!!field_label) {
                    obtmp.find(".field__label").val(field_label);
                }
                if (!!field_ar_name) {
                    obtmp.find(".field__ar-name").val(field_ar_name);
                    var field_ar_label = ar_label_by_name(field_ar_name); //working_line
                    const field_ar_placeholder = ar_placeholder_by_name(field_ar_name);
                    obtmp.find(".field__ar-label").val(field_ar_label);
  					 

                    console.log("field_ar_name12", field_ar_name);
                    console.log("field_label12", field_label);
                    console.log("if is called even first time");
					if (field_label == undefined || field_label == "") {
                        console.log("code insdie if works");
                        obtmp.find(".field__ar-placeholder").val(field_ar_placeholder);
                    }

					
					obtmp.find(".field__label-name").val(ar_label_name_by_name(field_ar_name));
                    if (field_ar_name === "ar_privacy_policy" && !field_label) {
                        obtmp.find(".field__label").val(webinarignitionTranslations.privacyPolicy);
                    }
                    if (field_ar_name === "ar_terms_and_conditions" && !field_label) {
                        obtmp.find(".field__label").val(webinarignitionTranslations.termsAndConditions);
                    }
                    if (field_ar_name === "ar_mailing_list" && !field_label) {
                        obtmp.find(".field__label").val(webinarignitionTranslations.mailingList);
                    }
                }
                if (!!field_mapping) {
                    obtmp.find(".field__ar-mapping").mouseover().val(field_mapping);
                }

                if (field_ar_name == "ar_custom_15" || field_ar_name == "ar_custom_16" || field_ar_name == "ar_custom_17" || field_ar_name == "ar_custom_18") {
                    var index = field_ar_name.replace("ar_custom_", "");
                    var options = "";

                    if ($("#ar-settings").find('[name="lp_optin_custom_select_' + index + '"]')) {
                        options = $("#ar-settings")
                            .find('[name="lp_optin_custom_select_' + index + '"]')
                            .val();
                    }

                    obtmp.find(".field__options").val(options);
                }

                if ($.inArray(field_ar_name, hiddenFields) >= 0) {
                    //Remove ar_ from hidden field names
                    var camelCased = field_ar_name.replace("ar_", "");
                } else {
                    var camelCased = field_ar_name.replace("ar", "opt").replace(/_([a-z])/g, function (g) {
                        return g[1].toUpperCase();
                    });
                }

                if (camelCased === "optLname") {
                    camelCased = "optLName";
                } else if (camelCased === "optPrivacyPolicy") {
                    camelCased = "optGDPR_PP";
                } else if (camelCased === "optTermsAndConditions") {
                    camelCased = "optGDPR_TC";
                } else if (camelCased === "optMailingList") {
                    camelCased = "optGDPR_ML";
                }

                obtmp.find(".field-block.field-block--cell:nth-child(2) .sublabel span").text(camelCased);
            }

            $form_builder.sortable();

            $form_builder.on("mouseover", ".field__ar-mapping", function () {
                var current_value = $(this).val();
                mappings = $("#ar_available_mappings").data("mappings");
                if (mappings) {
                    $(this).html("");
                    $(this).append($("<option>", { value: "", text: "* Not mapped" }));
                    for (i in mappings) {
                        $(this).append($("<option>", { value: mappings[i], text: mappings[i] }));
                    }
                    $(this).val(current_value);
                }
            });

            $form_builder.on("change", ".field__ar-mapping", function () {
                var $obj = $(this);
                var backup_value = $obj.val();
                $form_builder.find(".field__ar-mapping").each(function () {
                    if ($(this).val() === backup_value) $(this).val("");
                });
                $obj.val(backup_value);
            });

            $form_builder.on("click", ".js__fieldblock-remove", function (event) {
                event.preventDefault();
                $(this).parents(".wi-form-fieldblock").remove();
                hide_used_fields();
            });

            $(".wi-form-fields--hidden").on("click", ".js__fieldblock-remove", function (event) {
                event.preventDefault();
                $(this).closest(".wi-form-fields--hidden").find(".fieldblock__content").html("");
                hide_used_fields();
            });

            function refresh_form_builder() {
                $form_builder.sortable("refresh");
            }

            function jDOM(html) {
                return $("<div>" + html + "</div>");
            }

            function get_form_fields($form) {
				try {
					// Try to get form HTML correctly depending on element type
					var formContent = '';

					if ($form.is('textarea, input')) {
					formContent = $form.val();
					} else {
					formContent = $form.html() || $form.prop('outerHTML');
					}

					// Function to check if string is a valid URL
					function isValidURL(string) {
					try {
						new URL(string);
						return true;
					} catch (_) {
						return false;
					}
					}

					if (formContent) {
					// Check if formContent is a URL
					if (isValidURL(formContent)) {
						console.warn("URL detected instead of form HTML.");
						return $(); // Return an empty jQuery object
					}

					// Parse as HTML
					var $parsedForm = $(formContent);

					// If outer div contains the form inside it, find it
					if (!$parsedForm.is('form')) {
						$parsedForm = $parsedForm.find('form').first();
					}

					if ($parsedForm.length) {
						return $parsedForm.find('input, textarea, select');
					} else {
						console.warn("No form found in the provided HTML.");
						return $();
					}
					} else {
					console.warn("No valid content found in $form.");
					return $();
					}
				} catch (e) {
					console.error("Error parsing form content:", e);
					return $();
				}
			}


            function generate_ar_settings() {
                $ar_settings.html("");
                $("<input>").attr("type", "hidden").attr("name", "ar_url").val($("#ar_url").val()).appendTo($ar_settings);
                $("<input>").attr("type", "hidden").attr("name", "ar_method").val($("#ar_method").val()).appendTo($ar_settings);

                $form_builder.find(".wi-form-fieldblock").each(function (elem) {
                    var ar_mapping = $(this).find(".field__ar-mapping").val(),
                        ar_field_name = $(this).find(".field__ar-name").val(),
                        label_field_name = $(this).find(".field__label-name").val(),
                        label = $(this).find(".field__label").val(),
                        isRequired = $(this).find(".required_ar").is(":checked"), 
						// multi col code
                        // isShowInNewRow = $(this).find(".new_row_ar").is(":checked"),
                        options = "";

                    $("<input>").attr("type", "hidden").attr("name", "ar_fields_order[]").val(ar_field_name).appendTo($ar_settings);
                    $("<input>").attr("type", "hidden").attr("name", ar_field_name).val(ar_mapping).appendTo($ar_settings);
                    $("<input>").attr("type", "hidden").attr("name", label_field_name).val(label).appendTo($ar_settings);

                    if ($(this).find(".field__options").length) {
                        options = $(this).find(".field__options").val();
                        $("<textarea>").attr("name", label_field_name.replace("lp_optin_custom", "lp_optin_custom_select")).val(options).appendTo($ar_settings);
                    }

                    if (isRequired) {
                        $("<input>").attr("type", "hidden").attr("name", "ar_required_fields[]").val(ar_field_name).appendTo($ar_settings);
                    }
					// multi col code
                    // if (isShowInNewRow) {
                    //     $("<input>").attr("type", "hidden").attr("name", "ar_new_row_fields[]").val(ar_field_name).appendTo($ar_settings);
				    // }
                });

                var hidden_fields = "";
                $("#wi-form-hidden-fields")
                    .children(".field-group")
                    .each(function () {
                        var value = $(this).find(".fieldblock__value").val(),
                            name = $(this).find(".fieldblock__name").val();

                        hidden_fields += $("<input>").attr("type", "hidden").attr("name", name).val(value)[0].outerHTML;
                    });
                $("<input>").attr("type", "hidden").attr("name", "ar_hidden").val(hidden_fields).appendTo($ar_settings);
            }

            // assign webinarignition_pre_save variable
            webinarignition_pre_save = function () {
                generate_ar_settings();
            };

            function update_ar_builder_from_settings() {
                var $ar_url = $ar_settings.find('[name="ar_url"]'),
                    $ar_method = $ar_settings.find('[name="ar_method"]'),
                    $ar_hidden = $ar_settings.find('[name="ar_hidden"]');

                if (!!$ar_url.val()) {
                    $("#ar_url").val($ar_url.val());
                }
                if (!!$ar_method) {
                    $("#ar_method").val($ar_method.val());
                }

                $ar_settings.find('[name^="ar_fields_order"]').each(function () {
                    var current_field = $(this).val();
                    var validFields = [
                        "ar_name",
                        "ar_lname",
                        "ar_email",
                        "ar_phone",
                        "ar_salutation",
                        "ar_reason",
                        "ar_custom_1",
                        "ar_custom_2",
                        "ar_custom_3",
                        "ar_custom_4",
                        "ar_custom_5",
                        "ar_custom_6",
                        "ar_custom_7",
                        "ar_custom_8",
                        "ar_custom_9",
                        "ar_custom_10",
                        "ar_custom_11",
                        "ar_custom_12",
                        "ar_custom_13",
                        "ar_custom_14",

                        "ar_custom_15",
                        "ar_custom_16",
                        "ar_custom_17",
                        "ar_custom_18",

                        "ar_utm_source",
                        "ar_privacy_policy",
                        "ar_terms_and_conditions",
                        "ar_mailing_list",
                        "ar_webinar_title",
                        "ar_webinar_host",
                        "ar_webinar_url",
                        "ar_webinar_date",
                        "ar_webinar_time",
                        "ar_webinar_timezone",
                        "ar_webinar_registration_date",
                        "ar_webinar_registration_time",
						"ar_webinar_date_time",
                    ];
                    if ($.inArray(current_field, validFields) >= 0) {
                        _label_name = ar_label_name_by_name(current_field);
                        console.log("$ar_settings",$ar_settings);
                        console.log("_label_name",_label_name);
                        ar_add_form_field(current_field, $ar_settings.find('[name="' + _label_name + '"]').val(), $ar_settings.find('[name="' + current_field + '"]').val());
                    }
                });

                if (!!$ar_hidden) {
                    $hidden_fields = jDOM($ar_hidden.val()).find("input");
                    $hidden_fields_container.html("");
                    $hidden_fields.each(function () {
                        ar_add_form_field_hidden($(this).attr("name"), $(this).val());
                    });
                }
                //hide used fields from available fields
                hide_used_fields();
            }

            update_ar_builder_from_settings();
        },
    });
});

/*!
 * jQuery Plugin: Are-You-Sure (Dirty Form Detection)
 * https://github.com/codedance/jquery.AreYouSure/
 *
 * Copyright (c) 2012-2014, Chris Dance and PaperCut Software http://www.papercut.com/
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Author:  chris.dance@papercut.com
 * Version: 1.9.0
 * Date:    13th August 2014
 */
(function ($) {
    $.fn.areYouSure = function (options) {
        var settings = $.extend(
            {
                message: "You have unsaved changes!",
                dirtyClass: "dirty",
                change: null,
                silent: false,
                addRemoveFieldsMarksDirty: false,
                fieldEvents: "keyup propertychange input",
                fieldSelector: ":input:not(input[type=submit]):not(input[type=button])",
            },
            options
        );

        var getValue = function ($field) {
            if ($field.hasClass("ays-ignore") || $field.hasClass("aysIgnore") || $field.attr("data-ays-ignore") || $field.attr("name") === undefined) {
                return null;
            }

            if ($field.is(":disabled")) {
                return "ays-disabled";
            }

            var val;
            var type = $field.attr("type");
            if ($field.is("select")) {
                type = "select";
            }

            switch (type) {
                case "checkbox":
                case "radio":
                    val = $field.is(":checked");
                    break;
                case "select":
                    val = "";
                    $field.find("option").each(function (o) {
                        var $option = $(this);
                        if ($option.is(":selected")) {
                            val += $option.val();
                        }
                    });
                    break;
                default:
                    val = $field.val();
            }

            return val;
        };

        var storeOrigValue = function ($field) {
            $field.data("ays-orig", getValue($field));
        };

        var checkForm = function (evt) {
            var isFieldDirty = function ($field) {
                var origValue = $field.data("ays-orig");
                if (undefined === origValue) {
                    return false;
                }
                return getValue($field) != origValue;
            };

            var $form = $(this).is("form") ? $(this) : $(this).parents("form");

            // Test on the target first as it's the most likely to be dirty
            if (isFieldDirty($(evt.target))) {
                setDirtyStatus($form, true);
                return;
            }

            $fields = $form.find(settings.fieldSelector);

            if (settings.addRemoveFieldsMarksDirty) {
                // Check if field count has changed
                var origCount = $form.data("ays-orig-field-count");
                if (origCount != $fields.length) {
                    setDirtyStatus($form, true);
                    return;
                }
            }

            // Brute force - check each field
            var isDirty = false;
            $fields.each(function () {
                $field = $(this);
                if (isFieldDirty($field)) {
                    isDirty = true;
                    return false; // break
                }
            });

            setDirtyStatus($form, isDirty);
        };

        var initForm = function ($form) {
            var fields = $form.find(settings.fieldSelector);
            $(fields).each(function () {
                storeOrigValue($(this));
            });
            $(fields).off(settings.fieldEvents, checkForm);
            $(fields).on(settings.fieldEvents, checkForm);
            $form.data("ays-orig-field-count", $(fields).length);
            setDirtyStatus($form, false);
        };

        var setDirtyStatus = function ($form, isDirty) {
            var changed = isDirty != $form.hasClass(settings.dirtyClass);
            $form.toggleClass(settings.dirtyClass, isDirty);

            // Fire change event if required
            if (changed) {
                if (settings.change) settings.change.call($form, $form);

                if (isDirty) $form.trigger("dirty.areYouSure", [$form]);
                if (!isDirty) $form.trigger("clean.areYouSure", [$form]);
                $form.trigger("change.areYouSure", [$form]);
            }
        };

        var rescan = function () {
            var $form = $(this);
            var fields = $form.find(settings.fieldSelector);
            $(fields).each(function () {
                var $field = $(this);
                if (!$field.data("ays-orig")) {
                    storeOrigValue($field);
                    $field.bind(settings.fieldEvents, checkForm);
                }
            });
            // Check for changes while we're here
            $form.trigger("checkform.areYouSure");
        };

        var reinitialize = function () {
            initForm($(this));
        };

        if (!settings.silent && !window.aysUnloadSet) {
            window.aysUnloadSet = true;
            $(window).on("beforeunload", function () {
                $dirtyForms = $("form").filter("." + settings.dirtyClass);
                if ($dirtyForms.length == 0) {
                    return;
                }
                // Prevent multiple prompts - seen on Chrome and IE
                if (navigator.userAgent.toLowerCase().match(/msie|chrome/)) {
                    if (window.aysHasPrompted) {
                        return;
                    }
                    window.aysHasPrompted = true;
                    window.setTimeout(function () {
                        window.aysHasPrompted = false;
                    }, 900);
                }
                return settings.message;
            });
        }

        return this.each(function (elem) {
            if (!$(this).is("form")) {
                return;
            }
            var $form = $(this);

            $form.on("submit", function () {
                $form.removeClass(settings.dirtyClass);
            });
            $form.on("reset", function () {
                setDirtyStatus($form, false);
            });
            // Add a custom events
            $form.on("rescan.areYouSure", rescan);
            $form.on("reinitialize.areYouSure", reinitialize);
            $form.on("checkform.areYouSure", checkForm);
            initForm($form);
        });
    };
})(jQuery);

jQuery(document).ready(function ($) {
    $("#custom_registration_page_1").change(function () {
        let values = $(this).val();
        console.log("values jquery", values);

        $("#custom_registration_page")
            .children()
            .each(function () {
                if ($.inArray($(this).attr("value"), values) >= 0) {
                    $(this).prop("selected", true);
                } else {
                    $(this).prop("selected", false);
                }
            });
    });

    $(".wi_webinar_preview_box").click(function (e) {
        if (e.target.localName === "a" || (e.target.localName === "i" && e.target.className === "icon-external-link")) return;

        $(this).find("input").prop("checked", true);
        let value = $(this).find("input").val();

        $(".wi_webinar_preview_box").removeClass("checked");
        $(".wi_webinar_preview_box_" + value).addClass("checked");

        $(".wi_webinar_preview_box i.icon").removeClass("icon-circle").addClass("icon-circle-blank");
        $(".wi_webinar_preview_box_" + value + " i.icon")
            .removeClass("icon-circle-blank")
            .addClass("icon-circle");

        let url = $(".wi_webinar_preview_box.checked .default_registration_page").data("page_url");
        $("#custom_registration_page-shareUrl-1").val(url);
        $(".custom_registration_page-webinarPreviewLinkDefaultHolder-1").attr("href", url);
    });

    let url = $(".wi_webinar_preview_box.checked .default_registration_page").data("page_url");
    $("#custom_registration_page-shareUrl-1").val(url);
    $(".custom_registration_page-webinarPreviewLinkDefaultHolder-1").attr("href", url);
    if ($("#auto_registration_page-shareUrl-1").length) {
        $("#auto_registration_page-shareUrl-1").val(url + webinarignitionoegparama);
    }
    if ($("#auto_registration_page-shareUrl-2").length) {
        $("#auto_registration_page-shareUrl-2").val(url + webinarignitiononeclickparama);
    }
    $(".wi_webinar_preview_box").click(function () {
        var radioInput = $(this).find("input[type=radio][name=default_registration_page]");
        if (radioInput.length > 0) {
            // Do whatever you want with the radio input here
            var page_url_data = radioInput.data("page_url");
            $("#custom_registration_page-shareUrl-1").val(page_url_data);
            $("#auto_registration_page-shareUrl-1").val(page_url_data + webinarignitionoegparama);
            $("#auto_registration_page-shareUrl-2").val(page_url_data + webinarignitiononeclickparama);
        } else {
            console.log("Radio input not found within this div.");
        }
    });
});

//nav_prompt.js
jQuery(function () {
    jQuery("form").areYouSure(), jQuery("form.dirty-check").areYouSure(), jQuery("form").areYouSure({ message: "Your changes are not saved!" });
});

/*!
 * dist/jquery.inputmask.min
 * https://github.com/RobinHerbots/Inputmask
 * Copyright (c) 2010 - 2021 Robin Herbots
 * Licensed under the MIT license
 * Version: 5.0.6-beta.40
 */
!(function webpackUniversalModuleDefinition(root, factory) {
    if ("object" == typeof exports && "object" == typeof module) module.exports = factory(require("jquery"));
    else if ("function" == typeof define && define.amd) define(["jquery"], factory);
    else {
        var a = "object" == typeof exports ? factory(require("jquery")) : factory(root.jQuery);
        for (var i in a) ("object" == typeof exports ? exports : root)[i] = a[i];
    }
})(this, function (__WEBPACK_EXTERNAL_MODULE__10__) {
    return (
        (modules = [
            function (module) {
                module.exports = JSON.parse(
                    '{"BACKSPACE":8,"BACKSPACE_SAFARI":127,"DELETE":46,"DOWN":40,"END":35,"ENTER":13,"ESCAPE":27,"HOME":36,"INSERT":45,"LEFT":37,"PAGE_DOWN":34,"PAGE_UP":33,"RIGHT":39,"SPACE":32,"TAB":9,"UP":38,"X":88,"CONTROL":17,"KEY_229":229}'
                );
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }),
                    (exports.caret = caret),
                    (exports.determineLastRequiredPosition = determineLastRequiredPosition),
                    (exports.determineNewCaretPosition = determineNewCaretPosition),
                    (exports.getBuffer = getBuffer),
                    (exports.getBufferTemplate = getBufferTemplate),
                    (exports.getLastValidPosition = getLastValidPosition),
                    (exports.isMask = isMask),
                    (exports.resetMaskSet = resetMaskSet),
                    (exports.seekNext = seekNext),
                    (exports.seekPrevious = seekPrevious),
                    (exports.translatePosition = translatePosition);
                var _validationTests = __webpack_require__(3),
                    _validation = __webpack_require__(4);
                function caret(input, begin, end, notranslate, isDelete) {
                    var inputmask = this,
                        opts = this.opts,
                        range;
                    if (void 0 === begin)
                        return (
                            "selectionStart" in input && "selectionEnd" in input
                                ? ((begin = input.selectionStart), (end = input.selectionEnd))
                                : window.getSelection
                                ? ((range = window.getSelection().getRangeAt(0)), (range.commonAncestorContainer.parentNode !== input && range.commonAncestorContainer !== input) || ((begin = range.startOffset), (end = range.endOffset)))
                                : document.selection &&
                                  document.selection.createRange &&
                                  ((range = document.selection.createRange()), (begin = 0 - range.duplicate().moveStart("character", -input.inputmask._valueGet().length)), (end = begin + range.text.length)),
                            { begin: notranslate ? begin : translatePosition.call(this, begin), end: notranslate ? end : translatePosition.call(this, end) }
                        );
                    if (
                        (Array.isArray(begin) && ((end = this.isRTL ? begin[0] : begin[1]), (begin = this.isRTL ? begin[1] : begin[0])),
                        void 0 !== begin.begin && ((end = this.isRTL ? begin.begin : begin.end), (begin = this.isRTL ? begin.end : begin.begin)),
                        "number" == typeof begin)
                    ) {
                        (begin = notranslate ? begin : translatePosition.call(this, begin)), (end = notranslate ? end : translatePosition.call(this, end)), (end = "number" == typeof end ? end : begin);
                        var scrollCalc = parseInt(((input.ownerDocument.defaultView || window).getComputedStyle ? (input.ownerDocument.defaultView || window).getComputedStyle(input, null) : input.currentStyle).fontSize) * end;
                        if (
                            ((input.scrollLeft = scrollCalc > input.scrollWidth ? scrollCalc : 0),
                            (input.inputmask.caretPos = { begin: begin, end: end }),
                            opts.insertModeVisual && !1 === opts.insertMode && begin === end && (isDelete || end++),
                            input === (input.inputmask.shadowRoot || input.ownerDocument).activeElement)
                        )
                            if ("setSelectionRange" in input) input.setSelectionRange(begin, end);
                            else if (window.getSelection) {
                                if (((range = document.createRange()), void 0 === input.firstChild || null === input.firstChild)) {
                                    var textNode = document.createTextNode("");
                                    input.appendChild(textNode);
                                }
                                range.setStart(input.firstChild, begin < input.inputmask._valueGet().length ? begin : input.inputmask._valueGet().length),
                                    range.setEnd(input.firstChild, end < input.inputmask._valueGet().length ? end : input.inputmask._valueGet().length),
                                    range.collapse(!0);
                                var sel = window.getSelection();
                                sel.removeAllRanges(), sel.addRange(range);
                            } else input.createTextRange && ((range = input.createTextRange()), range.collapse(!0), range.moveEnd("character", end), range.moveStart("character", begin), range.select());
                    }
                }
                function determineLastRequiredPosition(returnDefinition) {
                    var inputmask = this,
                        maskset = this.maskset,
                        $ = this.dependencyLib,
                        buffer = _validationTests.getMaskTemplate.call(this, !0, getLastValidPosition.call(this), !0, !0),
                        bl = buffer.length,
                        pos,
                        lvp = getLastValidPosition.call(this),
                        positions = {},
                        lvTest = maskset.validPositions[lvp],
                        ndxIntlzr = void 0 !== lvTest ? lvTest.locator.slice() : void 0,
                        testPos;
                    for (pos = lvp + 1; pos < buffer.length; pos++) (testPos = _validationTests.getTestTemplate.call(this, pos, ndxIntlzr, pos - 1)), (ndxIntlzr = testPos.locator.slice()), (positions[pos] = $.extend(!0, {}, testPos));
                    var lvTestAlt = lvTest && void 0 !== lvTest.alternation ? lvTest.locator[lvTest.alternation] : void 0;
                    for (
                        pos = bl - 1;
                        lvp < pos &&
                        ((testPos = positions[pos]),
                        (testPos.match.optionality ||
                            (testPos.match.optionalQuantifier && testPos.match.newBlockMarker) ||
                            (lvTestAlt &&
                                ((lvTestAlt !== positions[pos].locator[lvTest.alternation] && 1 != testPos.match.static) ||
                                    (!0 === testPos.match.static &&
                                        testPos.locator[lvTest.alternation] &&
                                        _validation.checkAlternationMatch.call(this, testPos.locator[lvTest.alternation].toString().split(","), lvTestAlt.toString().split(",")) &&
                                        "" !== _validationTests.getTests.call(this, pos)[0].def)))) &&
                            buffer[pos] === _validationTests.getPlaceholder.call(this, pos, testPos.match));
                        pos--
                    )
                        bl--;
                    return returnDefinition ? { l: bl, def: positions[bl] ? positions[bl].match : void 0 } : bl;
                }
                function determineNewCaretPosition(selectedCaret, tabbed, positionCaretOnClick) {
                    var inputmask = this,
                        maskset = this.maskset,
                        opts = this.opts;
                    function doRadixFocus(clickPos) {
                        if ("" !== opts.radixPoint && 0 !== opts.digits) {
                            var vps = maskset.validPositions;
                            if (void 0 === vps[clickPos] || vps[clickPos].input === _validationTests.getPlaceholder.call(inputmask, clickPos)) {
                                if (clickPos < seekNext.call(inputmask, -1)) return !0;
                                var radixPos = getBuffer.call(inputmask).indexOf(opts.radixPoint);
                                if (-1 !== radixPos) {
                                    for (var vp in vps) if (vps[vp] && radixPos < vp && vps[vp].input !== _validationTests.getPlaceholder.call(inputmask, vp)) return !1;
                                    return !0;
                                }
                            }
                        }
                        return !1;
                    }
                    if ((tabbed && (inputmask.isRTL ? (selectedCaret.end = selectedCaret.begin) : (selectedCaret.begin = selectedCaret.end)), selectedCaret.begin === selectedCaret.end)) {
                        switch (((positionCaretOnClick = positionCaretOnClick || opts.positionCaretOnClick), positionCaretOnClick)) {
                            case "none":
                                break;
                            case "select":
                                selectedCaret = { begin: 0, end: getBuffer.call(inputmask).length };
                                break;
                            case "ignore":
                                selectedCaret.end = selectedCaret.begin = seekNext.call(inputmask, getLastValidPosition.call(inputmask));
                                break;
                            case "radixFocus":
                                if (doRadixFocus(selectedCaret.begin)) {
                                    var radixPos = getBuffer.call(inputmask).join("").indexOf(opts.radixPoint);
                                    selectedCaret.end = selectedCaret.begin = opts.numericInput ? seekNext.call(inputmask, radixPos) : radixPos;
                                    break;
                                }
                            default:
                                var clickPosition = selectedCaret.begin,
                                    lvclickPosition = getLastValidPosition.call(inputmask, clickPosition, !0),
                                    lastPosition = seekNext.call(inputmask, -1 !== lvclickPosition || isMask.call(inputmask, 0) ? lvclickPosition : -1);
                                if (clickPosition <= lastPosition) selectedCaret.end = selectedCaret.begin = isMask.call(inputmask, clickPosition, !1, !0) ? clickPosition : seekNext.call(inputmask, clickPosition);
                                else {
                                    var lvp = maskset.validPositions[lvclickPosition],
                                        tt = _validationTests.getTestTemplate.call(inputmask, lastPosition, lvp ? lvp.match.locator : void 0, lvp),
                                        placeholder = _validationTests.getPlaceholder.call(inputmask, lastPosition, tt.match);
                                    if (
                                        ("" !== placeholder && getBuffer.call(inputmask)[lastPosition] !== placeholder && !0 !== tt.match.optionalQuantifier && !0 !== tt.match.newBlockMarker) ||
                                        (!isMask.call(inputmask, lastPosition, opts.keepStatic, !0) && tt.match.def === placeholder)
                                    ) {
                                        var newPos = seekNext.call(inputmask, lastPosition);
                                        (newPos <= clickPosition || clickPosition === lastPosition) && (lastPosition = newPos);
                                    }
                                    selectedCaret.end = selectedCaret.begin = lastPosition;
                                }
                        }
                        return selectedCaret;
                    }
                }
                function getBuffer(noCache) {
                    var inputmask = this,
                        maskset = this.maskset;
                    return (
                        (void 0 !== maskset.buffer && !0 !== noCache) ||
                            ((maskset.buffer = _validationTests.getMaskTemplate.call(this, !0, getLastValidPosition.call(this), !0)), void 0 === maskset._buffer && (maskset._buffer = maskset.buffer.slice())),
                        maskset.buffer
                    );
                }
                function getBufferTemplate() {
                    var inputmask = this,
                        maskset = this.maskset;
                    return void 0 === maskset._buffer && ((maskset._buffer = _validationTests.getMaskTemplate.call(this, !1, 1)), void 0 === maskset.buffer && (maskset.buffer = maskset._buffer.slice())), maskset._buffer;
                }
                function getLastValidPosition(closestTo, strict, validPositions) {
                    var maskset = this.maskset,
                        before = -1,
                        after = -1,
                        valids = validPositions || maskset.validPositions;
                    for (var posNdx in (void 0 === closestTo && (closestTo = -1), valids)) {
                        var psNdx = parseInt(posNdx);
                        valids[psNdx] && (strict || !0 !== valids[psNdx].generatedInput) && (psNdx <= closestTo && (before = psNdx), closestTo <= psNdx && (after = psNdx));
                    }
                    return -1 === before || before == closestTo ? after : -1 == after ? before : closestTo - before < after - closestTo ? before : after;
                }
                function isMask(pos, strict, fuzzy) {
                    var inputmask = this,
                        maskset = this.maskset,
                        test = _validationTests.getTestTemplate.call(this, pos).match;
                    if (("" === test.def && (test = _validationTests.getTest.call(this, pos).match), !0 !== test.static)) return test.fn;
                    if (!0 === fuzzy && void 0 !== maskset.validPositions[pos] && !0 !== maskset.validPositions[pos].generatedInput) return !0;
                    if (!0 !== strict && -1 < pos) {
                        if (fuzzy) {
                            var tests = _validationTests.getTests.call(this, pos);
                            return tests.length > 1 + ("" === tests[tests.length - 1].match.def ? 1 : 0);
                        }
                        var testTemplate = _validationTests.determineTestTemplate.call(this, pos, _validationTests.getTests.call(this, pos)),
                            testPlaceHolder = _validationTests.getPlaceholder.call(this, pos, testTemplate.match);
                        return testTemplate.match.def !== testPlaceHolder;
                    }
                    return !1;
                }
                function resetMaskSet(soft) {
                    var maskset = this.maskset;
                    (maskset.buffer = void 0), !0 !== soft && ((maskset.validPositions = {}), (maskset.p = 0));
                }
                function seekNext(pos, newBlock, fuzzy) {
                    var inputmask = this;
                    void 0 === fuzzy && (fuzzy = !0);
                    for (
                        var position = pos + 1;
                        "" !== _validationTests.getTest.call(this, position).match.def &&
                        ((!0 === newBlock && (!0 !== _validationTests.getTest.call(this, position).match.newBlockMarker || !isMask.call(this, position, void 0, !0))) || (!0 !== newBlock && !isMask.call(this, position, void 0, fuzzy)));

                    )
                        position++;
                    return position;
                }
                function seekPrevious(pos, newBlock) {
                    var inputmask = this,
                        position = pos - 1;
                    if (pos <= 0) return 0;
                    for (
                        ;
                        0 < position &&
                        ((!0 === newBlock && (!0 !== _validationTests.getTest.call(this, position).match.newBlockMarker || !isMask.call(this, position, void 0, !0))) || (!0 !== newBlock && !isMask.call(this, position, void 0, !0)));

                    )
                        position--;
                    return position;
                }
                function translatePosition(pos) {
                    var inputmask = this,
                        opts = this.opts,
                        el = this.el;
                    return !this.isRTL || "number" != typeof pos || (opts.greedy && "" === opts.placeholder) || !el || (pos = Math.abs(this._valueGet().length - pos)), pos;
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0), __webpack_require__(16), __webpack_require__(17);
                var _mask = __webpack_require__(18),
                    _inputmask = _interopRequireDefault(__webpack_require__(12)),
                    _window = _interopRequireDefault(__webpack_require__(8)),
                    _maskLexer = __webpack_require__(19),
                    _validationTests = __webpack_require__(3),
                    _positioning = __webpack_require__(1),
                    _validation = __webpack_require__(4),
                    _inputHandling = __webpack_require__(5),
                    _eventruler = __webpack_require__(11),
                    _definitions = _interopRequireDefault(__webpack_require__(21)),
                    _defaults = _interopRequireDefault(__webpack_require__(22)),
                    _canUseDOM = _interopRequireDefault(__webpack_require__(9));
                function _typeof(obj) {
                    return (
                        (_typeof =
                            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                                ? function _typeof(obj) {
                                      return typeof obj;
                                  }
                                : function _typeof(obj) {
                                      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                                  }),
                        _typeof(obj)
                    );
                }
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var document = _window.default.document,
                    dataKey = "_inputmask_opts";
                function Inputmask(alias, options, internal) {
                    if (_canUseDOM.default) {
                        if (!(this instanceof Inputmask)) return new Inputmask(alias, options, internal);
                        (this.dependencyLib = _inputmask.default),
                            (this.el = void 0),
                            (this.events = {}),
                            (this.maskset = void 0),
                            !0 !== internal &&
                                ("[object Object]" === Object.prototype.toString.call(alias) ? (options = alias) : ((options = options || {}), alias && (options.alias = alias)),
                                (this.opts = _inputmask.default.extend(!0, {}, this.defaults, options)),
                                (this.noMasksCache = options && void 0 !== options.definitions),
                                (this.userOptions = options || {}),
                                resolveAlias(this.opts.alias, options, this.opts)),
                            (this.refreshValue = !1),
                            (this.undoValue = void 0),
                            (this.$el = void 0),
                            (this.skipKeyPressEvent = !1),
                            (this.skipInputEvent = !1),
                            (this.validationEvent = !1),
                            (this.ignorable = !1),
                            this.maxLength,
                            (this.mouseEnter = !1),
                            (this.originalPlaceholder = void 0),
                            (this.isComposing = !1);
                    }
                }
                function resolveAlias(aliasStr, options, opts) {
                    var aliasDefinition = Inputmask.prototype.aliases[aliasStr];
                    return aliasDefinition
                        ? (aliasDefinition.alias && resolveAlias(aliasDefinition.alias, void 0, opts), _inputmask.default.extend(!0, opts, aliasDefinition), _inputmask.default.extend(!0, opts, options), !0)
                        : (null === opts.mask && (opts.mask = aliasStr), !1);
                }
                function importAttributeOptions(npt, opts, userOptions, dataAttribute) {
                    function importOption(option, optionData) {
                        var attrOption = "" === dataAttribute ? option : dataAttribute + "-" + option;
                        (optionData = void 0 !== optionData ? optionData : npt.getAttribute(attrOption)),
                            null !== optionData &&
                                ("string" == typeof optionData && (0 === option.indexOf("on") ? (optionData = _window.default[optionData]) : "false" === optionData ? (optionData = !1) : "true" === optionData && (optionData = !0)),
                                (userOptions[option] = optionData));
                    }
                    if (!0 === opts.importDataAttributes) {
                        var attrOptions = npt.getAttribute(dataAttribute),
                            option,
                            dataoptions,
                            optionData,
                            p;
                        if ((attrOptions && "" !== attrOptions && ((attrOptions = attrOptions.replace(/'/g, '"')), (dataoptions = JSON.parse("{" + attrOptions + "}"))), dataoptions))
                            for (p in ((optionData = void 0), dataoptions))
                                if ("alias" === p.toLowerCase()) {
                                    optionData = dataoptions[p];
                                    break;
                                }
                        for (option in (importOption("alias", optionData), userOptions.alias && resolveAlias(userOptions.alias, userOptions, opts), opts)) {
                            if (dataoptions)
                                for (p in ((optionData = void 0), dataoptions))
                                    if (p.toLowerCase() === option.toLowerCase()) {
                                        optionData = dataoptions[p];
                                        break;
                                    }
                            importOption(option, optionData);
                        }
                    }
                    return (
                        _inputmask.default.extend(!0, opts, userOptions),
                        ("rtl" !== npt.dir && !opts.rightAlign) || (npt.style.textAlign = "right"),
                        ("rtl" !== npt.dir && !opts.numericInput) || ((npt.dir = "ltr"), npt.removeAttribute("dir"), (opts.isRTL = !0)),
                        Object.keys(userOptions).length
                    );
                }
                (Inputmask.prototype = {
                    dataAttribute: "data-inputmask",
                    defaults: _defaults.default,
                    definitions: _definitions.default,
                    aliases: {},
                    masksCache: {},
                    get isRTL() {
                        return this.opts.isRTL || this.opts.numericInput;
                    },
                    mask: function mask(elems) {
                        var that = this;
                        return (
                            "string" == typeof elems && (elems = document.getElementById(elems) || document.querySelectorAll(elems)),
                            (elems = elems.nodeName ? [elems] : Array.isArray(elems) ? elems : Array.from(elems)),
                            elems.forEach(function (el, ndx) {
                                var scopedOpts = _inputmask.default.extend(!0, {}, that.opts);
                                if (importAttributeOptions(el, scopedOpts, _inputmask.default.extend(!0, {}, that.userOptions), that.dataAttribute)) {
                                    var maskset = (0, _maskLexer.generateMaskSet)(scopedOpts, that.noMasksCache);
                                    void 0 !== maskset &&
                                        (void 0 !== el.inputmask && ((el.inputmask.opts.autoUnmask = !0), el.inputmask.remove()),
                                        (el.inputmask = new Inputmask(void 0, void 0, !0)),
                                        (el.inputmask.opts = scopedOpts),
                                        (el.inputmask.noMasksCache = that.noMasksCache),
                                        (el.inputmask.userOptions = _inputmask.default.extend(!0, {}, that.userOptions)),
                                        (el.inputmask.el = el),
                                        (el.inputmask.$el = (0, _inputmask.default)(el)),
                                        (el.inputmask.maskset = maskset),
                                        _inputmask.default.data(el, dataKey, that.userOptions),
                                        _mask.mask.call(el.inputmask));
                                }
                            }),
                            (elems && elems[0] && elems[0].inputmask) || this
                        );
                    },
                    option: function option(options, noremask) {
                        return "string" == typeof options ? this.opts[options] : "object" === _typeof(options) ? (_inputmask.default.extend(this.userOptions, options), this.el && !0 !== noremask && this.mask(this.el), this) : void 0;
                    },
                    unmaskedvalue: function unmaskedvalue(value) {
                        if (((this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache)), void 0 === this.el || void 0 !== value)) {
                            var valueBuffer = (("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, value, this.opts)) || value).split("");
                            _inputHandling.checkVal.call(this, void 0, !1, !1, valueBuffer), "function" == typeof this.opts.onBeforeWrite && this.opts.onBeforeWrite.call(this, void 0, _positioning.getBuffer.call(this), 0, this.opts);
                        }
                        return _inputHandling.unmaskedvalue.call(this, this.el);
                    },
                    remove: function remove() {
                        if (this.el) {
                            _inputmask.default.data(this.el, dataKey, null);
                            var cv = this.opts.autoUnmask ? (0, _inputHandling.unmaskedvalue)(this.el) : this._valueGet(this.opts.autoUnmask),
                                valueProperty;
                            cv !== _positioning.getBufferTemplate.call(this).join("") ? this._valueSet(cv, this.opts.autoUnmask) : this._valueSet(""),
                                _eventruler.EventRuler.off(this.el),
                                Object.getOwnPropertyDescriptor && Object.getPrototypeOf
                                    ? ((valueProperty = Object.getOwnPropertyDescriptor(Object.getPrototypeOf(this.el), "value")),
                                      valueProperty && this.__valueGet && Object.defineProperty(this.el, "value", { get: this.__valueGet, set: this.__valueSet, configurable: !0 }))
                                    : document.__lookupGetter__ && this.el.__lookupGetter__("value") && this.__valueGet && (this.el.__defineGetter__("value", this.__valueGet), this.el.__defineSetter__("value", this.__valueSet)),
                                (this.el.inputmask = void 0);
                        }
                        return this.el;
                    },
                    getemptymask: function getemptymask() {
                        return (this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache)), _positioning.getBufferTemplate.call(this).join("");
                    },
                    hasMaskedValue: function hasMaskedValue() {
                        return !this.opts.autoUnmask;
                    },
                    isComplete: function isComplete() {
                        return (this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache)), _validation.isComplete.call(this, _positioning.getBuffer.call(this));
                    },
                    getmetadata: function getmetadata() {
                        if (((this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache)), Array.isArray(this.maskset.metadata))) {
                            var maskTarget = _validationTests.getMaskTemplate.call(this, !0, 0, !1).join("");
                            return (
                                this.maskset.metadata.forEach(function (mtdt) {
                                    return mtdt.mask !== maskTarget || ((maskTarget = mtdt), !1);
                                }),
                                maskTarget
                            );
                        }
                        return this.maskset.metadata;
                    },
                    isValid: function isValid(value) {
                        if (((this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache)), value)) {
                            var valueBuffer = (("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, value, this.opts)) || value).split("");
                            _inputHandling.checkVal.call(this, void 0, !0, !1, valueBuffer);
                        } else value = this.isRTL ? _positioning.getBuffer.call(this).slice().reverse().join("") : _positioning.getBuffer.call(this).join("");
                        for (var buffer = _positioning.getBuffer.call(this), rl = _positioning.determineLastRequiredPosition.call(this), lmib = buffer.length - 1; rl < lmib && !_positioning.isMask.call(this, lmib); lmib--);
                        return (
                            buffer.splice(rl, lmib + 1 - rl), _validation.isComplete.call(this, buffer) && value === (this.isRTL ? _positioning.getBuffer.call(this).slice().reverse().join("") : _positioning.getBuffer.call(this).join(""))
                        );
                    },
                    format: function format(value, metadata) {
                        this.maskset = this.maskset || (0, _maskLexer.generateMaskSet)(this.opts, this.noMasksCache);
                        var valueBuffer = (("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, value, this.opts)) || value).split("");
                        _inputHandling.checkVal.call(this, void 0, !0, !1, valueBuffer);
                        var formattedValue = this.isRTL ? _positioning.getBuffer.call(this).slice().reverse().join("") : _positioning.getBuffer.call(this).join("");
                        return metadata ? { value: formattedValue, metadata: this.getmetadata() } : formattedValue;
                    },
                    setValue: function setValue(value) {
                        this.el && (0, _inputmask.default)(this.el).trigger("setvalue", [value]);
                    },
                    analyseMask: _maskLexer.analyseMask,
                }),
                    (Inputmask.extendDefaults = function (options) {
                        _inputmask.default.extend(!0, Inputmask.prototype.defaults, options);
                    }),
                    (Inputmask.extendDefinitions = function (definition) {
                        _inputmask.default.extend(!0, Inputmask.prototype.definitions, definition);
                    }),
                    (Inputmask.extendAliases = function (alias) {
                        _inputmask.default.extend(!0, Inputmask.prototype.aliases, alias);
                    }),
                    (Inputmask.format = function (value, options, metadata) {
                        return Inputmask(options).format(value, metadata);
                    }),
                    (Inputmask.unmask = function (value, options) {
                        return Inputmask(options).unmaskedvalue(value);
                    }),
                    (Inputmask.isValid = function (value, options) {
                        return Inputmask(options).isValid(value);
                    }),
                    (Inputmask.remove = function (elems) {
                        "string" == typeof elems && (elems = document.getElementById(elems) || document.querySelectorAll(elems)),
                            (elems = elems.nodeName ? [elems] : elems),
                            elems.forEach(function (el) {
                                el.inputmask && el.inputmask.remove();
                            });
                    }),
                    (Inputmask.setValue = function (elems, value) {
                        "string" == typeof elems && (elems = document.getElementById(elems) || document.querySelectorAll(elems)),
                            (elems = elems.nodeName ? [elems] : elems),
                            elems.forEach(function (el) {
                                el.inputmask ? el.inputmask.setValue(value) : (0, _inputmask.default)(el).trigger("setvalue", [value]);
                            });
                    }),
                    (Inputmask.dependencyLib = _inputmask.default),
                    (_window.default.Inputmask = Inputmask);
                var _default = Inputmask;
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                function getLocator(tst, align) {
                    var locator = (null != tst.alternation ? tst.mloc[getDecisionTaker(tst)] : tst.locator).join("");
                    if ("" !== locator) for (; locator.length < align; ) locator += "0";
                    return locator;
                }
                function getDecisionTaker(tst) {
                    var decisionTaker = tst.locator[tst.alternation];
                    return "string" == typeof decisionTaker && 0 < decisionTaker.length && (decisionTaker = decisionTaker.split(",")[0]), void 0 !== decisionTaker ? decisionTaker.toString() : "";
                }
                function getPlaceholder(pos, test, returnPL) {
                    var inputmask = this,
                        opts = this.opts,
                        maskset = this.maskset;
                    if (((test = test || getTest.call(this, pos).match), void 0 !== test.placeholder || !0 === returnPL)) return "function" == typeof test.placeholder ? test.placeholder(opts) : test.placeholder;
                    if (!0 !== test.static) return opts.placeholder.charAt(pos % opts.placeholder.length);
                    if (-1 < pos && void 0 === maskset.validPositions[pos]) {
                        var tests = getTests.call(this, pos),
                            staticAlternations = [],
                            prevTest;
                        if (tests.length > 1 + ("" === tests[tests.length - 1].match.def ? 1 : 0))
                            for (var i = 0; i < tests.length; i++)
                                if (
                                    "" !== tests[i].match.def &&
                                    !0 !== tests[i].match.optionality &&
                                    !0 !== tests[i].match.optionalQuantifier &&
                                    (!0 === tests[i].match.static || void 0 === prevTest || !1 !== tests[i].match.fn.test(prevTest.match.def, maskset, pos, !0, opts)) &&
                                    (staticAlternations.push(tests[i]), !0 === tests[i].match.static && (prevTest = tests[i]), 1 < staticAlternations.length && /[0-9a-bA-Z]/.test(staticAlternations[0].match.def))
                                )
                                    return opts.placeholder.charAt(pos % opts.placeholder.length);
                    }
                    return test.def;
                }
                function getMaskTemplate(baseOnInput, minimalPos, includeMode, noJit, clearOptionalTail) {
                    var inputmask = this,
                        opts = this.opts,
                        maskset = this.maskset,
                        greedy = opts.greedy;
                    clearOptionalTail && (opts.greedy = !1), (minimalPos = minimalPos || 0);
                    var maskTemplate = [],
                        ndxIntlzr,
                        pos = 0,
                        test,
                        testPos,
                        jitRenderStatic;
                    do {
                        if (!0 === baseOnInput && maskset.validPositions[pos])
                            (testPos =
                                clearOptionalTail &&
                                !0 === maskset.validPositions[pos].match.optionality &&
                                void 0 === maskset.validPositions[pos + 1] &&
                                (!0 === maskset.validPositions[pos].generatedInput || (maskset.validPositions[pos].input == opts.skipOptionalPartCharacter && 0 < pos))
                                    ? determineTestTemplate.call(this, pos, getTests.call(this, pos, ndxIntlzr, pos - 1))
                                    : maskset.validPositions[pos]),
                                (test = testPos.match),
                                (ndxIntlzr = testPos.locator.slice()),
                                maskTemplate.push(!0 === includeMode ? testPos.input : !1 === includeMode ? test.nativeDef : getPlaceholder.call(this, pos, test));
                        else {
                            (testPos = getTestTemplate.call(this, pos, ndxIntlzr, pos - 1)), (test = testPos.match), (ndxIntlzr = testPos.locator.slice());
                            var jitMasking = !0 !== noJit && (!1 !== opts.jitMasking ? opts.jitMasking : test.jit);
                            (jitRenderStatic =
                                ((jitRenderStatic && test.static && test.def !== opts.groupSeparator && null === test.fn) || (maskset.validPositions[pos - 1] && test.static && test.def !== opts.groupSeparator && null === test.fn)) &&
                                maskset.tests[pos] &&
                                1 === maskset.tests[pos].length),
                                jitRenderStatic || !1 === jitMasking || void 0 === jitMasking || ("number" == typeof jitMasking && isFinite(jitMasking) && pos < jitMasking)
                                    ? maskTemplate.push(!1 === includeMode ? test.nativeDef : getPlaceholder.call(this, pos, test))
                                    : (jitRenderStatic = !1);
                        }
                        pos++;
                    } while (((void 0 === this.maxLength || pos < this.maxLength) && (!0 !== test.static || "" !== test.def)) || pos < minimalPos);
                    return "" === maskTemplate[maskTemplate.length - 1] && maskTemplate.pop(), (!1 === includeMode && void 0 !== maskset.maskLength) || (maskset.maskLength = pos - 1), (opts.greedy = greedy), maskTemplate;
                }
                function getTestTemplate(pos, ndxIntlzr, tstPs) {
                    var inputmask = this,
                        maskset = this.maskset;
                    return maskset.validPositions[pos] || determineTestTemplate.call(this, pos, getTests.call(this, pos, ndxIntlzr ? ndxIntlzr.slice() : ndxIntlzr, tstPs));
                }
                function determineTestTemplate(pos, tests) {
                    var inputmask = this,
                        opts = this.opts;
                    pos = 0 < pos ? pos - 1 : 0;
                    for (var altTest = getTest.call(this, pos), targetLocator = getLocator(altTest), tstLocator, closest, bestMatch, ndx = 0; ndx < tests.length; ndx++) {
                        var tst = tests[ndx];
                        tstLocator = getLocator(tst, targetLocator.length);
                        var distance = Math.abs(tstLocator - targetLocator);
                        (void 0 === closest ||
                            ("" !== tstLocator && distance < closest) ||
                            (bestMatch && !opts.greedy && bestMatch.match.optionality && "master" === bestMatch.match.newBlockMarker && (!tst.match.optionality || !tst.match.newBlockMarker)) ||
                            (bestMatch && bestMatch.match.optionalQuantifier && !tst.match.optionalQuantifier)) &&
                            ((closest = distance), (bestMatch = tst));
                    }
                    return bestMatch;
                }
                function getTest(pos, tests) {
                    var inputmask = this,
                        maskset = this.maskset;
                    return maskset.validPositions[pos] ? maskset.validPositions[pos] : (tests || getTests.call(this, pos))[0];
                }
                function isSubsetOf(source, target, opts) {
                    function expand(pattern) {
                        for (var expanded = [], start = -1, end, i = 0, l = pattern.length; i < l; i++)
                            if ("-" === pattern.charAt(i)) for (end = pattern.charCodeAt(i + 1); ++start < end; ) expanded.push(String.fromCharCode(start));
                            else (start = pattern.charCodeAt(i)), expanded.push(pattern.charAt(i));
                        return expanded.join("");
                    }
                    return (
                        source.match.def === target.match.nativeDef ||
                        (!(!(opts.regex || (source.match.fn instanceof RegExp && target.match.fn instanceof RegExp)) || !0 === source.match.static || !0 === target.match.static) &&
                            -1 !== expand(target.match.fn.toString().replace(/[[\]/]/g, "")).indexOf(expand(source.match.fn.toString().replace(/[[\]/]/g, ""))))
                    );
                }
                function getTests(pos, ndxIntlzr, tstPs) {
                    var inputmask = this,
                        $ = this.dependencyLib,
                        maskset = this.maskset,
                        opts = this.opts,
                        el = this.el,
                        maskTokens = maskset.maskToken,
                        testPos = ndxIntlzr ? tstPs : 0,
                        ndxInitializer = ndxIntlzr ? ndxIntlzr.slice() : [0],
                        matches = [],
                        insertStop = !1,
                        latestMatch,
                        cacheDependency = ndxIntlzr ? ndxIntlzr.join("") : "";
                    function resolveTestFromToken(maskToken, ndxInitializer, loopNdx, quantifierRecurse) {
                        function handleMatch(match, loopNdx, quantifierRecurse) {
                            function isFirstMatch(latestMatch, tokenGroup) {
                                var firstMatch = 0 === tokenGroup.matches.indexOf(latestMatch);
                                return (
                                    firstMatch ||
                                        tokenGroup.matches.every(function (match, ndx) {
                                            return (
                                                !0 === match.isQuantifier
                                                    ? (firstMatch = isFirstMatch(latestMatch, tokenGroup.matches[ndx - 1]))
                                                    : Object.prototype.hasOwnProperty.call(match, "matches") && (firstMatch = isFirstMatch(latestMatch, match)),
                                                !firstMatch
                                            );
                                        }),
                                    firstMatch
                                );
                            }
                            function resolveNdxInitializer(pos, alternateNdx, targetAlternation) {
                                var bestMatch, indexPos;
                                if (
                                    ((maskset.tests[pos] || maskset.validPositions[pos]) &&
                                        (maskset.tests[pos] || [maskset.validPositions[pos]]).every(function (lmnt, ndx) {
                                            if (lmnt.mloc[alternateNdx]) return (bestMatch = lmnt), !1;
                                            var alternation = void 0 !== targetAlternation ? targetAlternation : lmnt.alternation,
                                                ndxPos = void 0 !== lmnt.locator[alternation] ? lmnt.locator[alternation].toString().indexOf(alternateNdx) : -1;
                                            return (void 0 === indexPos || ndxPos < indexPos) && -1 !== ndxPos && ((bestMatch = lmnt), (indexPos = ndxPos)), !0;
                                        }),
                                    bestMatch)
                                ) {
                                    var bestMatchAltIndex = bestMatch.locator[bestMatch.alternation],
                                        locator = bestMatch.mloc[alternateNdx] || bestMatch.mloc[bestMatchAltIndex] || bestMatch.locator;
                                    return locator.slice((void 0 !== targetAlternation ? targetAlternation : bestMatch.alternation) + 1);
                                }
                                return void 0 !== targetAlternation ? resolveNdxInitializer(pos, alternateNdx) : void 0;
                            }
                            function staticCanMatchDefinition(source, target) {
                                return !0 === source.match.static && !0 !== target.match.static && target.match.fn.test(source.match.def, maskset, pos, !1, opts, !1);
                            }
                            function setMergeLocators(targetMatch, altMatch) {
                                var alternationNdx = targetMatch.alternation,
                                    shouldMerge = void 0 === altMatch || (alternationNdx === altMatch.alternation && -1 === targetMatch.locator[alternationNdx].toString().indexOf(altMatch.locator[alternationNdx]));
                                if (!shouldMerge && alternationNdx > altMatch.alternation)
                                    for (var i = altMatch.alternation; i < alternationNdx; i++)
                                        if (targetMatch.locator[i] !== altMatch.locator[i]) {
                                            (alternationNdx = i), (shouldMerge = !0);
                                            break;
                                        }
                                if (shouldMerge) {
                                    targetMatch.mloc = targetMatch.mloc || {};
                                    var locNdx = targetMatch.locator[alternationNdx];
                                    if (void 0 !== locNdx) {
                                        if (("string" == typeof locNdx && (locNdx = locNdx.split(",")[0]), void 0 === targetMatch.mloc[locNdx] && (targetMatch.mloc[locNdx] = targetMatch.locator.slice()), void 0 !== altMatch)) {
                                            for (var ndx in altMatch.mloc) "string" == typeof ndx && (ndx = ndx.split(",")[0]), void 0 === targetMatch.mloc[ndx] && (targetMatch.mloc[ndx] = altMatch.mloc[ndx]);
                                            targetMatch.locator[alternationNdx] = Object.keys(targetMatch.mloc).join(",");
                                        }
                                        return !0;
                                    }
                                    targetMatch.alternation = void 0;
                                }
                                return !1;
                            }
                            function isSameLevel(targetMatch, altMatch) {
                                if (targetMatch.locator.length !== altMatch.locator.length) return !1;
                                for (var locNdx = targetMatch.alternation + 1; locNdx < targetMatch.locator.length; locNdx++) if (targetMatch.locator[locNdx] !== altMatch.locator[locNdx]) return !1;
                                return !0;
                            }
                            if (testPos > pos + opts._maxTestPos) throw "Inputmask: There is probably an error in your mask definition or in the code. Create an issue on github with an example of the mask you are using. " + maskset.mask;
                            if (testPos === pos && void 0 === match.matches) return matches.push({ match: match, locator: loopNdx.reverse(), cd: cacheDependency, mloc: {} }), !0;
                            if (void 0 !== match.matches) {
                                if (match.isGroup && quantifierRecurse !== match) {
                                    if (((match = handleMatch(maskToken.matches[maskToken.matches.indexOf(match) + 1], loopNdx, quantifierRecurse)), match)) return !0;
                                } else if (match.isOptional) {
                                    var optionalToken = match,
                                        mtchsNdx = matches.length;
                                    if (((match = resolveTestFromToken(match, ndxInitializer, loopNdx, quantifierRecurse)), match)) {
                                        if (
                                            (matches.forEach(function (mtch, ndx) {
                                                mtchsNdx <= ndx && (mtch.match.optionality = !0);
                                            }),
                                            (latestMatch = matches[matches.length - 1].match),
                                            void 0 !== quantifierRecurse || !isFirstMatch(latestMatch, optionalToken))
                                        )
                                            return !0;
                                        (insertStop = !0), (testPos = pos);
                                    }
                                } else if (match.isAlternator) {
                                    var alternateToken = match,
                                        malternateMatches = [],
                                        maltMatches,
                                        currentMatches = matches.slice(),
                                        loopNdxCnt = loopNdx.length,
                                        unMatchedAlternation = !1,
                                        altIndex = 0 < ndxInitializer.length ? ndxInitializer.shift() : -1;
                                    if (-1 === altIndex || "string" == typeof altIndex) {
                                        var currentPos = testPos,
                                            ndxInitializerClone = ndxInitializer.slice(),
                                            altIndexArr = [],
                                            amndx;
                                        if ("string" == typeof altIndex) altIndexArr = altIndex.split(",");
                                        else for (amndx = 0; amndx < alternateToken.matches.length; amndx++) altIndexArr.push(amndx.toString());
                                        if (void 0 !== maskset.excludes[pos]) {
                                            for (var altIndexArrClone = altIndexArr.slice(), i = 0, exl = maskset.excludes[pos].length; i < exl; i++) {
                                                var excludeSet = maskset.excludes[pos][i].toString().split(":");
                                                loopNdx.length == excludeSet[1] && altIndexArr.splice(altIndexArr.indexOf(excludeSet[0]), 1);
                                            }
                                            0 === altIndexArr.length && (delete maskset.excludes[pos], (altIndexArr = altIndexArrClone));
                                        }
                                        (!0 === opts.keepStatic || (isFinite(parseInt(opts.keepStatic)) && currentPos >= opts.keepStatic)) && (altIndexArr = altIndexArr.slice(0, 1));
                                        for (var ndx = 0; ndx < altIndexArr.length; ndx++) {
                                            (amndx = parseInt(altIndexArr[ndx])), (matches = []), (ndxInitializer = ("string" == typeof altIndex && resolveNdxInitializer(testPos, amndx, loopNdxCnt)) || ndxInitializerClone.slice());
                                            var tokenMatch = alternateToken.matches[amndx];
                                            if (tokenMatch && handleMatch(tokenMatch, [amndx].concat(loopNdx), quantifierRecurse)) match = !0;
                                            else if ((0 === ndx && (unMatchedAlternation = !0), tokenMatch && tokenMatch.matches && tokenMatch.matches.length > alternateToken.matches[0].matches.length)) break;
                                            (maltMatches = matches.slice()), (testPos = currentPos), (matches = []);
                                            for (var ndx1 = 0; ndx1 < maltMatches.length; ndx1++) {
                                                var altMatch = maltMatches[ndx1],
                                                    dropMatch = !1;
                                                (altMatch.match.jit = altMatch.match.jit || unMatchedAlternation), (altMatch.alternation = altMatch.alternation || loopNdxCnt), setMergeLocators(altMatch);
                                                for (var ndx2 = 0; ndx2 < malternateMatches.length; ndx2++) {
                                                    var altMatch2 = malternateMatches[ndx2];
                                                    if ("string" != typeof altIndex || (void 0 !== altMatch.alternation && altIndexArr.includes(altMatch.locator[altMatch.alternation].toString()))) {
                                                        if (altMatch.match.nativeDef === altMatch2.match.nativeDef) {
                                                            (dropMatch = !0), setMergeLocators(altMatch2, altMatch);
                                                            break;
                                                        }
                                                        if (isSubsetOf(altMatch, altMatch2, opts)) {
                                                            setMergeLocators(altMatch, altMatch2) && ((dropMatch = !0), malternateMatches.splice(malternateMatches.indexOf(altMatch2), 0, altMatch));
                                                            break;
                                                        }
                                                        if (isSubsetOf(altMatch2, altMatch, opts)) {
                                                            setMergeLocators(altMatch2, altMatch);
                                                            break;
                                                        }
                                                        if (staticCanMatchDefinition(altMatch, altMatch2)) {
                                                            isSameLevel(altMatch, altMatch2) || void 0 !== el.inputmask.userOptions.keepStatic
                                                                ? setMergeLocators(altMatch, altMatch2) && ((dropMatch = !0), malternateMatches.splice(malternateMatches.indexOf(altMatch2), 0, altMatch))
                                                                : (opts.keepStatic = !0);
                                                            break;
                                                        }
                                                    }
                                                }
                                                dropMatch || malternateMatches.push(altMatch);
                                            }
                                        }
                                        (matches = currentMatches.concat(malternateMatches)), (testPos = pos), (insertStop = 0 < matches.length), (match = 0 < malternateMatches.length), (ndxInitializer = ndxInitializerClone.slice());
                                    } else match = handleMatch(alternateToken.matches[altIndex] || maskToken.matches[altIndex], [altIndex].concat(loopNdx), quantifierRecurse);
                                    if (match) return !0;
                                } else if (match.isQuantifier && quantifierRecurse !== maskToken.matches[maskToken.matches.indexOf(match) - 1])
                                    for (var qt = match, qndx = 0 < ndxInitializer.length ? ndxInitializer.shift() : 0; qndx < (isNaN(qt.quantifier.max) ? qndx + 1 : qt.quantifier.max) && testPos <= pos; qndx++) {
                                        var tokenGroup = maskToken.matches[maskToken.matches.indexOf(qt) - 1];
                                        if (((match = handleMatch(tokenGroup, [qndx].concat(loopNdx), tokenGroup)), match)) {
                                            if (
                                                ((latestMatch = matches[matches.length - 1].match),
                                                (latestMatch.optionalQuantifier = qndx >= qt.quantifier.min),
                                                (latestMatch.jit = (qndx || 1) * tokenGroup.matches.indexOf(latestMatch) >= qt.quantifier.jit),
                                                latestMatch.optionalQuantifier && isFirstMatch(latestMatch, tokenGroup))
                                            ) {
                                                (insertStop = !0), (testPos = pos);
                                                break;
                                            }
                                            return latestMatch.jit && (maskset.jitOffset[pos] = tokenGroup.matches.length - tokenGroup.matches.indexOf(latestMatch)), !0;
                                        }
                                    }
                                else if (((match = resolveTestFromToken(match, ndxInitializer, loopNdx, quantifierRecurse)), match)) return !0;
                            } else testPos++;
                        }
                        for (var tndx = 0 < ndxInitializer.length ? ndxInitializer.shift() : 0; tndx < maskToken.matches.length; tndx++)
                            if (!0 !== maskToken.matches[tndx].isQuantifier) {
                                var match = handleMatch(maskToken.matches[tndx], [tndx].concat(loopNdx), quantifierRecurse);
                                if (match && testPos === pos) return match;
                                if (pos < testPos) break;
                            }
                    }
                    function mergeLocators(pos, tests) {
                        var locator = [],
                            alternation;
                        return (
                            Array.isArray(tests) || (tests = [tests]),
                            0 < tests.length &&
                                (void 0 === tests[0].alternation || !0 === opts.keepStatic
                                    ? ((locator = determineTestTemplate.call(inputmask, pos, tests.slice()).locator.slice()), 0 === locator.length && (locator = tests[0].locator.slice()))
                                    : tests.forEach(function (tst) {
                                          "" !== tst.def &&
                                              (0 === locator.length
                                                  ? ((alternation = tst.alternation), (locator = tst.locator.slice()))
                                                  : tst.locator[alternation] && -1 === locator[alternation].toString().indexOf(tst.locator[alternation]) && (locator[alternation] += "," + tst.locator[alternation]));
                                      })),
                            locator
                        );
                    }
                    if (-1 < pos && (void 0 === inputmask.maxLength || pos < inputmask.maxLength)) {
                        if (void 0 === ndxIntlzr) {
                            for (var previousPos = pos - 1, test; void 0 === (test = maskset.validPositions[previousPos] || maskset.tests[previousPos]) && -1 < previousPos; ) previousPos--;
                            void 0 !== test && -1 < previousPos && ((ndxInitializer = mergeLocators(previousPos, test)), (cacheDependency = ndxInitializer.join("")), (testPos = previousPos));
                        }
                        if (maskset.tests[pos] && maskset.tests[pos][0].cd === cacheDependency) return maskset.tests[pos];
                        for (var mtndx = ndxInitializer.shift(); mtndx < maskTokens.length; mtndx++) {
                            var match = resolveTestFromToken(maskTokens[mtndx], ndxInitializer, [mtndx]);
                            if ((match && testPos === pos) || pos < testPos) break;
                        }
                    }
                    return (
                        (0 !== matches.length && !insertStop) || matches.push({ match: { fn: null, static: !0, optionality: !1, casing: null, def: "", placeholder: "" }, locator: [], mloc: {}, cd: cacheDependency }),
                        void 0 !== ndxIntlzr && maskset.tests[pos] ? $.extend(!0, [], matches) : ((maskset.tests[pos] = $.extend(!0, [], matches)), maskset.tests[pos])
                    );
                }
                Object.defineProperty(exports, "__esModule", { value: !0 }),
                    (exports.determineTestTemplate = determineTestTemplate),
                    (exports.getDecisionTaker = getDecisionTaker),
                    (exports.getMaskTemplate = getMaskTemplate),
                    (exports.getPlaceholder = getPlaceholder),
                    (exports.getTest = getTest),
                    (exports.getTests = getTests),
                    (exports.getTestTemplate = getTestTemplate),
                    (exports.isSubsetOf = isSubsetOf);
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }),
                    (exports.alternate = alternate),
                    (exports.checkAlternationMatch = checkAlternationMatch),
                    (exports.isComplete = isComplete),
                    (exports.isValid = isValid),
                    (exports.refreshFromBuffer = refreshFromBuffer),
                    (exports.revalidateMask = revalidateMask),
                    (exports.handleRemove = handleRemove);
                var _validationTests = __webpack_require__(3),
                    _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _positioning = __webpack_require__(1),
                    _eventhandlers = __webpack_require__(6);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                function alternate(maskPos, c, strict, fromIsValid, rAltPos, selection) {
                    var inputmask = this,
                        $ = this.dependencyLib,
                        opts = this.opts,
                        maskset = this.maskset,
                        validPsClone = $.extend(!0, {}, maskset.validPositions),
                        tstClone = $.extend(!0, {}, maskset.tests),
                        lastAlt,
                        alternation,
                        isValidRslt = !1,
                        returnRslt = !1,
                        altPos,
                        prevAltPos,
                        i,
                        validPos,
                        decisionPos,
                        lAltPos = void 0 !== rAltPos ? rAltPos : _positioning.getLastValidPosition.call(this),
                        nextPos,
                        input,
                        begin,
                        end;
                    if ((selection && ((begin = selection.begin), (end = selection.end), selection.begin > selection.end && ((begin = selection.end), (end = selection.begin))), -1 === lAltPos && void 0 === rAltPos))
                        (lastAlt = 0), (prevAltPos = _validationTests.getTest.call(this, lastAlt)), (alternation = prevAltPos.alternation);
                    else
                        for (; 0 <= lAltPos; lAltPos--)
                            if (((altPos = maskset.validPositions[lAltPos]), altPos && void 0 !== altPos.alternation)) {
                                if (prevAltPos && prevAltPos.locator[altPos.alternation] !== altPos.locator[altPos.alternation]) break;
                                (lastAlt = lAltPos), (alternation = maskset.validPositions[lastAlt].alternation), (prevAltPos = altPos);
                            }
                    if (void 0 !== alternation) {
                        (decisionPos = parseInt(lastAlt)),
                            (maskset.excludes[decisionPos] = maskset.excludes[decisionPos] || []),
                            !0 !== maskPos && maskset.excludes[decisionPos].push((0, _validationTests.getDecisionTaker)(prevAltPos) + ":" + prevAltPos.alternation);
                        var validInputs = [],
                            resultPos = -1;
                        for (i = decisionPos; i < _positioning.getLastValidPosition.call(this, void 0, !0) + 1; i++)
                            -1 === resultPos && maskPos <= i && void 0 !== c && (validInputs.push(c), (resultPos = validInputs.length - 1)),
                                (validPos = maskset.validPositions[i]),
                                validPos && !0 !== validPos.generatedInput && (void 0 === selection || i < begin || end <= i) && validInputs.push(validPos.input),
                                delete maskset.validPositions[i];
                        for (-1 === resultPos && void 0 !== c && (validInputs.push(c), (resultPos = validInputs.length - 1)); void 0 !== maskset.excludes[decisionPos] && maskset.excludes[decisionPos].length < 10; ) {
                            for (
                                maskset.tests = {}, _positioning.resetMaskSet.call(this, !0), isValidRslt = !0, i = 0;
                                i < validInputs.length &&
                                ((nextPos = isValidRslt.caret || _positioning.getLastValidPosition.call(this, void 0, !0) + 1), (input = validInputs[i]), (isValidRslt = isValid.call(this, nextPos, input, !1, fromIsValid, !0)));
                                i++
                            )
                                i === resultPos && (returnRslt = isValidRslt), 1 == maskPos && isValidRslt && (returnRslt = { caretPos: i });
                            if (isValidRslt) break;
                            if (
                                (_positioning.resetMaskSet.call(this),
                                (prevAltPos = _validationTests.getTest.call(this, decisionPos)),
                                (maskset.validPositions = $.extend(!0, {}, validPsClone)),
                                (maskset.tests = $.extend(!0, {}, tstClone)),
                                !maskset.excludes[decisionPos])
                            ) {
                                returnRslt = alternate.call(this, maskPos, c, strict, fromIsValid, decisionPos - 1, selection);
                                break;
                            }
                            var decisionTaker = (0, _validationTests.getDecisionTaker)(prevAltPos);
                            if (-1 !== maskset.excludes[decisionPos].indexOf(decisionTaker + ":" + prevAltPos.alternation)) {
                                returnRslt = alternate.call(this, maskPos, c, strict, fromIsValid, decisionPos - 1, selection);
                                break;
                            }
                            for (maskset.excludes[decisionPos].push(decisionTaker + ":" + prevAltPos.alternation), i = decisionPos; i < _positioning.getLastValidPosition.call(this, void 0, !0) + 1; i++) delete maskset.validPositions[i];
                        }
                    }
                    return (returnRslt && !1 === opts.keepStatic) || delete maskset.excludes[decisionPos], returnRslt;
                }
                function casing(elem, test, pos) {
                    var opts = this.opts,
                        maskset = this.maskset;
                    switch (opts.casing || test.casing) {
                        case "upper":
                            elem = elem.toUpperCase();
                            break;
                        case "lower":
                            elem = elem.toLowerCase();
                            break;
                        case "title":
                            var posBefore = maskset.validPositions[pos - 1];
                            elem = 0 === pos || (posBefore && posBefore.input === String.fromCharCode(_keycode.default.SPACE)) ? elem.toUpperCase() : elem.toLowerCase();
                            break;
                        default:
                            if ("function" == typeof opts.casing) {
                                var args = Array.prototype.slice.call(arguments);
                                args.push(maskset.validPositions), (elem = opts.casing.apply(this, args));
                            }
                    }
                    return elem;
                }
                function checkAlternationMatch(altArr1, altArr2, na) {
                    for (var opts = this.opts, altArrC = opts.greedy ? altArr2 : altArr2.slice(0, 1), isMatch = !1, naArr = void 0 !== na ? na.split(",") : [], naNdx, i = 0; i < naArr.length; i++)
                        -1 !== (naNdx = altArr1.indexOf(naArr[i])) && altArr1.splice(naNdx, 1);
                    for (var alndx = 0; alndx < altArr1.length; alndx++)
                        if (altArrC.includes(altArr1[alndx])) {
                            isMatch = !0;
                            break;
                        }
                    return isMatch;
                }
                function handleRemove(input, k, pos, strict, fromIsValid) {
                    var inputmask = this,
                        maskset = this.maskset,
                        opts = this.opts;
                    if ((opts.numericInput || this.isRTL) && (k === _keycode.default.BACKSPACE ? (k = _keycode.default.DELETE) : k === _keycode.default.DELETE && (k = _keycode.default.BACKSPACE), this.isRTL)) {
                        var pend = pos.end;
                        (pos.end = pos.begin), (pos.begin = pend);
                    }
                    var lvp = _positioning.getLastValidPosition.call(this, void 0, !0),
                        offset;
                    if (
                        (pos.end >= _positioning.getBuffer.call(this).length && lvp >= pos.end && (pos.end = lvp + 1),
                        k === _keycode.default.BACKSPACE
                            ? pos.end - pos.begin < 1 && (pos.begin = _positioning.seekPrevious.call(this, pos.begin))
                            : k === _keycode.default.DELETE && pos.begin === pos.end && (pos.end = _positioning.isMask.call(this, pos.end, !0, !0) ? pos.end + 1 : _positioning.seekNext.call(this, pos.end) + 1),
                        !1 !== (offset = revalidateMask.call(this, pos)))
                    ) {
                        if ((!0 !== strict && !1 !== opts.keepStatic) || (null !== opts.regex && -1 !== _validationTests.getTest.call(this, pos.begin).match.def.indexOf("|"))) {
                            var result = alternate.call(this, !0);
                            if (result) {
                                var newPos = void 0 !== result.caret ? result.caret : result.pos ? _positioning.seekNext.call(this, result.pos.begin ? result.pos.begin : result.pos) : _positioning.getLastValidPosition.call(this, -1, !0);
                                (k !== _keycode.default.DELETE || pos.begin > newPos) && pos.begin;
                            }
                        }
                        !0 !== strict && (maskset.p = k === _keycode.default.DELETE ? pos.begin + offset : pos.begin);
                    }
                }
                function isComplete(buffer) {
                    var inputmask = this,
                        opts = this.opts,
                        maskset = this.maskset;
                    if ("function" == typeof opts.isComplete) return opts.isComplete(buffer, opts);
                    if ("*" !== opts.repeat) {
                        var complete = !1,
                            lrp = _positioning.determineLastRequiredPosition.call(this, !0),
                            aml = _positioning.seekPrevious.call(this, lrp.l);
                        if (void 0 === lrp.def || lrp.def.newBlockMarker || lrp.def.optionality || lrp.def.optionalQuantifier) {
                            complete = !0;
                            for (var i = 0; i <= aml; i++) {
                                var test = _validationTests.getTestTemplate.call(this, i).match;
                                if (
                                    (!0 !== test.static && void 0 === maskset.validPositions[i] && !0 !== test.optionality && !0 !== test.optionalQuantifier) ||
                                    (!0 === test.static && buffer[i] !== _validationTests.getPlaceholder.call(this, i, test))
                                ) {
                                    complete = !1;
                                    break;
                                }
                            }
                        }
                        return complete;
                    }
                }
                function isValid(pos, c, strict, fromIsValid, fromAlternate, validateOnly, fromCheckval) {
                    var inputmask = this,
                        $ = this.dependencyLib,
                        opts = this.opts,
                        maskset = inputmask.maskset;
                    function isSelection(posObj) {
                        return inputmask.isRTL ? 1 < posObj.begin - posObj.end || posObj.begin - posObj.end == 1 : 1 < posObj.end - posObj.begin || posObj.end - posObj.begin == 1;
                    }
                    strict = !0 === strict;
                    var maskPos = pos;
                    function processCommandObject(commandObj) {
                        if (void 0 !== commandObj) {
                            if (
                                (void 0 !== commandObj.remove &&
                                    (Array.isArray(commandObj.remove) || (commandObj.remove = [commandObj.remove]),
                                    commandObj.remove
                                        .sort(function (a, b) {
                                            return b.pos - a.pos;
                                        })
                                        .forEach(function (lmnt) {
                                            revalidateMask.call(inputmask, { begin: lmnt, end: lmnt + 1 });
                                        }),
                                    (commandObj.remove = void 0)),
                                void 0 !== commandObj.insert &&
                                    (Array.isArray(commandObj.insert) || (commandObj.insert = [commandObj.insert]),
                                    commandObj.insert
                                        .sort(function (a, b) {
                                            return a.pos - b.pos;
                                        })
                                        .forEach(function (lmnt) {
                                            "" !== lmnt.c && isValid.call(inputmask, lmnt.pos, lmnt.c, void 0 === lmnt.strict || lmnt.strict, void 0 !== lmnt.fromIsValid ? lmnt.fromIsValid : fromIsValid);
                                        }),
                                    (commandObj.insert = void 0)),
                                commandObj.refreshFromBuffer && commandObj.buffer)
                            ) {
                                var refresh = commandObj.refreshFromBuffer;
                                refreshFromBuffer.call(inputmask, !0 === refresh ? refresh : refresh.start, refresh.end, commandObj.buffer), (commandObj.refreshFromBuffer = void 0);
                            }
                            void 0 !== commandObj.rewritePosition && ((maskPos = commandObj.rewritePosition), (commandObj = !0));
                        }
                        return commandObj;
                    }
                    function _isValid(position, c, strict) {
                        var rslt = !1;
                        return (
                            _validationTests.getTests.call(inputmask, position).every(function (tst, ndx) {
                                var test = tst.match;
                                if (
                                    (_positioning.getBuffer.call(inputmask, !0),
                                    (rslt =
                                        null != test.fn
                                            ? test.fn.test(c, maskset, position, strict, opts, isSelection(pos))
                                            : (c === test.def || c === opts.skipOptionalPartCharacter) && "" !== test.def && { c: _validationTests.getPlaceholder.call(inputmask, position, test, !0) || test.def, pos: position }),
                                    !1 === rslt)
                                )
                                    return !0;
                                var elem = void 0 !== rslt.c ? rslt.c : c,
                                    validatedPos = position;
                                return (
                                    (elem = elem === opts.skipOptionalPartCharacter && !0 === test.static ? _validationTests.getPlaceholder.call(inputmask, position, test, !0) || test.def : elem),
                                    (rslt = processCommandObject(rslt)),
                                    !0 !== rslt && void 0 !== rslt.pos && rslt.pos !== position && (validatedPos = rslt.pos),
                                    (!0 !== rslt && void 0 === rslt.pos && void 0 === rslt.c) ||
                                        (!1 === revalidateMask.call(inputmask, pos, $.extend({}, tst, { input: casing.call(inputmask, elem, test, validatedPos) }), fromIsValid, validatedPos) && (rslt = !1)),
                                    !1
                                );
                            }),
                            rslt
                        );
                    }
                    void 0 !== pos.begin && (maskPos = inputmask.isRTL ? pos.end : pos.begin);
                    var result = !0,
                        positionsClone = $.extend(!0, {}, maskset.validPositions);
                    if (!1 === opts.keepStatic && void 0 !== maskset.excludes[maskPos] && !0 !== fromAlternate && !0 !== fromIsValid)
                        for (var i = maskPos; i < (inputmask.isRTL ? pos.begin : pos.end); i++) void 0 !== maskset.excludes[i] && ((maskset.excludes[i] = void 0), delete maskset.tests[i]);
                    if (
                        ("function" == typeof opts.preValidation &&
                            !0 !== fromIsValid &&
                            !0 !== validateOnly &&
                            ((result = opts.preValidation.call(inputmask, _positioning.getBuffer.call(inputmask), maskPos, c, isSelection(pos), opts, maskset, pos, strict || fromAlternate)), (result = processCommandObject(result))),
                        !0 === result)
                    ) {
                        if (void 0 === inputmask.maxLength || maskPos < _positioning.translatePosition.call(inputmask, inputmask.maxLength)) {
                            if (((result = _isValid(maskPos, c, strict)), (!strict || !0 === fromIsValid) && !1 === result && !0 !== validateOnly)) {
                                var currentPosValid = maskset.validPositions[maskPos];
                                if (!currentPosValid || !0 !== currentPosValid.match.static || (currentPosValid.match.def !== c && c !== opts.skipOptionalPartCharacter)) {
                                    if (opts.insertMode || void 0 === maskset.validPositions[_positioning.seekNext.call(inputmask, maskPos)] || pos.end > maskPos) {
                                        var skip = !1;
                                        if (
                                            (maskset.jitOffset[maskPos] &&
                                                void 0 === maskset.validPositions[_positioning.seekNext.call(inputmask, maskPos)] &&
                                                ((result = isValid.call(inputmask, maskPos + maskset.jitOffset[maskPos], c, !0)), !1 !== result && (!0 !== fromAlternate && (result.caret = maskPos), (skip = !0))),
                                            pos.end > maskPos && (maskset.validPositions[maskPos] = void 0),
                                            !skip && !_positioning.isMask.call(inputmask, maskPos, opts.keepStatic && 0 === maskPos))
                                        )
                                            for (var nPos = maskPos + 1, snPos = _positioning.seekNext.call(inputmask, maskPos, !1, 0 !== maskPos); nPos <= snPos; nPos++)
                                                if (((result = _isValid(nPos, c, strict)), !1 !== result)) {
                                                    (result = trackbackPositions.call(inputmask, maskPos, void 0 !== result.pos ? result.pos : nPos) || result), (maskPos = nPos);
                                                    break;
                                                }
                                    }
                                } else result = { caret: _positioning.seekNext.call(inputmask, maskPos) };
                            }
                        } else result = !1;
                        !1 !== result || !opts.keepStatic || (!isComplete.call(inputmask, _positioning.getBuffer.call(inputmask)) && 0 !== maskPos) || strict || !0 === fromAlternate
                            ? isSelection(pos) && maskset.tests[maskPos] && 1 < maskset.tests[maskPos].length && opts.keepStatic && !strict && !0 !== fromAlternate && (result = alternate.call(inputmask, !0))
                            : (result = alternate.call(inputmask, maskPos, c, strict, fromIsValid, void 0, pos)),
                            !0 === result && (result = { pos: maskPos });
                    }
                    if ("function" == typeof opts.postValidation && !0 !== fromIsValid && !0 !== validateOnly) {
                        var postResult = opts.postValidation.call(inputmask, _positioning.getBuffer.call(inputmask, !0), void 0 !== pos.begin ? (inputmask.isRTL ? pos.end : pos.begin) : pos, c, result, opts, maskset, strict, fromCheckval);
                        void 0 !== postResult && (result = !0 === postResult ? result : postResult);
                    }
                    result && void 0 === result.pos && (result.pos = maskPos),
                        !1 === result || !0 === validateOnly ? (_positioning.resetMaskSet.call(inputmask, !0), (maskset.validPositions = $.extend(!0, {}, positionsClone))) : trackbackPositions.call(inputmask, void 0, maskPos, !0);
                    var endResult = processCommandObject(result);
                    return endResult;
                }
                function positionCanMatchDefinition(pos, testDefinition, opts) {
                    for (var inputmask = this, maskset = this.maskset, valid = !1, tests = _validationTests.getTests.call(this, pos), tndx = 0; tndx < tests.length; tndx++) {
                        if (
                            tests[tndx].match &&
                            ((tests[tndx].match.nativeDef === testDefinition.match[opts.shiftPositions ? "def" : "nativeDef"] && (!opts.shiftPositions || !testDefinition.match.static)) ||
                                tests[tndx].match.nativeDef === testDefinition.match.nativeDef ||
                                (opts.regex && !tests[tndx].match.static && tests[tndx].match.fn.test(testDefinition.input)))
                        ) {
                            valid = !0;
                            break;
                        }
                        if (tests[tndx].match && tests[tndx].match.def === testDefinition.match.nativeDef) {
                            valid = void 0;
                            break;
                        }
                    }
                    return !1 === valid && void 0 !== maskset.jitOffset[pos] && (valid = positionCanMatchDefinition.call(this, pos + maskset.jitOffset[pos], testDefinition, opts)), valid;
                }
                function refreshFromBuffer(start, end, buffer) {
                    var inputmask = this,
                        maskset = this.maskset,
                        opts = this.opts,
                        $ = this.dependencyLib,
                        i,
                        p,
                        skipOptionalPartCharacter = opts.skipOptionalPartCharacter,
                        bffr = this.isRTL ? buffer.slice().reverse() : buffer;
                    if (((opts.skipOptionalPartCharacter = ""), !0 === start))
                        _positioning.resetMaskSet.call(this), (maskset.tests = {}), (start = 0), (end = buffer.length), (p = _positioning.determineNewCaretPosition.call(this, { begin: 0, end: 0 }, !1).begin);
                    else {
                        for (i = start; i < end; i++) delete maskset.validPositions[i];
                        p = start;
                    }
                    var keypress = new $.Event("keypress");
                    for (i = start; i < end; i++) {
                        (keypress.which = bffr[i].toString().charCodeAt(0)), (this.ignorable = !1);
                        var valResult = _eventhandlers.EventHandlers.keypressEvent.call(this, keypress, !0, !1, !1, p);
                        !1 !== valResult && (p = valResult.forwardPosition);
                    }
                    opts.skipOptionalPartCharacter = skipOptionalPartCharacter;
                }
                function trackbackPositions(originalPos, newPos, fillOnly) {
                    var inputmask = this,
                        maskset = this.maskset,
                        $ = this.dependencyLib;
                    if (void 0 === originalPos) for (originalPos = newPos - 1; 0 < originalPos && !maskset.validPositions[originalPos]; originalPos--);
                    for (var ps = originalPos; ps < newPos; ps++)
                        if (void 0 === maskset.validPositions[ps] && !_positioning.isMask.call(this, ps, !1)) {
                            var vp = 0 == ps ? _validationTests.getTest.call(this, ps) : maskset.validPositions[ps - 1];
                            if (vp) {
                                var tests = _validationTests.getTests.call(this, ps).slice();
                                "" === tests[tests.length - 1].match.def && tests.pop();
                                var bestMatch = _validationTests.determineTestTemplate.call(this, ps, tests),
                                    np;
                                if (
                                    bestMatch &&
                                    (!0 !== bestMatch.match.jit || ("master" === bestMatch.match.newBlockMarker && (np = maskset.validPositions[ps + 1]) && !0 === np.match.optionalQuantifier)) &&
                                    ((bestMatch = $.extend({}, bestMatch, { input: _validationTests.getPlaceholder.call(this, ps, bestMatch.match, !0) || bestMatch.match.def })),
                                    (bestMatch.generatedInput = !0),
                                    revalidateMask.call(this, ps, bestMatch, !0),
                                    !0 !== fillOnly)
                                ) {
                                    var cvpInput = maskset.validPositions[newPos].input;
                                    return (maskset.validPositions[newPos] = void 0), isValid.call(this, newPos, cvpInput, !0, !0);
                                }
                            }
                        }
                }
                function revalidateMask(pos, validTest, fromIsValid, validatedPos) {
                    var inputmask = this,
                        maskset = this.maskset,
                        opts = this.opts,
                        $ = this.dependencyLib;
                    function IsEnclosedStatic(pos, valids, selection) {
                        var posMatch = valids[pos];
                        if (void 0 === posMatch || !0 !== posMatch.match.static || !0 === posMatch.match.optionality || (void 0 !== valids[0] && void 0 !== valids[0].alternation)) return !1;
                        var prevMatch = selection.begin <= pos - 1 ? valids[pos - 1] && !0 === valids[pos - 1].match.static && valids[pos - 1] : valids[pos - 1],
                            nextMatch = selection.end > pos + 1 ? valids[pos + 1] && !0 === valids[pos + 1].match.static && valids[pos + 1] : valids[pos + 1];
                        return prevMatch && nextMatch;
                    }
                    var offset = 0,
                        begin = void 0 !== pos.begin ? pos.begin : pos,
                        end = void 0 !== pos.end ? pos.end : pos;
                    if (
                        (pos.begin > pos.end && ((begin = pos.end), (end = pos.begin)),
                        (validatedPos = void 0 !== validatedPos ? validatedPos : begin),
                        begin !== end || (opts.insertMode && void 0 !== maskset.validPositions[validatedPos] && void 0 === fromIsValid) || void 0 === validTest)
                    ) {
                        var positionsClone = $.extend(!0, {}, maskset.validPositions),
                            lvp = _positioning.getLastValidPosition.call(this, void 0, !0),
                            i;
                        for (maskset.p = begin, i = lvp; begin <= i; i--) delete maskset.validPositions[i], void 0 === validTest && delete maskset.tests[i + 1];
                        var valid = !0,
                            j = validatedPos,
                            posMatch = j,
                            t,
                            canMatch;
                        for (validTest && ((maskset.validPositions[validatedPos] = $.extend(!0, {}, validTest)), posMatch++, j++), i = validTest ? end : end - 1; i <= lvp; i++) {
                            if (void 0 !== (t = positionsClone[i]) && !0 !== t.generatedInput && (end <= i || (begin <= i && IsEnclosedStatic(i, positionsClone, { begin: begin, end: end })))) {
                                for (; "" !== _validationTests.getTest.call(this, posMatch).match.def; ) {
                                    if (!1 !== (canMatch = positionCanMatchDefinition.call(this, posMatch, t, opts)) || "+" === t.match.def) {
                                        "+" === t.match.def && _positioning.getBuffer.call(this, !0);
                                        var result = isValid.call(this, posMatch, t.input, "+" !== t.match.def, "+" !== t.match.def);
                                        if (((valid = !1 !== result), (j = (result.pos || posMatch) + 1), !valid && canMatch)) break;
                                    } else valid = !1;
                                    if (valid) {
                                        void 0 === validTest && t.match.static && i === pos.begin && offset++;
                                        break;
                                    }
                                    if (!valid && posMatch > maskset.maskLength) break;
                                    posMatch++;
                                }
                                "" == _validationTests.getTest.call(this, posMatch).match.def && (valid = !1), (posMatch = j);
                            }
                            if (!valid) break;
                        }
                        if (!valid) return (maskset.validPositions = $.extend(!0, {}, positionsClone)), _positioning.resetMaskSet.call(this, !0), !1;
                    } else validTest && _validationTests.getTest.call(this, validatedPos).match.cd === validTest.match.cd && (maskset.validPositions[validatedPos] = $.extend(!0, {}, validTest));
                    return _positioning.resetMaskSet.call(this, !0), offset;
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }),
                    (exports.applyInputValue = applyInputValue),
                    (exports.clearOptionalTail = clearOptionalTail),
                    (exports.checkVal = checkVal),
                    (exports.HandleNativePlaceholder = HandleNativePlaceholder),
                    (exports.unmaskedvalue = unmaskedvalue),
                    (exports.writeBuffer = writeBuffer);
                var _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _validationTests = __webpack_require__(3),
                    _positioning = __webpack_require__(1),
                    _validation = __webpack_require__(4),
                    _environment = __webpack_require__(7),
                    _eventhandlers = __webpack_require__(6);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                function applyInputValue(input, value) {
                    var inputmask = input ? input.inputmask : this,
                        opts = inputmask.opts;
                    (input.inputmask.refreshValue = !1),
                        "function" == typeof opts.onBeforeMask && (value = opts.onBeforeMask.call(inputmask, value, opts) || value),
                        (value = value.toString().split("")),
                        checkVal(input, !0, !1, value),
                        (inputmask.undoValue = _positioning.getBuffer.call(inputmask).join("")),
                        (opts.clearMaskOnLostFocus || opts.clearIncomplete) &&
                            input.inputmask._valueGet() === _positioning.getBufferTemplate.call(inputmask).join("") &&
                            -1 === _positioning.getLastValidPosition.call(inputmask) &&
                            input.inputmask._valueSet("");
                }
                function clearOptionalTail(buffer) {
                    var inputmask = this;
                    buffer.length = 0;
                    for (var template = _validationTests.getMaskTemplate.call(this, !0, 0, !0, void 0, !0), lmnt; void 0 !== (lmnt = template.shift()); ) buffer.push(lmnt);
                    return buffer;
                }
                function checkVal(input, writeOut, strict, nptvl, initiatingEvent) {
                    var inputmask = input ? input.inputmask : this,
                        maskset = inputmask.maskset,
                        opts = inputmask.opts,
                        $ = inputmask.dependencyLib,
                        inputValue = nptvl.slice(),
                        charCodes = "",
                        initialNdx = -1,
                        result = void 0,
                        skipOptionalPartCharacter = opts.skipOptionalPartCharacter;
                    function isTemplateMatch(ndx, charCodes) {
                        for (
                            var targetTemplate = _validationTests.getMaskTemplate.call(inputmask, !0, 0).slice(ndx, _positioning.seekNext.call(inputmask, ndx, !1, !1)).join("").replace(/'/g, ""),
                                charCodeNdx = targetTemplate.indexOf(charCodes);
                            0 < charCodeNdx && " " === targetTemplate[charCodeNdx - 1];

                        )
                            charCodeNdx--;
                        var match =
                            0 === charCodeNdx &&
                            !_positioning.isMask.call(inputmask, ndx) &&
                            (_validationTests.getTest.call(inputmask, ndx).match.nativeDef === charCodes.charAt(0) ||
                                (!0 === _validationTests.getTest.call(inputmask, ndx).match.static && _validationTests.getTest.call(inputmask, ndx).match.nativeDef === "'" + charCodes.charAt(0)) ||
                                (" " === _validationTests.getTest.call(inputmask, ndx).match.nativeDef &&
                                    (_validationTests.getTest.call(inputmask, ndx + 1).match.nativeDef === charCodes.charAt(0) ||
                                        (!0 === _validationTests.getTest.call(inputmask, ndx + 1).match.static && _validationTests.getTest.call(inputmask, ndx + 1).match.nativeDef === "'" + charCodes.charAt(0)))));
                        if (!match && 0 < charCodeNdx && !_positioning.isMask.call(inputmask, ndx, !1, !0)) {
                            var nextPos = _positioning.seekNext.call(inputmask, ndx);
                            inputmask.caretPos.begin < nextPos && (inputmask.caretPos = { begin: nextPos });
                        }
                        return match;
                    }
                    (opts.skipOptionalPartCharacter = ""),
                        _positioning.resetMaskSet.call(inputmask),
                        (maskset.tests = {}),
                        (initialNdx = opts.radixPoint ? _positioning.determineNewCaretPosition.call(inputmask, { begin: 0, end: 0 }, !1, !1 === opts.__financeInput ? "radixFocus" : void 0).begin : 0),
                        (maskset.p = initialNdx),
                        (inputmask.caretPos = { begin: initialNdx });
                    var staticMatches = [],
                        prevCaretPos = inputmask.caretPos;
                    if (
                        (inputValue.forEach(function (charCode, ndx) {
                            if (void 0 !== charCode) {
                                var keypress = new $.Event("_checkval");
                                (keypress.which = charCode.toString().charCodeAt(0)), (charCodes += charCode);
                                var lvp = _positioning.getLastValidPosition.call(inputmask, void 0, !0);
                                isTemplateMatch(initialNdx, charCodes)
                                    ? (result = _eventhandlers.EventHandlers.keypressEvent.call(inputmask, keypress, !0, !1, strict, lvp + 1))
                                    : ((result = _eventhandlers.EventHandlers.keypressEvent.call(inputmask, keypress, !0, !1, strict, inputmask.caretPos.begin)), result && ((initialNdx = inputmask.caretPos.begin + 1), (charCodes = ""))),
                                    result
                                        ? (void 0 !== result.pos &&
                                              maskset.validPositions[result.pos] &&
                                              !0 === maskset.validPositions[result.pos].match.static &&
                                              void 0 === maskset.validPositions[result.pos].alternation &&
                                              (staticMatches.push(result.pos), inputmask.isRTL || (result.forwardPosition = result.pos + 1)),
                                          writeBuffer.call(inputmask, void 0, _positioning.getBuffer.call(inputmask), result.forwardPosition, keypress, !1),
                                          (inputmask.caretPos = { begin: result.forwardPosition, end: result.forwardPosition }),
                                          (prevCaretPos = inputmask.caretPos))
                                        : void 0 === maskset.validPositions[ndx] && inputValue[ndx] === _validationTests.getPlaceholder.call(inputmask, ndx) && _positioning.isMask.call(inputmask, ndx, !0)
                                        ? inputmask.caretPos.begin++
                                        : (inputmask.caretPos = prevCaretPos);
                            }
                        }),
                        0 < staticMatches.length)
                    ) {
                        var sndx,
                            validPos,
                            nextValid = _positioning.seekNext.call(inputmask, -1, void 0, !1);
                        if (
                            (!_validation.isComplete.call(inputmask, _positioning.getBuffer.call(inputmask)) && staticMatches.length <= nextValid) ||
                            (_validation.isComplete.call(inputmask, _positioning.getBuffer.call(inputmask)) && 0 < staticMatches.length && staticMatches.length !== nextValid && 0 === staticMatches[0])
                        )
                            for (var nextSndx = nextValid; void 0 !== (sndx = staticMatches.shift()); ) {
                                var keypress = new $.Event("_checkval");
                                if (
                                    ((validPos = maskset.validPositions[sndx]),
                                    (validPos.generatedInput = !0),
                                    (keypress.which = validPos.input.charCodeAt(0)),
                                    (result = _eventhandlers.EventHandlers.keypressEvent.call(inputmask, keypress, !0, !1, strict, nextSndx)),
                                    result && void 0 !== result.pos && result.pos !== sndx && maskset.validPositions[result.pos] && !0 === maskset.validPositions[result.pos].match.static)
                                )
                                    staticMatches.push(result.pos);
                                else if (!result) break;
                                nextSndx++;
                            }
                    }
                    writeOut &&
                        writeBuffer.call(
                            inputmask,
                            input,
                            _positioning.getBuffer.call(inputmask),
                            result ? result.forwardPosition : inputmask.caretPos.begin,
                            initiatingEvent || new $.Event("checkval"),
                            initiatingEvent && "input" === initiatingEvent.type && inputmask.undoValue !== _positioning.getBuffer.call(inputmask).join("")
                        ),
                        (opts.skipOptionalPartCharacter = skipOptionalPartCharacter);
                }
                function HandleNativePlaceholder(npt, value) {
                    var inputmask = npt ? npt.inputmask : this;
                    if (_environment.ie) {
                        if (npt.inputmask._valueGet() !== value && (npt.placeholder !== value || "" === npt.placeholder)) {
                            var buffer = _positioning.getBuffer.call(inputmask).slice(),
                                nptValue = npt.inputmask._valueGet();
                            if (nptValue !== value) {
                                var lvp = _positioning.getLastValidPosition.call(inputmask);
                                -1 === lvp && nptValue === _positioning.getBufferTemplate.call(inputmask).join("") ? (buffer = []) : -1 !== lvp && clearOptionalTail.call(inputmask, buffer), writeBuffer(npt, buffer);
                            }
                        }
                    } else npt.placeholder !== value && ((npt.placeholder = value), "" === npt.placeholder && npt.removeAttribute("placeholder"));
                }
                function unmaskedvalue(input) {
                    var inputmask = input ? input.inputmask : this,
                        opts = inputmask.opts,
                        maskset = inputmask.maskset;
                    if (input) {
                        if (void 0 === input.inputmask) return input.value;
                        input.inputmask && input.inputmask.refreshValue && applyInputValue(input, input.inputmask._valueGet(!0));
                    }
                    var umValue = [],
                        vps = maskset.validPositions;
                    for (var pndx in vps) vps[pndx] && vps[pndx].match && (1 != vps[pndx].match.static || (Array.isArray(maskset.metadata) && !0 !== vps[pndx].generatedInput)) && umValue.push(vps[pndx].input);
                    var unmaskedValue = 0 === umValue.length ? "" : (inputmask.isRTL ? umValue.reverse() : umValue).join("");
                    if ("function" == typeof opts.onUnMask) {
                        var bufferValue = (inputmask.isRTL ? _positioning.getBuffer.call(inputmask).slice().reverse() : _positioning.getBuffer.call(inputmask)).join("");
                        unmaskedValue = opts.onUnMask.call(inputmask, bufferValue, unmaskedValue, opts);
                    }
                    return unmaskedValue;
                }
                function writeBuffer(input, buffer, caretPos, event, triggerEvents) {
                    var inputmask = input ? input.inputmask : this,
                        opts = inputmask.opts,
                        $ = inputmask.dependencyLib;
                    if (event && "function" == typeof opts.onBeforeWrite) {
                        var result = opts.onBeforeWrite.call(inputmask, event, buffer, caretPos, opts);
                        if (result) {
                            if (result.refreshFromBuffer) {
                                var refresh = result.refreshFromBuffer;
                                _validation.refreshFromBuffer.call(inputmask, !0 === refresh ? refresh : refresh.start, refresh.end, result.buffer || buffer), (buffer = _positioning.getBuffer.call(inputmask, !0));
                            }
                            void 0 !== caretPos && (caretPos = void 0 !== result.caret ? result.caret : caretPos);
                        }
                    }
                    if (
                        void 0 !== input &&
                        (input.inputmask._valueSet(buffer.join("")),
                        void 0 === caretPos ||
                            (void 0 !== event && "blur" === event.type) ||
                            _positioning.caret.call(inputmask, input, caretPos, void 0, void 0, void 0 !== event && "keydown" === event.type && (event.keyCode === _keycode.default.DELETE || event.keyCode === _keycode.default.BACKSPACE)),
                        !0 === triggerEvents)
                    ) {
                        var $input = $(input),
                            nptVal = input.inputmask._valueGet();
                        (input.inputmask.skipInputEvent = !0),
                            $input.trigger("input"),
                            setTimeout(function () {
                                nptVal === _positioning.getBufferTemplate.call(inputmask).join("") ? $input.trigger("cleared") : !0 === _validation.isComplete.call(inputmask, buffer) && $input.trigger("complete");
                            }, 0);
                    }
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.EventHandlers = void 0);
                var _positioning = __webpack_require__(1),
                    _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _environment = __webpack_require__(7),
                    _validation = __webpack_require__(4),
                    _inputHandling = __webpack_require__(5),
                    _validationTests = __webpack_require__(3);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var EventHandlers = {
                    keydownEvent: function keydownEvent(e) {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            $ = inputmask.dependencyLib,
                            maskset = inputmask.maskset,
                            input = this,
                            $input = $(input),
                            k = e.keyCode,
                            pos = _positioning.caret.call(inputmask, input),
                            kdResult = opts.onKeyDown.call(this, e, _positioning.getBuffer.call(inputmask), pos, opts);
                        if (void 0 !== kdResult) return kdResult;
                        if (k === _keycode.default.BACKSPACE || k === _keycode.default.DELETE || (_environment.iphone && k === _keycode.default.BACKSPACE_SAFARI) || (e.ctrlKey && k === _keycode.default.X && !("oncut" in input)))
                            e.preventDefault(),
                                _validation.handleRemove.call(inputmask, input, k, pos),
                                (0, _inputHandling.writeBuffer)(input, _positioning.getBuffer.call(inputmask, !0), maskset.p, e, input.inputmask._valueGet() !== _positioning.getBuffer.call(inputmask).join(""));
                        else if (k === _keycode.default.END || k === _keycode.default.PAGE_DOWN) {
                            e.preventDefault();
                            var caretPos = _positioning.seekNext.call(inputmask, _positioning.getLastValidPosition.call(inputmask));
                            _positioning.caret.call(inputmask, input, e.shiftKey ? pos.begin : caretPos, caretPos, !0);
                        } else
                            (k === _keycode.default.HOME && !e.shiftKey) || k === _keycode.default.PAGE_UP
                                ? (e.preventDefault(), _positioning.caret.call(inputmask, input, 0, e.shiftKey ? pos.begin : 0, !0))
                                : ((opts.undoOnEscape && k === _keycode.default.ESCAPE) || (90 === k && e.ctrlKey)) && !0 !== e.altKey
                                ? ((0, _inputHandling.checkVal)(input, !0, !1, inputmask.undoValue.split("")), $input.trigger("click"))
                                : !0 === opts.tabThrough && k === _keycode.default.TAB
                                ? !0 === e.shiftKey
                                    ? ((pos.end = _positioning.seekPrevious.call(inputmask, pos.end, !0)),
                                      !0 === _validationTests.getTest.call(inputmask, pos.end - 1).match.static && pos.end--,
                                      (pos.begin = _positioning.seekPrevious.call(inputmask, pos.end, !0)),
                                      0 <= pos.begin && 0 < pos.end && (e.preventDefault(), _positioning.caret.call(inputmask, input, pos.begin, pos.end)))
                                    : ((pos.begin = _positioning.seekNext.call(inputmask, pos.begin, !0)),
                                      (pos.end = _positioning.seekNext.call(inputmask, pos.begin, !0)),
                                      pos.end < maskset.maskLength && pos.end--,
                                      pos.begin <= maskset.maskLength && (e.preventDefault(), _positioning.caret.call(inputmask, input, pos.begin, pos.end)))
                                : e.shiftKey ||
                                  (opts.insertModeVisual &&
                                      !1 === opts.insertMode &&
                                      (k === _keycode.default.RIGHT
                                          ? setTimeout(function () {
                                                var caretPos = _positioning.caret.call(inputmask, input);
                                                _positioning.caret.call(inputmask, input, caretPos.begin);
                                            }, 0)
                                          : k === _keycode.default.LEFT &&
                                            setTimeout(function () {
                                                var caretPos_begin = _positioning.translatePosition.call(inputmask, input.inputmask.caretPos.begin),
                                                    caretPos_end = _positioning.translatePosition.call(inputmask, input.inputmask.caretPos.end);
                                                inputmask.isRTL
                                                    ? _positioning.caret.call(inputmask, input, caretPos_begin + (caretPos_begin === maskset.maskLength ? 0 : 1))
                                                    : _positioning.caret.call(inputmask, input, caretPos_begin - (0 === caretPos_begin ? 0 : 1));
                                            }, 0)));
                        inputmask.ignorable = opts.ignorables.includes(k);
                    },
                    keypressEvent: function keypressEvent(e, checkval, writeOut, strict, ndx) {
                        var inputmask = this.inputmask || this,
                            opts = inputmask.opts,
                            $ = inputmask.dependencyLib,
                            maskset = inputmask.maskset,
                            input = inputmask.el,
                            $input = $(input),
                            k = e.which || e.charCode || e.keyCode;
                        if (!(!0 === checkval || (e.ctrlKey && e.altKey)) && (e.ctrlKey || e.metaKey || inputmask.ignorable))
                            return (
                                k === _keycode.default.ENTER &&
                                    inputmask.undoValue !== _positioning.getBuffer.call(inputmask).join("") &&
                                    ((inputmask.undoValue = _positioning.getBuffer.call(inputmask).join("")),
                                    setTimeout(function () {
                                        $input.trigger("change");
                                    }, 0)),
                                (inputmask.skipInputEvent = !0),
                                !0
                            );
                        if (k) {
                            (44 !== k && 46 !== k) || 3 !== e.location || "" === opts.radixPoint || (k = opts.radixPoint.charCodeAt(0));
                            var pos = checkval ? { begin: ndx, end: ndx } : _positioning.caret.call(inputmask, input),
                                forwardPosition,
                                c = String.fromCharCode(k);
                            maskset.writeOutBuffer = !0;
                            var valResult = _validation.isValid.call(inputmask, pos, c, strict, void 0, void 0, void 0, checkval);
                            if (
                                (!1 !== valResult &&
                                    (_positioning.resetMaskSet.call(inputmask, !0),
                                    (forwardPosition = void 0 !== valResult.caret ? valResult.caret : _positioning.seekNext.call(inputmask, valResult.pos.begin ? valResult.pos.begin : valResult.pos)),
                                    (maskset.p = forwardPosition)),
                                (forwardPosition = opts.numericInput && void 0 === valResult.caret ? _positioning.seekPrevious.call(inputmask, forwardPosition) : forwardPosition),
                                !1 !== writeOut &&
                                    (setTimeout(function () {
                                        opts.onKeyValidation.call(input, k, valResult);
                                    }, 0),
                                    maskset.writeOutBuffer && !1 !== valResult))
                            ) {
                                var buffer = _positioning.getBuffer.call(inputmask);
                                (0, _inputHandling.writeBuffer)(input, buffer, forwardPosition, e, !0 !== checkval);
                            }
                            if ((e.preventDefault(), checkval)) return !1 !== valResult && (valResult.forwardPosition = forwardPosition), valResult;
                        }
                    },
                    keyupEvent: function keyupEvent(e) {
                        var inputmask = this.inputmask;
                        !inputmask.isComposing || (e.keyCode !== _keycode.default.KEY_229 && e.keyCode !== _keycode.default.ENTER) || inputmask.$el.trigger("input");
                    },
                    pasteEvent: function pasteEvent(e) {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            input = this,
                            inputValue = inputmask._valueGet(!0),
                            caretPos = _positioning.caret.call(inputmask, this),
                            tempValue;
                        inputmask.isRTL && ((tempValue = caretPos.end), (caretPos.end = caretPos.begin), (caretPos.begin = tempValue));
                        var valueBeforeCaret = inputValue.substr(0, caretPos.begin),
                            valueAfterCaret = inputValue.substr(caretPos.end, inputValue.length);
                        if (
                            (valueBeforeCaret == (inputmask.isRTL ? _positioning.getBufferTemplate.call(inputmask).slice().reverse() : _positioning.getBufferTemplate.call(inputmask)).slice(0, caretPos.begin).join("") &&
                                (valueBeforeCaret = ""),
                            valueAfterCaret == (inputmask.isRTL ? _positioning.getBufferTemplate.call(inputmask).slice().reverse() : _positioning.getBufferTemplate.call(inputmask)).slice(caretPos.end).join("") && (valueAfterCaret = ""),
                            window.clipboardData && window.clipboardData.getData)
                        )
                            inputValue = valueBeforeCaret + window.clipboardData.getData("Text") + valueAfterCaret;
                        else {
                            if (!e.clipboardData || !e.clipboardData.getData) return !0;
                            inputValue = valueBeforeCaret + e.clipboardData.getData("text/plain") + valueAfterCaret;
                        }
                        var pasteValue = inputValue;
                        if ("function" == typeof opts.onBeforePaste) {
                            if (((pasteValue = opts.onBeforePaste.call(inputmask, inputValue, opts)), !1 === pasteValue)) return e.preventDefault();
                            pasteValue = pasteValue || inputValue;
                        }
                        return (0, _inputHandling.checkVal)(this, !0, !1, pasteValue.toString().split(""), e), e.preventDefault();
                    },
                    inputFallBackEvent: function inputFallBackEvent(e) {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            $ = inputmask.dependencyLib;
                        function ieMobileHandler(input, inputValue, caretPos) {
                            if (_environment.iemobile) {
                                var inputChar = inputValue.replace(_positioning.getBuffer.call(inputmask).join(""), "");
                                if (1 === inputChar.length) {
                                    var iv = inputValue.split("");
                                    iv.splice(caretPos.begin, 0, inputChar), (inputValue = iv.join(""));
                                }
                            }
                            return inputValue;
                        }
                        function analyseChanges(inputValue, buffer, caretPos) {
                            for (
                                var frontPart = inputValue.substr(0, caretPos.begin).split(""),
                                    backPart = inputValue.substr(caretPos.begin).split(""),
                                    frontBufferPart = buffer.substr(0, caretPos.begin).split(""),
                                    backBufferPart = buffer.substr(caretPos.begin).split(""),
                                    fpl = frontPart.length >= frontBufferPart.length ? frontPart.length : frontBufferPart.length,
                                    bpl = backPart.length >= backBufferPart.length ? backPart.length : backBufferPart.length,
                                    bl,
                                    i,
                                    action = "",
                                    data = [],
                                    marker = "~",
                                    placeholder;
                                frontPart.length < fpl;

                            )
                                frontPart.push("~");
                            for (; frontBufferPart.length < fpl; ) frontBufferPart.push("~");
                            for (; backPart.length < bpl; ) backPart.unshift("~");
                            for (; backBufferPart.length < bpl; ) backBufferPart.unshift("~");
                            var newBuffer = frontPart.concat(backPart),
                                oldBuffer = frontBufferPart.concat(backBufferPart);
                            for (i = 0, bl = newBuffer.length; i < bl; i++)
                                switch (((placeholder = _validationTests.getPlaceholder.call(inputmask, _positioning.translatePosition.call(inputmask, i))), action)) {
                                    case "insertText":
                                        oldBuffer[i - 1] === newBuffer[i] && caretPos.begin == newBuffer.length - 1 && data.push(newBuffer[i]), (i = bl);
                                        break;
                                    case "insertReplacementText":
                                        "~" === newBuffer[i] ? caretPos.end++ : (i = bl);
                                        break;
                                    case "deleteContentBackward":
                                        "~" === newBuffer[i] ? caretPos.end++ : (i = bl);
                                        break;
                                    default:
                                        newBuffer[i] !== oldBuffer[i] &&
                                            (("~" !== newBuffer[i + 1] && newBuffer[i + 1] !== placeholder && void 0 !== newBuffer[i + 1]) || ((oldBuffer[i] !== placeholder || "~" !== oldBuffer[i + 1]) && "~" !== oldBuffer[i])
                                                ? "~" === oldBuffer[i + 1] && oldBuffer[i] === newBuffer[i + 1]
                                                    ? ((action = "insertText"), data.push(newBuffer[i]), caretPos.begin--, caretPos.end--)
                                                    : newBuffer[i] !== placeholder && "~" !== newBuffer[i] && ("~" === newBuffer[i + 1] || (oldBuffer[i] !== newBuffer[i] && oldBuffer[i + 1] === newBuffer[i + 1]))
                                                    ? ((action = "insertReplacementText"), data.push(newBuffer[i]), caretPos.begin--)
                                                    : "~" === newBuffer[i]
                                                    ? ((action = "deleteContentBackward"), (!_positioning.isMask.call(inputmask, _positioning.translatePosition.call(inputmask, i), !0) && oldBuffer[i] !== opts.radixPoint) || caretPos.end++)
                                                    : (i = bl)
                                                : ((action = "insertText"), data.push(newBuffer[i]), caretPos.begin--, caretPos.end--));
                                        break;
                                }
                            return { action: action, data: data, caret: caretPos };
                        }
                        var input = this,
                            inputValue = input.inputmask._valueGet(!0),
                            buffer = (inputmask.isRTL ? _positioning.getBuffer.call(inputmask).slice().reverse() : _positioning.getBuffer.call(inputmask)).join(""),
                            caretPos = _positioning.caret.call(inputmask, input, void 0, void 0, !0);
                        if (buffer !== inputValue) {
                            inputValue = ieMobileHandler(input, inputValue, caretPos);
                            var changes = analyseChanges(inputValue, buffer, caretPos);
                            switch (
                                ((input.inputmask.shadowRoot || input.ownerDocument).activeElement !== input && input.focus(),
                                (0, _inputHandling.writeBuffer)(input, _positioning.getBuffer.call(inputmask)),
                                _positioning.caret.call(inputmask, input, caretPos.begin, caretPos.end, !0),
                                changes.action)
                            ) {
                                case "insertText":
                                case "insertReplacementText":
                                    changes.data.forEach(function (entry, ndx) {
                                        var keypress = new $.Event("keypress");
                                        (keypress.which = entry.charCodeAt(0)), (inputmask.ignorable = !1), EventHandlers.keypressEvent.call(input, keypress);
                                    }),
                                        setTimeout(function () {
                                            inputmask.$el.trigger("keyup");
                                        }, 0);
                                    break;
                                case "deleteContentBackward":
                                    var keydown = new $.Event("keydown");
                                    (keydown.keyCode = _keycode.default.BACKSPACE), EventHandlers.keydownEvent.call(input, keydown);
                                    break;
                                default:
                                    (0, _inputHandling.applyInputValue)(input, inputValue);
                                    break;
                            }
                            e.preventDefault();
                        }
                    },
                    compositionendEvent: function compositionendEvent(e) {
                        var inputmask = this.inputmask;
                        (inputmask.isComposing = !1), inputmask.$el.trigger("input");
                    },
                    setValueEvent: function setValueEvent(e, argument_1, argument_2) {
                        var inputmask = this.inputmask,
                            input = this,
                            value = e && e.detail ? e.detail[0] : argument_1;
                        void 0 === value && (value = this.inputmask._valueGet(!0)),
                            (0, _inputHandling.applyInputValue)(this, value),
                            ((e.detail && void 0 !== e.detail[1]) || void 0 !== argument_2) && _positioning.caret.call(inputmask, this, e.detail ? e.detail[1] : argument_2);
                    },
                    focusEvent: function focusEvent(e) {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            input = this,
                            nptValue = this.inputmask._valueGet();
                        opts.showMaskOnFocus &&
                            nptValue !== _positioning.getBuffer.call(inputmask).join("") &&
                            (0, _inputHandling.writeBuffer)(this, _positioning.getBuffer.call(inputmask), _positioning.seekNext.call(inputmask, _positioning.getLastValidPosition.call(inputmask))),
                            !0 !== opts.positionCaretOnTab ||
                                !1 !== inputmask.mouseEnter ||
                                (_validation.isComplete.call(inputmask, _positioning.getBuffer.call(inputmask)) && -1 !== _positioning.getLastValidPosition.call(inputmask)) ||
                                EventHandlers.clickEvent.apply(this, [e, !0]),
                            (inputmask.undoValue = _positioning.getBuffer.call(inputmask).join(""));
                    },
                    invalidEvent: function invalidEvent(e) {
                        this.inputmask.validationEvent = !0;
                    },
                    mouseleaveEvent: function mouseleaveEvent() {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            input = this;
                        (inputmask.mouseEnter = !1), opts.clearMaskOnLostFocus && (this.inputmask.shadowRoot || this.ownerDocument).activeElement !== this && (0, _inputHandling.HandleNativePlaceholder)(this, inputmask.originalPlaceholder);
                    },
                    clickEvent: function clickEvent(e, tabbed) {
                        var inputmask = this.inputmask,
                            input = this;
                        if ((this.inputmask.shadowRoot || this.ownerDocument).activeElement === this) {
                            var newCaretPosition = _positioning.determineNewCaretPosition.call(inputmask, _positioning.caret.call(inputmask, this), tabbed);
                            void 0 !== newCaretPosition && _positioning.caret.call(inputmask, this, newCaretPosition);
                        }
                    },
                    cutEvent: function cutEvent(e) {
                        var inputmask = this.inputmask,
                            maskset = inputmask.maskset,
                            input = this,
                            pos = _positioning.caret.call(inputmask, this),
                            clipboardData = window.clipboardData || e.clipboardData,
                            clipData = inputmask.isRTL ? _positioning.getBuffer.call(inputmask).slice(pos.end, pos.begin) : _positioning.getBuffer.call(inputmask).slice(pos.begin, pos.end);
                        clipboardData.setData("text", inputmask.isRTL ? clipData.reverse().join("") : clipData.join("")),
                            document.execCommand && document.execCommand("copy"),
                            _validation.handleRemove.call(inputmask, this, _keycode.default.DELETE, pos),
                            (0, _inputHandling.writeBuffer)(this, _positioning.getBuffer.call(inputmask), maskset.p, e, inputmask.undoValue !== _positioning.getBuffer.call(inputmask).join(""));
                    },
                    blurEvent: function blurEvent(e) {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            $ = inputmask.dependencyLib,
                            $input = $(this),
                            input = this;
                        if (this.inputmask) {
                            (0, _inputHandling.HandleNativePlaceholder)(this, inputmask.originalPlaceholder);
                            var nptValue = this.inputmask._valueGet(),
                                buffer = _positioning.getBuffer.call(inputmask).slice();
                            "" !== nptValue &&
                                (opts.clearMaskOnLostFocus &&
                                    (-1 === _positioning.getLastValidPosition.call(inputmask) && nptValue === _positioning.getBufferTemplate.call(inputmask).join("")
                                        ? (buffer = [])
                                        : _inputHandling.clearOptionalTail.call(inputmask, buffer)),
                                !1 === _validation.isComplete.call(inputmask, buffer) &&
                                    (setTimeout(function () {
                                        $input.trigger("incomplete");
                                    }, 0),
                                    opts.clearIncomplete && (_positioning.resetMaskSet.call(inputmask), (buffer = opts.clearMaskOnLostFocus ? [] : _positioning.getBufferTemplate.call(inputmask).slice()))),
                                (0, _inputHandling.writeBuffer)(this, buffer, void 0, e)),
                                inputmask.undoValue !== _positioning.getBuffer.call(inputmask).join("") && ((inputmask.undoValue = _positioning.getBuffer.call(inputmask).join("")), $input.trigger("change"));
                        }
                    },
                    mouseenterEvent: function mouseenterEvent() {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts,
                            input = this;
                        if (((inputmask.mouseEnter = !0), (this.inputmask.shadowRoot || this.ownerDocument).activeElement !== this)) {
                            var bufferTemplate = (inputmask.isRTL ? _positioning.getBufferTemplate.call(inputmask).slice().reverse() : _positioning.getBufferTemplate.call(inputmask)).join("");
                            inputmask.placeholder !== bufferTemplate && this.placeholder !== inputmask.originalPlaceholder && (inputmask.originalPlaceholder = this.placeholder),
                                opts.showMaskOnHover && (0, _inputHandling.HandleNativePlaceholder)(this, bufferTemplate);
                        }
                    },
                    submitEvent: function submitEvent() {
                        var inputmask = this.inputmask,
                            opts = inputmask.opts;
                        inputmask.undoValue !== _positioning.getBuffer.call(inputmask).join("") && inputmask.$el.trigger("change"),
                            opts.clearMaskOnLostFocus &&
                                -1 === _positioning.getLastValidPosition.call(inputmask) &&
                                inputmask._valueGet &&
                                inputmask._valueGet() === _positioning.getBufferTemplate.call(inputmask).join("") &&
                                inputmask._valueSet(""),
                            opts.clearIncomplete && !1 === _validation.isComplete.call(inputmask, _positioning.getBuffer.call(inputmask)) && inputmask._valueSet(""),
                            opts.removeMaskOnSubmit &&
                                (inputmask._valueSet(inputmask.unmaskedvalue(), !0),
                                setTimeout(function () {
                                    (0, _inputHandling.writeBuffer)(inputmask.el, _positioning.getBuffer.call(inputmask));
                                }, 0));
                    },
                    resetEvent: function resetEvent() {
                        var inputmask = this.inputmask;
                        (inputmask.refreshValue = !0),
                            setTimeout(function () {
                                (0, _inputHandling.applyInputValue)(inputmask.el, inputmask._valueGet(!0));
                            }, 0);
                    },
                };
                exports.EventHandlers = EventHandlers;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.iphone = exports.iemobile = exports.mobile = exports.ie = exports.ua = void 0);
                var _window = _interopRequireDefault(__webpack_require__(8));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var ua = (_window.default.navigator && _window.default.navigator.userAgent) || "",
                    ie = 0 < ua.indexOf("MSIE ") || 0 < ua.indexOf("Trident/"),
                    mobile = "ontouchstart" in _window.default,
                    iemobile = /iemobile/i.test(ua),
                    iphone = /iphone/i.test(ua) && !iemobile;
                (exports.iphone = iphone), (exports.iemobile = iemobile), (exports.mobile = mobile), (exports.ie = ie), (exports.ua = ua);
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var _canUseDOM = _interopRequireDefault(__webpack_require__(9));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var _default = _canUseDOM.default ? window : {};
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var canUseDOM = !("undefined" == typeof window || !window.document || !window.document.createElement),
                    _default = canUseDOM;
                exports.default = _default;
            },
            function (module, exports) {
                module.exports = __WEBPACK_EXTERNAL_MODULE__10__;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.EventRuler = void 0);
                var _inputmask = _interopRequireDefault(__webpack_require__(2)),
                    _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _positioning = __webpack_require__(1),
                    _inputHandling = __webpack_require__(5);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var EventRuler = {
                    on: function on(input, eventName, eventHandler) {
                        var $ = input.inputmask.dependencyLib,
                            ev = function ev(e) {
                                e.originalEvent && ((e = e.originalEvent || e), (arguments[0] = e));
                                var that = this,
                                    args,
                                    inputmask = that.inputmask,
                                    opts = inputmask ? inputmask.opts : void 0;
                                if (void 0 === inputmask && "FORM" !== this.nodeName) {
                                    var imOpts = $.data(that, "_inputmask_opts");
                                    $(that).off(), imOpts && new _inputmask.default(imOpts).mask(that);
                                } else {
                                    if (
                                        ["submit", "reset", "setvalue"].includes(e.type) ||
                                        "FORM" === this.nodeName ||
                                        !(that.disabled || (that.readOnly && !(("keydown" === e.type && e.ctrlKey && 67 === e.keyCode) || (!1 === opts.tabThrough && e.keyCode === _keycode.default.TAB))))
                                    ) {
                                        switch (e.type) {
                                            case "input":
                                                if (!0 === inputmask.skipInputEvent || (e.inputType && "insertCompositionText" === e.inputType)) return (inputmask.skipInputEvent = !1), e.preventDefault();
                                                break;
                                            case "keydown":
                                                (inputmask.skipKeyPressEvent = !1), (inputmask.skipInputEvent = inputmask.isComposing = e.keyCode === _keycode.default.KEY_229);
                                                break;
                                            case "keyup":
                                            case "compositionend":
                                                inputmask.isComposing && (inputmask.skipInputEvent = !1);
                                                break;
                                            case "keypress":
                                                if (!0 === inputmask.skipKeyPressEvent) return e.preventDefault();
                                                inputmask.skipKeyPressEvent = !0;
                                                break;
                                            case "click":
                                            case "focus":
                                                return (
                                                    inputmask.validationEvent
                                                        ? ((inputmask.validationEvent = !1),
                                                          input.blur(),
                                                          (0, _inputHandling.HandleNativePlaceholder)(
                                                              input,
                                                              (inputmask.isRTL ? _positioning.getBufferTemplate.call(inputmask).slice().reverse() : _positioning.getBufferTemplate.call(inputmask)).join("")
                                                          ),
                                                          setTimeout(function () {
                                                              input.focus();
                                                          }, 3e3))
                                                        : ((args = arguments),
                                                          setTimeout(function () {
                                                              input.inputmask && eventHandler.apply(that, args);
                                                          }, 0)),
                                                    !1
                                                );
                                        }
                                        var returnVal = eventHandler.apply(that, arguments);
                                        return !1 === returnVal && (e.preventDefault(), e.stopPropagation()), returnVal;
                                    }
                                    e.preventDefault();
                                }
                            };
                        ["submit", "reset"].includes(eventName) ? ((ev = ev.bind(input)), null !== input.form && $(input.form).on(eventName, ev)) : $(input).on(eventName, ev),
                            (input.inputmask.events[eventName] = input.inputmask.events[eventName] || []),
                            input.inputmask.events[eventName].push(ev);
                    },
                    off: function off(input, event) {
                        if (input.inputmask && input.inputmask.events) {
                            var $ = input.inputmask.dependencyLib,
                                events = input.inputmask.events;
                            for (var eventName in (event && ((events = []), (events[event] = input.inputmask.events[event])), events)) {
                                for (var evArr = events[eventName]; 0 < evArr.length; ) {
                                    var ev = evArr.pop();
                                    ["submit", "reset"].includes(eventName) ? null !== input.form && $(input.form).off(eventName, ev) : $(input).off(eventName, ev);
                                }
                                delete input.inputmask.events[eventName];
                            }
                        }
                    },
                };
                exports.EventRuler = EventRuler;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var _jquery = _interopRequireDefault(__webpack_require__(10));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                if (void 0 === _jquery.default) throw "jQuery not loaded!";
                var _default = _jquery.default;
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = _default);
                var escapeRegexRegex = new RegExp("(\\" + ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^"].join("|\\") + ")", "gim");
                function _default(str) {
                    return str.replace(escapeRegexRegex, "\\$1");
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0), __webpack_require__(15), __webpack_require__(23), __webpack_require__(24), __webpack_require__(25);
                var _inputmask2 = _interopRequireDefault(__webpack_require__(2));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var _default = _inputmask2.default;
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                var _inputmask = _interopRequireDefault(__webpack_require__(2)),
                    _positioning = __webpack_require__(1),
                    _validationTests = __webpack_require__(3);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                _inputmask.default.extendDefinitions({
                    A: { validator: "[A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]", casing: "upper" },
                    "&": { validator: "[0-9A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]", casing: "upper" },
                    "#": { validator: "[0-9A-Fa-f]", casing: "upper" },
                });
                var ipValidatorRegex = new RegExp("25[0-5]|2[0-4][0-9]|[01][0-9][0-9]");
                function ipValidator(chrs, maskset, pos, strict, opts) {
                    return (
                        (chrs = -1 < pos - 1 && "." !== maskset.buffer[pos - 1] ? ((chrs = maskset.buffer[pos - 1] + chrs), -1 < pos - 2 && "." !== maskset.buffer[pos - 2] ? maskset.buffer[pos - 2] + chrs : "0" + chrs) : "00" + chrs),
                        ipValidatorRegex.test(chrs)
                    );
                }
                _inputmask.default.extendAliases({
                    cssunit: { regex: "[+-]?[0-9]+\\.?([0-9]+)?(px|em|rem|ex|%|in|cm|mm|pt|pc)" },
                    url: { regex: "(https?|ftp)://.*", autoUnmask: !1, keepStatic: !1, tabThrough: !0 },
                    ip: {
                        mask: "i[i[i]].j[j[j]].k[k[k]].l[l[l]]",
                        definitions: { i: { validator: ipValidator }, j: { validator: ipValidator }, k: { validator: ipValidator }, l: { validator: ipValidator } },
                        onUnMask: function onUnMask(maskedValue, unmaskedValue, opts) {
                            return maskedValue;
                        },
                        inputmode: "numeric",
                    },
                    email: {
                        mask: "*{1,64}[.*{1,64}][.*{1,64}][.*{1,63}]@-{1,63}.-{1,63}[.-{1,63}][.-{1,63}]",
                        greedy: !1,
                        casing: "lower",
                        onBeforePaste: function onBeforePaste(pastedValue, opts) {
                            return (pastedValue = pastedValue.toLowerCase()), pastedValue.replace("mailto:", "");
                        },
                        definitions: { "*": { validator: "[0-9\uff11-\uff19A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5!#$%&'*+/=?^_`{|}~-]" }, "-": { validator: "[0-9A-Za-z-]" } },
                        onUnMask: function onUnMask(maskedValue, unmaskedValue, opts) {
                            return maskedValue;
                        },
                        inputmode: "email",
                    },
                    mac: { mask: "##:##:##:##:##:##" },
                    vin: { mask: "V{13}9{4}", definitions: { V: { validator: "[A-HJ-NPR-Za-hj-npr-z\\d]", casing: "upper" } }, clearIncomplete: !0, autoUnmask: !0 },
                    ssn: {
                        mask: "999-99-9999",
                        postValidation: function postValidation(buffer, pos, c, currentResult, opts, maskset, strict) {
                            var bffr = _validationTests.getMaskTemplate.call(this, !0, _positioning.getLastValidPosition.call(this), !0, !0);
                            return /^(?!219-09-9999|078-05-1120)(?!666|000|9.{2}).{3}-(?!00).{2}-(?!0{4}).{4}$/.test(bffr.join(""));
                        },
                    },
                });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                function _typeof(obj) {
                    return (
                        (_typeof =
                            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                                ? function _typeof(obj) {
                                      return typeof obj;
                                  }
                                : function _typeof(obj) {
                                      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                                  }),
                        _typeof(obj)
                    );
                }
                "function" != typeof Object.getPrototypeOf &&
                    (Object.getPrototypeOf =
                        "object" === _typeof("test".__proto__)
                            ? function (object) {
                                  return object.__proto__;
                              }
                            : function (object) {
                                  return object.constructor.prototype;
                              });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Array.prototype.includes ||
                    Object.defineProperty(Array.prototype, "includes", {
                        value: function value(searchElement, fromIndex) {
                            if (null == this) throw new TypeError('"this" is null or not defined');
                            var o = Object(this),
                                len = o.length >>> 0;
                            if (0 == len) return !1;
                            for (var n = 0 | fromIndex, k = Math.max(0 <= n ? n : len - Math.abs(n), 0); k < len; ) {
                                if (o[k] === searchElement) return !0;
                                k++;
                            }
                            return !1;
                        },
                    });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.mask = mask);
                var _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _positioning = __webpack_require__(1),
                    _inputHandling = __webpack_require__(5),
                    _eventruler = __webpack_require__(11),
                    _environment = __webpack_require__(7),
                    _validation = __webpack_require__(4),
                    _eventhandlers = __webpack_require__(6);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                function mask() {
                    var inputmask = this,
                        opts = this.opts,
                        el = this.el,
                        $ = this.dependencyLib;
                    function isElementTypeSupported(input, opts) {
                        function patchValueProperty(npt) {
                            var valueGet, valueSet;
                            function patchValhook(type) {
                                if ($.valHooks && (void 0 === $.valHooks[type] || !0 !== $.valHooks[type].inputmaskpatch)) {
                                    var valhookGet =
                                            $.valHooks[type] && $.valHooks[type].get
                                                ? $.valHooks[type].get
                                                : function (elem) {
                                                      return elem.value;
                                                  },
                                        valhookSet =
                                            $.valHooks[type] && $.valHooks[type].set
                                                ? $.valHooks[type].set
                                                : function (elem, value) {
                                                      return (elem.value = value), elem;
                                                  };
                                    $.valHooks[type] = {
                                        get: function get(elem) {
                                            if (elem.inputmask) {
                                                if (elem.inputmask.opts.autoUnmask) return elem.inputmask.unmaskedvalue();
                                                var result = valhookGet(elem);
                                                return -1 !== _positioning.getLastValidPosition.call(inputmask, void 0, void 0, elem.inputmask.maskset.validPositions) || !0 !== opts.nullable ? result : "";
                                            }
                                            return valhookGet(elem);
                                        },
                                        set: function set(elem, value) {
                                            var result = valhookSet(elem, value);
                                            return elem.inputmask && (0, _inputHandling.applyInputValue)(elem, value), result;
                                        },
                                        inputmaskpatch: !0,
                                    };
                                }
                            }
                            function getter() {
                                return this.inputmask
                                    ? this.inputmask.opts.autoUnmask
                                        ? this.inputmask.unmaskedvalue()
                                        : -1 !== _positioning.getLastValidPosition.call(inputmask) || !0 !== opts.nullable
                                        ? (this.inputmask.shadowRoot || this.ownerDocument).activeElement === this && opts.clearMaskOnLostFocus
                                            ? (inputmask.isRTL
                                                  ? _inputHandling.clearOptionalTail.call(inputmask, _positioning.getBuffer.call(inputmask).slice()).reverse()
                                                  : _inputHandling.clearOptionalTail.call(inputmask, _positioning.getBuffer.call(inputmask).slice())
                                              ).join("")
                                            : valueGet.call(this)
                                        : ""
                                    : valueGet.call(this);
                            }
                            function setter(value) {
                                valueSet.call(this, value), this.inputmask && (0, _inputHandling.applyInputValue)(this, value);
                            }
                            function installNativeValueSetFallback(npt) {
                                _eventruler.EventRuler.on(npt, "mouseenter", function () {
                                    var input = this,
                                        value = this.inputmask._valueGet(!0);
                                    value !== (inputmask.isRTL ? _positioning.getBuffer.call(inputmask).reverse() : _positioning.getBuffer.call(inputmask)).join("") && (0, _inputHandling.applyInputValue)(this, value);
                                });
                            }
                            if (!npt.inputmask.__valueGet) {
                                if (!0 !== opts.noValuePatching) {
                                    if (Object.getOwnPropertyDescriptor) {
                                        var valueProperty = Object.getPrototypeOf ? Object.getOwnPropertyDescriptor(Object.getPrototypeOf(npt), "value") : void 0;
                                        valueProperty && valueProperty.get && valueProperty.set
                                            ? ((valueGet = valueProperty.get), (valueSet = valueProperty.set), Object.defineProperty(npt, "value", { get: getter, set: setter, configurable: !0 }))
                                            : "input" !== npt.tagName.toLowerCase() &&
                                              ((valueGet = function valueGet() {
                                                  return this.textContent;
                                              }),
                                              (valueSet = function valueSet(value) {
                                                  this.textContent = value;
                                              }),
                                              Object.defineProperty(npt, "value", { get: getter, set: setter, configurable: !0 }));
                                    } else
                                        document.__lookupGetter__ &&
                                            npt.__lookupGetter__("value") &&
                                            ((valueGet = npt.__lookupGetter__("value")), (valueSet = npt.__lookupSetter__("value")), npt.__defineGetter__("value", getter), npt.__defineSetter__("value", setter));
                                    (npt.inputmask.__valueGet = valueGet), (npt.inputmask.__valueSet = valueSet);
                                }
                                (npt.inputmask._valueGet = function (overruleRTL) {
                                    return inputmask.isRTL && !0 !== overruleRTL ? valueGet.call(this.el).split("").reverse().join("") : valueGet.call(this.el);
                                }),
                                    (npt.inputmask._valueSet = function (value, overruleRTL) {
                                        valueSet.call(this.el, null == value ? "" : !0 !== overruleRTL && inputmask.isRTL ? value.split("").reverse().join("") : value);
                                    }),
                                    void 0 === valueGet &&
                                        ((valueGet = function valueGet() {
                                            return this.value;
                                        }),
                                        (valueSet = function valueSet(value) {
                                            this.value = value;
                                        }),
                                        patchValhook(npt.type),
                                        installNativeValueSetFallback(npt));
                            }
                        }
                        "textarea" !== input.tagName.toLowerCase() && opts.ignorables.push(_keycode.default.ENTER);
                        var elementType = input.getAttribute("type"),
                            isSupported = ("input" === input.tagName.toLowerCase() && opts.supportsInputType.includes(elementType)) || input.isContentEditable || "textarea" === input.tagName.toLowerCase();
                        if (!isSupported)
                            if ("input" === input.tagName.toLowerCase()) {
                                var el = document.createElement("input");
                                el.setAttribute("type", elementType), (isSupported = "text" === el.type), (el = null);
                            } else isSupported = "partial";
                        return !1 !== isSupported ? patchValueProperty(input) : (input.inputmask = void 0), isSupported;
                    }
                    _eventruler.EventRuler.off(el);
                    var isSupported = isElementTypeSupported(el, opts);
                    if (!1 !== isSupported) {
                        (inputmask.originalPlaceholder = el.placeholder),
                            (inputmask.maxLength = void 0 !== el ? el.maxLength : void 0),
                            -1 === inputmask.maxLength && (inputmask.maxLength = void 0),
                            "inputMode" in el && null === el.getAttribute("inputmode") && ((el.inputMode = opts.inputmode), el.setAttribute("inputmode", opts.inputmode)),
                            !0 === isSupported &&
                                ((opts.showMaskOnFocus = opts.showMaskOnFocus && -1 === ["cc-number", "cc-exp"].indexOf(el.autocomplete)),
                                _environment.iphone && (opts.insertModeVisual = !1),
                                _eventruler.EventRuler.on(el, "submit", _eventhandlers.EventHandlers.submitEvent),
                                _eventruler.EventRuler.on(el, "reset", _eventhandlers.EventHandlers.resetEvent),
                                _eventruler.EventRuler.on(el, "blur", _eventhandlers.EventHandlers.blurEvent),
                                _eventruler.EventRuler.on(el, "focus", _eventhandlers.EventHandlers.focusEvent),
                                _eventruler.EventRuler.on(el, "invalid", _eventhandlers.EventHandlers.invalidEvent),
                                _eventruler.EventRuler.on(el, "click", _eventhandlers.EventHandlers.clickEvent),
                                _eventruler.EventRuler.on(el, "mouseleave", _eventhandlers.EventHandlers.mouseleaveEvent),
                                _eventruler.EventRuler.on(el, "mouseenter", _eventhandlers.EventHandlers.mouseenterEvent),
                                _eventruler.EventRuler.on(el, "paste", _eventhandlers.EventHandlers.pasteEvent),
                                _eventruler.EventRuler.on(el, "cut", _eventhandlers.EventHandlers.cutEvent),
                                _eventruler.EventRuler.on(el, "complete", opts.oncomplete),
                                _eventruler.EventRuler.on(el, "incomplete", opts.onincomplete),
                                _eventruler.EventRuler.on(el, "cleared", opts.oncleared),
                                !0 !== opts.inputEventOnly &&
                                    (_eventruler.EventRuler.on(el, "keydown", _eventhandlers.EventHandlers.keydownEvent),
                                    _eventruler.EventRuler.on(el, "keypress", _eventhandlers.EventHandlers.keypressEvent),
                                    _eventruler.EventRuler.on(el, "keyup", _eventhandlers.EventHandlers.keyupEvent)),
                                (_environment.mobile || opts.inputEventOnly) && el.removeAttribute("maxLength"),
                                _eventruler.EventRuler.on(el, "input", _eventhandlers.EventHandlers.inputFallBackEvent),
                                _eventruler.EventRuler.on(el, "compositionend", _eventhandlers.EventHandlers.compositionendEvent)),
                            _eventruler.EventRuler.on(el, "setvalue", _eventhandlers.EventHandlers.setValueEvent),
                            (inputmask.undoValue = _positioning.getBufferTemplate.call(inputmask).join(""));
                        var activeElement = (el.inputmask.shadowRoot || el.ownerDocument).activeElement;
                        if ("" !== el.inputmask._valueGet(!0) || !1 === opts.clearMaskOnLostFocus || activeElement === el) {
                            (0, _inputHandling.applyInputValue)(el, el.inputmask._valueGet(!0), opts);
                            var buffer = _positioning.getBuffer.call(inputmask).slice();
                            !1 === _validation.isComplete.call(inputmask, buffer) && opts.clearIncomplete && _positioning.resetMaskSet.call(inputmask),
                                opts.clearMaskOnLostFocus && activeElement !== el && (-1 === _positioning.getLastValidPosition.call(inputmask) ? (buffer = []) : _inputHandling.clearOptionalTail.call(inputmask, buffer)),
                                (!1 === opts.clearMaskOnLostFocus || (opts.showMaskOnFocus && activeElement === el) || "" !== el.inputmask._valueGet(!0)) && (0, _inputHandling.writeBuffer)(el, buffer),
                                activeElement === el && _positioning.caret.call(inputmask, el, _positioning.seekNext.call(inputmask, _positioning.getLastValidPosition.call(inputmask)));
                        }
                    }
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.generateMaskSet = generateMaskSet), (exports.analyseMask = analyseMask);
                var _inputmask = _interopRequireDefault(__webpack_require__(12)),
                    _masktoken = _interopRequireDefault(__webpack_require__(20));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                function generateMaskSet(opts, nocache) {
                    var ms;
                    function generateMask(mask, metadata, opts) {
                        var regexMask = !1,
                            masksetDefinition,
                            maskdefKey;
                        if (
                            ((null !== mask && "" !== mask) || ((regexMask = null !== opts.regex), (mask = regexMask ? ((mask = opts.regex), mask.replace(/^(\^)(.*)(\$)$/, "$2")) : ((regexMask = !0), ".*"))),
                            1 === mask.length && !1 === opts.greedy && 0 !== opts.repeat && (opts.placeholder = ""),
                            0 < opts.repeat || "*" === opts.repeat || "+" === opts.repeat)
                        ) {
                            var repeatStart = "*" === opts.repeat ? 0 : "+" === opts.repeat ? 1 : opts.repeat;
                            mask = opts.groupmarker[0] + mask + opts.groupmarker[1] + opts.quantifiermarker[0] + repeatStart + "," + opts.repeat + opts.quantifiermarker[1];
                        }
                        return (
                            (maskdefKey = regexMask ? "regex_" + opts.regex : opts.numericInput ? mask.split("").reverse().join("") : mask),
                            !1 !== opts.keepStatic && (maskdefKey = "ks_" + maskdefKey),
                            void 0 === Inputmask.prototype.masksCache[maskdefKey] || !0 === nocache
                                ? ((masksetDefinition = {
                                      mask: mask,
                                      maskToken: Inputmask.prototype.analyseMask(mask, regexMask, opts),
                                      validPositions: {},
                                      _buffer: void 0,
                                      buffer: void 0,
                                      tests: {},
                                      excludes: {},
                                      metadata: metadata,
                                      maskLength: void 0,
                                      jitOffset: {},
                                  }),
                                  !0 !== nocache && ((Inputmask.prototype.masksCache[maskdefKey] = masksetDefinition), (masksetDefinition = _inputmask.default.extend(!0, {}, Inputmask.prototype.masksCache[maskdefKey]))))
                                : (masksetDefinition = _inputmask.default.extend(!0, {}, Inputmask.prototype.masksCache[maskdefKey])),
                            masksetDefinition
                        );
                    }
                    if (("function" == typeof opts.mask && (opts.mask = opts.mask(opts)), Array.isArray(opts.mask))) {
                        if (1 < opts.mask.length) {
                            null === opts.keepStatic && (opts.keepStatic = !0);
                            var altMask = opts.groupmarker[0];
                            return (
                                (opts.isRTL ? opts.mask.reverse() : opts.mask).forEach(function (msk) {
                                    1 < altMask.length && (altMask += opts.groupmarker[1] + opts.alternatormarker + opts.groupmarker[0]), void 0 !== msk.mask && "function" != typeof msk.mask ? (altMask += msk.mask) : (altMask += msk);
                                }),
                                (altMask += opts.groupmarker[1]),
                                generateMask(altMask, opts.mask, opts)
                            );
                        }
                        opts.mask = opts.mask.pop();
                    }
                    return (
                        null === opts.keepStatic && (opts.keepStatic = !1),
                        (ms = opts.mask && void 0 !== opts.mask.mask && "function" != typeof opts.mask.mask ? generateMask(opts.mask.mask, opts.mask, opts) : generateMask(opts.mask, opts.mask, opts)),
                        ms
                    );
                }
                function analyseMask(mask, regexMask, opts) {
                    var tokenizer = /(?:[?*+]|\{[0-9+*]+(?:,[0-9+*]*)?(?:\|[0-9+*]*)?\})|[^.?*+^${[]()|\\]+|./g,
                        regexTokenizer = /\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g,
                        escaped = !1,
                        currentToken = new _masktoken.default(),
                        match,
                        m,
                        openenings = [],
                        maskTokens = [],
                        openingToken,
                        currentOpeningToken,
                        alternator,
                        lastMatch,
                        closeRegexGroup = !1;
                    function insertTestDefinition(mtoken, element, position) {
                        position = void 0 !== position ? position : mtoken.matches.length;
                        var prevMatch = mtoken.matches[position - 1];
                        if (regexMask)
                            0 === element.indexOf("[") || (escaped && /\\d|\\s|\\w]/i.test(element)) || "." === element
                                ? mtoken.matches.splice(position++, 0, {
                                      fn: new RegExp(element, opts.casing ? "i" : ""),
                                      static: !1,
                                      optionality: !1,
                                      newBlockMarker: void 0 === prevMatch ? "master" : prevMatch.def !== element,
                                      casing: null,
                                      def: element,
                                      placeholder: void 0,
                                      nativeDef: element,
                                  })
                                : (escaped && (element = element[element.length - 1]),
                                  element.split("").forEach(function (lmnt, ndx) {
                                      (prevMatch = mtoken.matches[position - 1]),
                                          mtoken.matches.splice(position++, 0, {
                                              fn: /[a-z]/i.test(opts.staticDefinitionSymbol || lmnt) ? new RegExp("[" + (opts.staticDefinitionSymbol || lmnt) + "]", opts.casing ? "i" : "") : null,
                                              static: !0,
                                              optionality: !1,
                                              newBlockMarker: void 0 === prevMatch ? "master" : prevMatch.def !== lmnt && !0 !== prevMatch.static,
                                              casing: null,
                                              def: opts.staticDefinitionSymbol || lmnt,
                                              placeholder: void 0 !== opts.staticDefinitionSymbol ? lmnt : void 0,
                                              nativeDef: (escaped ? "'" : "") + lmnt,
                                          });
                                  })),
                                (escaped = !1);
                        else {
                            var maskdef = (opts.definitions && opts.definitions[element]) || (opts.usePrototypeDefinitions && Inputmask.prototype.definitions[element]);
                            maskdef && !escaped
                                ? mtoken.matches.splice(position++, 0, {
                                      fn: maskdef.validator
                                          ? "string" == typeof maskdef.validator
                                              ? new RegExp(maskdef.validator, opts.casing ? "i" : "")
                                              : new (function () {
                                                    this.test = maskdef.validator;
                                                })()
                                          : new RegExp("."),
                                      static: maskdef.static || !1,
                                      optionality: !1,
                                      newBlockMarker: void 0 === prevMatch ? "master" : prevMatch.def !== (maskdef.definitionSymbol || element),
                                      casing: maskdef.casing,
                                      def: maskdef.definitionSymbol || element,
                                      placeholder: maskdef.placeholder,
                                      nativeDef: element,
                                      generated: maskdef.generated,
                                  })
                                : (mtoken.matches.splice(position++, 0, {
                                      fn: /[a-z]/i.test(opts.staticDefinitionSymbol || element) ? new RegExp("[" + (opts.staticDefinitionSymbol || element) + "]", opts.casing ? "i" : "") : null,
                                      static: !0,
                                      optionality: !1,
                                      newBlockMarker: void 0 === prevMatch ? "master" : prevMatch.def !== element && !0 !== prevMatch.static,
                                      casing: null,
                                      def: opts.staticDefinitionSymbol || element,
                                      placeholder: void 0 !== opts.staticDefinitionSymbol ? element : void 0,
                                      nativeDef: (escaped ? "'" : "") + element,
                                  }),
                                  (escaped = !1));
                        }
                    }
                    function verifyGroupMarker(maskToken) {
                        maskToken &&
                            maskToken.matches &&
                            maskToken.matches.forEach(function (token, ndx) {
                                var nextToken = maskToken.matches[ndx + 1];
                                (void 0 === nextToken || void 0 === nextToken.matches || !1 === nextToken.isQuantifier) &&
                                    token &&
                                    token.isGroup &&
                                    ((token.isGroup = !1), regexMask || (insertTestDefinition(token, opts.groupmarker[0], 0), !0 !== token.openGroup && insertTestDefinition(token, opts.groupmarker[1]))),
                                    verifyGroupMarker(token);
                            });
                    }
                    function defaultCase() {
                        if (0 < openenings.length) {
                            if (((currentOpeningToken = openenings[openenings.length - 1]), insertTestDefinition(currentOpeningToken, m), currentOpeningToken.isAlternator)) {
                                alternator = openenings.pop();
                                for (var mndx = 0; mndx < alternator.matches.length; mndx++) alternator.matches[mndx].isGroup && (alternator.matches[mndx].isGroup = !1);
                                0 < openenings.length ? ((currentOpeningToken = openenings[openenings.length - 1]), currentOpeningToken.matches.push(alternator)) : currentToken.matches.push(alternator);
                            }
                        } else insertTestDefinition(currentToken, m);
                    }
                    function reverseTokens(maskToken) {
                        function reverseStatic(st) {
                            return (
                                st === opts.optionalmarker[0]
                                    ? (st = opts.optionalmarker[1])
                                    : st === opts.optionalmarker[1]
                                    ? (st = opts.optionalmarker[0])
                                    : st === opts.groupmarker[0]
                                    ? (st = opts.groupmarker[1])
                                    : st === opts.groupmarker[1] && (st = opts.groupmarker[0]),
                                st
                            );
                        }
                        for (var match in ((maskToken.matches = maskToken.matches.reverse()), maskToken.matches))
                            if (Object.prototype.hasOwnProperty.call(maskToken.matches, match)) {
                                var intMatch = parseInt(match);
                                if (maskToken.matches[match].isQuantifier && maskToken.matches[intMatch + 1] && maskToken.matches[intMatch + 1].isGroup) {
                                    var qt = maskToken.matches[match];
                                    maskToken.matches.splice(match, 1), maskToken.matches.splice(intMatch + 1, 0, qt);
                                }
                                void 0 !== maskToken.matches[match].matches ? (maskToken.matches[match] = reverseTokens(maskToken.matches[match])) : (maskToken.matches[match] = reverseStatic(maskToken.matches[match]));
                            }
                        return maskToken;
                    }
                    function groupify(matches) {
                        var groupToken = new _masktoken.default(!0);
                        return (groupToken.openGroup = !1), (groupToken.matches = matches), groupToken;
                    }
                    function closeGroup() {
                        if (((openingToken = openenings.pop()), (openingToken.openGroup = !1), void 0 !== openingToken))
                            if (0 < openenings.length) {
                                if (((currentOpeningToken = openenings[openenings.length - 1]), currentOpeningToken.matches.push(openingToken), currentOpeningToken.isAlternator)) {
                                    alternator = openenings.pop();
                                    for (var mndx = 0; mndx < alternator.matches.length; mndx++) (alternator.matches[mndx].isGroup = !1), (alternator.matches[mndx].alternatorGroup = !1);
                                    0 < openenings.length ? ((currentOpeningToken = openenings[openenings.length - 1]), currentOpeningToken.matches.push(alternator)) : currentToken.matches.push(alternator);
                                }
                            } else currentToken.matches.push(openingToken);
                        else defaultCase();
                    }
                    function groupQuantifier(matches) {
                        var lastMatch = matches.pop();
                        return lastMatch.isQuantifier && (lastMatch = groupify([matches.pop(), lastMatch])), lastMatch;
                    }
                    for (regexMask && ((opts.optionalmarker[0] = void 0), (opts.optionalmarker[1] = void 0)); (match = regexMask ? regexTokenizer.exec(mask) : tokenizer.exec(mask)); ) {
                        if (((m = match[0]), regexMask))
                            switch (m.charAt(0)) {
                                case "?":
                                    m = "{0,1}";
                                    break;
                                case "+":
                                case "*":
                                    m = "{" + m + "}";
                                    break;
                                case "|":
                                    if (0 === openenings.length) {
                                        var altRegexGroup = groupify(currentToken.matches);
                                        (altRegexGroup.openGroup = !0), openenings.push(altRegexGroup), (currentToken.matches = []), (closeRegexGroup = !0);
                                    }
                                    break;
                            }
                        if (escaped) defaultCase();
                        else
                            switch (m.charAt(0)) {
                                case "$":
                                case "^":
                                    regexMask || defaultCase();
                                    break;
                                case "(?=":
                                    openenings.push(new _masktoken.default(!0));
                                    break;
                                case "(?!":
                                    openenings.push(new _masktoken.default(!0));
                                    break;
                                case "(?<=":
                                    openenings.push(new _masktoken.default(!0));
                                    break;
                                case "(?<!":
                                    openenings.push(new _masktoken.default(!0));
                                    break;
                                case opts.escapeChar:
                                    (escaped = !0), regexMask && defaultCase();
                                    break;
                                case opts.optionalmarker[1]:
                                case opts.groupmarker[1]:
                                    closeGroup();
                                    break;
                                case opts.optionalmarker[0]:
                                    openenings.push(new _masktoken.default(!1, !0));
                                    break;
                                case opts.groupmarker[0]:
                                    openenings.push(new _masktoken.default(!0));
                                    break;
                                case opts.quantifiermarker[0]:
                                    var quantifier = new _masktoken.default(!1, !1, !0);
                                    m = m.replace(/[{}]/g, "");
                                    var mqj = m.split("|"),
                                        mq = mqj[0].split(","),
                                        mq0 = isNaN(mq[0]) ? mq[0] : parseInt(mq[0]),
                                        mq1 = 1 === mq.length ? mq0 : isNaN(mq[1]) ? mq[1] : parseInt(mq[1]);
                                    ("*" !== mq0 && "+" !== mq0) || (mq0 = "*" === mq1 ? 0 : 1), (quantifier.quantifier = { min: mq0, max: mq1, jit: mqj[1] });
                                    var matches = 0 < openenings.length ? openenings[openenings.length - 1].matches : currentToken.matches;
                                    if (((match = matches.pop()), match.isAlternator)) {
                                        matches.push(match), (matches = match.matches);
                                        var groupToken = new _masktoken.default(!0),
                                            tmpMatch = matches.pop();
                                        matches.push(groupToken), (matches = groupToken.matches), (match = tmpMatch);
                                    }
                                    match.isGroup || (match = groupify([match])), matches.push(match), matches.push(quantifier);
                                    break;
                                case opts.alternatormarker:
                                    if (0 < openenings.length) {
                                        currentOpeningToken = openenings[openenings.length - 1];
                                        var subToken = currentOpeningToken.matches[currentOpeningToken.matches.length - 1];
                                        lastMatch =
                                            currentOpeningToken.openGroup && (void 0 === subToken.matches || (!1 === subToken.isGroup && !1 === subToken.isAlternator)) ? openenings.pop() : groupQuantifier(currentOpeningToken.matches);
                                    } else lastMatch = groupQuantifier(currentToken.matches);
                                    if (lastMatch.isAlternator) openenings.push(lastMatch);
                                    else if (
                                        (lastMatch.alternatorGroup ? ((alternator = openenings.pop()), (lastMatch.alternatorGroup = !1)) : (alternator = new _masktoken.default(!1, !1, !1, !0)),
                                        alternator.matches.push(lastMatch),
                                        openenings.push(alternator),
                                        lastMatch.openGroup)
                                    ) {
                                        lastMatch.openGroup = !1;
                                        var alternatorGroup = new _masktoken.default(!0);
                                        (alternatorGroup.alternatorGroup = !0), openenings.push(alternatorGroup);
                                    }
                                    break;
                                default:
                                    defaultCase();
                            }
                    }
                    for (closeRegexGroup && closeGroup(); 0 < openenings.length; ) (openingToken = openenings.pop()), currentToken.matches.push(openingToken);
                    return 0 < currentToken.matches.length && (verifyGroupMarker(currentToken), maskTokens.push(currentToken)), (opts.numericInput || opts.isRTL) && reverseTokens(maskTokens[0]), maskTokens;
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                function _default(isGroup, isOptional, isQuantifier, isAlternator) {
                    (this.matches = []),
                        (this.openGroup = isGroup || !1),
                        (this.alternatorGroup = !1),
                        (this.isGroup = isGroup || !1),
                        (this.isOptional = isOptional || !1),
                        (this.isQuantifier = isQuantifier || !1),
                        (this.isAlternator = isAlternator || !1),
                        (this.quantifier = { min: 1, max: 1 });
                }
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = _default);
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var _default = {
                    9: { validator: "[0-9\uff10-\uff19]", definitionSymbol: "*" },
                    a: { validator: "[A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]", definitionSymbol: "*" },
                    "*": { validator: "[0-9\uff10-\uff19A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]" },
                };
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var _default = {
                    _maxTestPos: 500,
                    placeholder: "_",
                    optionalmarker: ["[", "]"],
                    quantifiermarker: ["{", "}"],
                    groupmarker: ["(", ")"],
                    alternatormarker: "|",
                    escapeChar: "\\",
                    mask: null,
                    regex: null,
                    oncomplete: function oncomplete() {},
                    onincomplete: function onincomplete() {},
                    oncleared: function oncleared() {},
                    repeat: 0,
                    greedy: !1,
                    autoUnmask: !1,
                    removeMaskOnSubmit: !1,
                    clearMaskOnLostFocus: !0,
                    insertMode: !0,
                    insertModeVisual: !0,
                    clearIncomplete: !1,
                    alias: null,
                    onKeyDown: function onKeyDown() {},
                    onBeforeMask: null,
                    onBeforePaste: function onBeforePaste(pastedValue, opts) {
                        return "function" == typeof opts.onBeforeMask ? opts.onBeforeMask.call(this, pastedValue, opts) : pastedValue;
                    },
                    onBeforeWrite: null,
                    onUnMask: null,
                    showMaskOnFocus: !0,
                    showMaskOnHover: !0,
                    onKeyValidation: function onKeyValidation() {},
                    skipOptionalPartCharacter: " ",
                    numericInput: !1,
                    rightAlign: !1,
                    undoOnEscape: !0,
                    radixPoint: "",
                    _radixDance: !1,
                    groupSeparator: "",
                    keepStatic: null,
                    positionCaretOnTab: !0,
                    tabThrough: !1,
                    supportsInputType: ["text", "tel", "url", "password", "search"],
                    ignorables: [8, 9, 19, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 0, 229],
                    isComplete: null,
                    preValidation: null,
                    postValidation: null,
                    staticDefinitionSymbol: void 0,
                    jitMasking: !1,
                    nullable: !0,
                    inputEventOnly: !1,
                    noValuePatching: !1,
                    positionCaretOnClick: "lvp",
                    casing: null,
                    inputmode: "text",
                    importDataAttributes: !0,
                    shiftPositions: !0,
                    usePrototypeDefinitions: !0,
                };
                exports.default = _default;
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                var _inputmask = _interopRequireDefault(__webpack_require__(2)),
                    _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _escapeRegex = _interopRequireDefault(__webpack_require__(13)),
                    _positioning = __webpack_require__(1);
                function _typeof(obj) {
                    return (
                        (_typeof =
                            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                                ? function _typeof(obj) {
                                      return typeof obj;
                                  }
                                : function _typeof(obj) {
                                      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                                  }),
                        _typeof(obj)
                    );
                }
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var $ = _inputmask.default.dependencyLib,
                    currentYear = new Date().getFullYear(),
                    formatCode = {
                        d: ["[1-9]|[12][0-9]|3[01]", Date.prototype.setDate, "day", Date.prototype.getDate],
                        dd: [
                            "0[1-9]|[12][0-9]|3[01]",
                            Date.prototype.setDate,
                            "day",
                            function () {
                                return pad(Date.prototype.getDate.call(this), 2);
                            },
                        ],
                        ddd: [""],
                        dddd: [""],
                        m: [
                            "[1-9]|1[012]",
                            Date.prototype.setMonth,
                            "month",
                            function () {
                                return Date.prototype.getMonth.call(this) + 1;
                            },
                        ],
                        mm: [
                            "0[1-9]|1[012]",
                            Date.prototype.setMonth,
                            "month",
                            function () {
                                return pad(Date.prototype.getMonth.call(this) + 1, 2);
                            },
                        ],
                        mmm: [""],
                        mmmm: [""],
                        yy: [
                            "[0-9]{2}",
                            Date.prototype.setFullYear,
                            "year",
                            function () {
                                return pad(Date.prototype.getFullYear.call(this), 2);
                            },
                        ],
                        yyyy: [
                            "[0-9]{4}",
                            Date.prototype.setFullYear,
                            "year",
                            function () {
                                return pad(Date.prototype.getFullYear.call(this), 4);
                            },
                        ],
                        h: ["[1-9]|1[0-2]", Date.prototype.setHours, "hours", Date.prototype.getHours],
                        hh: [
                            "0[1-9]|1[0-2]",
                            Date.prototype.setHours,
                            "hours",
                            function () {
                                return pad(Date.prototype.getHours.call(this), 2);
                            },
                        ],
                        hx: [
                            function (x) {
                                return "[0-9]{".concat(x, "}");
                            },
                            Date.prototype.setHours,
                            "hours",
                            function (x) {
                                return Date.prototype.getHours;
                            },
                        ],
                        H: ["1?[0-9]|2[0-3]", Date.prototype.setHours, "hours", Date.prototype.getHours],
                        HH: [
                            "0[0-9]|1[0-9]|2[0-3]",
                            Date.prototype.setHours,
                            "hours",
                            function () {
                                return pad(Date.prototype.getHours.call(this), 2);
                            },
                        ],
                        Hx: [
                            function (x) {
                                return "[0-9]{".concat(x, "}");
                            },
                            Date.prototype.setHours,
                            "hours",
                            function (x) {
                                return function () {
                                    return pad(Date.prototype.getHours.call(this), x);
                                };
                            },
                        ],
                        M: ["[1-5]?[0-9]", Date.prototype.setMinutes, "minutes", Date.prototype.getMinutes],
                        MM: [
                            "0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9]",
                            Date.prototype.setMinutes,
                            "minutes",
                            function () {
                                return pad(Date.prototype.getMinutes.call(this), 2);
                            },
                        ],
                        s: ["[1-5]?[0-9]", Date.prototype.setSeconds, "seconds", Date.prototype.getSeconds],
                        ss: [
                            "0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9]",
                            Date.prototype.setSeconds,
                            "seconds",
                            function () {
                                return pad(Date.prototype.getSeconds.call(this), 2);
                            },
                        ],
                        l: [
                            "[0-9]{3}",
                            Date.prototype.setMilliseconds,
                            "milliseconds",
                            function () {
                                return pad(Date.prototype.getMilliseconds.call(this), 3);
                            },
                        ],
                        L: [
                            "[0-9]{2}",
                            Date.prototype.setMilliseconds,
                            "milliseconds",
                            function () {
                                return pad(Date.prototype.getMilliseconds.call(this), 2);
                            },
                        ],
                        t: ["[ap]"],
                        tt: ["[ap]m"],
                        T: ["[AP]"],
                        TT: ["[AP]M"],
                        Z: [""],
                        o: [""],
                        S: [""],
                    },
                    formatAlias = { isoDate: "yyyy-mm-dd", isoTime: "HH:MM:ss", isoDateTime: "yyyy-mm-dd'T'HH:MM:ss", isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'" };
                function formatcode(match) {
                    var dynMatches = new RegExp("\\d+$").exec(match[0]);
                    if (dynMatches && void 0 !== dynMatches[0]) {
                        var fcode = formatCode[match[0][0] + "x"].slice("");
                        return (fcode[0] = fcode[0](dynMatches[0])), (fcode[3] = fcode[3](dynMatches[0])), fcode;
                    }
                    if (formatCode[match[0]]) return formatCode[match[0]];
                }
                function getTokenizer(opts) {
                    if (!opts.tokenizer) {
                        var tokens = [],
                            dyntokens = [];
                        for (var ndx in formatCode)
                            if (/\.*x$/.test(ndx)) {
                                var dynToken = ndx[0] + "\\d+";
                                -1 === dyntokens.indexOf(dynToken) && dyntokens.push(dynToken);
                            } else -1 === tokens.indexOf(ndx[0]) && tokens.push(ndx[0]);
                        (opts.tokenizer = "(" + (0 < dyntokens.length ? dyntokens.join("|") + "|" : "") + tokens.join("+|") + ")+?|."), (opts.tokenizer = new RegExp(opts.tokenizer, "g"));
                    }
                    return opts.tokenizer;
                }
                function prefillYear(dateParts, currentResult, opts) {
                    if (dateParts.year !== dateParts.rawyear) {
                        var crrntyear = currentYear.toString(),
                            enteredPart = dateParts.rawyear.replace(/[^0-9]/g, ""),
                            currentYearPart = crrntyear.slice(0, enteredPart.length),
                            currentYearNextPart = crrntyear.slice(enteredPart.length);
                        if (2 === enteredPart.length && enteredPart === currentYearPart) {
                            var entryCurrentYear = new Date(currentYear, dateParts.month - 1, dateParts.day);
                            dateParts.day == entryCurrentYear.getDate() &&
                                (!opts.max || opts.max.date.getTime() >= entryCurrentYear.getTime()) &&
                                (dateParts.date.setFullYear(currentYear),
                                (dateParts.year = crrntyear),
                                (currentResult.insert = [
                                    { pos: currentResult.pos + 1, c: currentYearNextPart[0] },
                                    { pos: currentResult.pos + 2, c: currentYearNextPart[1] },
                                ]));
                        }
                    }
                    return currentResult;
                }
                function isValidDate(dateParts, currentResult, opts) {
                    if (
                        void 0 === dateParts.rawday ||
                        (!isFinite(dateParts.rawday) && new Date(dateParts.date.getFullYear(), isFinite(dateParts.rawmonth) ? dateParts.month : dateParts.date.getMonth() + 1, 0).getDate() >= dateParts.day) ||
                        ("29" == dateParts.day && !Number.isFinite(dateParts.rawyear)) ||
                        new Date(dateParts.date.getFullYear(), isFinite(dateParts.rawmonth) ? dateParts.month : dateParts.date.getMonth() + 1, 0).getDate() >= dateParts.day
                    )
                        return currentResult;
                    if ("29" == dateParts.day) {
                        var tokenMatch = getTokenMatch(currentResult.pos, opts);
                        if ("yyyy" === tokenMatch.targetMatch[0] && currentResult.pos - tokenMatch.targetMatchIndex == 2) return (currentResult.remove = currentResult.pos + 1), currentResult;
                    } else if ("02" == dateParts.month && "30" == dateParts.day && void 0 !== currentResult.c)
                        return (
                            (dateParts.day = "03"),
                            dateParts.date.setDate(3),
                            dateParts.date.setMonth(1),
                            (currentResult.insert = [
                                { pos: currentResult.pos, c: "0" },
                                { pos: currentResult.pos + 1, c: currentResult.c },
                            ]),
                            (currentResult.caret = _positioning.seekNext.call(this, currentResult.pos + 1)),
                            currentResult
                        );
                    return !1;
                }
                function isDateInRange(dateParts, result, opts, maskset, fromCheckval) {
                    if (!result) return result;
                    if (opts.min) {
                        if (dateParts.rawyear) {
                            var rawYear = dateParts.rawyear.replace(/[^0-9]/g, ""),
                                minYear = opts.min.year.substr(0, rawYear.length),
                                maxYear;
                            if (rawYear < minYear) {
                                var tokenMatch = getTokenMatch(result.pos, opts);
                                if (((rawYear = dateParts.rawyear.substr(0, result.pos - tokenMatch.targetMatchIndex + 1).replace(/[^0-9]/g, "0")), (minYear = opts.min.year.substr(0, rawYear.length)), minYear <= rawYear))
                                    return (result.remove = tokenMatch.targetMatchIndex + rawYear.length), result;
                                if (
                                    ((rawYear = "yyyy" === tokenMatch.targetMatch[0] ? dateParts.rawyear.substr(1, 1) : dateParts.rawyear.substr(0, 1)),
                                    (minYear = opts.min.year.substr(2, 1)),
                                    (maxYear = opts.max ? opts.max.year.substr(2, 1) : rawYear),
                                    1 === rawYear.length && minYear <= rawYear && rawYear <= maxYear && !0 !== fromCheckval)
                                )
                                    return (
                                        "yyyy" === tokenMatch.targetMatch[0]
                                            ? ((result.insert = [{ pos: result.pos + 1, c: rawYear, strict: !0 }]), (result.caret = result.pos + 2), (maskset.validPositions[result.pos].input = opts.min.year[1]))
                                            : ((result.insert = [
                                                  { pos: result.pos + 1, c: opts.min.year[1], strict: !0 },
                                                  { pos: result.pos + 2, c: rawYear, strict: !0 },
                                              ]),
                                              (result.caret = result.pos + 3),
                                              (maskset.validPositions[result.pos].input = opts.min.year[0])),
                                        result
                                    );
                                result = !1;
                            }
                        }
                        result && dateParts.year && dateParts.year === dateParts.rawyear && opts.min.date.getTime() == opts.min.date.getTime() && (result = opts.min.date.getTime() <= dateParts.date.getTime());
                    }
                    return result && opts.max && opts.max.date.getTime() == opts.max.date.getTime() && (result = opts.max.date.getTime() >= dateParts.date.getTime()), result;
                }
                function parse(format, dateObjValue, opts, raw) {
                    var mask = "",
                        match,
                        fcode;
                    for (getTokenizer(opts).lastIndex = 0; (match = getTokenizer(opts).exec(format)); )
                        if (void 0 === dateObjValue)
                            if ((fcode = formatcode(match))) mask += "(" + fcode[0] + ")";
                            else
                                switch (match[0]) {
                                    case "[":
                                        mask += "(";
                                        break;
                                    case "]":
                                        mask += ")?";
                                        break;
                                    default:
                                        mask += (0, _escapeRegex.default)(match[0]);
                                }
                        else if ((fcode = formatcode(match)))
                            if (!0 !== raw && fcode[3]) {
                                var getFn = fcode[3];
                                mask += getFn.call(dateObjValue.date);
                            } else fcode[2] ? (mask += dateObjValue["raw" + fcode[2]]) : (mask += match[0]);
                        else mask += match[0];
                    return mask;
                }
                function pad(val, len, right) {
                    for (val = String(val), len = len || 2; val.length < len; ) val = right ? val + "0" : "0" + val;
                    return val;
                }
                function analyseMask(maskString, format, opts) {
                    var dateObj = { date: new Date(1, 0, 1) },
                        targetProp,
                        mask = maskString,
                        match,
                        dateOperation;
                    function setValue(dateObj, value, opts) {
                        if (((dateObj[targetProp] = value.replace(/[^0-9]/g, "0")), (dateObj["raw" + targetProp] = value), void 0 !== dateOperation)) {
                            var datavalue = dateObj[targetProp];
                            (("day" === targetProp && 29 === parseInt(datavalue)) || ("month" === targetProp && 2 === parseInt(datavalue))) &&
                                (29 !== parseInt(dateObj.day) || 2 !== parseInt(dateObj.month) || ("" !== dateObj.year && void 0 !== dateObj.year) || dateObj.date.setFullYear(2012, 1, 29)),
                                "day" === targetProp && 0 === parseInt(datavalue) && (datavalue = 1),
                                "month" === targetProp && ((datavalue = parseInt(datavalue)), 0 < datavalue) && (datavalue -= 1),
                                "year" === targetProp && datavalue.length < 4 && (datavalue = pad(datavalue, 4, !0)),
                                "" !== datavalue && dateOperation.call(dateObj.date, datavalue);
                        }
                    }
                    if ("string" == typeof mask) {
                        for (getTokenizer(opts).lastIndex = 0; (match = getTokenizer(opts).exec(format)); ) {
                            var dynMatches = new RegExp("\\d+$").exec(match[0]),
                                fcode = dynMatches ? match[0][0] + "x" : match[0],
                                value = void 0;
                            if (dynMatches) {
                                var lastIndex = getTokenizer(opts).lastIndex,
                                    tokanMatch = getTokenMatch(match.index, opts);
                                (getTokenizer(opts).lastIndex = lastIndex), (value = mask.slice(0, mask.indexOf(tokanMatch.nextMatch[0])));
                            } else value = mask.slice(0, fcode.length);
                            Object.prototype.hasOwnProperty.call(formatCode, fcode) && ((targetProp = formatCode[fcode][2]), (dateOperation = formatCode[fcode][1]), setValue(dateObj, value, opts)), (mask = mask.slice(value.length));
                        }
                        return dateObj;
                    }
                    if (mask && "object" === _typeof(mask) && Object.prototype.hasOwnProperty.call(mask, "date")) return mask;
                }
                function importDate(dateObj, opts) {
                    return parse(opts.inputFormat, { date: dateObj }, opts);
                }
                function getTokenMatch(pos, opts) {
                    var calcPos = 0,
                        targetMatch,
                        match,
                        matchLength = 0;
                    for (getTokenizer(opts).lastIndex = 0; (match = getTokenizer(opts).exec(opts.inputFormat)); ) {
                        var dynMatches = new RegExp("\\d+$").exec(match[0]);
                        if (((matchLength = dynMatches ? parseInt(dynMatches[0]) : match[0].length), (calcPos += matchLength), pos <= calcPos)) {
                            (targetMatch = match), (match = getTokenizer(opts).exec(opts.inputFormat));
                            break;
                        }
                    }
                    return { targetMatchIndex: calcPos - matchLength, nextMatch: match, targetMatch: targetMatch };
                }
                _inputmask.default.extendAliases({
                    datetime: {
                        mask: function mask(opts) {
                            return (
                                (opts.numericInput = !1),
                                (formatCode.S = opts.i18n.ordinalSuffix.join("|")),
                                (opts.inputFormat = formatAlias[opts.inputFormat] || opts.inputFormat),
                                (opts.displayFormat = formatAlias[opts.displayFormat] || opts.displayFormat || opts.inputFormat),
                                (opts.outputFormat = formatAlias[opts.outputFormat] || opts.outputFormat || opts.inputFormat),
                                (opts.placeholder = "" !== opts.placeholder ? opts.placeholder : opts.inputFormat.replace(/[[\]]/, "")),
                                (opts.regex = parse(opts.inputFormat, void 0, opts)),
                                (opts.min = analyseMask(opts.min, opts.inputFormat, opts)),
                                (opts.max = analyseMask(opts.max, opts.inputFormat, opts)),
                                null
                            );
                        },
                        placeholder: "",
                        inputFormat: "isoDateTime",
                        displayFormat: void 0,
                        outputFormat: void 0,
                        min: null,
                        max: null,
                        skipOptionalPartCharacter: "",
                        i18n: {
                            dayNames: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                            monthNames: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sep",
                                "Oct",
                                "Nov",
                                "Dec",
                                "January",
                                "February",
                                "March",
                                "April",
                                "May",
                                "June",
                                "July",
                                "August",
                                "September",
                                "October",
                                "November",
                                "December",
                            ],
                            ordinalSuffix: ["st", "nd", "rd", "th"],
                        },
                        preValidation: function preValidation(buffer, pos, c, isSelection, opts, maskset, caretPos, strict) {
                            if (strict) return !0;
                            if (isNaN(c) && buffer[pos] !== c) {
                                var tokenMatch = getTokenMatch(pos, opts);
                                if (tokenMatch.nextMatch && tokenMatch.nextMatch[0] === c && 1 < tokenMatch.targetMatch[0].length) {
                                    var validator = formatCode[tokenMatch.targetMatch[0]][0];
                                    if (new RegExp(validator).test("0" + buffer[pos - 1]))
                                        return (buffer[pos] = buffer[pos - 1]), (buffer[pos - 1] = "0"), { fuzzy: !0, buffer: buffer, refreshFromBuffer: { start: pos - 1, end: pos + 1 }, pos: pos + 1 };
                                }
                            }
                            return !0;
                        },
                        postValidation: function postValidation(buffer, pos, c, currentResult, opts, maskset, strict, fromCheckval) {
                            var inputmask = this,
                                tokenMatch,
                                validator;
                            if (strict) return !0;
                            if (
                                !1 === currentResult &&
                                ((tokenMatch = getTokenMatch(pos + 1, opts)),
                                tokenMatch.targetMatch && tokenMatch.targetMatchIndex === pos && 1 < tokenMatch.targetMatch[0].length && void 0 !== formatCode[tokenMatch.targetMatch[0]]
                                    ? (validator = formatCode[tokenMatch.targetMatch[0]][0])
                                    : ((tokenMatch = getTokenMatch(pos + 2, opts)),
                                      tokenMatch.targetMatch &&
                                          tokenMatch.targetMatchIndex === pos + 1 &&
                                          1 < tokenMatch.targetMatch[0].length &&
                                          void 0 !== formatCode[tokenMatch.targetMatch[0]] &&
                                          (validator = formatCode[tokenMatch.targetMatch[0]][0])),
                                void 0 !== validator &&
                                    (void 0 !== maskset.validPositions[pos + 1] && new RegExp(validator).test(c + "0")
                                        ? ((buffer[pos] = c), (buffer[pos + 1] = "0"), (currentResult = { pos: pos + 2, caret: pos }))
                                        : new RegExp(validator).test("0" + c) && ((buffer[pos] = "0"), (buffer[pos + 1] = c), (currentResult = { pos: pos + 2 }))),
                                !1 === currentResult)
                            )
                                return currentResult;
                            if (
                                (currentResult.fuzzy && ((buffer = currentResult.buffer), (pos = currentResult.pos)),
                                (tokenMatch = getTokenMatch(pos, opts)),
                                tokenMatch.targetMatch && tokenMatch.targetMatch[0] && void 0 !== formatCode[tokenMatch.targetMatch[0]])
                            ) {
                                validator = formatCode[tokenMatch.targetMatch[0]][0];
                                var part = buffer.slice(tokenMatch.targetMatchIndex, tokenMatch.targetMatchIndex + tokenMatch.targetMatch[0].length);
                                !1 === new RegExp(validator).test(part.join("")) &&
                                    2 === tokenMatch.targetMatch[0].length &&
                                    maskset.validPositions[tokenMatch.targetMatchIndex] &&
                                    maskset.validPositions[tokenMatch.targetMatchIndex + 1] &&
                                    (maskset.validPositions[tokenMatch.targetMatchIndex + 1].input = "0");
                            }
                            var result = currentResult,
                                dateParts = analyseMask(buffer.join(""), opts.inputFormat, opts);
                            return (
                                result &&
                                    dateParts.date.getTime() == dateParts.date.getTime() &&
                                    (opts.prefillYear && (result = prefillYear(dateParts, result, opts)), (result = isValidDate.call(this, dateParts, result, opts)), (result = isDateInRange(dateParts, result, opts, maskset, fromCheckval))),
                                void 0 !== pos && result && currentResult.pos !== pos
                                    ? { buffer: parse(opts.inputFormat, dateParts, opts).split(""), refreshFromBuffer: { start: pos, end: currentResult.pos }, pos: currentResult.caret || currentResult.pos }
                                    : result
                            );
                        },
                        onKeyDown: function onKeyDown(e, buffer, caretPos, opts) {
                            var input = this;
                            e.ctrlKey && e.keyCode === _keycode.default.RIGHT && (this.inputmask._valueSet(importDate(new Date(), opts)), $(this).trigger("setvalue"));
                        },
                        onUnMask: function onUnMask(maskedValue, unmaskedValue, opts) {
                            return unmaskedValue ? parse(opts.outputFormat, analyseMask(maskedValue, opts.inputFormat, opts), opts, !0) : unmaskedValue;
                        },
                        casing: function casing(elem, test, pos, validPositions) {
                            return 0 == test.nativeDef.indexOf("[ap]") ? elem.toLowerCase() : 0 == test.nativeDef.indexOf("[AP]") ? elem.toUpperCase() : elem;
                        },
                        onBeforeMask: function onBeforeMask(initialValue, opts) {
                            return "[object Date]" === Object.prototype.toString.call(initialValue) && (initialValue = importDate(initialValue, opts)), initialValue;
                        },
                        insertMode: !1,
                        shiftPositions: !1,
                        keepStatic: !1,
                        inputmode: "numeric",
                        prefillYear: !0,
                    },
                });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                var _inputmask = _interopRequireDefault(__webpack_require__(2)),
                    _keycode = _interopRequireDefault(__webpack_require__(0)),
                    _escapeRegex = _interopRequireDefault(__webpack_require__(13));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var $ = _inputmask.default.dependencyLib;
                function autoEscape(txt, opts) {
                    for (var escapedTxt = "", i = 0; i < txt.length; i++)
                        _inputmask.default.prototype.definitions[txt.charAt(i)] ||
                        opts.definitions[txt.charAt(i)] ||
                        opts.optionalmarker[0] === txt.charAt(i) ||
                        opts.optionalmarker[1] === txt.charAt(i) ||
                        opts.quantifiermarker[0] === txt.charAt(i) ||
                        opts.quantifiermarker[1] === txt.charAt(i) ||
                        opts.groupmarker[0] === txt.charAt(i) ||
                        opts.groupmarker[1] === txt.charAt(i) ||
                        opts.alternatormarker === txt.charAt(i)
                            ? (escapedTxt += "\\" + txt.charAt(i))
                            : (escapedTxt += txt.charAt(i));
                    return escapedTxt;
                }
                function alignDigits(buffer, digits, opts, force) {
                    if (0 < buffer.length && 0 < digits && (!opts.digitsOptional || force)) {
                        var radixPosition = buffer.indexOf(opts.radixPoint),
                            negationBack = !1;
                        opts.negationSymbol.back === buffer[buffer.length - 1] && ((negationBack = !0), buffer.length--), -1 === radixPosition && (buffer.push(opts.radixPoint), (radixPosition = buffer.length - 1));
                        for (var i = 1; i <= digits; i++) isFinite(buffer[radixPosition + i]) || (buffer[radixPosition + i] = "0");
                    }
                    return negationBack && buffer.push(opts.negationSymbol.back), buffer;
                }
                function findValidator(symbol, maskset) {
                    var posNdx = 0;
                    if ("+" === symbol) {
                        for (posNdx in maskset.validPositions);
                        posNdx = parseInt(posNdx);
                    }
                    for (var tstNdx in maskset.tests)
                        if (((tstNdx = parseInt(tstNdx)), posNdx <= tstNdx))
                            for (var ndx = 0, ndxl = maskset.tests[tstNdx].length; ndx < ndxl; ndx++)
                                if ((void 0 === maskset.validPositions[tstNdx] || "-" === symbol) && maskset.tests[tstNdx][ndx].match.def === symbol) return tstNdx + (void 0 !== maskset.validPositions[tstNdx] && "-" !== symbol ? 1 : 0);
                    return posNdx;
                }
                function findValid(symbol, maskset) {
                    var ret = -1;
                    for (var ndx in maskset.validPositions) {
                        var tst = maskset.validPositions[ndx];
                        if (tst && tst.match.def === symbol) {
                            ret = parseInt(ndx);
                            break;
                        }
                    }
                    return ret;
                }
                function parseMinMaxOptions(opts) {
                    void 0 === opts.parseMinMaxOptions &&
                        (null !== opts.min &&
                            ((opts.min = opts.min.toString().replace(new RegExp((0, _escapeRegex.default)(opts.groupSeparator), "g"), "")),
                            "," === opts.radixPoint && (opts.min = opts.min.replace(opts.radixPoint, ".")),
                            (opts.min = isFinite(opts.min) ? parseFloat(opts.min) : NaN),
                            isNaN(opts.min) && (opts.min = Number.MIN_VALUE)),
                        null !== opts.max &&
                            ((opts.max = opts.max.toString().replace(new RegExp((0, _escapeRegex.default)(opts.groupSeparator), "g"), "")),
                            "," === opts.radixPoint && (opts.max = opts.max.replace(opts.radixPoint, ".")),
                            (opts.max = isFinite(opts.max) ? parseFloat(opts.max) : NaN),
                            isNaN(opts.max) && (opts.max = Number.MAX_VALUE)),
                        (opts.parseMinMaxOptions = "done"));
                }
                function genMask(opts) {
                    (opts.repeat = 0),
                        opts.groupSeparator === opts.radixPoint &&
                            opts.digits &&
                            "0" !== opts.digits &&
                            ("." === opts.radixPoint ? (opts.groupSeparator = ",") : "," === opts.radixPoint ? (opts.groupSeparator = ".") : (opts.groupSeparator = "")),
                        " " === opts.groupSeparator && (opts.skipOptionalPartCharacter = void 0),
                        1 < opts.placeholder.length && (opts.placeholder = opts.placeholder.charAt(0)),
                        "radixFocus" === opts.positionCaretOnClick && "" === opts.placeholder && (opts.positionCaretOnClick = "lvp");
                    var decimalDef = "0",
                        radixPointDef = opts.radixPoint;
                    !0 === opts.numericInput && void 0 === opts.__financeInput
                        ? ((decimalDef = "1"),
                          (opts.positionCaretOnClick = "radixFocus" === opts.positionCaretOnClick ? "lvp" : opts.positionCaretOnClick),
                          (opts.digitsOptional = !1),
                          isNaN(opts.digits) && (opts.digits = 2),
                          (opts._radixDance = !1),
                          (radixPointDef = "," === opts.radixPoint ? "?" : "!"),
                          "" !== opts.radixPoint &&
                              void 0 === opts.definitions[radixPointDef] &&
                              ((opts.definitions[radixPointDef] = {}),
                              (opts.definitions[radixPointDef].validator = "[" + opts.radixPoint + "]"),
                              (opts.definitions[radixPointDef].placeholder = opts.radixPoint),
                              (opts.definitions[radixPointDef].static = !0),
                              (opts.definitions[radixPointDef].generated = !0)))
                        : ((opts.__financeInput = !1), (opts.numericInput = !0));
                    var mask = "[+]",
                        altMask;
                    if (
                        ((mask += autoEscape(opts.prefix, opts)),
                        "" !== opts.groupSeparator
                            ? (void 0 === opts.definitions[opts.groupSeparator] &&
                                  ((opts.definitions[opts.groupSeparator] = {}),
                                  (opts.definitions[opts.groupSeparator].validator = "[" + opts.groupSeparator + "]"),
                                  (opts.definitions[opts.groupSeparator].placeholder = opts.groupSeparator),
                                  (opts.definitions[opts.groupSeparator].static = !0),
                                  (opts.definitions[opts.groupSeparator].generated = !0)),
                              (mask += opts._mask(opts)))
                            : (mask += "9{+}"),
                        void 0 !== opts.digits && 0 !== opts.digits)
                    ) {
                        var dq = opts.digits.toString().split(",");
                        isFinite(dq[0]) && dq[1] && isFinite(dq[1])
                            ? (mask += radixPointDef + decimalDef + "{" + opts.digits + "}")
                            : (isNaN(opts.digits) || 0 < parseInt(opts.digits)) &&
                              (opts.digitsOptional ? ((altMask = mask + radixPointDef + decimalDef + "{0," + opts.digits + "}"), (opts.keepStatic = !0)) : (mask += radixPointDef + decimalDef + "{" + opts.digits + "}"));
                    }
                    return (mask += autoEscape(opts.suffix, opts)), (mask += "[-]"), altMask && (mask = [altMask + autoEscape(opts.suffix, opts) + "[-]", mask]), (opts.greedy = !1), parseMinMaxOptions(opts), mask;
                }
                function hanndleRadixDance(pos, c, radixPos, maskset, opts) {
                    return (
                        opts._radixDance &&
                            opts.numericInput &&
                            c !== opts.negationSymbol.back &&
                            pos <= radixPos &&
                            (0 < radixPos || c == opts.radixPoint) &&
                            (void 0 === maskset.validPositions[pos - 1] || maskset.validPositions[pos - 1].input !== opts.negationSymbol.back) &&
                            (pos -= 1),
                        pos
                    );
                }
                function decimalValidator(chrs, maskset, pos, strict, opts) {
                    var radixPos = maskset.buffer ? maskset.buffer.indexOf(opts.radixPoint) : -1,
                        result = -1 !== radixPos && new RegExp("[0-9\uff11-\uff19]").test(chrs);
                    return opts._radixDance && result && null == maskset.validPositions[radixPos] ? { insert: { pos: radixPos === pos ? radixPos + 1 : radixPos, c: opts.radixPoint }, pos: pos } : result;
                }
                function checkForLeadingZeroes(buffer, opts) {
                    var numberMatches = new RegExp(
                            "(^" +
                                ("" !== opts.negationSymbol.front ? (0, _escapeRegex.default)(opts.negationSymbol.front) + "?" : "") +
                                (0, _escapeRegex.default)(opts.prefix) +
                                ")(.*)(" +
                                (0, _escapeRegex.default)(opts.suffix) +
                                ("" != opts.negationSymbol.back ? (0, _escapeRegex.default)(opts.negationSymbol.back) + "?" : "") +
                                "$)"
                        ).exec(buffer.slice().reverse().join("")),
                        number = numberMatches ? numberMatches[2] : "",
                        leadingzeroes = !1;
                    return (
                        number && ((number = number.split(opts.radixPoint.charAt(0))[0]), (leadingzeroes = new RegExp("^[0" + opts.groupSeparator + "]*").exec(number))),
                        !(!leadingzeroes || !(1 < leadingzeroes[0].length || (0 < leadingzeroes[0].length && leadingzeroes[0].length < number.length))) && leadingzeroes
                    );
                }
                _inputmask.default.extendAliases({
                    numeric: {
                        mask: genMask,
                        _mask: function _mask(opts) {
                            return "(" + opts.groupSeparator + "999){+|1}";
                        },
                        digits: "*",
                        digitsOptional: !0,
                        enforceDigitsOnBlur: !1,
                        radixPoint: ".",
                        positionCaretOnClick: "radixFocus",
                        _radixDance: !0,
                        groupSeparator: "",
                        allowMinus: !0,
                        negationSymbol: { front: "-", back: "" },
                        prefix: "",
                        suffix: "",
                        min: null,
                        max: null,
                        SetMaxOnOverflow: !1,
                        step: 1,
                        inputType: "text",
                        unmaskAsNumber: !1,
                        roundingFN: Math.round,
                        inputmode: "decimal",
                        shortcuts: { k: "000", m: "000000" },
                        placeholder: "0",
                        greedy: !1,
                        rightAlign: !0,
                        insertMode: !0,
                        autoUnmask: !1,
                        skipOptionalPartCharacter: "",
                        definitions: {
                            0: { validator: decimalValidator },
                            1: { validator: decimalValidator, definitionSymbol: "9" },
                            "+": {
                                validator: function validator(chrs, maskset, pos, strict, opts) {
                                    return opts.allowMinus && ("-" === chrs || chrs === opts.negationSymbol.front);
                                },
                            },
                            "-": {
                                validator: function validator(chrs, maskset, pos, strict, opts) {
                                    return opts.allowMinus && chrs === opts.negationSymbol.back;
                                },
                            },
                        },
                        preValidation: function preValidation(buffer, pos, c, isSelection, opts, maskset, caretPos, strict) {
                            if (!1 !== opts.__financeInput && c === opts.radixPoint) return !1;
                            var pattern;
                            if ((pattern = opts.shortcuts && opts.shortcuts[c])) {
                                if (1 < pattern.length) for (var inserts = [], i = 0; i < pattern.length; i++) inserts.push({ pos: pos + i, c: pattern[i], strict: !1 });
                                return { insert: inserts };
                            }
                            var radixPos = buffer.indexOf(opts.radixPoint),
                                initPos = pos;
                            if (((pos = hanndleRadixDance(pos, c, radixPos, maskset, opts)), "-" === c || c === opts.negationSymbol.front)) {
                                if (!0 !== opts.allowMinus) return !1;
                                var isNegative = !1,
                                    front = findValid("+", maskset),
                                    back = findValid("-", maskset);
                                return (
                                    -1 !== front && (isNegative = [front, back]),
                                    !1 !== isNegative
                                        ? { remove: isNegative, caret: initPos - opts.negationSymbol.front.length }
                                        : {
                                              insert: [
                                                  { pos: findValidator("+", maskset), c: opts.negationSymbol.front, fromIsValid: !0 },
                                                  { pos: findValidator("-", maskset), c: opts.negationSymbol.back, fromIsValid: void 0 },
                                              ],
                                              caret: initPos + opts.negationSymbol.back.length,
                                          }
                                );
                            }
                            if (c === opts.groupSeparator) return { caret: initPos };
                            if (strict) return !0;
                            if (-1 !== radixPos && !0 === opts._radixDance && !1 === isSelection && c === opts.radixPoint && void 0 !== opts.digits && (isNaN(opts.digits) || 0 < parseInt(opts.digits)) && radixPos !== pos)
                                return { caret: opts._radixDance && pos === radixPos - 1 ? radixPos + 1 : radixPos };
                            if (!1 === opts.__financeInput)
                                if (isSelection) {
                                    if (opts.digitsOptional) return { rewritePosition: caretPos.end };
                                    if (!opts.digitsOptional) {
                                        if (caretPos.begin > radixPos && caretPos.end <= radixPos)
                                            return c === opts.radixPoint ? { insert: { pos: radixPos + 1, c: "0", fromIsValid: !0 }, rewritePosition: radixPos } : { rewritePosition: radixPos + 1 };
                                        if (caretPos.begin < radixPos) return { rewritePosition: caretPos.begin - 1 };
                                    }
                                } else if (!opts.showMaskOnHover && !opts.showMaskOnFocus && !opts.digitsOptional && 0 < opts.digits && "" === this.__valueGet.call(this.el)) return { rewritePosition: radixPos };
                            return { rewritePosition: pos };
                        },
                        postValidation: function postValidation(buffer, pos, c, currentResult, opts, maskset, strict) {
                            if (!1 === currentResult) return currentResult;
                            if (strict) return !0;
                            if (null !== opts.min || null !== opts.max) {
                                var unmasked = opts.onUnMask(buffer.slice().reverse().join(""), void 0, $.extend({}, opts, { unmaskAsNumber: !0 }));
                                if (null !== opts.min && unmasked < opts.min && (unmasked.toString().length > opts.min.toString().length || unmasked < 0)) return !1;
                                if (null !== opts.max && unmasked > opts.max)
                                    return !!opts.SetMaxOnOverflow && { refreshFromBuffer: !0, buffer: alignDigits(opts.max.toString().replace(".", opts.radixPoint).split(""), opts.digits, opts).reverse() };
                            }
                            return currentResult;
                        },
                        onUnMask: function onUnMask(maskedValue, unmaskedValue, opts) {
                            if ("" === unmaskedValue && !0 === opts.nullable) return unmaskedValue;
                            var processValue = maskedValue.replace(opts.prefix, "");
                            return (
                                (processValue = processValue.replace(opts.suffix, "")),
                                (processValue = processValue.replace(new RegExp((0, _escapeRegex.default)(opts.groupSeparator), "g"), "")),
                                "" !== opts.placeholder.charAt(0) && (processValue = processValue.replace(new RegExp(opts.placeholder.charAt(0), "g"), "0")),
                                opts.unmaskAsNumber
                                    ? ("" !== opts.radixPoint && -1 !== processValue.indexOf(opts.radixPoint) && (processValue = processValue.replace(_escapeRegex.default.call(this, opts.radixPoint), ".")),
                                      (processValue = processValue.replace(new RegExp("^" + (0, _escapeRegex.default)(opts.negationSymbol.front)), "-")),
                                      (processValue = processValue.replace(new RegExp((0, _escapeRegex.default)(opts.negationSymbol.back) + "$"), "")),
                                      Number(processValue))
                                    : processValue
                            );
                        },
                        isComplete: function isComplete(buffer, opts) {
                            var maskedValue = (opts.numericInput ? buffer.slice().reverse() : buffer).join("");
                            return (
                                (maskedValue = maskedValue.replace(new RegExp("^" + (0, _escapeRegex.default)(opts.negationSymbol.front)), "-")),
                                (maskedValue = maskedValue.replace(new RegExp((0, _escapeRegex.default)(opts.negationSymbol.back) + "$"), "")),
                                (maskedValue = maskedValue.replace(opts.prefix, "")),
                                (maskedValue = maskedValue.replace(opts.suffix, "")),
                                (maskedValue = maskedValue.replace(new RegExp((0, _escapeRegex.default)(opts.groupSeparator) + "([0-9]{3})", "g"), "$1")),
                                "," === opts.radixPoint && (maskedValue = maskedValue.replace((0, _escapeRegex.default)(opts.radixPoint), ".")),
                                isFinite(maskedValue)
                            );
                        },
                        onBeforeMask: function onBeforeMask(initialValue, opts) {
                            var radixPoint = opts.radixPoint || ",";
                            isFinite(opts.digits) && (opts.digits = parseInt(opts.digits)),
                                ("number" != typeof initialValue && "number" !== opts.inputType) || "" === radixPoint || (initialValue = initialValue.toString().replace(".", radixPoint));
                            var isNagtive = "-" === initialValue.charAt(0) || initialValue.charAt(0) === opts.negationSymbol.front,
                                valueParts = initialValue.split(radixPoint),
                                integerPart = valueParts[0].replace(/[^\-0-9]/g, ""),
                                decimalPart = 1 < valueParts.length ? valueParts[1].replace(/[^0-9]/g, "") : "",
                                forceDigits = 1 < valueParts.length;
                            initialValue = integerPart + ("" !== decimalPart ? radixPoint + decimalPart : decimalPart);
                            var digits = 0;
                            if ("" !== radixPoint && ((digits = opts.digitsOptional ? (opts.digits < decimalPart.length ? opts.digits : decimalPart.length) : opts.digits), "" !== decimalPart || !opts.digitsOptional)) {
                                var digitsFactor = Math.pow(10, digits || 1);
                                (initialValue = initialValue.replace((0, _escapeRegex.default)(radixPoint), ".")),
                                    isNaN(parseFloat(initialValue)) || (initialValue = (opts.roundingFN(parseFloat(initialValue) * digitsFactor) / digitsFactor).toFixed(digits)),
                                    (initialValue = initialValue.toString().replace(".", radixPoint));
                            }
                            if ((0 === opts.digits && -1 !== initialValue.indexOf(radixPoint) && (initialValue = initialValue.substring(0, initialValue.indexOf(radixPoint))), null !== opts.min || null !== opts.max)) {
                                var numberValue = initialValue.toString().replace(radixPoint, ".");
                                null !== opts.min && numberValue < opts.min
                                    ? (initialValue = opts.min.toString().replace(".", radixPoint))
                                    : null !== opts.max && numberValue > opts.max && (initialValue = opts.max.toString().replace(".", radixPoint));
                            }
                            return isNagtive && "-" !== initialValue.charAt(0) && (initialValue = "-" + initialValue), alignDigits(initialValue.toString().split(""), digits, opts, forceDigits).join("");
                        },
                        onBeforeWrite: function onBeforeWrite(e, buffer, caretPos, opts) {
                            function stripBuffer(buffer, stripRadix) {
                                if (!1 !== opts.__financeInput || stripRadix) {
                                    var position = buffer.indexOf(opts.radixPoint);
                                    -1 !== position && buffer.splice(position, 1);
                                }
                                if ("" !== opts.groupSeparator) for (; -1 !== (position = buffer.indexOf(opts.groupSeparator)); ) buffer.splice(position, 1);
                                return buffer;
                            }
                            var result,
                                leadingzeroes = checkForLeadingZeroes(buffer, opts);
                            if (leadingzeroes)
                                for (
                                    var caretNdx = buffer.join("").lastIndexOf(leadingzeroes[0].split("").reverse().join("")) - (leadingzeroes[0] == leadingzeroes.input ? 0 : 1),
                                        offset = leadingzeroes[0] == leadingzeroes.input ? 1 : 0,
                                        i = leadingzeroes[0].length - offset;
                                    0 < i;
                                    i--
                                )
                                    delete this.maskset.validPositions[caretNdx + i], delete buffer[caretNdx + i];
                            if (e)
                                switch (e.type) {
                                    case "blur":
                                    case "checkval":
                                        if (null !== opts.min) {
                                            var unmasked = opts.onUnMask(buffer.slice().reverse().join(""), void 0, $.extend({}, opts, { unmaskAsNumber: !0 }));
                                            if (null !== opts.min && unmasked < opts.min) return { refreshFromBuffer: !0, buffer: alignDigits(opts.min.toString().replace(".", opts.radixPoint).split(""), opts.digits, opts).reverse() };
                                        }
                                        if (buffer[buffer.length - 1] === opts.negationSymbol.front) {
                                            var nmbrMtchs = new RegExp(
                                                    "(^" +
                                                        ("" != opts.negationSymbol.front ? (0, _escapeRegex.default)(opts.negationSymbol.front) + "?" : "") +
                                                        (0, _escapeRegex.default)(opts.prefix) +
                                                        ")(.*)(" +
                                                        (0, _escapeRegex.default)(opts.suffix) +
                                                        ("" != opts.negationSymbol.back ? (0, _escapeRegex.default)(opts.negationSymbol.back) + "?" : "") +
                                                        "$)"
                                                ).exec(stripBuffer(buffer.slice(), !0).reverse().join("")),
                                                number = nmbrMtchs ? nmbrMtchs[2] : "";
                                            0 == number && (result = { refreshFromBuffer: !0, buffer: [0] });
                                        } else
                                            "" !== opts.radixPoint && buffer[0] === opts.radixPoint && (result && result.buffer ? result.buffer.shift() : (buffer.shift(), (result = { refreshFromBuffer: !0, buffer: stripBuffer(buffer) })));
                                        if (opts.enforceDigitsOnBlur) {
                                            result = result || {};
                                            var bffr = (result && result.buffer) || buffer.slice().reverse();
                                            (result.refreshFromBuffer = !0), (result.buffer = alignDigits(bffr, opts.digits, opts, !0).reverse());
                                        }
                                }
                            return result;
                        },
                        onKeyDown: function onKeyDown(e, buffer, caretPos, opts) {
                            var $input = $(this),
                                bffr;
                            if (e.ctrlKey)
                                switch (e.keyCode) {
                                    case _keycode.default.UP:
                                        return this.inputmask.__valueSet.call(this, parseFloat(this.inputmask.unmaskedvalue()) + parseInt(opts.step)), $input.trigger("setvalue"), !1;
                                    case _keycode.default.DOWN:
                                        return this.inputmask.__valueSet.call(this, parseFloat(this.inputmask.unmaskedvalue()) - parseInt(opts.step)), $input.trigger("setvalue"), !1;
                                }
                            if (!e.shiftKey && (e.keyCode === _keycode.default.DELETE || e.keyCode === _keycode.default.BACKSPACE || e.keyCode === _keycode.default.BACKSPACE_SAFARI) && caretPos.begin !== buffer.length) {
                                if (buffer[e.keyCode === _keycode.default.DELETE ? caretPos.begin - 1 : caretPos.end] === opts.negationSymbol.front)
                                    return (bffr = buffer.slice().reverse()), "" !== opts.negationSymbol.front && bffr.shift(), "" !== opts.negationSymbol.back && bffr.pop(), $input.trigger("setvalue", [bffr.join(""), caretPos.begin]), !1;
                                if (!0 === opts._radixDance) {
                                    var radixPos = buffer.indexOf(opts.radixPoint);
                                    if (opts.digitsOptional) {
                                        if (0 === radixPos) return (bffr = buffer.slice().reverse()), bffr.pop(), $input.trigger("setvalue", [bffr.join(""), caretPos.begin >= bffr.length ? bffr.length : caretPos.begin]), !1;
                                    } else if (-1 !== radixPos && (caretPos.begin < radixPos || caretPos.end < radixPos || (e.keyCode === _keycode.default.DELETE && caretPos.begin === radixPos)))
                                        return (
                                            caretPos.begin !== caretPos.end || (e.keyCode !== _keycode.default.BACKSPACE && e.keyCode !== _keycode.default.BACKSPACE_SAFARI) || caretPos.begin++,
                                            (bffr = buffer.slice().reverse()),
                                            bffr.splice(bffr.length - caretPos.begin, caretPos.begin - caretPos.end + 1),
                                            (bffr = alignDigits(bffr, opts.digits, opts).join("")),
                                            $input.trigger("setvalue", [bffr, caretPos.begin >= bffr.length ? radixPos + 1 : caretPos.begin]),
                                            !1
                                        );
                                }
                            }
                        },
                    },
                    currency: { prefix: "", groupSeparator: ",", alias: "numeric", digits: 2, digitsOptional: !1 },
                    decimal: { alias: "numeric" },
                    integer: { alias: "numeric", inputmode: "numeric", digits: 0 },
                    percentage: { alias: "numeric", min: 0, max: 100, suffix: " %", digits: 0, allowMinus: !1 },
                    indianns: {
                        alias: "numeric",
                        _mask: function _mask(opts) {
                            return "(" + opts.groupSeparator + "99){*|1}(" + opts.groupSeparator + "999){1|1}";
                        },
                        groupSeparator: ",",
                        radixPoint: ".",
                        placeholder: "0",
                        digits: 2,
                        digitsOptional: !1,
                    },
                });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                var _window = _interopRequireDefault(__webpack_require__(8)),
                    _inputmask = _interopRequireDefault(__webpack_require__(2)),
                    _canUseDOM = _interopRequireDefault(__webpack_require__(9));
                function _typeof(obj) {
                    return (
                        (_typeof =
                            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                                ? function _typeof(obj) {
                                      return typeof obj;
                                  }
                                : function _typeof(obj) {
                                      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                                  }),
                        _typeof(obj)
                    );
                }
                function _classCallCheck(instance, Constructor) {
                    if (!(instance instanceof Constructor)) throw new TypeError("Cannot call a class as a function");
                }
                function _inherits(subClass, superClass) {
                    if ("function" != typeof superClass && null !== superClass) throw new TypeError("Super expression must either be null or a function");
                    (subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: !0, configurable: !0 } })), superClass && _setPrototypeOf(subClass, superClass);
                }
                function _createSuper(Derived) {
                    var hasNativeReflectConstruct = _isNativeReflectConstruct();
                    return function _createSuperInternal() {
                        var Super = _getPrototypeOf(Derived),
                            result;
                        if (hasNativeReflectConstruct) {
                            var NewTarget = _getPrototypeOf(this).constructor;
                            result = Reflect.construct(Super, arguments, NewTarget);
                        } else result = Super.apply(this, arguments);
                        return _possibleConstructorReturn(this, result);
                    };
                }
                function _possibleConstructorReturn(self, call) {
                    return !call || ("object" !== _typeof(call) && "function" != typeof call) ? _assertThisInitialized(self) : call;
                }
                function _assertThisInitialized(self) {
                    if (void 0 === self) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                    return self;
                }
                function _wrapNativeSuper(Class) {
                    var _cache = "function" == typeof Map ? new Map() : void 0;
                    return (
                        (_wrapNativeSuper = function _wrapNativeSuper(Class) {
                            if (null === Class || !_isNativeFunction(Class)) return Class;
                            if ("function" != typeof Class) throw new TypeError("Super expression must either be null or a function");
                            if ("undefined" != typeof _cache) {
                                if (_cache.has(Class)) return _cache.get(Class);
                                _cache.set(Class, Wrapper);
                            }
                            function Wrapper() {
                                return _construct(Class, arguments, _getPrototypeOf(this).constructor);
                            }
                            return (Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: !1, writable: !0, configurable: !0 } })), _setPrototypeOf(Wrapper, Class);
                        }),
                        _wrapNativeSuper(Class)
                    );
                }
                function _construct(Parent, args, Class) {
                    return (
                        (_construct = _isNativeReflectConstruct()
                            ? Reflect.construct
                            : function _construct(Parent, args, Class) {
                                  var a = [null];
                                  a.push.apply(a, args);
                                  var Constructor = Function.bind.apply(Parent, a),
                                      instance = new Constructor();
                                  return Class && _setPrototypeOf(instance, Class.prototype), instance;
                              }),
                        _construct.apply(null, arguments)
                    );
                }
                function _isNativeReflectConstruct() {
                    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                    if (Reflect.construct.sham) return !1;
                    if ("function" == typeof Proxy) return !0;
                    try {
                        return Date.prototype.toString.call(Reflect.construct(Date, [], function () {})), !0;
                    } catch (e) {
                        return !1;
                    }
                }
                function _isNativeFunction(fn) {
                    return -1 !== Function.toString.call(fn).indexOf("[native code]");
                }
                function _setPrototypeOf(o, p) {
                    return (
                        (_setPrototypeOf =
                            Object.setPrototypeOf ||
                            function _setPrototypeOf(o, p) {
                                return (o.__proto__ = p), o;
                            }),
                        _setPrototypeOf(o, p)
                    );
                }
                function _getPrototypeOf(o) {
                    return (
                        (_getPrototypeOf = Object.setPrototypeOf
                            ? Object.getPrototypeOf
                            : function _getPrototypeOf(o) {
                                  return o.__proto__ || Object.getPrototypeOf(o);
                              }),
                        _getPrototypeOf(o)
                    );
                }
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                var document = _window.default.document;
                if (_canUseDOM.default && document && document.head && document.head.attachShadow && _window.default.customElements && void 0 === _window.default.customElements.get("input-mask")) {
                    var InputmaskElement = (function (_HTMLElement) {
                        _inherits(InputmaskElement, _HTMLElement);
                        var _super = _createSuper(InputmaskElement);
                        function InputmaskElement() {
                            var _this;
                            _classCallCheck(this, InputmaskElement), (_this = _super.call(this));
                            var attributeNames = _this.getAttributeNames(),
                                shadow = _this.attachShadow({ mode: "closed" }),
                                input = document.createElement("input");
                            for (var attr in ((input.type = "text"), shadow.appendChild(input), attributeNames))
                                Object.prototype.hasOwnProperty.call(attributeNames, attr) && input.setAttribute(attributeNames[attr], _this.getAttribute(attributeNames[attr]));
                            var im = new _inputmask.default();
                            return (im.dataAttribute = ""), im.mask(input), (input.inputmask.shadowRoot = shadow), _this;
                        }
                        return InputmaskElement;
                    })(_wrapNativeSuper(HTMLElement));
                    _window.default.customElements.define("input-mask", InputmaskElement);
                }
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                var _jquery = _interopRequireDefault(__webpack_require__(10)),
                    _inputmask = _interopRequireDefault(__webpack_require__(2));
                function _typeof(obj) {
                    return (
                        (_typeof =
                            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                                ? function _typeof(obj) {
                                      return typeof obj;
                                  }
                                : function _typeof(obj) {
                                      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                                  }),
                        _typeof(obj)
                    );
                }
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                void 0 === _jquery.default.fn.inputmask &&
                    (_jquery.default.fn.inputmask = function (fn, options) {
                        var nptmask,
                            input = this[0];
                        if ((void 0 === options && (options = {}), "string" == typeof fn))
                            switch (fn) {
                                case "unmaskedvalue":
                                    return input && input.inputmask ? input.inputmask.unmaskedvalue() : (0, _jquery.default)(input).val();
                                case "remove":
                                    return this.each(function () {
                                        this.inputmask && this.inputmask.remove();
                                    });
                                case "getemptymask":
                                    return input && input.inputmask ? input.inputmask.getemptymask() : "";
                                case "hasMaskedValue":
                                    return !(!input || !input.inputmask) && input.inputmask.hasMaskedValue();
                                case "isComplete":
                                    return !input || !input.inputmask || input.inputmask.isComplete();
                                case "getmetadata":
                                    return input && input.inputmask ? input.inputmask.getmetadata() : void 0;
                                case "setvalue":
                                    _inputmask.default.setValue(input, options);
                                    break;
                                case "option":
                                    if ("string" != typeof options)
                                        return this.each(function () {
                                            if (void 0 !== this.inputmask) return this.inputmask.option(options);
                                        });
                                    if (input && void 0 !== input.inputmask) return input.inputmask.option(options);
                                    break;
                                default:
                                    return (
                                        (options.alias = fn),
                                        (nptmask = new _inputmask.default(options)),
                                        this.each(function () {
                                            nptmask.mask(this);
                                        })
                                    );
                            }
                        else {
                            if (Array.isArray(fn))
                                return (
                                    (options.alias = fn),
                                    (nptmask = new _inputmask.default(options)),
                                    this.each(function () {
                                        nptmask.mask(this);
                                    })
                                );
                            if ("object" == _typeof(fn))
                                return (
                                    (nptmask = new _inputmask.default(fn)),
                                    void 0 === fn.mask && void 0 === fn.alias
                                        ? this.each(function () {
                                              if (void 0 !== this.inputmask) return this.inputmask.option(fn);
                                              nptmask.mask(this);
                                          })
                                        : this.each(function () {
                                              nptmask.mask(this);
                                          })
                                );
                            if (void 0 === fn)
                                return this.each(function () {
                                    (nptmask = new _inputmask.default(options)), nptmask.mask(this);
                                });
                        }
                    });
            },
            function (module, exports, __webpack_require__) {
                "use strict";
                Object.defineProperty(exports, "__esModule", { value: !0 }), (exports.default = void 0);
                var _bundle = _interopRequireDefault(__webpack_require__(14));
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : { default: obj };
                }
                __webpack_require__(26);
                var _default = _bundle.default;
                exports.default = _default;
            },
        ]),
        (installedModules = {}),
        (__webpack_require__.m = modules),
        (__webpack_require__.c = installedModules),
        (__webpack_require__.d = function (exports, name, getter) {
            __webpack_require__.o(exports, name) || Object.defineProperty(exports, name, { enumerable: !0, get: getter });
        }),
        (__webpack_require__.r = function (exports) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(exports, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(exports, "__esModule", { value: !0 });
        }),
        (__webpack_require__.t = function (value, mode) {
            if ((1 & mode && (value = __webpack_require__(value)), 8 & mode)) return value;
            if (4 & mode && "object" == typeof value && value && value.__esModule) return value;
            var ns = Object.create(null);
            if ((__webpack_require__.r(ns), Object.defineProperty(ns, "default", { enumerable: !0, value: value }), 2 & mode && "string" != typeof value))
                for (var key in value)
                    __webpack_require__.d(
                        ns,
                        key,
                        function (key) {
                            return value[key];
                        }.bind(null, key)
                    );
            return ns;
        }),
        (__webpack_require__.n = function (module) {
            var getter =
                module && module.__esModule
                    ? function getDefault() {
                          return module.default;
                      }
                    : function getModuleExports() {
                          return module;
                      };
            return __webpack_require__.d(getter, "a", getter), getter;
        }),
        (__webpack_require__.o = function (object, property) {
            return Object.prototype.hasOwnProperty.call(object, property);
        }),
        (__webpack_require__.p = ""),
        __webpack_require__((__webpack_require__.s = 27))
    );
    function __webpack_require__(moduleId) {
        if (installedModules[moduleId]) return installedModules[moduleId].exports;
        var module = (installedModules[moduleId] = { i: moduleId, l: !1, exports: {} });
        return modules[moduleId].call(module.exports, module, module.exports, __webpack_require__), (module.l = !0), module.exports;
    }
    var modules, installedModules;
});
