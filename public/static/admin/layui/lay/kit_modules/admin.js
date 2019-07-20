/** kitadmin-v2.1.0 MIT License By http://kit.zhengjinfan.cn Author Van Zheng */
;"use strict";
var mods = ["element", "sidebar", "mockjs", "select", "tabs", "menu", "route", "utils", "component", "kit"];
layui.define(mods, function (e) {
    layui.element;
    var t = layui.utils, a = layui.jquery, n = (layui.lodash, layui.route), i = layui.tabs, l = layui.layer,
        o = layui.menu, m = layui.component, s = layui.kit, p = function () {
            this.config = {elem: "#app", loadType: "SPA"}, this.version = "1.0.0"
        };
    p.prototype.ready = function (e) {
        var i = this.config, o = (0, t.localStorage.getItem)("KITADMIN_SETTING_LOADTYPE");
        null !== o && void 0 !== o.loadType && (i.loadType = o.loadType), s.set({type: i.loadType}).init(), u.routeInit(i), u.menuInit(i), "TABS" === i.loadType && u.tabsInit(), "" === location.hash && t.setUrlState("主页", "#/"), layui.sidebar.render({
            elem: "#ccleft",
            title: "这是左侧打开的栗子",
            shade: !0,
            direction: "left",
            dynamicRender: !0,
            ///url: "/admin/index/teble2",
            width: "50%"
        }), a("#cc").on("click", function () {
            layui.sidebar.render({
                elem: this,
                title: "这是表单盒子",
                shade: !0,
                dynamicRender: !0,
                url: "views/form/index.html",
                width: "50%"
            })
        }), m.on("nav(header_right)", function (e) {
            var t = e.elem.attr("kit-target");
            "setting" === t && layui.sidebar.render({
                elem: e.elem,
                title: "设置",
                shade: !0,
                dynamicRender: !0,
                url: "views/setting.html"
            }), "help" === t && l.alert("")
        }), layui.mockjs.inject(APIs), "SPA" === i.loadType && n.render(), "function" == typeof e && e()
    };

    var u = {
        routeInit: function (e) {
            var t = this, a = {
                routes: [
                    {path: "/", component: '/admin/index/app',name: '首页'},
                    {path: "/app/unit", component: "/admin/index/general", name:'插件'},
                    {path: "/app/users", component: "/admin/index/users", name:'人员'},
                    {path: "/app/approval", component: "/admin/index/approval", name:'审批'},
                    {path: "/app/procurement", component: "/admin/index/procurement", name:'采购审批'},
                    {path: "/app/salary", component: "/admin/index/salary", name:'工资'},
                    {path: "/app/reimbursement", component: "/admin/index/reimbursement", name:'通用报销'},
                    {path: "/app/paytype", component: "/admin/index/paytype", name:'财务报销支付类型'},
                    {path: "/app/admin", component: "/admin/index/admin", name:'后台管理人员'},
                    {path: "/app/user", component: "/admin/index/user", name:'后台管理人员'},
                    {path: "/app/user_log", component: "/admin/index/user_log", name:'行为日志'},
               ]
            };

            return "TABS" === e.loadType && (a.onChanged = function () {
                i.existsByPath(location.hash) ? i.switchByPath(location.hash) : t.addTab(location.hash, (new Date).getTime())
            }), n.setRoutes(a), this
        }, addTab: function (e, t) {
            var a = n.getRoute(e);
            a && i.add({id: t, title: a.name, path: e, component: a.component, rendered: !1, icon: "&#xe62e;"})
        }, menuInit: function (e) {
            var t = this;
            return o.set({
                dynamicRender: !1, isJump: "SPA" === e.loadType, onClicked: function (a) {
                    if ("TABS" === e.loadType && !a.hasChild) {
                        var n = a.data, i = n.href, l = n.layid;
                        t.addTab(i, l)
                    }
                }, elem: "#menu-box", remote: {url: "/api/getmenus", method: "post"}, cached: !1
            }).render(), t
        }, tabsInit: function () {
            i.set({
                onChanged: function (e) {
                }
            }).render(function (e) {
                e.isIndex && n.render("#/")
            })
        }
    };
    (new p).ready(function () {
        console.log("Init successed.")
    }), e("admin", {})
});
