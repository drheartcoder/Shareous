! function() {
    "use strict";
    ! function() {
        var c = Math.PI;

        function a(t) {
            throw new Error(t)
        }
        var tt = Array.prototype,
            i = tt.slice,
            o = function() {},
            r = o,
            t = {},
            e = Date.now || function() {
                return (new Date).getTime()
            },
            n = navigator.userAgent;

        function s(t) {
            return t.test(n)
        }
        window.addEventListener;
        var u = s(/iPhone/),
            l = u || s(/iPad/),
            f = s(/Android/),
            d = (s(/Windows Phone/), s(/Android [2-4]/)),
            h = s(/; wv\) |Gecko\) Version\/[^ ]+ Chrome|Windows Phone|Opera Mini|UCBrowser|FBAN|CriOS/) || l || d && !s(/Chrome/),
            p = (s(/(Windows Phone|\(iP.+UCBrowser\/)/), s(/iPhone|Android 2\./), s(/Windows Phone/), n.match(/Chrome\/(\d+)/));
        p && (p = parseInt(p[1], 10));
        s(/iPhone OS 7/), f && (p || s(/firefox/));

        function m(t) {
            return "boolean" == typeof t
        }

        function v(t) {
            return "number" == typeof t
        }

        function g(t) {
            return "function" == typeof t
        }

        function y(t) {
            return "string" == typeof t
        }

        function b(t) {
            return t && "object" == typeof t
        }

        function w(t, e, n) {
            var r;
            if (arguments.length < 3 && (n = this), t)
                if (void 0 !== t.length)
                    for (r = 0; r < t.length; r++) e.call(n, r, t[r]);
                else
                    for (r in t) t.hasOwnProperty(r) && e.call(n, r, t[r])
        }

        function k(t, e, n) {
            y(t) && (t = e[t]);
            var r = arguments;
            return 3 <= r.length ? function() {
                t.apply(e, i.call(r, 2))
            } : function() {
                return t.apply(e, arguments)
            }
        }

        function _(t, e, n, r) {
            if (v(r)) return setTimeout(function() {
                _(t, e, n)
            }, r);
            if (y(t) && (t = e && e[t]), g(t)) {
                e || (e = this);
                try {
                    return 3 <= arguments.length ? t.call(e, n) : t.call(e)
                } catch (t) {
                    o("invoke", t)
                }
            }
        }
        var x = k(JSON.stringify, JSON),
            M = k(document.querySelector, document),
            N = k(document.querySelectorAll, document);
        k(document.getElementById, document);

        function S(t, e, n, r) {
            if (!y(r)) {
                if ("get" === n) return r || (r = window), void(r.location = t);
                r && (r = r.name)
            }
            var i = document.createElement("form");
            i.setAttribute("action", t), n && i.setAttribute("method", n), r && i.setAttribute("target", r), e && (i.innerHTML = C(e)), O.appendChild(i), i.submit(), O.removeChild(i)
        }

        function C(t, n) {
            if (b(t)) {
                var r = "";
                return w(t, function(t, e) {
                    n && (t = n + "[" + t + "]"), r += C(e, t)
                }), r
            }
            return '<input type="hidden" name="' + n + '" value="' + t + '">'
        }
        var T, R = function(t) {
            return y(t) ? R(document.querySelector(t)) : this instanceof R ? void(this[0] = t) : new R(t)
        };

        function D(t) {
            ! function(s) {
                if (!window.requestAnimationFrame) return scrollBy(0, s);
                T && clearTimeout(T);
                T = setTimeout(function() {
                    var r = pageYOffset,
                        i = Math.min(r + s, R(L).height() - innerHeight);
                    s = i - r;
                    var o = 0,
                        a = performance.now();
                    requestAnimationFrame(function t(e) {
                        o += (e - a) / 300;
                        if (1 <= o) return scrollTo(0, i);
                        var n = Math.sin(c * o / 2);
                        scrollTo(0, r + Math.round(s * n));
                        a = e;
                        requestAnimationFrame(t)
                    })
                }, 100)
            }(t - pageYOffset)
        }
        R.prototype = {
            on: function(t, e, n, r) {
                var i, o = this[0];
                if (o && (y(e) && (e = r[e]), g(e))) {
                    var a = window.addEventListener;
                    return i = a ? function(t) {
                        return 3 === t.target.nodeType && (t.target = t.target.parentNode), e.call(r || this, t)
                    } : function(t) {
                        return t || (t = window.event), t.target || (t.target = t.srcElement || document), t.preventDefault || (t.preventDefault = function() {
                            this.returnValue = !1
                        }), t.stopPropagation || (t.stopPropagation = t.preventDefault), t.currentTarget || (t.currentTarget = o), e.call(r || o, t)
                    }, w(t.split(" "), function(t, e) {
                        a ? o.addEventListener(e, i, !!n) : o.attachEvent("on" + e, i)
                    }), k(function() {
                        this.off(t, i, n)
                    }, this)
                }
            },
            off: function(t, e, n) {
                window.removeEventListener ? this[0].removeEventListener(t, e, !!n) : window.detachEvent && this[0].detachEvent("on" + t, e)
            },
            prop: function(t, e) {
                var n = this[0];
                return 1 === arguments.length ? n && n[t] : n ? (n && (n[t] = e), this) : ""
            },
            attr: function(t, e) {
                if (b(t)) return w(t, function(t, e) {
                    this.attr(t, e)
                }, this), this;
                var n = arguments.length,
                    r = this[0];
                return 1 === n ? r && r.getAttribute(t) : (r && (e ? r.setAttribute(t, e) : r.removeAttribute(t)), this)
            },
            reflow: function() {
                return this.prop("offsetHeight"), this
            },
            remove: function() {
                try {
                    var t = this[0];
                    t.parentNode.removeChild(t)
                } catch (t) {}
                return this
            },
            append: function(t) {
                this[0].appendChild(t)
            },
            hasClass: function(t) {
                return 0 <= (" " + this[0].className + " ").indexOf(" " + t + " ")
            },
            addClass: function(t) {
                var e = this[0];
                return t && e && (e.className ? this.hasClass(t) || (e.className += " " + t) : e.className = t), this
            },
            removeClass: function(t) {
                var e = this[0];
                if (e) {
                    var n = (" " + e.className + " ").replace(" " + t + " ", " ").replace(/^ | $/g, "");
                    e.className !== n && (e.className = n)
                }
                return this
            },
            toggleClass: function(t, e) {
                return 1 === arguments.length && (e = !this.hasClass(t)), this[(e ? "add" : "remove") + "Class"](t)
            },
            qs: function(t) {
                var e = this[0];
                if (e) return e.querySelector(t)
            },
            find: function(t) {
                var e = this[0];
                if (e) return e.querySelectorAll(t)
            },
            $: function(t) {
                return R(this.qs(t))
            },
            $0: function() {
                return R(this.firstElementChild)
            },
            css: function(t, e) {
                var n = this.prop("style");
                if (n)
                    if (1 === arguments.length) {
                        if (!b(t)) return n[t];
                        w(t, function(t, e) {
                            this.css(t, e)
                        }, this)
                    } else try {
                        n[t] = e
                    } catch (t) {}
                return this
            },
            bbox: function() {
                return this[0] ? this[0].getBoundingClientRect() : t
            },
            offht: function() {
                return this.prop("offsetHeight")
            },
            height: function(t) {
                return v(t) && (t = t.toFixed(2) + "px"), y(t) ? this.css("height", t) : this[0] ? this.bbox().height : void 0
            },
            hide: function() {
                return this.css("display", "none")
            },
            toggle: function(t) {
                _(t ? "show" : "hide", this)
            },
            show: function() {
                return this.css("display", "block")
            },
            parent: function() {
                return R(this.prop("parentNode"))
            },
            val: function(t) {
                return arguments.length ? (this[0].value = t, this) : this[0].value
            },
            html: function(t) {
                return arguments.length ? (this[0] && (this[0].innerHTML = (e = t, P.innerHTML = "", P.appendChild(document.createTextNode(e)), P.innerHTML)), this) : this[0].innerHTML;
                var e
            },
            focus: function() {
                if (this[0]) try {
                    this[0].focus()
                } catch (t) {}
                return this
            },
            blur: function() {
                if (this[0]) try {
                    this[0].blur()
                } catch (t) {}
                return this
            },
            scrollTo: function(t) {
                if (this[0]) try {
                    this[0].scrollTo(0, t)
                } catch (t) {}
                return this
            }
        };
        var P = document.createElement("div");
        var L, A = function() {
                var t = window,
                    e = (t.Boolean, t.Array),
                    i = t.Object,
                    o = t.String,
                    n = (t.Number, t.Date),
                    r = t.Math,
                    a = t.setTimeout,
                    s = (t.setInterval, t.clearTimeout),
                    p = (t.clearInterval, t.parseInt, t.encodeURIComponent),
                    m = (t.decodeURIComponent, t.btoa),
                    v = t.unescape,
                    c = function(n) {
                        return function(e, t) {
                            return arguments.length < 2 ? function(t) {
                                return null === t ? null : n.call(null, t, e)
                            } : n.call(null, e, t)
                        }
                    },
                    u = function(r) {
                        return function(e, n, t) {
                            return arguments.length < 3 ? function(t) {
                                return null === t ? null : r.call(null, t, e, n)
                            } : r.call(null, e, n, t)
                        }
                    },
                    l = c(function(t, e) {
                        return typeof t === e
                    }),
                    f = (l("boolean"), l("number"), l("string")),
                    d = l("function"),
                    h = l("object"),
                    g = (e.isArray, function(t) {
                        return null !== t && h(t)
                    }),
                    y = c(function(t, e) {
                        return t && t[e]
                    }),
                    b = y("length"),
                    w = y("prototype"),
                    k = c(function(t, e) {
                        return t instanceof e
                    }),
                    _ = n.now,
                    x = r.random,
                    M = r.floor;

                function N(t, e) {
                    return {
                        error: (n = t, r = e, i = {
                            description: o(n)
                        }, r && (i.field = r), i)
                    };
                    var n, r, i
                }
                var S = function(t) {
                    return /data:image\/[^;]+;base64/.test(t)
                };

                function C(n) {
                    if (!g(n)) return "";
                    var t = i.keys(n),
                        r = e(b(t));
                    return t.forEach(function(t, e) {
                        return r[e] = p(t) + "=" + p(n[t])
                    }), r.join("&")
                }
                var T, R = w(e),
                    D = (R.slice, c(function(t, e) {
                        return t && R.forEach.call(t, e), t
                    })),
                    P = u(function(t, e, n) {
                        return tt.reduce.call(t, e, n)
                    }),
                    L = function(t) {
                        return t
                    },
                    A = (w(Function), c(function(t, e) {
                        return t && t.hasOwnProperty(e)
                    })),
                    E = u(function(t, e, n) {
                        return t[e] = n, t
                    }),
                    z = u(function(t, e, n) {
                        return n && (t[e] = n), t
                    }),
                    O = c(function(t, e) {
                        return delete t[e], t
                    }),
                    B = c(function(e, n) {
                        var t;
                        return D((t = e, i.keys(t)), function(t) {
                            return n(e[t], t, e)
                        }), e
                    }),
                    I = JSON.stringify,
                    F = XMLHttpRequest;

                function K(t) {
                    if (!k(this, K)) return new K(t);
                    this.options = function(t) {
                        f(t) && (t = {
                            url: t
                        });
                        var e = t,
                            n = e.method,
                            r = e.headers,
                            i = e.data,
                            o = void 0 === i ? null : i;
                        r || (t.headers = {});
                        n || (t.method = "get");
                        g(o) && (o = C(o));
                        return t.data = o, t
                    }(t), this.defer()
                }((T = {
                    till: function(e) {
                        var n = this;
                        return this.abort(), this.xhr = a(function() {
                            n.call(function(t) {
                                e(t) ? n.till(e) : n.options.callback(t)
                            })
                        }, 3e3), this
                    },
                    abort: function() {
                        var t = this.xhr;
                        k(t, F) ? t.abort() : t && s(t), this.xhr = null
                    },
                    defer: function() {
                        var t = this;
                        this.xhr = a(function() {
                            return t.call()
                        })
                    },
                    call: function(e) {
                        var t, n;
                        void 0 === e && (e = this.options.callback);
                        var r = this.options,
                            i = r.url,
                            o = r.method,
                            a = r.data,
                            s = r.headers,
                            c = this.xhr = new F;
                        c.open(o, i, !0), d(e) && (c.onreadystatechange = function() {
                            if (4 === c.readyState && c.status) {
                                var t = function(t) {
                                    try {
                                        return JSON.parse(t)
                                    } catch (t) {}
                                }(c.responseText);
                                t || ((t = N("Parsing error")).xhr = {
                                    status: c.status,
                                    text: c.responseText
                                }), e(t)
                            }
                        }, c.onerror = function() {
                            var t = N("Network error");
                            t.xhr = {
                                status: 0
                            }, e(t)
                        }), n = s, t = z("X-Razorpay-SessionId", void 0)(n), B(function(t, e) {
                            return c.setRequestHeader(e, t)
                        })(t), c.send(a)
                    }
                }).constructor = K).prototype = T, K.post = function(t) {
                    return t.method = "post", t.headers || (t.headers = {}), t.headers["Content-type"] = "application/x-www-form-urlencoded", K(t)
                }, K.setSessionId = void 0;
                var $, H = w(o).slice,
                    U = u(function(t, e, n) {
                        return H.call(t, e, n)
                    }),
                    Y = c(function(t, e) {
                        return H.call(t, e)
                    }),
                    G = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
                    j = ($ = G, P(function(t, e, n) {
                        return E(t, e, n)
                    }, {})($));

                function Z(t) {
                    for (var e = ""; t;) e = G[t % 62] + e, t = M(t / 62);
                    return e
                }

                function J() {
                    var t, n, r = Z(o(_() - 13885344e5) + Y("000000" + M(1e6 * x()), -6)) + Z(M(238328 * x())) + "0",
                        i = 0;
                    return t = r, D(function(t, e) {
                        n = j[r[r.length - 1 - e]], (r.length - e) % 2 && (n *= 2), 62 <= n && (n = n % 62 + 1), i += n
                    })(t), (n = i % 62) && (n = G[62 - n]), U(r, 0, 13) + n
                }
                var W = J(),
                    q = {
                        library: "checkoutjs",
                        platform: "browser",
                        referer: location.href
                    };

                function V(t) {
                    var e, n = {
                        checkout_id: t ? t.id : W
                    };
                    return e = ["integration", "referer", "library", "platform", "platform_version", "os", "os_version", "device"], D(function(t) {
                        var e;
                        return e = n, z(t, q[t])(e)
                    })(e), n
                }

                function X(f, d, h) {
                    f.isLiveMode() && a(function() {
                        h instanceof Error && (h = {
                            message: h.message,
                            stack: h.stack
                        });
                        var t = V(f);
                        t.user_agent = null, t.mode = "live";
                        var e = f.get("order_id");
                        e && (t.order_id = e);
                        var i = {},
                            n = {
                                options: i
                            };
                        h && (n.data = h);
                        var o = ["key", "amount", "prefill", "theme", "image", "description", "name", "method", "display_currency", "display_amount"];
                        B(f.get(), function(t, e) {
                                var n = e.split("."),
                                    r = n[0]; - 1 !== o.indexOf(r) && (1 < n.length ? (o.hasOwnProperty(r) || (i[r] = {}), i[r][n[1]] = t) : i[e] = t)
                            }), i.image && S(i.image) && (i.image = "base64"),
                            function(t, e) {
                                var n = t._payment;
                                if (n) {
                                    var r, i, o;
                                    n.payment_id && (e.payment_id = n.payment_id), r = n, A("magicPossible")(r) && (e.magic_possible = n.magicPossible), i = n, A("isMagicPayment")(i) && (e.magic_attempted = n.isMagicPayment), o = n, A("magicCoproto")(o) && (e.magic_coproto = n.magicCoproto)
                                }
                            }(f, n), W && (n.local_order_id = W);
                        var r = {
                            context: t,
                            events: [{
                                event: d,
                                properties: n,
                                timestamp: _()
                            }]
                        };
                        try {
                            var a, s, c, u, l;
                            K.post({
                                url: "https://lumberjack.razorpay.com/v1/track",
                                data: {
                                    key: "ZmY5N2M0YzVkN2JiYzkyMWM1ZmVmYWJk",
                                    data: (l = r, u = I(l), c = p(u), s = v(c), a = m(s), p(a))
                                }
                            })
                        } catch (t) {}
                    })
                }
                X.makeUid = J, X.common = V, X.props = q, X.id = W, X.updateUid = function(t) {
                    X.id = W = t
                };
                var Q = function(t, e) {
                    var n, r, i = {
                        data: t.data,
                        error: t.error || L,
                        success: t.success || L,
                        callback: t.callback || L,
                        url: t.url || ""
                    };
                    return i.data || (i.data = {}), i.data.callback = "Razorpay." + e, E(i, "computedUrl", (n = i.url, r = i.data, n += 0 < n.indexOf("?") ? "&" : "?", n += C(r)))
                };
                return function(t) {
                    t.data || (t.data = {});
                    var e = "jsonp_cb_" + X.makeUid(),
                        n = Q(t, e),
                        r = !1;
                    et[e] = function(t) {
                        O(t, "http_status_code"), n.success(t, n), n.callback(t, n), O(et, e)
                    };
                    var i = document.createElement("script");
                    return i.src = n.computedUrl, i.async = !0, i.onerror = function(t) {
                        n.error({
                            error: !0,
                            url: i.src,
                            event: t
                        }), n.callback({
                            error: !0,
                            url: i.src,
                            event: t
                        }, n)
                    }, i.onload = i.onreadystatechange = function() {
                        r || this.readyState && "loaded" !== this.readyState && "complete" !== this.readyState || (r = !0, i.onload = i.onreadystatechange = null, i.parentNode.removeChild(i), i = null)
                    }, document.documentElement.appendChild(i), {
                        abort: function() {
                            et[e] && (et[e] = L)
                        }
                    }
                }
            }(),
            E = (function() {
                var t = window,
                    a = (t.Boolean, t.Array),
                    s = t.Object,
                    o = t.String,
                    e = (t.Number, t.Date),
                    n = t.Math,
                    r = t.setTimeout,
                    i = (t.setInterval, t.clearTimeout),
                    c = (t.clearInterval, t.parseInt, t.encodeURIComponent),
                    u = (t.decodeURIComponent, t.btoa, t.unescape, function(n) {
                        return function(e, t) {
                            return arguments.length < 2 ? function(t) {
                                return null === t ? null : n.call(null, t, e)
                            } : n.call(null, e, t)
                        }
                    }),
                    l = u(function(t, e) {
                        return typeof t === e
                    }),
                    f = (l("boolean"), l("number"), l("string")),
                    d = l("function"),
                    h = l("object"),
                    p = (a.isArray, function(t) {
                        return null !== t && h(t)
                    }),
                    m = u(function(t, e) {
                        return t && t[e]
                    }),
                    v = m("length"),
                    g = m("prototype"),
                    y = u(function(t, e) {
                        return t instanceof e
                    });
                e.now, n.random, n.floor;

                function b(t, e) {
                    return {
                        error: (n = t, r = e, i = {
                            description: o(n)
                        }, r && (i.field = r), i)
                    };
                    var n, r, i
                }
                var w, k, _ = g(a),
                    x = (_.slice, u(function(t, e) {
                        return t && _.forEach.call(t, e), t
                    })),
                    M = (w = function(t, e, n) {
                        return n && (t[e] = n), t
                    }, function(e, n, t) {
                        return arguments.length < 3 ? function(t) {
                            return null === t ? null : w.call(null, t, e, n)
                        } : w.call(null, e, n, t)
                    }),
                    N = u(function(e, n) {
                        var t;
                        return x((t = e, s.keys(t)), function(t) {
                            return n(e[t], t, e)
                        }), e
                    }),
                    S = (JSON.stringify, g(Function), XMLHttpRequest);

                function C(t) {
                    if (!y(this, C)) return new C(t);
                    this.options = function(t) {
                        f(t) && (t = {
                            url: t
                        });
                        var e = t,
                            n = e.method,
                            r = e.headers,
                            i = e.data,
                            o = void 0 === i ? null : i;
                        r || (t.headers = {});
                        n || (t.method = "get");
                        p(o) && (o = function(n) {
                            if (!p(n)) return "";
                            var t = s.keys(n),
                                r = a(v(t));
                            return t.forEach(function(t, e) {
                                return r[e] = c(t) + "=" + c(n[t])
                            }), r.join("&")
                        }(o));
                        return t.data = o, t
                    }(t), this.defer()
                }((k = {
                    till: function(e) {
                        var n = this;
                        return this.abort(), this.xhr = r(function() {
                            n.call(function(t) {
                                e(t) ? n.till(e) : n.options.callback(t)
                            })
                        }, 3e3), this
                    },
                    abort: function() {
                        var t = this.xhr;
                        y(t, S) ? t.abort() : t && i(t), this.xhr = null
                    },
                    defer: function() {
                        var t = this;
                        this.xhr = r(function() {
                            return t.call()
                        })
                    },
                    call: function(e) {
                        var t, n;
                        void 0 === e && (e = this.options.callback);
                        var r = this.options,
                            i = r.url,
                            o = r.method,
                            a = r.data,
                            s = r.headers,
                            c = this.xhr = new S;
                        c.open(o, i, !0), d(e) && (c.onreadystatechange = function() {
                            if (4 === c.readyState && c.status) {
                                var t = function(t) {
                                    try {
                                        return JSON.parse(t)
                                    } catch (t) {}
                                }(c.responseText);
                                t || ((t = b("Parsing error")).xhr = {
                                    status: c.status,
                                    text: c.responseText
                                }), e(t)
                            }
                        }, c.onerror = function() {
                            var t = b("Network error");
                            t.xhr = {
                                status: 0
                            }, e(t)
                        }), n = s, t = M("X-Razorpay-SessionId", void 0)(n), N(function(t, e) {
                            return c.setRequestHeader(e, t)
                        })(t), c.send(a)
                    }
                }).constructor = C).prototype = k, C.post = function(t) {
                    return t.method = "post", t.headers || (t.headers = {}), t.headers["Content-type"] = "application/x-www-form-urlencoded", C(t)
                }, C.setSessionId = void 0
            }(), function() {
                var t = window,
                    a = (t.Boolean, t.Array),
                    s = t.Object,
                    o = t.String,
                    e = (t.Number, t.Date),
                    n = t.Math,
                    r = t.setTimeout,
                    i = (t.setInterval, t.clearTimeout),
                    m = (t.clearInterval, t.parseInt, t.encodeURIComponent),
                    v = (t.decodeURIComponent, t.btoa),
                    g = t.unescape,
                    c = function(n) {
                        return function(e, t) {
                            return arguments.length < 2 ? function(t) {
                                return null === t ? null : n.call(null, t, e)
                            } : n.call(null, e, t)
                        }
                    },
                    u = function(r) {
                        return function(e, n, t) {
                            return arguments.length < 3 ? function(t) {
                                return null === t ? null : r.call(null, t, e, n)
                            } : r.call(null, e, n, t)
                        }
                    },
                    l = c(function(t, e) {
                        return typeof t === e
                    }),
                    f = (l("boolean"), l("number"), l("string")),
                    d = l("function"),
                    h = l("object"),
                    p = (a.isArray, function(t) {
                        return null !== t && h(t)
                    }),
                    y = c(function(t, e) {
                        return t && t[e]
                    }),
                    b = y("length"),
                    w = y("prototype"),
                    k = c(function(t, e) {
                        return t instanceof e
                    }),
                    _ = e.now,
                    x = n.random,
                    M = n.floor;

                function N(t, e) {
                    return {
                        error: (n = t, r = e, i = {
                            description: o(n)
                        }, r && (i.field = r), i)
                    };
                    var n, r, i
                }
                var S, C = w(a),
                    T = (C.slice, c(function(t, e) {
                        return t && C.forEach.call(t, e), t
                    })),
                    R = u(function(t, e, n) {
                        return tt.reduce.call(t, e, n)
                    }),
                    D = c(function(t, e) {
                        return t && t.hasOwnProperty(e)
                    }),
                    P = u(function(t, e, n) {
                        return t[e] = n, t
                    }),
                    L = u(function(t, e, n) {
                        return n && (t[e] = n), t
                    }),
                    A = c(function(e, n) {
                        var t;
                        return T((t = e, s.keys(t)), function(t) {
                            return n(e[t], t, e)
                        }), e
                    }),
                    E = JSON.stringify,
                    z = (w(Function), XMLHttpRequest);

                function O(t) {
                    if (!k(this, O)) return new O(t);
                    this.options = function(t) {
                        f(t) && (t = {
                            url: t
                        });
                        var e = t,
                            n = e.method,
                            r = e.headers,
                            i = e.data,
                            o = void 0 === i ? null : i;
                        r || (t.headers = {});
                        n || (t.method = "get");
                        p(o) && (o = function(n) {
                            if (!p(n)) return "";
                            var t = s.keys(n),
                                r = a(b(t));
                            return t.forEach(function(t, e) {
                                return r[e] = m(t) + "=" + m(n[t])
                            }), r.join("&")
                        }(o));
                        return t.data = o, t
                    }(t), this.defer()
                }((S = {
                    till: function(e) {
                        var n = this;
                        return this.abort(), this.xhr = r(function() {
                            n.call(function(t) {
                                e(t) ? n.till(e) : n.options.callback(t)
                            })
                        }, 3e3), this
                    },
                    abort: function() {
                        var t = this.xhr;
                        k(t, z) ? t.abort() : t && i(t), this.xhr = null
                    },
                    defer: function() {
                        var t = this;
                        this.xhr = r(function() {
                            return t.call()
                        })
                    },
                    call: function(e) {
                        var t, n;
                        void 0 === e && (e = this.options.callback);
                        var r = this.options,
                            i = r.url,
                            o = r.method,
                            a = r.data,
                            s = r.headers,
                            c = this.xhr = new z;
                        c.open(o, i, !0), d(e) && (c.onreadystatechange = function() {
                            if (4 === c.readyState && c.status) {
                                var t = function(t) {
                                    try {
                                        return JSON.parse(t)
                                    } catch (t) {}
                                }(c.responseText);
                                t || ((t = N("Parsing error")).xhr = {
                                    status: c.status,
                                    text: c.responseText
                                }), e(t)
                            }
                        }, c.onerror = function() {
                            var t = N("Network error");
                            t.xhr = {
                                status: 0
                            }, e(t)
                        }), n = s, t = L("X-Razorpay-SessionId", void 0)(n), A(function(t, e) {
                            return c.setRequestHeader(e, t)
                        })(t), c.send(a)
                    }
                }).constructor = O).prototype = S, O.post = function(t) {
                    return t.method = "post", t.headers || (t.headers = {}), t.headers["Content-type"] = "application/x-www-form-urlencoded", O(t)
                }, O.setSessionId = void 0;
                var B, I = w(o).slice,
                    F = u(function(t, e, n) {
                        return I.call(t, e, n)
                    }),
                    K = c(function(t, e) {
                        return I.call(t, e)
                    }),
                    $ = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
                    H = (B = $, R(function(t, e, n) {
                        return P(t, e, n)
                    }, {})(B));

                function U(t) {
                    for (var e = ""; t;) e = $[t % 62] + e, t = M(t / 62);
                    return e
                }

                function Y() {
                    var t, n, r = U(o(_() - 13885344e5) + K("000000" + M(1e6 * x()), -6)) + U(M(238328 * x())) + "0",
                        i = 0;
                    return t = r, T(function(t, e) {
                        n = H[r[r.length - 1 - e]], (r.length - e) % 2 && (n *= 2), 62 <= n && (n = n % 62 + 1), i += n
                    })(t), (n = i % 62) && (n = $[62 - n]), F(r, 0, 13) + n
                }
                var G = Y(),
                    j = {
                        library: "checkoutjs",
                        platform: "browser",
                        referer: location.href
                    };

                function Z(t) {
                    var e, n = {
                        checkout_id: t ? t.id : G
                    };
                    return e = ["integration", "referer", "library", "platform", "platform_version", "os", "os_version", "device"], T(function(t) {
                        var e;
                        return e = n, L(t, j[t])(e)
                    })(e), n
                }

                function J(d, h, p) {
                    d.isLiveMode() && r(function() {
                        p instanceof Error && (p = {
                            message: p.message,
                            stack: p.stack
                        });
                        var t = Z(d);
                        t.user_agent = null, t.mode = "live";
                        var e = d.get("order_id");
                        e && (t.order_id = e);
                        var i = {},
                            n = {
                                options: i
                            };
                        p && (n.data = p);
                        var r, o = ["key", "amount", "prefill", "theme", "image", "description", "name", "method", "display_currency", "display_amount"];
                        A(d.get(), function(t, e) {
                                var n = e.split("."),
                                    r = n[0]; - 1 !== o.indexOf(r) && (1 < n.length ? (o.hasOwnProperty(r) || (i[r] = {}), i[r][n[1]] = t) : i[e] = t)
                            }), i.image && (r = i.image, /data:image\/[^;]+;base64/.test(r)) && (i.image = "base64"),
                            function(t, e) {
                                var n = t._payment;
                                if (n) {
                                    var r, i, o;
                                    n.payment_id && (e.payment_id = n.payment_id), r = n, D("magicPossible")(r) && (e.magic_possible = n.magicPossible), i = n, D("isMagicPayment")(i) && (e.magic_attempted = n.isMagicPayment), o = n, D("magicCoproto")(o) && (e.magic_coproto = n.magicCoproto)
                                }
                            }(d, n), G && (n.local_order_id = G);
                        var a = {
                            context: t,
                            events: [{
                                event: h,
                                properties: n,
                                timestamp: _()
                            }]
                        };
                        try {
                            var s, c, u, l, f;
                            O.post({
                                url: "https://lumberjack.razorpay.com/v1/track",
                                data: {
                                    key: "ZmY5N2M0YzVkN2JiYzkyMWM1ZmVmYWJk",
                                    data: (f = a, l = E(f), u = m(l), c = g(u), s = v(c), m(s))
                                }
                            })
                        } catch (t) {}
                    })
                }
                return J.makeUid = Y, J.common = Z, J.props = j, J.id = G, J.updateUid = function(t) {
                    J.id = G = t
                }, J
            }()),
            z = function() {
                function o() {
                    return this._evts = {}, this._defs = {}, this
                }
                return o.prototype = {
                    onNew: r,
                    def: function(t, e) {
                        this._defs[t] = e
                    },
                    on: function(t, e) {
                        if (y(t) && g(e)) {
                            var n = this._evts;
                            n[t] || (n[t] = []), !1 !== this.onNew(t, e) && n[t].push(e)
                        }
                        return this
                    },
                    once: function(t, e) {
                        var n = e,
                            r = this,
                            i = function() {
                                n.apply(r, arguments), r.off(t, i)
                            };
                        return e = i, this.on(t, e)
                    },
                    off: function(e, t) {
                        var n = arguments.length;
                        if (!n) return o.call(this);
                        var r = this._evts;
                        if (2 === n) {
                            var i = r[e];
                            if (!(g(t) && i instanceof Array)) return;
                            if (i.splice(function(t, e) {
                                    if (tt.indexOf) return t.indexOf(e);
                                    var n = t.length >>> 0,
                                        r = Number(e) || 0;
                                    for ((r = r < 0 ? Math.ceil(r) : Math.floor(r)) < 0 && (r += n); r < n; r++)
                                        if (r in t && t[r] === e) return r;
                                    return -1
                                }(i, t), 1), i.length) return
                        }
                        return r[e] ? delete r[e] : (e += ".", w(r, function(t) {
                            t.indexOf(e) || delete r[t]
                        })), this
                    },
                    emit: function(t, n) {
                        return w(this._evts[t], function(t, e) {
                            try {
                                e.call(this, n)
                            } catch (t) {
                                console.error
                            }
                        }, this), this
                    },
                    emitter: function() {
                        var t = this,
                            e = arguments;
                        return function() {
                            t.emit.apply(t, e)
                        }
                    }
                }, o
            }();
        ! function t() {
            (L = document.body || document.getElementsByTagName("body")[0]) || setTimeout(t, 99)
        }();
        var O = L || document.documentElement;

        function B(e) {
            return function t() {
                return L ? e.call(this) : (function(t, e) {
                    if (1 === arguments.length && (e = 0), arguments.length < 3) setTimeout(t, e);
                    else {
                        var n = arguments;
                        setTimeout(function() {
                            t.apply(null, i.call(n, 2))
                        }, e)
                    }
                }(k(t, this), 99), this)
            }
        }
        var I = {
            api: "https://api.razorpay.com/",
            version: "v1/",
            frameApi: "/",
            cdn: "https://cdn.razorpay.com/"
        };
        try {
            var F = window.Razorpay.config;
            for (var K in F) I[K] = F[K]
        } catch (t) {}

        function $(t) {
            return t || (t = ""), I.api + I.version + t
        }
        var H = ["key", "order_id", "invoice_id", "subscription_id", "payment_link_id"];
        var et = window.Razorpay = function(e) {
                if (!(this instanceof et)) return new et(e);
                var n;
                z.call(this), this.id = E.makeUid();
                try {
                    n = function(t) {
                        t && "object" == typeof t || a("Invalid options");
                        var e = X(t);
                        i = e, i = i.get(), w(J, function(t, e) {
                            t in i && (o = e(i[t], i)), y(o) && a("Invalid " + t + " (" + o + ")")
                        }), n = e, r = n.get("notes"), w(r, function(t, e) {
                            y(e) ? 254 < e.length && (r[t] = e.slice(0, 254)) : v(e) || m(e) || delete r[t]
                        }), e.get("callback_url") && h && e.set("redirect", !0);
                        var n, r;
                        var i, o;
                        return e
                    }(e), this.get = n.get, this.set = n.set
                } catch (t) {
                    var r = t.message;
                    this.get && this.isLiveMode() || b(e) && !e.parent && alert(r), a(r)
                }
                H.every(function(t) {
                    return !n.get(t)
                }) && a("No key passed"), Z.validate(this), Z.isCheckout || E(this, "init"), this.postInit()
            },
            U = et.prototype = new z;

        function Y(t, e) {
            return A({
                url: $("preferences"),
                data: t,
                timeout: 3e4,
                success: function(t) {
                    _(e, null, t)
                }
            })
        }
        U.postInit = r, U.onNew = function(t, e) {
            if ("ready" === t) {
                var n = this;
                n.prefs ? e(t, n.prefs) : Y(j(n), function(t) {
                    t.methods && (n.prefs = t, n.methods = t.methods), e(n.prefs)
                })
            }
        }, U.emi_calculator = function(t, e) {
            return et.emi.calculator(this.get("amount") / 100, t, e)
        }, et.emi = {
            calculator: function(t, e, n) {
                if (!n) return Math.ceil(t / e);
                n /= 1200;
                var r = Math.pow(1 + n, e);
                return parseInt(t * n * r / (r - 1), 10)
            }
        };
        et.payment = {
            getMethods: function(e) {
                return Y({
                    key_id: et.defaults.key
                }, function(t) {
                    e(t.methods || t)
                })
            }
        };
        var G = et.defaults = {
            key: "",
            account_id: "",
            image: "",
            amount: 100,
            currency: "INR",
            order_id: "",
            invoice_id: "",
            subscription_id: "",
            payment_link_id: "",
            notes: null,
            callback_url: "",
            redirect: !1,
            description: "",
            customer_id: "",
            recurring: null,
            signature: "",
            retry: !0,
            target: "",
            subscription_card_change: null,
            display_currency: "",
            display_amount: "",
            recurring_token: {
                max_amount: 0,
                expire_by: 0
            }
        };

        function j(t) {
            if (t) {
                var r = t.get,
                    i = {},
                    e = r("key");
                return e && (i.key_id = e), w(["order_id", "customer_id", "invoice_id", "payment_link_id", "subscription_id", "recurring", "subscription_card_change", "account_id"], function(t, e) {
                    var n = r(e);
                    n && (i[e] = n)
                }), i
            }
        }
        U.isLiveMode = function() {
            return /^rzp_l/.test(this.get("key"))
        };
        var Z = {
                validate: r,
                msg: {
                    wrongotp: "Entered OTP was incorrect. Re-enter to proceed."
                },
                isBase64Image: function(t) {
                    return /data:image\/[^;]+;base64/.test(t)
                },
                cancelMsg: "Payment cancelled",
                error: function(t) {
                    return {
                        error: {
                            description: t || Z.cancelMsg
                        }
                    }
                },
                redirect: function(t) {
                    if (!t.target && window !== window.parent) return _(et.sendMessage, null, {
                        event: "redirect",
                        data: t
                    });
                    S(t.url, t.content, t.method, t.target)
                }
            },
            J = {
                notes: function(t) {
                    var e = "";
                    if (b(t)) {
                        var n = 0;
                        if (w(t, function() {
                                n++
                            }), !(15 < n)) return;
                        e = "At most 15 notes are allowed"
                    }
                    return e
                },
                amount: function(t, e) {
                    if ((/[^0-9]/.test(n = t) || !(100 <= (n = parseInt(n, 10)))) && !e.recurring) {
                        return "should be passed in integer paise. Minimum value is 100 paise, i.e. ₹ 1"
                    }
                    var n
                },
                currency: function(t) {
                    if ("INR" !== t && "USD" !== t) return "INR and USD are the only supported values for currency field."
                },
                display_currency: function(t) {
                    if (!(t in Z.currencies) && t !== et.defaults.display_currency) return "This display currency is not supported"
                },
                display_amount: function(t) {
                    if (!(t = String(t).replace(/([^0-9\.])/g, "")) && t !== et.defaults.display_amount) return ""
                }
            };
        et.configure = function(t) {
            w(V(t, et.defaults), function(t, e) {
                typeof et.defaults[t] == typeof e && (et.defaults[t] = e)
            })
        }, Z.currencies = {
            AFN: "&#x60b;",
            ALL: "&#x6b;",
            DZD: "د.ج",
            WST: "T",
            EUR: "&#8364;",
            AOA: "Kz",
            XCD: "EC$",
            ARS: "$",
            AMD: "&#1423;",
            AWG: "ƒ",
            AUD: "A$",
            AZN: "ман",
            BSD: "B$",
            BHD: "د.ب",
            BDT: "&#x9f3;",
            BBD: "Bds$",
            BYR: "Br",
            BZD: "BZ$",
            XOF: "CFA",
            BMD: "BD$",
            BTN: "Nu.",
            BOB: "Bs.",
            BAM: "KM",
            BWP: "P",
            BRL: "R$",
            USD: "$",
            BND: "B$",
            BGN: "лв",
            BIF: "FBu",
            KHR: "៛",
            XAF: "CFA",
            CAD: "C$",
            CVE: "Esc",
            KYD: "KY$",
            CLP: "$",
            CNY: "&#165;",
            COP: "$",
            KMF: "CF",
            NZD: "NZ$",
            CRC: "&#x20a1;",
            HRK: "Kn",
            CUC: "&#x20b1;",
            ANG: "ƒ",
            CZK: "Kč",
            CDF: "FC",
            DKK: "Kr.",
            DJF: "Fdj",
            DOP: "RD$",
            EGP: "E&#163;",
            SVC: "&#x20a1;",
            ERN: "Nfa",
            ETB: "Br",
            FKP: "FK&#163;",
            FJD: "FJ$",
            XPF: "F",
            GMD: "D",
            GEL: "ლ",
            GHS: "&#x20b5;",
            GIP: "&#163;",
            GTQ: "Q",
            GBP: "&#163;",
            GNF: "FG",
            GYD: "GY$",
            HTG: "G",
            HNL: "L",
            HKD: "HK$",
            HUF: "Ft",
            ISK: "Kr",
            IDR: "Rp",
            IRR: "&#xfdfc;",
            IQD: "ع.د",
            ILS: "&#x20aa;",
            JMD: "J$",
            JPY: "&#165;",
            JOD: "د.ا",
            KZT: "&#x20b8;",
            KES: "KSh",
            KWD: "د.ك",
            KGS: "лв",
            LAK: "&#x20ad;",
            LVL: "Ls",
            LBP: "L&#163;",
            LSL: "L",
            LRD: "L$",
            LD: "ل.د",
            LYD: "ل.د",
            CHF: "Fr",
            LTL: "Lt",
            MOP: "P",
            MKD: "ден",
            MGA: "Ar",
            MWK: "MK",
            MYR: "RM",
            MVR: "Rf",
            MRO: "UM",
            MUR: "Ɍs",
            MXN: "$",
            MDL: "L",
            MNT: "&#x20ae;",
            MAD: "د.م.",
            MZN: "MT",
            MMK: "K",
            NAD: "N$",
            NPR: "NɌs",
            NIO: "C$",
            NGN: "&#x20a6;",
            KPW: "₩",
            NOK: "Kr",
            OMR: "ر.ع.",
            PKR: "Ɍs",
            PAB: "B/.",
            PGK: "K",
            PYG: "&#x20b2;",
            PEN: "S/.",
            PHP: "&#x20b1;",
            PLN: "Zł",
            QAR: "QAR",
            RON: "L",
            RUB: "руб",
            RWF: "RF",
            SHP: "&#163;",
            STD: "Db",
            SAR: "ر.س",
            RSD: "Дин.",
            SCR: "Ɍs",
            SLL: "Le",
            SGD: "S$",
            SBD: "SI$",
            SOS: "So. Sh.",
            ZAR: "R",
            KRW: "₩",
            SDG: "&#163;Sd",
            LKR: "Rs",
            SFR: "Fr",
            SRD: "$",
            SZL: "L",
            SEK: "Kr",
            SYP: "S&#163;",
            TWD: "NT$",
            TJS: "SM",
            TZS: "TSh",
            THB: "&#x0e3f;",
            TOP: "T$",
            TTD: "TT$",
            TND: "د.ت",
            TRY: "TL",
            TMT: "M",
            UGX: "USh",
            UAH: "&#x20b4;",
            AED: "د.إ",
            UYU: "$U",
            UZS: "лв",
            VUV: "VT",
            VEF: "Bs",
            VND: "&#x20ab;",
            YER: "&#xfdfc;",
            ZMK: "ZK",
            ZWL: "Z$"
        }, G.handler = function(t) {
            if (this instanceof et) {
                var e = this.get("callback_url");
                e && S(e, t, "post")
            }
        }, G.timeout = 0, G.buttontext = "Pay Now", G.parent = null, G.name = "", G.ecod = !1, G.remember_customer = !1, G.method = {
            netbanking: !0,
            card: !0,
            wallet: null,
            emi: !0,
            upi: !0,
            upi_intent: null
        }, G.prefill = {
            amount: "",
            wallet: "",
            method: "",
            name: "",
            contact: "",
            email: "",
            vpa: "",
            "card[number]": "",
            "card[expiry]": "",
            "card[cvv]": "",
            bank: "",
            "bank_account[name]": "",
            "bank_account[account_number]": "",
            "bank_account[ifsc]": "",
            "aadhaar[vid]": "",
            auth_type: ""
        }, G.features = {
            cardsaving: !0
        }, G.readonly = {
            contact: !1,
            email: !1,
            name: !1
        }, G.modal = {
            confirm_close: !1,
            ondismiss: r,
            onhidden: r,
            escape: !0,
            animation: !0,
            backdropclose: !1
        }, G.external = {
            wallets: [],
            handler: r
        }, G.theme = {
            upi_only: !1,
            color: "",
            backdrop_color: "rgba(0,0,0,0.6)",
            image_padding: !0,
            image_frame: !0,
            close_button: !0,
            close_method_back: !1,
            hide_topbar: !1,
            branding: "",
            emi_mode: !1,
            debit_card: !1
        }, J.parent = function(t) {
            if (!R(t)[0]) return "parent provided for embedded mode doesn't exist"
        };
        var W = {};

        function q(t, e, n, r) {
            var i = e[n = n.toLowerCase()],
                o = typeof i;
            "string" === o && (v(r) || m(r)) ? r = String(r) : "number" === o ? r = Number(r) : "boolean" === o && y(r) && ("true" === r ? r = !0 : "false" === r && (r = !1)), null !== i && o !== typeof r || (t[n] = r)
        }

        function V(t, r) {
            var i = {};
            return w(t, function(n, t) {
                n in W ? w(t, function(t, e) {
                    q(i, r, n + "." + t, e)
                }) : q(i, r, n, t)
            }), i
        }

        function X(n) {
            if (!(this instanceof X)) return new X(n, e);
            var e = et.defaults;
            n = V(n, e), this.get = function(t) {
                return arguments.length ? t in n ? n[t] : e[t] : n
            }, this.set = function(t, e) {
                n[t] = e
            }, this.unset = function(t) {
                delete n[t]
            }
        }
        w(et.defaults, function(n, t) {
            b(t) && (W[n] = !0, w(t, function(t, e) {
                et.defaults[n + "." + t] = e
            }), delete et.defaults[n])
        });
        var Q, nt = O.style,
            rt = {
                overflow: "",
                metas: null,
                orientationchange: function() {
                    rt.resize.call(this), rt.scroll.call(this)
                },
                resize: function() {
                    var t = innerHeight || screen.height;
                    ct.style.position = t < 450 ? "absolute" : "fixed", this.el.style.height = Math.max(t, 460) + "px"
                },
                scroll: function() {
                    if ("number" == typeof window.pageYOffset)
                        if (innerHeight < 460) {
                            var t = 460 - innerHeight;
                            pageYOffset > t + 120 && D(t)
                        } else this.isFocused || D(0)
                }
            };

        function it() {
            return rt.metas || (rt.metas = N('head meta[name=viewport],head meta[name="theme-color"]')), rt.metas
        }

        function ot(t) {
            try {
                ut.style.background = t
            } catch (t) {}
        }

        function at(t) {
            if (t) return this.getEl(t), this.openRzp(t);
            this.getEl(), this.time = e()
        }
        at.prototype = {
            getEl: function(t) {
                if (!this.el) {
                    var e = {
                        style: "opacity: 1; height: 100%; position: relative; background: none; display: block; border: 0 none transparent; margin: 0px; padding: 0px;",
                        allowtransparency: !0,
                        frameborder: 0,
                        width: "100%",
                        height: "100%",
                        allowpaymentrequest: !0,
                        src: function(t) {
                            var e = I.frame;
                            if (!e) {
                                e = $("checkout");
                                var n = j(t);
                                n || (n = {}, e += "/public"), I.js && (n.checkout = I.js);
                                var r = [];
                                w(n, function(t, e) {
                                    r.push(t + "=" + e)
                                }), r.length && (e += "?" + r.join("&"))
                            }
                            return e
                        }(t),
                        class: "razorpay-checkout-frame"
                    };
                    this.el = R(document.createElement("iframe")).attr(e)[0]
                }
                return this.el
            },
            openRzp: function(t) {
                var e = R(this.el).css({
                        width: "100%",
                        height: "100%"
                    }),
                    n = t.get("parent"),
                    r = R(n || ct);
                ! function(t, e) {
                    if (!Q) try {
                        (Q = document.createElement("div")).className = "razorpay-loader";
                        var n = "margin:-25px 0 0 -25px;height:50px;width:50px;animation:rzp-rot 1s infinite linear;-webkit-animation:rzp-rot 1s infinite linear;border: 1px solid rgba(255, 255, 255, 0.2);border-top-color: rgba(255, 255, 255, 0.7);border-radius: 50%;";
                        n += e ? "margin: 100px auto -150px;border: 1px solid rgba(0, 0, 0, 0.2);border-top-color: rgba(0, 0, 0, 0.7);" : "position:absolute;left:50%;top:50%;", Q.setAttribute("style", n), t.append(Q)
                    } catch (t) {}
                }(r, n), t !== this.rzp && (e.parent() !== r[0] && r.append(e[0]), this.rzp = t), n ? (e.css("minHeight", "530px"), this.embedded = !0) : (r.css("display", "block").reflow(), ot(t.get("theme.backdrop_color")), /^rzp_t/.test(t.get("key")) && (lt.style.opacity = 1), this.setMetaAndOverflow()), this.bind(), this.onload()
            },
            makeMessage: function() {
                var t = this.rzp,
                    n = t.get(),
                    e = {
                        integration: E.props.integration,
                        referer: location.href,
                        options: n,
                        id: t.id
                    };
                return w(t.modal.options, function(t, e) {
                        n["modal." + t] = e
                    }), this.embedded && (delete n.parent, e.embedded = !0),
                    function(t) {
                        var e = t.image;
                        if (e && y(e)) {
                            if (Z.isBase64Image(e)) return;
                            if (e.indexOf("http")) {
                                var n = location.protocol + "//" + location.hostname + (location.port ? ":" + location.port : ""),
                                    r = "";
                                "/" !== e[0] && "/" !== (r += location.pathname.replace(/[^\/]*$/g, ""))[0] && (r = "/" + r), t.image = n + r + e
                            }
                        }
                    }(n), e
            },
            close: function() {
                ot(""), lt.style.opacity = 0,
                    function(t) {
                        t && w(t, function(t, e) {
                            R(e[0]).remove()
                        });
                        var e = it();
                        e && w(e, function(t, e) {
                            M("head").appendChild(e)
                        })
                    }(this.$metas), nt.overflow = rt.overflow, this.unbind(), u && scrollTo(0, rt.oldY)
            },
            bind: function() {
                if (!this.listeners) {
                    this.listeners = [];
                    var t = {};
                    u && (t.orientationchange = rt.orientationchange, this.rzp.get("parent") || (t.scroll = rt.scroll, t.resize = rt.resize)), w(t, function(t, e) {
                        this.listeners.push(R(window).on(t, e, null, this))
                    }, this)
                }
            },
            unbind: function() {
                var n;
                w(this.listeners, function(t, e) {
                    e.call(n)
                }, n), this.listeners = null
            },
            setMetaAndOverflow: function() {
                var n = M("head");
                n && (w(it(), function(t, e) {
                    R(e).remove()
                }), this.$metas = [R(document.createElement("meta")).attr({
                    name: "viewport",
                    content: "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
                }), R(document.createElement("meta")).attr({
                    name: "theme-color",
                    content: this.rzp.get("theme.color")
                })], w(this.$metas, function(t, e) {
                    n.appendChild(e[0])
                }), rt.overflow = nt.overflow, nt.overflow = "hidden", u && (rt.oldY = pageYOffset, window.scrollTo(0, 0), rt.orientationchange.call(this)))
            },
            postMessage: function(t) {
                t.id = this.rzp.id, t = x(t), this.el.contentWindow.postMessage(t, "*")
            },
            onmessage: function(t) {
                var e;
                try {
                    e = JSON.parse(t.data)
                } catch (t) {
                    return
                }
                var n = e.event,
                    r = this.rzp;
                t.origin && "frame" === e.source && t.source === this.el.contentWindow && (_("on" + n, this, e = e.data), "dismiss" !== n && "fault" !== n || E(r, n, e))
            },
            onload: function() {
                this.rzp && this.postMessage(this.makeMessage())
            },
            onfocus: function() {
                this.isFocused = !0
            },
            onblur: function() {
                this.isFocused = !1, rt.orientationchange.call(this)
            },
            onrender: function() {
                Q && (R(Q).remove(), Q = null)
            },
            onredirect: function(t) {
                Z.redirect(t)
            },
            onsubmit: function(n) {
                if ("wallet" === n.method) {
                    var r = this.rzp;
                    w(r.get("external.wallets"), function(t, e) {
                        if (e === n.wallet) try {
                            r.get("external.handler").call(r, n)
                        } catch (t) {
                            o("merc", t)
                        }
                    })
                }
            },
            ondismiss: function(t) {
                this.close(), _(this.rzp.get("modal.ondismiss"), 0, t)
            },
            onhidden: function() {
                this.afterClose(), _(this.rzp.get("modal.onhidden"))
            },
            oncomplete: function(t) {
                $('#flagPayment').val('success');
                this.close();
                var e = this.rzp;
                E(e, "checkout_success", t), _(function() {
                    _(this.get("handler"), this, t)
                }, e, null, 200)
            },
            onpaymenterror: function(t) {
                try {
                    // console.log(t);
                    this.rzp.emit("payment.error", t), this.rzp.emit("payment.failed", t)
                } catch (t) {}
            },
            onfailure: function(t) {
                this.ondismiss(), alert("Payment Failed.\n" + t.error.description), this.onhidden()
            },
            onfault: function(t) {
                this.rzp.close(), alert("Oops! Something went wrong.\n" + t), this.afterClose()
            },
            afterClose: function() {
                ct.style.display = "none"
            }
        }, Z.isCheckout = !0;
        var st, ct, ut, lt, ft, dt = document.currentScript || (st = document.getElementsByTagName("script"))[st.length - 1],
            ht = function(t) {
                var e = dt.parentNode,
                    n = document.createElement("div");
                n.innerHTML = C(t), e.appendChild(n), e.onsubmit = r, e.submit()
            };

        function pt() {
            var o = {};
            w(dt.attributes, function(t, e) {
                var n = e.name.toLowerCase();
                if (/^data-/.test(n)) {
                    var r = o;
                    n = n.replace(/^data-/, "");
                    var i = e.value;
                    "true" === i ? i = !0 : "false" === i && (i = !1), /^notes\./.test(n) && (o.notes || (o.notes = {}), r = o.notes, n = n.replace(/^notes\./, "")), r[n] = i
                }
            });
            var s, t, c, e = o.key;
            if (e && 0 < e.length) {
                dt.parentElement.action;
                o.handler = ht;
                var n = et(o);
                o.parent || (s = n, t = document.createElement("input"), c = dt.parentElement, t.type = "submit", t.value = s.get("buttontext"), t.className = "razorpay-payment-button", c.appendChild(t), c.onsubmit = function(t) {
                    t.preventDefault();
                    var e = c.action,
                        n = s.get();
                    if (y(e) && e && !n.callback_url && window.btoa) {
                        var r = {};
                        w(R(c).find("[name]"), function(t, e) {
                            r[e.name] = e.value
                        });
                        var i = {
                            url: e
                        };
                        "post" === c.method && (i.method = "post");
                        var o = c.target;
                        o && y(o) && (i.target = c.target), Object.keys(r).length && (i.content = r);
                        try {
                            var a = btoa(x({
                                request: i,
                                options: x(n),
                                back: location.href
                            }));
                            n.callback_url = $("checkout/onyx") + "?data=" + a
                        } catch (t) {}
                    }
                    return s.open(), !1
                })
            }
        }

        function mt(t) {
            return ft ? ft.openRzp(t) : (ft = new at(t), R(window).on("message", k("onmessage", ft)), ct.appendChild(ft.el)), ft
        }
        et.open = function(t) {
            return et(t).open()
        }, U.postInit = function() {
            this.modal = {
                options: t
            }, this.get("parent") && this.open()
        };
        var vt = U.onNew;
        U.onNew = function(t, e) {
            "payment.error" === t && E(this, "event_paymenterror", location.href), g(vt) && vt.call(this, t, e)
        }, U.open = B(function() {
            var t = this.checkoutFrame = mt(this);
            return E(this, "open"), t.el.contentWindow || (t.close(), t.afterClose(), alert("This browser is not supported.\nPlease try payment in another browser.")), "-new.js" === dt.src.slice(-7) && E(this, "oldscript", location.href), this
        }), U.close = function() {
            var t = this.checkoutFrame;
            t && t.postMessage({
                event: "close"
            })
        }, B(function() {
            ct = function() {
                var t = document.createElement("div");
                t.className = "razorpay-container", t.innerHTML = "<style>@keyframes rzp-rot{to{transform: rotate(360deg);}}@-webkit-keyframes rzp-rot{to{-webkit-transform: rotate(360deg);}}</style>";
                var n = t.style;
                return w({
                    zIndex: 1e9,
                    position: "fixed",
                    top: 0,
                    display: "none",
                    left: 0,
                    height: "100%",
                    width: "100%",
                    "-webkit-overflow-scrolling": "touch",
                    "-webkit-backface-visibility": "hidden",
                    "overflow-y": "visible"
                }, function(t, e) {
                    n[t] = e
                }), L.appendChild(t), t
            }(), ut = function() {
                var t = document.createElement("div");
                t.className = "razorpay-backdrop";
                var n = t.style;
                return w({
                    "min-height": "100%",
                    transition: "0.3s ease-out",
                    "-webkit-transition": "0.3s ease-out",
                    "-moz-transition": "0.3s ease-out",
                    position: "fixed",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%",
                    filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#96000000, endColorstr=#96000000)"
                }, function(t, e) {
                    n[t] = e
                }), ct.appendChild(t), t
            }(), lt = function() {
                var t = document.createElement("span");
                t.target = "_blank", t.href = "", t.innerHTML = "Test Mode";
                var n = t.style,
                    e = "opacity 0.3s ease-in",
                    r = "rotate(45deg)";
                return w({
                    "text-decoration": "none",
                    background: "#D64444",
                    border: "1px dashed white",
                    padding: "3px",
                    opacity: "0",
                    "-webkit-transform": r,
                    "-moz-transform": r,
                    "-ms-transform": r,
                    "-o-transform": r,
                    transform: r,
                    "-webkit-transition": e,
                    "-moz-transition": e,
                    transition: e,
                    "font-family": "lato,ubuntu,helvetica,sans-serif",
                    color: "white",
                    position: "absolute",
                    width: "200px",
                    "text-align": "center",
                    right: "-50px",
                    top: "50px"
                }, function(t, e) {
                    n[t] = e
                }), ut.appendChild(t), t
            }(), ft = mt();
            try {
                pt()
            } catch (t) {}
        })()
    }()
}();