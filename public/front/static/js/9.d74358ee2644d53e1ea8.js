webpackJsonp([9],{"06/o":function(t,e){},"2iNt":function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"status":{"1000":"报名中","1100":"工作中","1200":"已完成"}},"en":{"输入项目标题进行搜索":"Enter project title to search","搜索":"Search","项目状态":"Project status","项目关键字":"Project keywords","输入关键字后按下回车添加":"Enter keyword and type Enter to add","查看详情":"View detail","发布于":"Published at","重新加载":"Reload","设计费":"Design fees","人收藏":"favoritors","项目类型":"Project type","项目功能":"Project feature","希望用多长时间找设计师":"How long to find a designer","status":{"1000":"Applying","1100":"Working","1200":"Completed"}}}'),delete t.options._Ctor}},GjHG:function(t,e){},JdBT:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"输入设计师姓名进行搜索":"Enter keyword to search","专业领域":"Professional fields","排序方式":"Order by","默认排序":"Default","完成项目数从多到少":"Completed project count desc","搜索":"Search","关注":"Following","取消关注":"Unfollow","粉丝":"Follower","重新加载":"Reload"}}'),delete t.options._Ctor}},Rv23:function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=r("XyMi"),o=r("EMlb"),i={filters:{statusToType:function(t){return{1000:null,1100:"warning",1200:"info"}[t]}},data:function(){return{statusOptions:["1000","1100","1200"],projects:[],status:[],title:"",keywords:[],pageCount:1,currentPage:1,loading:!1,error:!1}},created:function(){this.$route.query.p&&(this.currentPage=parseInt(this.$route.query.p)),this.$route.query.status&&(Array.isArray(this.$route.query.status)?this.status=this.$route.query.status:this.status=[this.$route.query.status]),this.$route.query.keywords&&(Array.isArray(this.$route.query.keywords)?this.keywords=this.$route.query.keywords:this.keywords=[this.$route.query.keywords]),this.$route.query.title&&(this.title=this.$route.query.title),this.getProjects()},methods:{getProjects:function(){var t=this;this.loading=!0,this.error=!1;var e=this.currentPage,r=this.status,s=this.title,i=this.keywords;Object(o.q)(e,{title:s,status:r,keywords:i}).then(function(e){var r=e.data,s=r.data,o=r.meta.pagination;t.loading=!1,t.projects=s,t.pageCount=o.total_pages}).catch(function(){t.loading=!1,t.error=!0})},onSearch:function(){this.$router.push({path:this.$route.path,query:{type:"project",status:this.status,title:this.title,keywords:this.keywords}})},onChangePage:function(t){this.$router.push({path:this.$route.path,query:{type:"project",status:this.$route.query.status,title:this.$route.query.title,keywords:this.$route.query.keywords,p:t}})}}},n={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"container"},[r("el-form",{attrs:{size:"small",inline:""}},[r("el-form-item",[r("el-input",{attrs:{placeholder:t.$t("输入项目标题进行搜索")},nativeOn:{keyup:function(e){return"button"in e||!t._k(e.keyCode,"enter",13,e.key,"Enter")?t.onSearch(e):null}},model:{value:t.title,callback:function(e){t.title=e},expression:"title"}})],1),t._v(" "),r("el-form-item",[r("el-select",{attrs:{placeholder:t.$t("项目状态"),multiple:""},model:{value:t.status,callback:function(e){t.status=e},expression:"status"}},t._l(t.statusOptions,function(e){return r("el-option",{key:e,attrs:{label:t.$t("status."+e),value:e}})}))],1),t._v(" "),r("el-form-item",[r("el-select",{attrs:{placeholder:t.$t("项目关键字"),filterable:"","allow-create":"",multiple:"","default-first-option":""},model:{value:t.keywords,callback:function(e){t.keywords=e},expression:"keywords"}},[r("el-option",{attrs:{value:t.$t("输入关键字后按下回车添加")}})],1)],1),t._v(" "),r("el-form-item",[r("el-button",{attrs:{type:"primary"},on:{click:t.onSearch}},[t._v(t._s(t.$t("搜索")))])],1)],1),t._v(" "),t.loading||t.error?r("my-loader",{attrs:{loading:t.loading,error:t.error,"btn-text":t.$t("重新加载"),"on-reload":t.getProjects}}):t.projects.length?[r("div",{staticClass:"project-list"},t._l(t.projects,function(e){return r("div",{key:e.id,staticClass:"project-list-item"},[r("el-tag",{attrs:{type:t._f("statusToType")(e.status)}},[t._v(t._s(t.$t("status."+e.status)))]),t._v(" "),r("router-link",{staticClass:"project-list-item__title",attrs:{to:"/project/"+e.id},domProps:{textContent:t._s(e.title)}}),t._v(" "),r("span",{staticClass:"black-65 bold",domProps:{textContent:t._s(t.$t("设计费")+"："+e.payment)}}),t._v(" "),r("div",{staticClass:"project-list-item__content"},[r("p",[t._v(t._s(t.$t("项目类型"))+"："+t._s(e.types.join("/")))]),t._v(" "),r("p",[t._v(t._s(t.$t("项目功能"))+"："+t._s(e.features.join("/")))]),t._v(" "),r("p",[t._v(t._s(t.$t("希望用多长时间找设计师"))+"："+t._s(e.find_time))]),t._v(" "),e.keywords.length?r("p",[t._v(t._s(t.$t("项目关键字"))+"：\n            "),t._l(e.keywords,function(e){return r("el-tag",{key:e,staticClass:"mr1",attrs:{type:"info",size:"mini"}},[t._v(t._s(e))])})],2):t._e()]),t._v(" "),r("div",{staticClass:"project-list-item__publisher"},[r("my-avatar",{attrs:{"avatar-url":e.user.avatar_url}}),t._v(" "),r("router-link",{staticClass:"f-14",attrs:{to:"/profile?uid="+e.user.id}},[t._v(t._s(e.user.name))]),t._v(" "),r("span",{staticClass:"f-14 black-45"},[t._v(t._s(t.$t("发布于"))+" "+t._s(e.created_at))]),t._v(" "),r("span",{staticClass:"f-14 black-45"},[t._v(t._s(e.favorite_count+" "+t.$t("人收藏")))])],1)],1)})),t._v(" "),r("el-pagination",{staticClass:"mt2 center",attrs:{"current-page":t.currentPage,"page-count":t.pageCount,background:"",layout:"prev, pager, next"},on:{"update:currentPage":function(e){t.currentPage=e},"current-change":t.onChangePage}})]:r("my-empty")],2)},staticRenderFns:[]};var a=function(t){r("06/o")},l=Object(s.a)(i,n,!1,a,"data-v-c936d88a",null),u=r("2iNt");u&&u.__esModule&&(u=u.default),"function"==typeof u&&u(l);var c=l.exports,d=r("vMJZ"),p={data:function(){return{fields:["建筑设计","室内设计","景观设计","城市设计","城市规划","概念规划","Architectural Design","Interior Design","Landscape Design","Urban Design","Urban Planning","Concept Planning"],users:[],keyword:"",selectedFields:[],order:null,pageCount:1,currentPage:1,loading:!1,error:!1,followLoadings:{}}},computed:{userType:function(){return this.$route.query.type}},created:function(){this.$route.query.p&&(this.currentPage=parseInt(this.$route.query.p)),this.$route.query.keyword&&(this.keyword=this.$route.query.keyword),this.$route.query.order&&(this.order=this.$route.query.order),this.$route.query.fields&&(Array.isArray(this.$route.query.fields)?this.selectedFields=this.$route.query.fields:this.selectedFields=[this.$route.query.fields]),this.getUsers()},methods:{getUsers:function(){var t=this;this.loading=!0,this.error=!1;var e=this.currentPage,r=this.keyword,s=this.userType,o=this.selectedFields,i=this.order;Object(d.j)(e,r,s,{professional_fields:o,order:i}).then(function(e){var r=e.data,s=r.data,o=r.meta.pagination;t.loading=!1,t.users=s,t.pageCount=o.total_pages}).catch(function(){t.loading=!1,t.error=!0})},onToggleFollow:function(t){var e=this;if(!this.followLoadings[t]){this.$set(this.followLoadings,t,!0);var r=this.users[t],s=r.following?"UNFOLLOW":"FOLLOW";this.$store.dispatch(s,r.id).then(function(){r.following=!r.following,e.followLoadings[t]=!1}).catch(function(){e.followLoadings[t]=!1})}},onSearch:function(){this.$router.push({path:this.$route.path,query:{type:this.userType,keyword:this.keyword,fields:this.selectedFields,order:this.order}})},onChangePage:function(t){this.$router.push({path:this.$route.path,query:{type:this.userType,keyword:this.$route.query.keyword,fields:this.selectedFields,p:t,order:this.order}})}}},_={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"container"},[r("el-form",{attrs:{size:"small",inline:""}},[r("el-form-item",[r("el-input",{attrs:{placeholder:t.$t("输入关键字进行搜索"),clearable:""},nativeOn:{keyup:function(e){return"button"in e||!t._k(e.keyCode,"enter",13,e.key,"Enter")?t.onSearch(e):null}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}})],1),t._v(" "),r("el-form-item",[r("el-select",{attrs:{placeholder:t.$t("专业领域"),multiple:""},model:{value:t.selectedFields,callback:function(e){t.selectedFields=e},expression:"selectedFields"}},t._l(t.fields,function(t){return r("el-option",{key:t,attrs:{label:t,value:t}})}))],1),t._v(" "),r("el-form-item",[r("el-select",{attrs:{placeholder:t.$t("排序方式"),clearable:""},model:{value:t.order,callback:function(e){t.order=e},expression:"order"}},[r("el-option",{attrs:{label:"默认排序",value:"default"}}),t._v(" "),r("el-option",{attrs:{label:"完成项目数从多到少",value:"completed_project_count_desc"}})],1)],1),t._v(" "),r("el-form-item",[r("el-button",{attrs:{type:"primary"},on:{click:t.onSearch}},[t._v(t._s(t.$t("搜索")))])],1)],1),t._v(" "),t.loading||t.error?r("my-loader",{attrs:{loading:t.loading,error:t.error,"btn-text":t.$t("重新加载"),"on-reload":t.getUsers}}):t.users.length?[r("div",{staticClass:"user-list"},t._l(t.users,function(e,s){return r("div",{key:e.id,staticClass:"user-list-item"},[r("router-link",{attrs:{to:"/profile?uid="+e.id}},[r("my-avatar",{staticClass:"user-list-item__avatar",attrs:{"avatar-url":e.avatar_url}})],1),t._v(" "),r("div",{staticClass:"user-list-item__info"},[r("router-link",{staticClass:"bold black-85",attrs:{to:"/profile?uid="+e.id}},[t._v(t._s(e.name))]),t._v(" "),r("p",[r("span",{staticClass:"color-primary"},[t._v(t._s(t.$t("关注")))]),t._v(" "),r("span",{domProps:{textContent:t._s(e.following_count)}}),t._v(" "),r("span",{staticClass:"color-primary"},[t._v(t._s(t.$t("粉丝")))]),t._v(" "),r("span",{domProps:{textContent:t._s(e.follower_count)}})]),t._v(" "),r("p",{domProps:{textContent:t._s(e.title)}}),t._v(" "),r("p",{domProps:{textContent:t._s(e.introduction)}}),t._v(" "),r("div",{staticClass:"mt1"},t._l(e.professional_fields,function(e){return r("el-tag",{key:e,staticClass:"mr1",attrs:{size:"mini",type:"info"}},[t._v(t._s(e))])}))],1),t._v(" "),e.id!=t.$uid()?[e.following?r("el-button",{attrs:{loading:t.followLoadings[s],plain:"",size:"mini"},on:{click:function(e){t.onToggleFollow(s)}}},[t._v(t._s(t.$t("取消关注")))]):r("el-button",{attrs:{loading:t.followLoadings[s],plain:"",type:"primary",size:"mini"},on:{click:function(e){t.onToggleFollow(s)}}},[t._v(t._s(t.$t("关注")))])]:t._e()],2)})),t._v(" "),r("el-pagination",{staticClass:"mt2 center",attrs:{"current-page":t.currentPage,"page-count":t.pageCount,background:"",layout:"prev, pager, next"},on:{"update:currentPage":function(e){t.currentPage=e},"current-change":t.onChangePage}})]:r("my-empty"),t._v(" "),t.loading||t.error||"designer"!==t.userType?t._e():r("my-invite-alert",{staticClass:"mt2"})],2)},staticRenderFns:[]};var h=function(t){r("GjHG")},y=Object(s.a)(p,_,!1,h,"data-v-f7cb3d40",null),f=r("JdBT");f&&f.__esModule&&(f=f.default),"function"==typeof f&&f(y);var v={components:{ProjectList:c,UserList:y.exports},computed:{searchType:function(){return this.$route.query.type||"project"}},methods:{onNavigate:function(t){this.$router.push({path:this.$route.path,query:{type:t,p:1}})}}},g={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("el-menu",{attrs:{"default-active":t.searchType,mode:"horizontal"},on:{select:t.onNavigate}},[r("el-menu-item",{attrs:{index:"project"}},[t._v(t._s(t.$t("项目")))]),t._v(" "),r("el-menu-item",{attrs:{index:"designer"}},[t._v(t._s(t.$t("设计师")))])],1),t._v(" "),"project"===t.searchType?r("project-list"):t._e(),t._v(" "),"designer"===t.searchType?r("user-list"):t._e()],1)},staticRenderFns:[]},m=Object(s.a)(v,g,!1,null,null,null),k=r("kOen");k&&k.__esModule&&(k=k.default),"function"==typeof k&&k(m);e.default=m.exports},kOen:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"项目":"Projects","设计师":"Designers"}}'),delete t.options._Ctor}}});
//# sourceMappingURL=9.d74358ee2644d53e1ea8.js.map