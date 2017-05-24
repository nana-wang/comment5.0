require.def("tabs/homeTab",["domplate/domplate","domplate/tabView","core/lib","core/cookies","core/trace","i18n!nls/homeTab","text!tabs/homeTab.html","preview/harModel"],function(Domplate,TabView,Lib,Cookies,Trace,Strings,HomeTabHtml,HarModel){with(Domplate){function HomeTab(){}HomeTab.prototype=Lib.extend(TabView.Tab.prototype,{id:"Home",label:Strings.homeTabLabel,bodyTag:DIV({"class":"homeBody"}),onUpdateBody:function(a,b){b=this.bodyTag.replace({},b),b.innerHTML=HomeTabHtml.replace("@HAR_SPEC_URL@",a.harSpecURL,"g"),$("#appendPreview").click(Lib.bindFixed(this.onAppendPreview,this)),$(".linkAbout").click(Lib.bind(this.onAbout,this));var c=$("#content");c.bind("dragenter",Lib.bind(Lib.cancelEvent,Lib)),c.bind("dragover",Lib.bind(Lib.cancelEvent,Lib)),c.bind("drop",Lib.bind(this.onDrop,this)),this.validateNode=$("#validate");var d=Cookies.getCookie("validate");d&&this.validateNode.attr("checked",d=="false"?!1:!0),this.validateNode.change(Lib.bind(this.onValidationChange,this)),$(".example").click(Lib.bind(this.onLoadExample,this))},onAppendPreview:function(a){a||(a=$("#sourceEditor").val()),a&&this.tabView.appendPreview(a)},onAbout:function(){this.tabView.selectTabByName("About")},onValidationChange:function(){var a=this.validateNode.attr("checked");Cookies.setCookie("validate",a)},onLoadExample:function(a){var b=Lib.fixEvent(a),c=b.target.getAttribute("path"),d=document.location.href,e=d.indexOf("?");document.location=d.substr(0,e)+"?path="+c,Cookies.setCookie("timeline",!0),Cookies.setCookie("stats",!0)},onDrop:function(a){var b=Lib.fixEvent(a);Lib.cancelEvent(b);try{this.handleDrop(a.originalEvent.dataTransfer)}catch(c){Trace.exception("HomeTab.onDrop EXCEPTION",c)}},handleDrop:function(a){if(!a)return!1;var b=a.files;if(b)for(var c=0;c<b.length;c++){var d=b[c],e=Lib.getFileExtension(d.name);if(e.toLowerCase()!="har")continue;var f=this,g=this.getFileReader(d,function(a){a&&f.onAppendPreview(a)});g()}},getFileReader:function(a,b){return function c(){if(typeof a.getAsText!="undefined")b(a.getAsText(""));else if(typeof FileReader!="undefined"){var c=new FileReader;c.onloadend=function(){b(c.result)},c.readAsText(a)}}},loadInProgress:function(a,b){$("#sourceEditor").val(a?b?b:Strings.loadingHar:"")}});return HomeTab}})