webpackJsonp([0],{"/ER6":function(t,e){},"0Iqu":function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"loadmore":"加载更多"},"en":{"loadmore":"Load more"}}'),delete t.options._Ctor}},"0xDb":function(t,e,n){"use strict";e.c=function(t){return t.replace(/\B(?=(?:\d{4})+$)/g," ")},e.b=function(t){return String(t).replace(/\B(?=(?:\d{3})+$)/g,",")},e.a=function(){var t=new Date,e=t.getDate(),n=t.getMonth()+1,i=t.getFullYear();e<10&&(e="0"+e);n<10&&(n="0"+n);return t=n+"/"+e+"/"+i,new Date(t)}},"5zde":function(t,e,n){n("zQR9"),n("qyJz"),t.exports=n("FeBl").Array.from},"6gxQ":function(t,e,n){"use strict";var i=n("XyMi"),o={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("ul",{staticClass:"list-reset navbar-menu"},[n("li",{class:["navbar-menu-item",{"is-active":t.isActive("feed")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/feed"}},[n("my-svg-icon",{attrs:{"icon-class":"home"}}),t._v(" "),n("span",[t._v(t._s(t.$t("home")))])],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("follow")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/follow"}},[n("my-svg-icon",{attrs:{"icon-class":"team"}}),t._v(" "),n("span",[t._v(t._s(t.$t("follow")))])],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("order")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/order"}},[n("my-svg-icon",{attrs:{"icon-class":"snippets"}}),t._v(" "),n("span",[t._v(t._s(t.$t("order")))])],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("message")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/message"}},[n("el-badge",{attrs:{hidden:!t.hasUnreadMessages,"is-dot":""}},[n("my-svg-icon",{attrs:{"icon-class":"message"}})],1),t._v(" "),n("span",[t._v(t._s(t.$t("message")))])],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("notification")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/notification"}},[n("el-badge",{attrs:{hidden:!t.hasUnreadNotifications,"is-dot":""}},[n("my-svg-icon",{attrs:{"icon-class":"bell"}})],1),t._v(" "),n("span",[t._v(t._s(t.$t("notification")))])],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("me")},"border-right"]},[n("el-dropdown",{attrs:{trigger:"click"},on:{command:t.onClickDropdownItem}},[n("div",{staticClass:"navbar-menu-item__container"},[n("my-avatar",{staticClass:"avatar",attrs:{"avatar-url":t.avatarUrl}}),t._v(" "),n("span",[t._v(t._s(t.$t("me"))),n("i",{staticClass:"el-icon-caret-bottom ml-4"})])],1),t._v(" "),n("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[n("el-dropdown-item",{attrs:{command:"me"}},[t._v("个人中心")]),t._v(" "),n("el-dropdown-item",{attrs:{command:"signout",divided:""}},[t._v("退出登录")])],1)],1)],1),t._v(" "),n("li",{class:["navbar-menu-item",{"is-active":t.isActive("search")}]},[n("router-link",{staticClass:"navbar-menu-item__container",attrs:{to:"/search"}},[n("my-svg-icon",{attrs:{"icon-class":"appstore"}}),t._v(" "),n("span",[t._v(t._s(t.$t("square")))])],1)],1)])},staticRenderFns:[]};var r=function(t){n("NhW3"),n("TFZW")},s=Object(i.a)({computed:{avatarUrl:function(){return this.$store.getters.userInfo.avatar_url},hasUnreadNotifications:function(){return!!this.$store.getters.userInfo.notification_count},hasUnreadMessages:function(){return!!this.$store.getters.userInfo.unread_message_count}},methods:{isActive:function(t){return this.$route.name===t},onClickDropdownItem:function(t){var e=this;"signout"===t&&this.$store.dispatch("SIGN_OUT").then(function(){e.$router.replace({path:"/"})}),"me"===t&&this.$router.push({path:"/me"})}}},o,!1,r,"data-v-3889d360",null),a=n("G0iC");a&&a.__esModule&&(a=a.default),"function"==typeof a&&a(s);var c={components:{NavbarMenu:s.exports},data:function(){return{keyword:""}},computed:{isRoot:function(){return"/"===this.$route.path},isSignIn:function(){return this.$store.getters.isSignIn}},methods:{onInputKeyword:function(t,e){e(t?[{value:t,type:"project",label:this.$t("项目")},{value:t,type:"designer",label:this.$t("设计师")}]:[])},onSelectItem:function(t){t.value&&this.$router.push({path:"/search",query:{type:t.type,keyword:t.value}})}}},l={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("el-menu",{staticClass:"navbar",attrs:{mode:"horizontal","background-color":"#283E4A"}},[n("icon",{attrs:{name:"app-logo"},nativeOn:{click:function(e){t.$router.push("/")}}}),t._v(" "),t.isRoot?n("div",{class:{links:!t.isSignIn}},[n("router-link",{attrs:{to:"/signin?type=designer"}},[n("el-button",{staticClass:"line-btn ml-12",attrs:{plain:""}},[t._v(t._s(t.$t("设计师入口")))])],1),t._v(" "),n("router-link",{attrs:{to:"/signin?type=party"}},[n("el-button",{staticClass:"line-btn ml-24",attrs:{plain:""}},[t._v(t._s(t.$t("业主入口")))])],1)],1):n("el-autocomplete",{staticClass:"search",attrs:{"fetch-suggestions":t.onInputKeyword,placeholder:t.$t("搜索项目、设计师"),"popper-class":"search-popover","select-when-unmatched":""},on:{select:t.onSelectItem},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.item;return[n("span",{staticClass:"search__value"},[t._v(t._s(i.value))]),t._v(" "),n("el-tag",{attrs:{type:"info"}},[t._v(t._s(i.label)+"↵ ")])]}}]),model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}}),t._v(" "),t.isSignIn?n("navbar-menu",{staticClass:"links"}):t.isRoot?t._e():n("div",{staticClass:"links"},[n("router-link",{attrs:{to:"/signin"}},[n("el-button",{staticClass:"signin-btn",attrs:{type:"text"}},[t._v(t._s(t.$t("登录")))])],1),t._v(" "),n("router-link",{attrs:{to:"/signup"}},[n("el-button",{staticClass:"line-btn signup-btn",attrs:{plain:""}},[t._v(t._s(t.$t("马上加入")))])],1)],1)],1)},staticRenderFns:[]};var u=function(t){n("cR5j"),n("oA8J")},p=Object(i.a)(c,l,!1,u,"data-v-0bea7051",null),d=n("qUWd");d&&d.__esModule&&(d=d.default),"function"==typeof d&&d(p);var v=p.exports,f=n("PByF");n.d(e,"b",function(){return v}),n.d(e,"a",function(){return f.a})},E4LH:function(t,e,n){"use strict";n.d(e,"c",function(){return i}),n.d(e,"d",function(){return o}),n.d(e,"a",function(){return r}),n.d(e,"b",function(){return s});var i=function(t){return/^[a-z0-9](?:[-_.+]?[a-z0-9]+)*@[a-z0-9]+\.com$/i.test(t)},o=function(t){return/^1[34578]\d{9}$/.test(t)},r=/^(?:[\u4e00-\u9fa5]+)(?:·[\u4e00-\u9fa5]+)*$|^[a-zA-Z0-9]+\s?[\.·\-()a-zA-Z]*[a-zA-Z]+$/,s=/^1[34578]\d{9}$/},EMlb:function(t,e,n){"use strict";e.p=function(t){return r.b.post("/projects",t)},e.m=function(t){return r.b.get("/projects/"+t)},e.t=function(t,e){return r.b.patch("/projects/"+t,e)},e.v=function(t,e){return r.b.patch("/projects/"+t+"/remittance",{remittance:e})},e.u=function(t,e){var n=arguments.length>2&&void 0!==arguments[2]&&arguments[2];return r.b.patch("/projects/"+t+"/payment",{payment_remark:e,mark_as_completed:n})},e.d=function(t){return r.b.put("/user/canceled/projects/"+t)},e.g=function(t){return r.b.put("/user/favoriting/projects/"+t)},e.r=function(t){return r.b.delete("/user/favoriting/projects/"+t)},e.b=function(t,e){return r.b.post("/projects/"+t+"/applications",e)},e.c=function(t){return r.b.delete("/user/applying/projects/"+t)},e.a=function(t){return r.b.put("/projects/"+t+"/accepted/invitations")},e.e=function(t,e){return r.b.put("/projects/"+t+"/declined/invitations",{refusal_cause:e})},e.n=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return r.b.get("/user/projects?page="+t,{params:e})},e.l=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return r.b.get("/user/processing/projects?page="+t,{params:e})},e.j=function(t,e){return r.b.get("/user/favoriting/projects?page="+t,{params:e})},e.o=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;return r.b.get("/user/recommended/projects?page="+t)},e.q=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return r.b.get("/projects",{params:o()({page:t},e)})},e.h=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return r.b.get("/projects/"+t+"/applications?page="+e)},e.k=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return r.b.get("/projects/"+t+"/invitations?page="+e)},e.f=function(t,e){return r.b.post("/projects/"+t+"/deliveries",e)},e.s=function(t,e){return r.b.patch("/deliveries/"+t,e)},e.i=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return r.b.get("/projects/"+t+"/deliveries?page="+e)};var i=n("Dd8w"),o=n.n(i),r=n("vLgD")},G0iC:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"home":"主页","follow":"关注","order":"订单","message":"消息","notification":"通知","me":"我","signOut":"退出登录","square":"项目广场"},"en":{"home":"Home","follow":"Follow","order":"Order","message":"Message","notification":"Notice","me":"Me","signOut":"Sign out","square":"Square"}}'),delete t.options._Ctor}},GUlH:function(t,e,n){"use strict";e.a=function(t){return i.b.post("/works",t)},e.h=function(t,e){return i.b.patch("/works/"+t,e)},e.c=function(t){return i.b.get("/works/"+t)},e.e=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/users/"+t+"/works?page="+e)},e.b=function(t){return i.b.delete("/works/"+t)},e.d=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;return i.b.get("/works?page="+t)},e.f=function(t){return i.b.post("/works/"+t+"/likes")},e.g=function(t){return i.b.delete("/works/"+t+"/likes")};var i=n("vLgD")},Gu7T:function(t,e,n){"use strict";e.__esModule=!0;var i,o=n("c/Tr"),r=(i=o)&&i.__esModule?i:{default:i};e.default=function(t){if(Array.isArray(t)){for(var e=0,n=Array(t.length);e<t.length;e++)n[e]=t[e];return n}return(0,r.default)(t)}},MeGD:function(t,e){},NhW3:function(t,e){},ORdI:function(t,e,n){"use strict";e.d=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/users/"+t+"/reviews",{params:{page:e,include:"reviewer"}})},e.c=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/users/"+t+"/reviews",{params:{page:e,type:"posted",include:"user"}})},e.e=function(t){return i.b.post("/user/reviews/users",{invited_user_id:t})},e.f=function(t,e){return i.b.post("/users/"+t+"/reviews",{content:e})},e.b=function(t){return i.b.delete("/reviews/"+t)},e.g=function(t){return i.b.put("/reviews/"+t+"/stick")},e.h=function(t){return i.b.put("/reviews/"+t+"/unstick")},e.a=function(t){return i.b.get("/user/can_review?uid="+t)};var i=n("vLgD")},PByF:function(t,e,n){"use strict";var i=n("XyMi"),o={components:{LangSelect:n("vRPQ").a}},r={render:function(){var t=this.$createElement,e=this._self._c||t;return e("footer",[this._v("copyright © "+this._s(this.$t("app.cpr"))+" "),e("lang-select")],1)},staticRenderFns:[]};var s=function(t){n("TeD8")},a=Object(i.a)(o,r,!1,s,"data-v-3ea5b4bb",null);e.a=a.exports},QfiH:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"reply":"回复","post":"评论","cancel":"取消","delete":"删除","notice":"提示","noticeMessage":"此评论将被永久删除，是否确认？","confirm":"确定","successfulDeleted":"删除成功","getReplies":"共 {count} 条回复","getMoreReplies":"更多 {count} 条回复"},"en":{"reply":"Reply","post":"Post","cancel":"Cancel","delete":"Delete","notice":"Notice","noticeMessage":"This reply will be deleted forever. Is it confirmed?","confirm":"Confirm","successfulDeleted":"Successful Deleted","getReplies":"Total {count} replies","getMoreReplies":"More {count} replies"}}'),delete t.options._Ctor}},SPey:function(t,e){},SaQS:function(t,e,n){"use strict";var i=n("XyMi"),o=n("Ty/O"),r=n("Gu7T"),s=n.n(r),a=n("woOf"),c=n.n(a),l={name:"ReplyListItem",props:{activity:{type:Object,default:function(){return{}}},reply:{type:Object,default:function(){return{id:0,activity_id:0,reply_id:null,content:"",created_at:"",replyee:{},user:{}}}}},data:function(){return{showInput:!1,content:"",buttonLoading:!1,replies:[],currentPage:1,listLoading:!1}},computed:{isDeletable:function(){var t=this.$store.getters.uid;return t===this.reply.user_id||t===this.activity.user_id},getRepliesText:function(){var t=this.reply.reply_count-this.replies.length;return this.replies.length?this.$t("getMoreReplies",{count:t}):this.$t("getReplies",{count:t})}},methods:{getReplies:function(){var t=this;this.listLoading=!0,Object(o.g)(this.reply.id,this.currentPage).then(function(e){var n=e.data,i=n.data,o=n.meta.pagination;t.listLoading=!1,t.mergeReplies(i),t.reply.reply_count=o.total,t.currentPage++}).catch(function(){t.listLoading=!1})},onShowInput:function(){var t=this;this.showInput=!0,this.$nextTick(function(){return t.$refs.input.focus()})},onPost:function(){var t=this;this.buttonLoading=!0,Object(o.j)(this.reply.activity_id,{content:this.content,reply_id:this.reply.id}).then(function(e){var n=e.data;t.reply.reply_id?(c()(t.$data,t.$options.data()),t.$emit("post",n)):(t.content="",t.reply.reply_count++,t.mergeReplies(n,!0),t.buttonLoading=!1,t.showInput=!1)}).catch(function(){t.buttonLoading=!1})},onDelete:function(){var t=this;this.$confirm(this.$t("noticeMessage"),this.$t("notice"),{confirmButtonText:this.$t("confirm"),cancelButtonText:this.$t("cancel"),type:"warning"}).then(function(){var e=t.reply,n=e.id,i=e.activity_id;Object(o.b)(i,n).then(function(){t.$emit("delete"),t.$message.success(t.$t("successfulDeleted"))})}).catch(function(){})},onPostInList:function(t){this.mergeReplies(t,!0),this.reply.reply_count++},onDeleteInList:function(t){this.replies.splice(t,1),this.reply.reply_count--},mergeReplies:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];t=Array.isArray(t)?[].concat(s()(t)):[t],e?(t=this.filterReplies(t),this.replies=[].concat(s()(t),s()(this.replies))):(t=this.filterReplies(t),this.replies=[].concat(s()(this.replies),s()(t)))},filterReplies:function(t){var e=this;return t.filter(function(t){return!e.replies.some(function(e){return e.id===t.id})})}}},u={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"reply-list-item"},[n("router-link",{attrs:{to:"/profile?uid="+t.reply.user.id}},[n("my-avatar",{staticClass:"reply-list-item__avatar",attrs:{"avatar-url":t.reply.user.avatar_url}})],1),t._v(" "),n("div",{staticClass:"reply-list-item__content"},[n("p",{staticClass:"m0 f-14"},[n("router-link",{attrs:{to:"/profile?uid="+t.reply.user.id}},[n("span",{staticClass:"bold black"},[t._v(t._s(t.reply.user.name))])]),t._v(" "),n("span",{staticClass:"black-45",domProps:{textContent:t._s(t.reply.user.title)}}),t._v(" "),t.reply.reply_id?[n("span",{staticClass:"black-45"},[t._v(" "+t._s(t.$t("reply"))+"  ")]),t._v(" "),n("router-link",{attrs:{to:"/profile?uid="+t.reply.replyee.id}},[n("span",{staticClass:"bold black"},[t._v(t._s(t.reply.replyee.name))])])]:t._e()],2),t._v(" "),n("p",{staticClass:"m0 f-12 black-45",domProps:{textContent:t._s(t.reply.created_at)}}),t._v(" "),n("p",{staticClass:"reply-list-item__content-text"},[t._v(t._s(t.reply.content))]),t._v(" "),t.showInput?[n("el-input",{ref:"input",staticClass:"reply-list-item__content-input",attrs:{maxlength:200,placeholder:t.$t("reply")+" "+t.reply.user.name,autosize:{maxRows:5},size:"small",type:"textarea",rows:"1",resize:"none"},model:{value:t.content,callback:function(e){t.content=e},expression:"content"}}),t._v(" "),n("div",{staticClass:"right-align"},[n("el-button",{attrs:{loading:t.buttonLoading,disabled:!t.content.length,size:"small",type:"primary"},on:{click:t.onPost}},[t._v(t._s(t.$t("post")))]),t._v(" "),n("el-button",{attrs:{size:"small",type:"text"},on:{click:function(e){t.showInput=!1}}},[t._v(t._s(t.$t("cancel")))])],1)]:n("div",{staticClass:"reply-list-item__content-button-area right-align"},[n("el-button",{attrs:{type:"text",size:"mini"},on:{click:t.onShowInput}},[t._v(t._s(t.$t("reply")))]),t._v(" "),t.isDeletable?n("el-button",{attrs:{size:"mini",type:"text"},on:{click:t.onDelete}},[t._v(t._s(t.$t("delete")))]):t._e()],1),t._v(" "),!t.reply.reply_id&&t.reply.reply_count?n("div",{staticClass:"reply-list-item__reply-list"},[t._l(t.replies,function(e,i){return n("reply-list-item",{key:e.id,attrs:{reply:e,activity:t.activity},on:{post:t.onPostInList,delete:function(e){t.onDeleteInList(i)}}})}),t._v(" "),t.reply.reply_count>t.replies.length?n("el-button",{attrs:{loading:t.listLoading,size:"mini",type:"text"},on:{click:t.getReplies}},[t._v(t._s(t.getRepliesText))]):t._e()],2):t._e()],2)],1)},staticRenderFns:[]};var p=function(t){n("MeGD")},d=Object(i.a)(l,u,!1,p,"data-v-ef3b630c",null),v=n("QfiH");v&&v.__esModule&&(v=v.default),"function"==typeof v&&v(d);var f={components:{ReplyListItem:d.exports},props:{activity:{type:Object,default:function(){return{}}}},data:function(){return{buttonLoading:!1,content:"",replies:[],currentPage:1,pageCount:0,listLoading:!1}},computed:{user:function(){return this.$store.getters.userInfo}},created:function(){this.getReplies()},methods:{onPost:function(){var t=this;this.content&&(this.buttonLoading=!0,Object(o.j)(this.activity.id,{content:this.content}).then(function(e){var n=e.data;t.buttonLoading=!1,t.content="",t.replies.unshift(n)}).catch(function(){t.buttonLoading=!1}))},getReplies:function(t){var e=this;this.listLoading=!0,this.replies=[],Object(o.f)(this.activity.id,t).then(function(t){var n=t.data,i=n.data,o=n.meta.pagination;e.listLoading=!1,e.replies=i,e.pageCount=o.total_pages}).catch(function(){e.listLoading=!1})},onDelete:function(t){this.replies.splice(t,1)}}},_={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"reply-input-area"},[n("my-avatar",{staticClass:"reply-input-area__avatar",attrs:{"avatar-url":t.user.avatar_url}}),t._v(" "),n("el-input",{staticClass:"reply-input-area__input",attrs:{maxlength:200,placeholder:t.$t("placeholder"),autosize:{maxRows:5},size:"small",type:"textarea",rows:"1",resize:"none"},model:{value:t.content,callback:function(e){t.content=e},expression:"content"}}),t._v(" "),n("el-button",{staticClass:"reply-input-area__button",attrs:{loading:t.buttonLoading,size:"small",type:"primary"},on:{click:t.onPost}},[t._v(t._s(t.$t("post")))])],1),t._v(" "),n("div",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],staticClass:"reply-list-area"},[t.replies.length?[t._l(t.replies,function(e,i){return n("reply-list-item",{key:e.id,attrs:{reply:e,activity:t.activity},on:{delete:function(e){t.onDelete(i)}}})}),t._v(" "),n("el-pagination",{staticStyle:{"text-align":"center",padding:"16px 0"},attrs:{"current-page":t.currentPage,"page-count":t.pageCount,background:"",layout:"prev, pager, next"},on:{"update:currentPage":function(e){t.currentPage=e},"current-change":t.getReplies}})]:n("div",{directives:[{name:"t",rawName:"v-t",value:t.$t("noReply"),expression:"$t('noReply')"}],staticClass:"p2 center black-25 f-14"})],2)])},staticRenderFns:[]};var g=function(t){n("hsQw")},h=Object(i.a)(f,_,!1,g,"data-v-44396698",null),m=n("Vkbf");m&&m.__esModule&&(m=m.default),"function"==typeof m&&m(h);var y=h.exports,b=n("0xDb"),w={components:{ReplyList:y},props:{activity:{type:Object,default:function(){return{id:0,content:"",photo_urls:[],like_count:0,reply_count:0,created_at:"",liked:!1,user:{id:"",avatar_url:"",name:"",follower_count:0,following:!1}}}},showActionButton:{type:Boolean,default:!1},defaultShowReplyList:{type:Boolean,default:!1}},data:function(){return{followBtnLoading:!1,unfollowBtnLoading:!1,showReplyList:!1}},computed:{user:function(){return this.activity.user},splittedFollowerCount:function(){return Object(b.b)(this.activity.user.follower_count)},likeCountStr:function(){return this.activity.like_count?" ("+this.activity.like_count+")":""},replyCountStr:function(){return this.activity.reply_count?" ("+this.activity.reply_count+")":""}},created:function(){this.showReplyList=this.defaultShowReplyList},methods:{onToggleLike:function(){var t=this,e=this.activity.liked;(e?o.l:o.i)(this.activity.id).then(function(n){var i=n.data.like_count;t.activity.liked=!e,t.activity.like_count=i})},onFollow:function(){var t=this;this.followBtnLoading=!0,this.$store.dispatch("FOLLOW",this.user.id).then(function(){t.followBtnLoading=!1,t.user.following=!0,t.user.follower_count++,t.$emit("follow",t.user.id)}).catch(function(){t.followBtnLoading=!1})},onUnfollow:function(){var t=this;this.$store.dispatch("UNFOLLOW",this.user.id).then(function(){t.activity.user.following=!1,t.user.follower_count--,t.$emit("unfollow",t.user.id)})},onClickCommand:function(t){var e=this;"unfollow"===t&&this.onUnfollow(),"delete"===t&&this.$confirm(this.$t("确认删除该动态？"),this.$t("g.notice"),{confirmButtonText:this.$t("g.confirmBtn"),cancelButtonText:this.$t("g.cancelBtn"),type:"warning"}).then(function(){Object(o.a)(e.activity.id).then(function(){e.$emit("deleted",e.activity),e.$message.success(e.$t("g.successfullyDeleted"))})}).catch(function(){})}}},k={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"card activity-card"},[n("div",{staticClass:"activity-card__header"},[n("router-link",{attrs:{to:"/profile?uid="+t.user.id}},[n("my-avatar",{staticClass:"activity-card__header-avatar",attrs:{"avatar-url":t.user.avatar_url}})],1),t._v(" "),n("div",{staticClass:"activity-card__header-text"},[n("router-link",{attrs:{to:"/profile?uid="+t.user.id}},[n("p",{staticClass:"m0 f-15 bold black inline-block"},[t._v(t._s(t.user.name))])]),t._v(" "),n("p",{staticClass:"m0 f-13 bold black-65"},[t._v(t._s(t.splittedFollowerCount+" "+t.$t("g.follower")))]),t._v(" "),n("p",{staticClass:"m0 f-13 bold black-65"},[t._v(t._s(t.$t("g.published_at")+" "+t.activity.created_at))])],1),t._v(" "),t.showActionButton?[t.$uid()==t.user.id?n("el-dropdown",{attrs:{trigger:"click"},on:{command:t.onClickCommand}},[n("el-button",{staticClass:"activity-card__header-cancel-follow-button",attrs:{type:"text",icon:"el-icon-arrow-down"}}),t._v(" "),n("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[n("el-dropdown-item",{attrs:{command:"delete"}},[t._v(t._s(t.$t("删除")))])],1)],1):t.user.following?n("el-dropdown",{attrs:{trigger:"click"},on:{command:t.onClickCommand}},[n("el-button",{staticClass:"activity-card__header-cancel-follow-button",attrs:{type:"text",icon:"el-icon-arrow-down"}}),t._v(" "),n("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[n("el-dropdown-item",{attrs:{command:"unfollow"}},[t._v(t._s(t.$t("g.cancelFollow")))])],1)],1):n("el-button",{staticClass:"activity-card__header-follow-button",attrs:{loading:t.followBtnLoading,plain:"",round:"",type:"primary",size:"small"},on:{click:t.onFollow}},[t._v(t._s(t.$t("g.follow")))])]:t._e()],2),t._v(" "),n("div",{staticClass:"activity-card__content"},[n("p",{staticClass:"activity-card__content-text"},[t._v(t._s(t.activity.content))]),t._v(" "),t.activity.photo_urls.length?n("el-carousel",{staticClass:"activity-card__content-carousel",attrs:{autoplay:!1,trigger:"click",height:"250px"}},t._l(t.activity.photo_urls,function(e,i){return n("el-carousel-item",{key:e,nativeOn:{click:function(e){t.$emit("preview",{urls:t.activity.photo_urls,index:i})}}},[n("img",{staticClass:"activity-card__content-carousel-item",attrs:{src:e}})])})):t._e(),t._v(" "),n("p")],1),t._v(" "),n("my-divider"),t._v(" "),n("div",{staticClass:"activity-card__action-btns"},[n("el-button",{class:{"is-liked":t.activity.liked},attrs:{type:"text"},on:{click:t.onToggleLike}},[t._v(t._s(t.$t("g.like")+t.likeCountStr))]),t._v(" "),n("el-button",{attrs:{type:"text"},on:{click:function(e){t.showReplyList=!t.showReplyList}}},[t._v(t._s(t.$t("g.comment")+t.replyCountStr))])],1),t._v(" "),t.showReplyList?n("reply-list",{staticClass:"activity-card__reply-list",attrs:{activity:t.activity}}):t._e()],1)},staticRenderFns:[]};var C=function(t){n("SPey")},$=Object(i.a)(w,k,!1,C,"data-v-05f5582c",null),R=n("hkRs");R&&R.__esModule&&(R=R.default),"function"==typeof R&&R($);e.a=$.exports},TFZW:function(t,e){},TeD8:function(t,e){},"Ty/O":function(t,e,n){"use strict";e.e=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;return i.b.get("/user/feeds?page="+t)},e.c=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/users/"+t+"/activities?page="+e)},e.h=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;return i.b.get("/activities/trending?page="+t)},e.k=function(t){return i.b.post("/activities",t)},e.d=function(t){return i.b.get("/activities/"+t)},e.a=function(t){return i.b.delete("/activities/"+t)},e.i=function(t){return i.b.post("/activities/"+t+"/likes")},e.l=function(t){return i.b.delete("/activities/"+t+"/likes")},e.j=function(t,e){return i.b.post("/activities/"+t+"/replies",e)},e.f=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/activities/"+t+"/replies?page="+e)},e.g=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;return i.b.get("/replies/"+t+"/replies?page="+e)},e.b=function(t,e){return i.b.delete("/activities/"+t+"/replies/"+e)};var i=n("vLgD")},Vkbf:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"post":"发表","placeholder":"说点什么吧~","noReply":"暂无评论"},"en":{"post":"Post","placeholder":"Reply something","noReply":"No replies"}}'),delete t.options._Ctor}},"c/Tr":function(t,e,n){t.exports={default:n("5zde"),__esModule:!0}},cR5j:function(t,e){},fBQ2:function(t,e,n){"use strict";var i=n("evD5"),o=n("X8DO");t.exports=function(t,e,n){e in t?i.f(t,e,o(0,n)):t[e]=n}},hkRs:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"确认删除该动态？":"Is it confirmed to delete the activity?","删除":"Delete"}}'),delete t.options._Ctor}},hsQw:function(t,e){},oA8J:function(t,e){},qUWd:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"搜索项目、设计师":"Search projects or designers","登录":"Sign in","马上加入":"Sign up","项目":"Project","设计师":"Designer","业主入口":"Party","设计师入口":"Designer"}}'),delete t.options._Ctor}},qyJz:function(t,e,n){"use strict";var i=n("+ZMJ"),o=n("kM2E"),r=n("sB3e"),s=n("msXi"),a=n("Mhyx"),c=n("QRG4"),l=n("fBQ2"),u=n("3fs2");o(o.S+o.F*!n("dY0y")(function(t){Array.from(t)}),"Array",{from:function(t){var e,n,o,p,d=r(t),v="function"==typeof this?this:Array,f=arguments.length,_=f>1?arguments[1]:void 0,g=void 0!==_,h=0,m=u(d);if(g&&(_=i(_,f>2?arguments[2]:void 0,2)),void 0==m||v==Array&&a(m))for(n=new v(e=c(d.length));e>h;h++)l(n,h,g?_(d[h],h):d[h]);else for(p=m.call(d),n=new v;!(o=p.next()).done;h++)l(n,h,g?s(p,_,[o.value,h],!0):o.value);return n.length=h,n}})},vjYN:function(t,e,n){"use strict";n.d(e,"a",function(){return i}),n.d(e,"b",function(){return o});var i={STATUS_CANCELED:500,STATUS_REVIEW_FAILED:600,STATUS_REVIEWING:900,STATUS_TENDERING:1e3,STATUS_WORKING:1100,STATUS_COMPLETED:1200},o={STATUS_NOT_VIEWED:0,STATUS_ACCEPTED:1,STATUS_DECLINED:2}},xoXj:function(t,e,n){"use strict";var i=n("XyMi"),o={components:{ActivityCard:n("SaQS").a},props:{showActionButton:{type:Boolean,default:!1},getActivities:{type:Function,required:!0}},data:function(){return{activities:[],currentPage:0,loading:!1,nomore:!1,error:!1}},computed:{busy:function(){return this.loading||this.nomore||this.error}},methods:{onReachBottom:function(){var t=this;return this.loading=!0,this.getActivities(this.currentPage+1).then(function(e){var n=e.data,i=n.data,o=n.meta.pagination;t.loading=!1,t.activities.push.apply(t.activities,i),t.currentPage=o.current_page,t.nomore=o.total_pages<=o.current_page}).catch(function(){t.error=!0,t.loading=!1})},onReload:function(){this.error=!1},onPreview:function(t){this.$refs.preview.open(t.urls,t.index)},onDeleted:function(t){this.activities.splice(t,1)},onToggleFollow:function(t,e){this.activities.forEach(function(n){var i=n.user;i.id===t&&(i.following=e)})}}},r={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.onReachBottom,expression:"onReachBottom"}],attrs:{"infinite-scroll-disabled":"busy","infinite-scroll-distance":"200"}},[n("transition-group",{attrs:{tag:"div",name:"fade-transform-y"}},t._l(t.activities,function(e,i){return n("activity-card",{key:e.id,staticClass:"activity-card",attrs:{activity:e,"show-action-button":t.showActionButton},on:{preview:t.onPreview,follow:function(e){t.onToggleFollow(e,!0)},unfollow:function(e){t.onToggleFollow(e,!1)},deleted:function(e){t.onDeleted(i)}}})})),t._v(" "),n("my-loader",{attrs:{loading:t.loading,error:t.error,"btn-text":t.$t("loadmore"),"on-reload":t.onReload}}),t._v(" "),t.nomore?t._t("nomore",[n("p",{directives:[{name:"t",rawName:"v-t",value:t.$t("g.nomore"),expression:"$t('g.nomore')"}],staticClass:"no-more"})]):t._e(),t._v(" "),n("my-multi-preview",{ref:"preview"})],2)},staticRenderFns:[]};var s=function(t){n("/ER6")},a=Object(i.a)(o,r,!1,s,"data-v-0e76484b",null),c=n("0Iqu");c&&c.__esModule&&(c=c.default),"function"==typeof c&&c(a);e.a=a.exports}});
//# sourceMappingURL=0.7fce0b0d09af037003be.js.map