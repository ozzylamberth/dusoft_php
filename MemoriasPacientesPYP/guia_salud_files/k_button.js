
var k_button={"ff_link":document.getElementById("kampylink"),"close_button":document.getElementById("k_close_button"),"extra_params":null,"newwindow":'',"popitup":function(url,longUrl){if(!this.newwindow.closed&&this.newwindow.location)
this.newwindow.location.href=url;else
{this.newwindow=window.open(url,'kampyle_ff','left='+((window.screenX||window.screenLeft)+10)+',top='+((window.screenY||window.screenTop)+10)+',height=502px,width=440px,resizable=false');if(!this.newwindow.opener)this.newwindow.opener=self;}
if(window.focus)
this.newwindow.focus()
if(longUrl!='kampyle_ff')
this.newwindow.name=longUrl;return false;},"open_ff":function(ff_params,url)
{var url2send=url||window.location.href;url2send=encodeURIComponent(url2send);if(!ff_params)
var ff_url=k_button.ff_link.href;else
{var ff_link_rel=k_button.ff_link.rel;k_button.ff_link.rel='';if(ff_link_rel=='nofollow')
ff_link_rel='';if(k_button.ff_link.href.substring(0,24)=='http://www.inputlive.com')
var ff_url='http://www.inputlive.com/feedback_form/ff-feedback-form.php?'+ff_params+ff_link_rel
else
if(k_button.ff_link.href.substring(0,16)=='http://localhost')
var ff_url='http://localhost/feedback_form/ff-feedback-form.php?'+ff_params+ff_link_rel
else
var ff_url='http://www.kampyle.com/feedback_form/ff-feedback-form.php?'+ff_params+ff_link_rel}
if(this.extra_params)
{var extra_params=this.make_query_string(this.extra_params);ff_url=ff_url+'&'+extra_params;}
longUrl='kampyle_ff';if((ff_url.length+url2send.length)>250)
{longUrl=url2send;url2send='noUrl';}
this.popitup(ff_url+'&url='+url2send,longUrl);},"hide_button":function()
{k_button.ff_link.style.display="none";k_button.close_button.style.display="none";},"make_query_string":function(params)
{var query_string='';var params_tmp=[];for(var s in params)
params_tmp.push(s+'='+encodeURIComponent(params[s]));query_string=params_tmp.join('&');return query_string;},"addCss":function(path)
{var fileref=document.createElement("link")
fileref.setAttribute("rel","stylesheet")
fileref.setAttribute("type","text/css")
fileref.setAttribute("href",path)
if(typeof fileref!="undefined")
document.getElementsByTagName("head")[0].appendChild(fileref)}}
if(((screen.width<=800)&&(screen.height<=600))&&(k_button.ff_link.className!='k_static'))
{k_button.close_button.onclick=k_button.hide_button;k_button.close_button.innerHTML='X';k_button.close_button.style.display="block";}