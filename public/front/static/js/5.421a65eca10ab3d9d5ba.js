webpackJsonp([5],{QCYB:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a("XyMi"),o={props:{notification:{type:Object,default:function(){return{id:null,data:{}}}}},data:function(){return{loading:!1}},computed:{data:function(){return this.notification.data},unread:function(){return!this.notification.read_at}},methods:{onClickCommand:function(t){var e=this;"markAsRead"===t&&(this.loading=!0,this.$store.dispatch("markAsReadById",this.notification.id).then(function(){e.loading=!1}).catch(function(){e.loading=!1})),"delete"===t&&(this.loading=!0,this.$store.dispatch("deleteNotificationById",this.notification).then(function(){e.loading=!1}).catch(function(){e.loading=!1}))}}},n={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticClass:"notification-item",class:{unread:t.unread}},[a("el-dropdown",{staticClass:"notification-item__dropdown",attrs:{trigger:"click"},on:{command:t.onClickCommand}},[a("el-button",{attrs:{type:"text",icon:"el-icon-arrow-down"}}),t._v(" "),a("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[t.unread?a("el-dropdown-item",{attrs:{command:"markAsRead"}},[t._v(t._s(t.$t("标为已读")))]):t._e(),t._v(" "),a("el-dropdown-item",{attrs:{command:"delete"}},[t._v(t._s(t.$t("删除")))])],1)],1),t._v(" "),"activity_replied"===t.data.type||"reply_replied"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("router-link",{attrs:{to:"/activity/"+t.data.activity_id,place:"activityContent"}},[t._v(t._s(t.data.activity_content||t.$t("点击查看")))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("p",{staticClass:"reply-content",domProps:{textContent:t._s(t.data.reply_content)}})]:"reviewed"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("p",{staticClass:"content",domProps:{textContent:t._s(t.data.content)}})]:"project_applied"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("p",{staticClass:"application-remark",domProps:{textContent:t._s(t.data.application_remark)}}),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:"invite_to_review"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("查看Ta的个人主页")))])],1),t._v(" "),a("router-link",{attrs:{to:"/review/post?uid="+t.data.user_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("发表评价")))])],1)]:"project_invited"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.party_id,place:"name"}},[t._v(t._s(t.data.party_name))]),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:"project_remitted"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:"project_payed"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-85 bold",attrs:{place:"amount"},domProps:{textContent:t._s(t.data.amount)}}),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("router-link",{attrs:{to:"/payment/"+t.data.payment_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:"project_delivered"===t.data.type||"project_invitation_accepted"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:"project_invitation_declined"===t.data.type?[a("i18n",{attrs:{path:t.data.type,tag:"div"}},[a("router-link",{attrs:{to:"/profile?uid="+t.data.user_id,place:"name"}},[t._v(t._s(t.data.user_name))]),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id,place:"projectTitle"}},[t._v(t._s(t.data.project_title))]),t._v(" "),a("span",{staticClass:"ml-4 black-45",attrs:{place:"createdAt"},domProps:{textContent:t._s(t.notification.created_at)}})],1),t._v(" "),a("p",{staticClass:"mt1 mb0",domProps:{textContent:t._s(t.$t("refusalCause",{text:t.data.refusal_cause}))}}),t._v(" "),a("router-link",{attrs:{to:"/project/"+t.data.project_id}},[a("el-button",{staticStyle:{"padding-bottom":"0"},attrs:{type:"text"}},[t._v(t._s(t.$t("点击查看")))])],1)]:t._e()],2)},staticRenderFns:[]};var r=function(t){a("xLeK")},c=Object(i.a)(o,n,!1,r,null,null),s=a("cNUf");s&&s.__esModule&&(s=s.default),"function"==typeof s&&s(c);var d={components:{NotificationItem:c.exports,Empty:a("pid2").default},data:function(){return{pageCount:0,loading:!1,error:!1,markButtonLoading:!1,deleteButtonLoading:!1}},computed:{notifications:function(){return this.$store.getters.notifications},type:function(){return this.$route.query.type||"unread"},currentPage:function(){return parseInt(this.$route.query.p)||1}},created:function(){this.getNotifications()},methods:{getNotifications:function(){var t=this;this.error=!1,this.loading=!0,this.$store.dispatch("getNotifications",{type:this.type,page:this.currentPage}).then(function(e){t.loading=!1,t.pageCount=e.total_pages}).catch(function(){t.loading=!1,t.error=!0})},markAllAsRead:function(){var t=this;this.markButtonLoading=!0,this.$store.dispatch("markAllAsRead").then(function(){t.markButtonLoading=!1,t.$message.success(t.$t("标记成功"))}).catch(function(){t.markButtonLoading=!1})},deleteAllRead:function(){var t=this;this.deleteButtonLoading=!0,this.$store.dispatch("deleteAllReadNotifications").then(function(){t.deleteButtonLoading=!1}).catch(function(){t.deleteButtonLoading=!1})},onCurrentPageChange:function(t){this.$router.push({path:this.$route.path,query:{type:this.type,p:t}})}}},p={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"container"},[a("my-loader",{attrs:{loading:t.loading,error:t.error,"btn-text":t.$t("g.reload"),"on-reload":t.getNotifications}}),t._v(" "),t.loading||t.error?t._e():[t.notifications.length?[a("div",{staticClass:"button-area"},[a("el-button",{attrs:{loading:t.markButtonLoading,size:"small"},on:{click:t.markAllAsRead}},[t._v(t._s(t.$t("标记所有通知为已读"))+" ")]),t._v(" "),"all"===t.type?a("el-button",{attrs:{loading:t.deleteButtonLoading,size:"small"},on:{click:t.deleteAllRead}},[t._v(t._s(t.$t("清空所有已读通知"))+" ")]):t._e()],1),t._v(" "),a("transition-group",{attrs:{tag:"div",name:"fade"}},t._l(t.notifications,function(t){return a("notification-item",{key:t.id,staticClass:"notification-item",attrs:{notification:t}})})),t._v(" "),a("el-pagination",{staticStyle:{"text-align":"center",padding:"16px 0"},attrs:{"current-page":t.currentPage,"page-count":t.pageCount,background:"",layout:"prev, pager, next"},on:{"current-change":t.onCurrentPageChange}})]:a("empty")]],2)},staticRenderFns:[]};var l=function(t){a("m6C3")},_=Object(i.a)(d,p,!1,l,"data-v-5d43cc0e",null),u=a("WId3");u&&u.__esModule&&(u=u.default),"function"==typeof u&&u(_);e.default=_.exports},TCcH:function(t,e){},"Tt+U":function(t,e,a){"use strict";var i={render:function(){this.$createElement;this._self._c;return this._m(0)},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{attrs:{className:"empty-content"}},[e("div",{staticClass:"exception-content"},[e("img",{staticClass:"imgException",attrs:{src:a("qAb6")}})])])}]};e.a=i},WId3:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"标记所有通知为已读":"Mark all as read","清空所有已读通知":"Delete all read notifications","标记成功":"Successfully marked all notifications as read"}}'),delete t.options._Ctor}},YeQz:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"en":{"没有新的通知":"No new notifications"}}'),delete t.options._Ctor}},cNUf:function(t,e){t.exports=function(t){t.options.__i18n=t.options.__i18n||[],t.options.__i18n.push('{"zh":{"reviewed":"{name} 评价了您 {createdAt}","activity_replied":"{name} 评论了你的动态 {activityContent} {createdAt}","reply_replied":"{name} 回复了你在动态 {activityContent} 下的评论 {createdAt}","project_applied":"{name} 报名了你的项目 {projectTitle} {createdAt}","project_delivered":"{name} 提交了您的项目 {projectTitle} 的设计文件 {createdAt}","project_invitation_accepted":"{name} 接受了您的项目邀请 {projectTitle} {createdAt}","project_invitation_declined":"{name} 拒绝了您的项目邀请 {projectTitle} {createdAt}","refusalCause":"拒绝原因：{text}","project_remitted":"您参与的项目 {projectTitle} 已托管赏金，您可以开始工作了！ {createdAt}","invite_to_review":"{name} 邀请您评价Ta，您的评价将展示在Ta的个人主页 {createdAt}","project_invited":"{name} 邀请您参与Ta的项目 {projectTitle}，请选择是否接受邀请 {createdAt}","project_payed":"您已收到项目 {projectTitle} 的设计费 {amount} 元 {createdAt}"},"en":{"reviewed":"{name} posted a review to you {createdAt}","activity_replied":"{name} replied your activity {activityContent} {createdAt}","reply_replied":"{name} replied your reply under activity {activityContent} {createdAt}","project_applied":"{name} applied your project {projectTitle} {createdAt}","project_delivered":"{name} has delivered the design file of your project {projectTitle} {createdAt}","project_invitation_accepted":"{name} accepted your invitation in the project {projectTitle} {createdAt}","project_invitation_declined":"{name} declined your invitation in the project {projectTitle} {createdAt}","refusalCause":"Refusal cause: {text}","project_remitted":"The project {projectTitle} you participating has remitted design fees. You can start working now! {createdAt}","invite_to_review":"{name} invited you to review him, your review will display on his profile page {createdAt}","project_invited":"{name} invitied you to participate his/her project {projectTitle}. Please accept or decline the invitation {createdAt}","project_payed":"You have received design fee of {projectTitle}: {amount} {createdAt}","点击查看":"Click to view","标为已读":"Mark as read","查看Ta的个人主页":"View his profile","发表评价":"Post review","删除":"Delete"}}'),delete t.options._Ctor}},m6C3:function(t,e){},pid2:function(t,e,a){"use strict";var i=a("XyMi"),o=a("smXi"),n=a.n(o),r=a("Tt+U");var c=function(t){a("TCcH")},s=Object(i.a)(n.a,r.a,!1,c,"data-v-0bf15ff4",null),d=a("YeQz");d&&d.__esModule&&(d=d.default),"function"==typeof d&&d(s),e.default=s.exports},qAb6:function(t,e,a){t.exports=a.p+"static/img/logo_primary_20.2365b53.png"},smXi:function(t,e){},xLeK:function(t,e){}});
//# sourceMappingURL=5.421a65eca10ab3d9d5ba.js.map