(window["aioseopjsonp"]=window["aioseopjsonp"]||[]).push([["setup-wizard-Category-vue"],{"10a4":function(e,t,s){"use strict";s.r(t);var a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"aioseo-wizard-category"},[s("wizard-header"),s("wizard-container",[s("wizard-body",{scopedSlots:e._u([{key:"footer",fn:function(){return[s("div",{staticClass:"go-back"},[s("router-link",{staticClass:"no-underline",attrs:{to:e.getPrevLink}},[e._v("←")]),e._v("   "),s("router-link",{attrs:{to:e.getPrevLink}},[e._v(e._s(e.strings.goBack))])],1),s("div",{staticClass:"spacer"}),s("base-button",{attrs:{type:"blue",loading:e.loading},on:{click:e.saveAndContinue}},[e._v(e._s(e.strings.saveAndContinue)+" →")])]},proxy:!0}])},[s("wizard-steps"),s("div",{staticClass:"header"},[e._v(" "+e._s(e.strings.category)+" ")]),s("div",{staticClass:"description"},[e._v(e._s(e.strings.selectCategory))]),s("div",{staticClass:"categories"},[s("grid-row",e._l(e.categories,(function(t,a){return s("grid-column",{key:a,attrs:{md:"6"}},[s("base-highlight-toggle",{attrs:{type:"radio",active:e.isActive(t),name:"category",value:e.getValue(t)},on:{input:function(s){return e.updateValue(s,t)}}},[t.icon?s(t.icon,{tag:"component",staticClass:"icon"}):e._e(),e._v(" "+e._s(t.name)+" ")],1)],1)})),1),s("base-highlight-toggle",{staticClass:"other-category",attrs:{type:"radio",active:e.isActive(e.otherCategory),name:"category",value:e.getValue(e.otherCategory)},on:{input:function(t){return e.updateValue(t,e.otherCategory)}}},[e.otherCategory.icon?s(e.otherCategory.icon,{tag:"component",staticClass:"icon"}):e._e(),e._v(" "+e._s(e.otherCategory.name)+" "),e.selected.length?s("base-input",{ref:"other-category",attrs:{disabled:"other"!==e.selected[0].value,size:"medium",placeholder:e.strings.enterYourAnswer},model:{value:e.category.categoryOther,callback:function(t){e.$set(e.category,"categoryOther",t)},expression:"category.categoryOther"}}):e._e()],1)],1),e.loaded?s("div",{staticClass:"site-info"},[s("div",{staticClass:"site-title aioseo-settings-row no-border"},[s("div",{staticClass:"settings-name"},[s("div",{staticClass:"name small-margin"},[e._v(e._s(e.strings.siteTitle))])]),s("core-html-tags-editor",{attrs:{"line-numbers":!1,single:"","tags-context":"homePage","default-tags":["site_title","separator_sa","tagline"]},model:{value:e.category.siteTitle,callback:function(t){e.$set(e.category,"siteTitle",t)},expression:"category.siteTitle"}})],1),s("div",{staticClass:"site-description aioseo-settings-row no-border no-margin small-padding"},[s("div",{staticClass:"settings-name"},[s("div",{staticClass:"name small-margin"},[e._v(e._s(e.strings.metaDescription))])]),s("core-html-tags-editor",{attrs:{"line-numbers":!1,description:"","tags-context":"homePage","default-tags":["site_title","separator_sa","tagline"]},model:{value:e.category.metaDescription,callback:function(t){e.$set(e.category,"metaDescription",t)},expression:"category.metaDescription"}})],1)]):e._e()],1),s("wizard-close-and-exit")],1)],1)},i=[],o=s("5530"),n=(s("caad"),s("2532"),s("c740"),s("9c0e")),r=s("2f62"),c={mixins:[n["q"]],data:function(){return{loaded:!1,titleCount:0,descriptionCount:0,loading:!1,stage:"category",strings:{category:this.$t.__("Which category best describes your website?",this.$td),selectCategory:this.$t.__("Select a category to help us narrow down the SEO options that work best for you and your site.",this.$td),enterYourAnswer:this.$t.__("Enter your answer",this.$td),siteTitle:this.$t.__("Home Page Title",this.$td),metaDescription:this.$t.__("Home Page Meta Description",this.$td)},categories:[{value:"blog",name:this.$t.__("Blog",this.$td),icon:"svg-blog"},{value:"news-channel",name:this.$t.__("News Channel",this.$td),icon:"svg-news-channel"},{value:"online-store",name:this.$t.__("Online Store",this.$td),icon:"svg-online-store"},{value:"small-offline-business",name:this.$t.__("Small Offline Business",this.$td),icon:"svg-local-business"},{value:"corporation",name:this.$t.__("Corporation",this.$td),icon:"svg-corporation"},{value:"portfolio",name:this.$t.__("Portfolio",this.$td),icon:"svg-image-seo"}],otherCategory:{value:"other",name:this.$t.__("Other:",this.$td),icon:"svg-pencil",valueInput:null},selected:[]}},watch:{selected:function(e){this.category.category=e[0].value;var t=["optimized-search-appearance","analytics","image-seo","sitemaps"];switch(e[0].value){case"blog":case"portfolio":case"other":break;case"news-channel":t.push("news-sitemap"),t.push("video-sitemap");break;case"online-store":t.push("video-sitemap");break;case"small-offline-business":t.push("local-seo"),t.push("video-sitemap");break;case"corporation":t.push("local-seo"),t.push("news-sitemap"),t.push("video-sitemap");break}this.updateFeatures(t)}},computed:Object(o["a"])(Object(o["a"])({},Object(r["e"])(["options"])),Object(r["e"])("wizard",["category"])),methods:Object(o["a"])(Object(o["a"])(Object(o["a"])({},Object(r["d"])("wizard",["updateFeatures"])),Object(r["b"])("wizard",["saveWizard"])),{},{updateValue:function(e,t){var s=this;this.selected=[],e&&(this.selected.push(t),"other"===t.value&&this.$nextTick((function(){s.$refs["other-category"].$el.querySelector("input").focus()})))},getValue:function(e){return this.selected.includes(e)},isActive:function(e){var t=this.selected.findIndex((function(t){return t.value===e.value}));return-1!==t},saveAndContinue:function(){var e=this;this.loading=!0,this.saveWizard("category").then((function(){e.$router.push(e.getNextLink)}))}}),mounted:function(){this.selected.push(this.categories[0]),!this.category.siteTitle&&this.options.searchAppearance.global.siteTitle&&(this.category.siteTitle=this.options.searchAppearance.global.siteTitle),!this.category.metaDescription&&this.options.searchAppearance.global.metaDescription&&(this.category.metaDescription=this.options.searchAppearance.global.metaDescription),this.loaded=!0}},l=c,u=(s("eff7"),s("2877")),g=Object(u["a"])(l,a,i,!1,null,null,null);t["default"]=g.exports},4968:function(e,t,s){},eff7:function(e,t,s){"use strict";s("4968")}}]);