webpackJsonp([33],{"7l0H":function(e,t){},KBvX:function(e,t){e.exports=function(e){e.options.__i18n=e.options.__i18n||[],e.options.__i18n.push('{"zh":{"countdown":"重新获取验证码({count})"},"en":{"使用邮箱注册":"Sign up with email","邮箱":"Email","用户类型":"User type","设计师":"Designer","甲方":"Party","真实姓名":"Real name","获取验证码":"Send verification code","验证码":"Verification code","密码":"Password","确 定":"Submit","请选择用户类型":"Please select user type","请输入真实姓名（中文名或英文名，2~50个字符）":"Please enter your real name (between 2 to 50 characters)","请输入合法邮箱地址":"Please enter a valid email address","请输入6位验证码":"Please enter 6 characters verification code","请输入密码":"Please enter a password","密码长度为 6 到 25 个字符":"Password length is 6 to 25 characters","注册成功":"Successfully sign up","已有账号登录":"Sign in","countdown":"Resend verification code ({count})"}}'),delete e.options._Ctor}},"c/vr":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n("XyMi"),o=n("E4LH"),r=n("vMJZ"),s={data:function(){return{form:{type:null,name:"",email:"",password:"",verification_code:""},rules:{type:{required:!0,message:this.$t("请选择用户类型")},name:{required:!0,pattern:o.a,min:1,max:50,message:this.$t("请输入真实姓名（中文名或英文名，2~50个字符）")},email:{required:!0,type:"email",message:this.$t("请输入合法邮箱"),trigger:"blur"},verification_code:{required:!0,len:6,message:this.$t("请输入6位验证码"),trigger:"blur"},password:[{required:!0,trigger:"blur",message:this.$t("请输入密码")},{min:6,max:25,trigger:"blur",message:"密码长度为 6 到 25 个字符"}]},loading:!1,sending:!1,count:0,timer:null}},computed:{sendButtonText:function(){var e=this.count;return e?this.$t("countdown",{count:e}):this.$t("获取验证码")}},methods:{onSend:function(){var e=this;if(Object(o.c)(this.form.email)){this.sending=!0;var t=this.form.email;Object(r.n)(t,"register").then(function(){e.sending=!1,e.count=60,e.countDown()}).catch(function(){e.sending=!1})}else this.$message.warning(this.$t("请输入合法邮箱地址"))},onSubmit:function(){var e=this;this.$refs.form.validate(function(t){if(t)return e.loading=!0,e.$store.dispatch("signUp",e.form).then(function(){e.loading=!1,e.$message.success(e.$t("注册成功")),e.$router.push({path:"/feed"})}).catch(function(){e.loading=!1})})},countDown:function(){var e=this;this.count>0?(this.count--,this.timer=setTimeout(function(){e.countDown()},1e3)):clearTimeout(this.timer)}}},a={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("section",{staticClass:"container"},[n("h1",{directives:[{name:"t",rawName:"v-t",value:"使用邮箱注册",expression:"'使用邮箱注册'"}]}),e._v(" "),n("el-form",{ref:"form",attrs:{rules:e.rules,model:e.form,size:"small"}},[n("el-form-item",{attrs:{label:e.$t("用户类型"),prop:"type"}},[n("el-radio-group",{model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},[n("el-radio",{attrs:{label:"party"}},[e._v(e._s(e.$t("甲方")))]),e._v(" "),n("el-radio",{attrs:{label:"designer"}},[e._v(e._s(e.$t("设计师")))])],1)],1),e._v(" "),n("el-form-item",{attrs:{label:e.$t("真实姓名"),prop:"name"}},[n("el-input",{attrs:{type:"text"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),n("el-form-item",{attrs:{label:e.$t("邮箱"),prop:"email"}},[n("el-input",{model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}},[n("el-button",{attrs:{slot:"append",loading:e.sending,disabled:!!e.count},on:{click:e.onSend},slot:"append"},[e._v(e._s(e.sendButtonText)+" ")])],1)],1),e._v(" "),n("el-form-item",{attrs:{label:e.$t("验证码"),prop:"verification_code"}},[n("el-input",{model:{value:e.form.verification_code,callback:function(t){e.$set(e.form,"verification_code",t)},expression:"form.verification_code"}})],1),e._v(" "),n("el-form-item",{attrs:{label:e.$t("密码"),prop:"password"}},[n("el-input",{attrs:{type:"text"},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),e._v(" "),n("el-form-item",[n("el-button",{attrs:{loading:e.loading,type:"primary"},on:{click:e.onSubmit}},[e._v(e._s(e.$t("确 定")))]),e._v(" "),n("router-link",{staticClass:"center",staticStyle:{display:"block"},attrs:{to:"/signin"}},[e._v(e._s(e.$t("已有账号登录")))])],1)],1)],1)},staticRenderFns:[]};var l=function(e){n("7l0H")},c=Object(i.a)(s,a,!1,l,"data-v-20e4dd98",null),u=n("KBvX");u&&u.__esModule&&(u=u.default),"function"==typeof u&&u(c);t.default=c.exports}});
//# sourceMappingURL=33.e533c156ea968d2df566.js.map