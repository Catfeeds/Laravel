webpackJsonp([13],{"LC+d":function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"加载更多":"Load more","编辑":"Edit","删除":"Delete","确认删除该作品？":"Is it confirmed to delete the work?"}}'),delete t.options._Ctor}},NphS:function(t,e){},V2YY:function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=o("XyMi"),i=o("xoXj"),r=o("GUlH"),s=o("0xDb"),a={data:function(){return{works:[],currentPage:0,loading:!1,nomore:!1,error:!1,followLoadings:{}}},computed:{busy:function(){return this.loading||this.nomore||this.error}},methods:{likeCountStr:function(t){return t?this.$t("g.like")+" ("+t+")":this.$t("g.like")},onToggleLike:function(t){var e=this.works[t],o=e.liked;(o?r.g:r.f)(e.id).then(function(t){var n=t.data.like_count;e.liked=!o,e.like_count=n})},onReachBottom:function(){var t=this;return this.loading=!0,Object(r.d)(this.currentPage+1).then(function(e){var o=e.data,n=o.data,i=o.meta.pagination;t.loading=!1,t.works.push.apply(t.works,n),t.currentPage=i.current_page,t.nomore=i.total_pages<=i.current_page}).catch(function(){t.error=!0,t.loading=!1})},onReload:function(){this.error=!1},onFollow:function(t){var e=this;if(!this.followLoadings[t]){this.$set(this.followLoadings,t,!0);var o=this.works[t].user;console.log("user",o),this.$store.dispatch("FOLLOW",o.id).then(function(){e.followLoadings[t]=!1,e.works.forEach(function(t){var e=t.user;e.id===o.id&&(e.following=!0,e.follower_count++)})}).catch(function(){e.followLoadings[t]=!1})}},onUnfollow:function(t){var e=this,o=this.works[t].user;this.$store.dispatch("UNFOLLOW",o.id).then(function(){e.works.forEach(function(t){var e=t.user;e.id===o.id&&(e.following=!1,e.follower_count--)})})},onEdit:function(t){var e=this.works[t].id;this.$router.push("/work/"+e+"/edit")},onDelete:function(t){var e=this;this.$confirm(this.$t("确认删除该作品？"),this.$t("g.notice"),{confirmButtonText:this.$t("g.confirmBtn"),cancelButtonText:this.$t("g.cancelBtn"),type:"warning"}).then(function(){Object(r.b)(e.works[t].id).then(function(){e.works.splice(t,1),e.$message.success(e.$t("g.successfullyDeleted"))})}).catch(function(){})},onPreview:function(t,e){this.$refs.preview.open(t,e)},splittedFollowerCount:function(t){return Object(s.b)(t)}}},c={render:function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.onReachBottom,expression:"onReachBottom"}],attrs:{"infinite-scroll-disabled":"busy","infinite-scroll-distance":"200"}},[o("transition-group",{attrs:{tag:"div",name:"fade-transform-y"}},t._l(t.works,function(e,n){return o("div",{key:e.id,staticClass:"work-card"},[o("div",{staticClass:"work-card__header"},[o("router-link",{attrs:{to:"/profile?uid="+e.user.id}},[o("my-avatar",{staticClass:"work-card__header-avatar",attrs:{"avatar-url":e.user.avatar_url}})],1),t._v(" "),o("div",{staticClass:"work-card__header-text"},[o("router-link",{attrs:{to:"/profile?uid="+e.user.id}},[o("p",{staticClass:"m0 f-15 bold black inline-block"},[t._v(t._s(e.user.name))])]),t._v(" "),o("p",{staticClass:"m0 f-13 bold black-65"},[t._v(t._s(t.splittedFollowerCount(e.user.follower_count)+" "+t.$t("g.follower")))]),t._v(" "),o("p",{staticClass:"m0 f-13 bold black-65"},[t._v(t._s(t.$t("g.published_at")+" "+e.created_at))])],1),t._v(" "),t.$uid()==e.user.id?o("el-dropdown",{attrs:{trigger:"click"}},[o("el-button",{staticClass:"work-card__header-dropdown",attrs:{type:"text",icon:"el-icon-arrow-down"}}),t._v(" "),o("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[o("el-dropdown-item",{nativeOn:{click:function(e){t.onEdit(n)}}},[t._v(t._s(t.$t("编辑")))]),t._v(" "),o("el-dropdown-item",{nativeOn:{click:function(e){t.onDelete(n)}}},[t._v(t._s(t.$t("删除")))])],1)],1):e.user.following?o("el-dropdown",{attrs:{trigger:"click"},on:{command:t.onUnfollow}},[o("el-button",{staticClass:"work-card__header-dropdown",attrs:{type:"text",icon:"el-icon-arrow-down"}}),t._v(" "),o("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[o("el-dropdown-item",{attrs:{command:n}},[t._v(t._s(t.$t("g.cancelFollow")))])],1)],1):o("el-button",{staticClass:"work-card__header-follow-button",attrs:{loading:t.followLoadings[n],plain:"",round:"",type:"primary",size:"small"},on:{click:function(e){t.onFollow(n)}}},[t._v(t._s(t.$t("g.follow")))])],1),t._v(" "),o("div",{staticClass:"work-card__content"},[o("el-carousel",{staticClass:"work-card__content-carousel",attrs:{autoplay:!1,trigger:"click"}},t._l(e.photo_urls,function(n,i){return o("el-carousel-item",{key:n,nativeOn:{click:function(o){t.onPreview(e.photo_urls,i)}}},[o("img",{staticClass:"work-card__content-carousel-item",attrs:{src:n}})])})),t._v(" "),o("div",{staticClass:"work-card__content-info"},[o("p",{staticClass:"work-card__content-info-title",domProps:{textContent:t._s(e.title)}}),t._v(" "),o("p",{staticClass:"work-card__content-info-description",domProps:{textContent:t._s(e.description)}})])],1),t._v(" "),o("div",{staticClass:"work-card__action-btn"},[o("el-button",{class:{"is-liked":e.liked},attrs:{type:"text"},on:{click:function(e){t.onToggleLike(n)}}},[t._v(t._s(t.likeCountStr(e.like_count)))])],1)])})),t._v(" "),o("my-loader",{attrs:{loading:t.loading,error:t.error,"btn-text":t.$t("加载更多"),"on-reload":t.onReload}}),t._v(" "),t.nomore?o("p",{directives:[{name:"t",rawName:"v-t",value:t.$t("g.nomore"),expression:"$t('g.nomore')"}],staticClass:"no-more"}):t._e(),t._v(" "),o("my-multi-preview",{ref:"preview"})],1)},staticRenderFns:[]};var l=function(t){o("NphS")},d=Object(n.a)(a,c,!1,l,"data-v-5372dbcb",null),u=o("LC+d");u&&u.__esModule&&(u=u.default),"function"==typeof u&&u(d);var _=d.exports,v=o("Ty/O"),p={components:{ActivityList:i.a,WorkList:_},computed:{type:function(){return this.$route.query.type||"activity"}},methods:{getActivities:function(t){return Object(v.h)(t)},onNavigate:function(t){var e=t.name;this.$router.push({path:this.$route.path,query:{type:e}})}}},f={render:function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",[o("div",{staticClass:"banner"},[o("h1",{directives:[{name:"t",rawName:"v-t",value:"设计师广场",expression:"'设计师广场'"}],staticClass:"banner__title"}),t._v(" "),o("div",{staticClass:"banner__sub"},[o("p",{domProps:{textContent:t._s("人们为了生活来到城市，为了更好的生活而留在城市。")}}),t._v(" "),o("p",{domProps:{textContent:t._s("Men come to city to live, they stay in the city to live well.")}}),t._v(" "),o("p",{staticClass:"right-align",domProps:{textContent:t._s("——"+t.$t("亚里士多德"))}})])]),t._v(" "),o("div",{staticClass:"wrap-container"},[o("div",{staticClass:"main-container"},[o("div",{staticClass:"left-container"},[o("el-tabs",{attrs:{value:t.type,type:"card"},on:{"tab-click":t.onNavigate}},[o("el-tab-pane",{attrs:{label:t.$t("热门动态"),name:"activity"}}),t._v(" "),o("el-tab-pane",{attrs:{label:t.$t("欣赏作品"),name:"work"}})],1),t._v(" "),"activity"===t.type?o("activity-list",{staticClass:"activity-list",attrs:{"get-activities":t.getActivities,"show-action-button":""}}):o("work-list")],1),t._v(" "),o("div",{staticClass:"right-container"},[o("div",{directives:[{name:"t",rawName:"v-t",value:"tip1",expression:"'tip1'"}],staticClass:"tip"}),t._v(" "),o("div",{staticClass:"tip"},[t._v("\n          "+t._s(t.$t("想要更精确地查找设计师？"))+"\n          "),o("router-link",{attrs:{to:"/search?type=designer"}},[t._v(t._s(t.$t("试试这个搜索页")))])],1)])])])])},staticRenderFns:[]};var h=function(t){o("jy+z")},w=Object(n.a)(p,f,!1,h,"data-v-12a0deae",null),m=o("oRmW");m&&m.__esModule&&(m=m.default),"function"==typeof m&&m(w);e.default=w.exports},"jy+z":function(t,e){},oRmW:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"tip1":"设计师广场包含设计师们近一个月的热门动态（依据点赞数与评论数排列）与作品集。"},"en":{"tip1":"Designer Square contains the trending activities of designers for nearly a month (according to the number of likes and comments) and their works.","设计师广场":"Designer Plaza","亚里士多德":"Aristotle","热门动态":"Trending activities","欣赏作品":"Appreciate works","想要更精确地查找设计师？":"Want to find a designer more accurately?","试试这个搜索页":"Try this search"}}'),delete t.options._Ctor}}});
//# sourceMappingURL=13.d080d52abfbbe42fb3b8.js.map