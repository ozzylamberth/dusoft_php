
var aaa=0;if(typeof(k_push_vars)=="undefined")
k_push_vars={}
var k_push={"after_time":20000,"popup_w":380,"popup_h":175,"myWidth":0,"myHeight":0,"popup_open":false,"counter":0,"lastPosy":null,"getSize":function(){if(typeof(window.innerWidth)=='number'){k_push.myWidth=window.innerWidth;k_push.myHeight=window.innerHeight;}else if(document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight)){k_push.myWidth=document.documentElement.clientWidth;k_push.myHeight=document.documentElement.clientHeight;}else if(document.body&&(document.body.clientWidth||document.body.clientHeight)){k_push.myWidth=document.body.clientWidth;k_push.myHeight=document.body.clientHeight;}},"getScrollY":function(){var scrOfY=0;if(typeof(window.pageYOffset)=='number')
scrOfY=window.pageYOffset;else if(document.body&&(document.body.scrollTop))
scrOfY=document.body.scrollTop;else if(document.documentElement&&(document.documentElement.scrollTop))
scrOfY=document.documentElement.scrollTop;return scrOfY;},"mousePos":function(e){if(k_push.popup_open)
return;k_push.getSize();var posy=0;if(!e)var e=window.event;if(e.pageX||e.pageY)
posy=e.pageY;else if(e.clientX||e.clientY)
posy=e.clientY+k_push.getScrollY();aaa=k_push.myHeight/2-k_push.popup_h/2+document.body.scrollTop;if(k_push.lastPosy==null)
k_push.lastPosy=posy;else
{if(k_push.lastPosy<posy)
k_push.lastPosy=posy;else
{if(posy<k_push.getScrollY()+15)
k_push.openPopup();}}},"popup_init":function(){k_push.getSize();document.onmousemove=k_push.mousePos;var maskBG=document.createElement('div');maskBG.setAttribute('id','k_maskBG');document.body.appendChild(maskBG);var popup=document.createElement('div');popup.setAttribute('id','k_popup');popup.style.width=k_push.popup_w+'px';popup.style.height=k_push.popup_h+'px';popup.style.position='absolute';popup.style.top='0px';popup.style.left='0px';popup.style.zIndex='9999';popup.style.fontFamily='arial';popup.style.fontWeight='bold';popup.style.color=(k_push_vars.popup_font_color)?k_push_vars.popup_font_color:'#000000';k_push.ff_link_id=(k_push_vars.ff_link_id)?k_push_vars.ff_link_id:'kampylink';k_push.header=(k_push_vars.header)?k_push_vars.header:'Your feedback is important to us!';k_push.question=(k_push_vars.question)?k_push_vars.question:'Would you be willing to give us a short (1 minute) feedback?';k_push.footer=(k_push_vars.footer)?k_push_vars.footer:'Thank you for helping us improve our website';k_push.yes=(k_push_vars.yes)?k_push_vars.yes:'Yes';k_push.no=(k_push_vars.no)?k_push_vars.no:'No';k_push.yes_background=(k_push_vars.yes_background)?k_push_vars.yes_background:'#76AC78';k_push.no_background=(k_push_vars.no_background)?k_push_vars.no_background:'#8D9B86';k_push.dir=(k_push_vars.text_direction=='rtl')?'rtl':'ltr';k_push.remind=k_push_vars.remind?k_push_vars.remind:"Remind me later";if(k_push.dir=='ltr')
{k_push.yes_float='left';k_push.no_float='right';}
else
{k_push.yes_float='right';k_push.no_float='left';}
k_push.images_dir=(k_push_vars.images_dir)?k_push_vars.images_dir:'/images/';popup.innerHTML="<div style='border-bottom: 1px solid #ffffff; text-align: center; font-size: 20px; padding: 10px; direction:"+k_push.dir+"'><strong>"+k_push.header+"</strong></div><div style='border-bottom: 1px solid #ffffff; font-size: 11px; padding: 10px 0 10px 0; text-align: center; direction:"+k_push.dir+"'>"+k_push.question+"<div style='margin: 0 auto; width: 138px; padding: 10px 0 0 0;'><a style='cursor:pointer;background-color:"+k_push.yes_background+";border-color:#D9DFEA rgb(14, 31, 91) rgb(14, 31, 91) rgb(217, 223, 234);border-style:solid;border-width:1px;padding:2px 15px 3px;text-align:center;font-size:11px;font-weight: bold; text-decoration: none; color: black; width: 26px; float: "+k_push.yes_float+";' onclick='document.getElementById(\""+k_push.ff_link_id+"\").rel = \"&amp;push=1\";document.getElementById(\""+k_push.ff_link_id+"\").onclick();k_push.closePopup(); k_push.increaseCount(1); return false;'>"+k_push.yes+"</a><a style='cursor:pointer;background-color:"+k_push.no_background+";border-color:#D9DFEA rgb(14, 31, 91) rgb(14, 31, 91) rgb(217, 223, 234);border-style:solid;border-width:1px;padding:2px 15px 3px;text-align:center;font-size:11px;font-weight: bold; text-decoration: none; color: black;  width: 26px; float: "+k_push.no_float+";' onclick='k_push.closePopup(); k_push.increaseCount(0); return false;'>"+k_push.no+"</a><br /><br /><a href='#' onclick='k_push.remind_later()' style='font-weight:normal;'>"+k_push.remind+"</a></div></div><div style='font-size: 11px; padding: 10px 0 6px 0; text-align: center; direction:"+k_push.dir+"'>"+k_push.footer+"</div>";popup.style.background=(k_push_vars.popup_background)?k_push_vars.popup_background:'#D0DDE5';popup.style.display='none';document.body.appendChild(popup);},remind_later:function()
{k_push.closePopup();var k_load_time=(new Date()).getTime();if(k_push_vars.display_after)
{var timeout=k_push_vars.display_after*1000;}
else
{var timeout=k_push.after_time;}
if(timeout<1000*60*2)
{}
k_load_time=k_load_time+parseInt(1000*60*2);if(document.domain!="undefined"&&document.domain!="")
{if(document.domain=='localhost')
main_domain='';else
main_domain=document.domain.substring(document.domain.indexOf('.')+1);}
else
main_domain='';k_push.Set_Cookie("push_time_start",k_load_time,0,"/",main_domain,'');k_push.Set_Cookie("k_push8",0,0,"/",main_domain,'');},"openPopup":function(){if(k_push_vars.display_after)
{var timeout=k_push_vars.display_after*1000;}
else
{var timeout=k_push.after_time;}
var difference=(new Date).getTime()-k_load_time;if(difference<timeout)
{return;}
if(document.domain!="undefined"&&document.domain!="")
{if(document.domain=='localhost')
main_domain='';else
main_domain=document.domain.substring(document.domain.indexOf('.')+1);}
else
main_domain='';k_push.Set_Cookie('k_push8','1','21','/',main_domain,'');if((k_push.popup_open)||(k_push.counter>0)||(!document.getElementById(k_push.ff_link_id)))
return;k_push.counter++;k_push.popup_open=true;var maskBG=document.getElementById('k_maskBG')
var popup=document.getElementById('k_popup')
var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;popup.style.top=(k_push.myHeight/2)-(k_push.popup_h/2)+scrollTop+'px';popup.style.left=k_push.myWidth/2-k_push.popup_w/2+'px';popup.style.display='block';maskBG.innerHTML='<div style="width: 100%; height: 100%; opacity: .7; filter: alpha(opacity=70); background-color:transparent !important; background-color: #333333; background-image/**/: url(\''+k_push.images_dir+'maskBG.png\') !important; background-image:none; background-repeat: repeat;"></div>';maskBG.style.position='absolute';maskBG.style.top='0px';maskBG.style.left='0px';maskBG.style.zIndex='998';maskBG.style.width=document.body.scrollWidth+'px';var h=(document.documentElement.scrollHeight>document.body.scrollHeight)?document.documentElement.scrollHeight:document.body.scrollHeight;maskBG.style.height=h+'px';maskBG.style.display='block';if(document.all)
k_push.toggleSelects('hidden');},"closePopup":function(){k_push.popup_open=false;var maskBG=document.getElementById('k_maskBG');var popup=document.getElementById('k_popup');popup.style.display='none';maskBG.style.display='none';if(document.all)
k_push.toggleSelects('visible');},Delete_Cookie:function(name,path,domain)
{if(k_push.Get_Cookie(name))
document.cookie=name+"="+
((path)?";path="+path:"")+
((domain)?";domain="+domain:"")+";expires=Thu, 01-Jan-1970 00:00:01 GMT";},"Set_Cookie":function(name,value,expires,path,domain,secure)
{var today=new Date();today.setTime(today.getTime());if(expires)
expires=expires*1000*60*60*24;var expires_date=new Date(today.getTime()+(expires));document.cookie=name+"="+escape(value)+
((expires)?";expires="+expires_date.toGMTString():"")+
((path)?";path="+path:"")+
((domain)?";domain="+domain:"")+
((secure)?";secure":"");},"Get_Cookie":function(name)
{var start=document.cookie.indexOf(name+"=");var len=start+name.length+1;if((!start)&&(name!=document.cookie.substring(0,name.length)))
return null;if(start==-1)return null;var end=document.cookie.indexOf(";",len);if(end==-1)end=document.cookie.length;return unescape(document.cookie.substring(len,end));},"toggleSelects":function(visibility){try{var selects=document.getElementsByTagName('select');for(i=0;i<selects.length;i++)
selects[i].style.visibility=visibility;}
catch(err)
{}},"increaseCount":function(choice){}};if(k_push.Get_Cookie("push_time_start")>0)
{var k_load_time=k_push.Get_Cookie("push_time_start");}
else
{var k_load_time=(new Date()).getTime();if(document.domain!="undefined"&&document.domain!="")
{if(document.domain=='localhost')
main_domain='';else
main_domain=document.domain.substring(document.domain.indexOf('.')+1);}
else
main_domain='';k_push.Set_Cookie("push_time_start",k_load_time,0,"/",main_domain,'');}
if(!k_push_vars.view_percentage)
k_push.n=0;else
k_push.n=(k_push_vars.view_percentage&&((k_push_vars.view_percentage>=0)&&(k_push_vars.view_percentage<=100)))?k_push_vars.view_percentage:10;if(((k_push.n==null)||(((Math.random()*100)<k_push.n))))
{if(!k_push_vars.disable_cookie)
{if(!k_push.Get_Cookie('k_push8')||k_push.Get_Cookie('k_push8')==0)
{if(window.addEventListener)
window.addEventListener('load',k_push.popup_init,false);else
window.attachEvent('onload',k_push.popup_init);}}
else
{if(window.addEventListener)
window.addEventListener('load',k_push.popup_init,false);else
window.attachEvent('onload',k_push.popup_init);}}