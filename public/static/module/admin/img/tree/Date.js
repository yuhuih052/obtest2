//----------------------------------------------------------------------------
//  �@��÷��������һ���Օ� Javascript ����_������헣��m���΢ܛ�� IE ��5.0���ϣ����[��
//  ���{�ú����� setday(this,[object])��setday(this)��[object]�ǿ����ݔ���Ŀ���������e�ɂ����ӣ�
//  һ��<input name=txt size="20"><input type=button value=setday onclick="setday(this,document.all.txt)">
//  ����<input onfocus="setday(this)" size="20">
//  ����ʲ�N����ĵط����������и��õĽ��h��Ո�c���M��mail: meizz@hzcnc.com
//  ���Օѵ���������ǣ�1000 - 9999��
//  ��ESC�I�P�]ԓ�����
//  ������µ��@ʾ�ط��c���r���քe�����c�µ��������
//  ������������c��һ�c�����P�]ԓ�����
/* ����walkingpoison���޸��f��
walkingpoison�M��ʽ��wayx@kali.com.cn

Ver	2.0
�޸����ڣ�2002-12-13
�޸ă��ݣ�
1.*ȫ���޸�ʹ��iframe�����Օѵ��d�w�����ٱ�select��flash�ȿ���헓�ס��
2.��������ֲ��iframe���Ƅ��Օѿ���헵Ć��}��

Ver	1.5
�޸����ڣ�2002-12-4
�޸ă��ݣ�
1.�x�е������@ʾ�鰼��ȥ�Ę�ʽ
2.�޸����P�]�ӵķ�����ʹ��ʧȥ���c�ĕr���܉��P�]�Օѡ�
3.�޸İ��I̎��ʹ��Tab�ГQ���c�ĕr������P�]�����
4.*�����Զ��x�Օ��Ƿ�����τ�

Ver 1.4
�޸����ڣ�2002-12-3
�޸ă��ݣ�
1.�����x����/�·���������ᰴEsc�I������/�·ݲ��@ʾ�Ć��}
2.����ʹ����������x���·���ɵ������e�`���ִ��D���锵λ�Ć��}��
3.*���^��ʽ�ĸ��M��ʹ�ÿ���헏��hС��׃�������������Z���Ļҹ���׃���˸��F�Ĺ������ġ������x�߿����Լ��M��ǡ���ı�����
4.�ٴ�������/�·ݵ��c�����g���K��������ε�λ�������{��

ע��*̖��ʾ���^�P�I�ĸĄ�

�f����
1.�ܵ�iframe�����ƣ�����τӳ��Օ�ҕ�����t�Օѕ�ֹͣ�Ƅӡ�
*/

//==================================================== �����O������ =======================================================
var bMoveable=true;		//�O���Օ��Ƿ�����τ�
var _VersionInfo="Version:2.0 &#13;2.0����:walkingpoison&#13;1.0����: F.R.Huang(meizz) &#13;MAIL: meizz@hzcnc.com"	//�汾�YӍ

//==================================================== WEB ����@ʾ���� =====================================================
var strFrame;		//����Օьӵ�HTML���a
document.writeln('<iframe id=meizzDateLayer Author=wayx frameborder=0 style="position: absolute; width: 144; height: 211; z-index: 9998; display: none"></iframe>');
strFrame='<style>';
strFrame+='INPUT.button{';
strFrame+='border:1px solid #ff9900; BACKGROUND-COLOR: #fff8ec;font-family:MingLiU;}';
strFrame+='TD{FONT-SIZE: 9pt;font-family:MingLiU;}';
strFrame+='</style>';
strFrame+='<scr' + 'ipt>';
strFrame+='var datelayerx,datelayery;	/*����Օѿ���헵Ļ���λ��*/';
strFrame+='var bDrag;	/*��ӛ�Ƿ��_ʼ�τ�*/';
strFrame+='function document.onmousemove()	/*�ڻ����Ƅ��¼��У�����_ʼ�τ��Օѣ��t�Ƅ��Օ�*/';
strFrame+='{if(bDrag && window.event.button==1)';
strFrame+='	{var DateLayer=parent.document.all.meizzDateLayer.style;';
strFrame+='		DateLayer.posLeft += window.event.clientX-datelayerx;/*���ÿ���Ƅ����Ử��λ�ö��֏͞��ʼ��λ�ã���ˌ����cdiv�в�ͬ*/';
strFrame+='		DateLayer.posTop += window.event.clientY-datelayery;}}';
strFrame+='function DragStart()		/*�_ʼ�Օ��τ�*/';
strFrame+='{var DateLayer=parent.document.all.meizzDateLayer.style;';
strFrame+='	datelayerx=window.event.clientX;';
strFrame+='	datelayery=window.event.clientY;';
strFrame+='	bDrag=true;}';
strFrame+='function DragEnd(){		/*�Y���Օ��τ�*/';
strFrame+='	bDrag=false;}';
strFrame+='</scr' + 'ipt>';
strFrame+='<div style="z-index:9999;position: absolute; left:0; top:0;" onselectstart="return false"><span id=tmpSelectYearLayer Author=wayx style="z-index: 9999;position: absolute;top: 3; left: 19;display: none"></span>';
strFrame+='<span id=tmpSelectMonthLayer Author=wayx style="z-index: 9999;position: absolute;top: 3; left: 78;display: none"></span>';
strFrame+='<table border=1 cellspacing=0 cellpadding=0 width=142 height=160 bordercolor=#ff9900 bgcolor=#ff9900 Author="wayx">';
strFrame+='  <tr Author="wayx"><td width=142 height=23 Author="wayx" bgcolor=#FFFFFF><table border=0 cellspacing=1 cellpadding=0 width=140 Author="wayx" height=23>';
strFrame+='      <tr align=center Author="wayx"><td width=16 align=center bgcolor=#ff9900 style="font-size:12px;cursor: hand;color: #ffffff" ';
strFrame+='        onclick="parent.meizzPrevM()" title="��ǰ�� 1 ��" Author=meizz><b Author=meizz>&lt;</b>';
strFrame+='        </td><td width=60 align=center style="font-size:12px;cursor:default" Author=meizz ';
strFrame+='onmouseover="style.backgroundColor=\'#FFD700\'" onmouseout="style.backgroundColor=\'white\'" ';
strFrame+='onclick="parent.tmpSelectYearInnerHTML(this.innerText.substring(0,4))" title="�c���@�Y�x�����"><span Author=meizz id=meizzYearHead></span></td>';
strFrame+='<td width=48 align=center style="font-size:12px;cursor:default" Author=meizz onmouseover="style.backgroundColor=\'#FFD700\'" ';
strFrame+=' onmouseout="style.backgroundColor=\'white\'" onclick="parent.tmpSelectMonthInnerHTML(this.innerText.length==3?this.innerText.substring(0,1):this.innerText.substring(0,2))"';
strFrame+='        title="�c���@�Y�x���·�"><span id=meizzMonthHead Author=meizz></span></td>';
strFrame+='        <td width=16 bgcolor=#ff9900 align=center style="font-size:12px;cursor: hand;color: #ffffff" ';
strFrame+='         onclick="parent.meizzNextM()" title="���ᷭ 1 ��" Author=meizz><b Author=meizz>&gt;</b></td></tr>';
strFrame+='    </table></td></tr>';
strFrame+='  <tr Author="wayx"><td width=142 height=18 Author="wayx">';
strFrame+='<table border=1 cellspacing=0 cellpadding=0 bgcolor=#ff9900 ' + (bMoveable? 'onmousedown="DragStart()" onmouseup="DragEnd()"':'');
strFrame+=' BORDERCOLORLIGHT=#FF9900 BORDERCOLORDARK=#FFFFFF width=140 height=20 Author="wayx" style="cursor:' + (bMoveable ? 'move':'default') + '">';
strFrame+='<tr Author="wayx" align=center valign=bottom><td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td>';
strFrame+='<td style="font-size:12px;color:#FFFFFF" Author=meizz>һ</td><td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td>';
strFrame+='<td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td><td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td>';
strFrame+='<td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td><td style="font-size:12px;color:#FFFFFF" Author=meizz>��</td></tr>';
strFrame+='</table></td></tr><!-- Author:F.R.Huang(meizz) http://www.meizz.com/ mail: meizz@hzcnc.com 2002-10-8 -->';
strFrame+='  <tr Author="wayx"><td width=142 height=120 Author="wayx">';
strFrame+='    <table border=1 cellspacing=2 cellpadding=0 BORDERCOLORLIGHT=#FF9900 BORDERCOLORDARK=#FFFFFF bgcolor=#fff8ec width=140 height=120 Author="wayx">';
var n=0; for (j=0;j<5;j++){ strFrame+= ' <tr align=center Author="wayx">'; for (i=0;i<7;i++){
strFrame+='<td width=20 height=20 id=meizzDay'+n+' style="font-size:12px" Author=meizz onclick=parent.meizzDayClick(this.innerText,0)></td>';n++;}
strFrame+='</tr>';}
strFrame+='      <tr align=center Author="wayx">';
for (i=35;i<39;i++)strFrame+='<td width=20 height=20 id=meizzDay'+i+' style="font-size:12px" Author=wayx onclick="parent.meizzDayClick(this.innerText,0)"></td>';
strFrame+='        <td colspan=3 align=right Author=meizz><span onclick=parent.closeLayer() style="font-size:12px;cursor: hand"';
strFrame+='         Author=meizz title="' + _VersionInfo + '"><u>�P�]</u></span>&nbsp;</td></tr>';
strFrame+='    </table></td></tr><tr Author="wayx"><td Author="wayx">';
strFrame+='        <table border=0 cellspacing=1 cellpadding=0 width=100% Author="wayx" bgcolor=#FFFFFF>';
strFrame+='          <tr Author="wayx"><td Author=meizz align=left><input Author=meizz type=button class=button value="<<" title="��ǰ�� 1 ��" onclick="parent.meizzPrevY()" ';
strFrame+='             onfocus="this.blur()" style="font-size: 12px; height: 20px"><input Author=meizz class=button title="��ǰ�� 1 ��" type=button ';
strFrame+='             value="< " onclick="parent.meizzPrevM()" onfocus="this.blur()" style="font-size: 12px; height: 20px"></td><td ';
strFrame+='             Author=meizz align=center><input Author=meizz type=button class=button value=���� onclick="parent.meizzToday()" ';
strFrame+='             onfocus="this.blur()" title="��ǰ����" style="font-size: 12px; height: 20px; cursor:hand"></td><td ';
strFrame+='             Author=meizz align=right><input Author=meizz type=button class=button value=" >" onclick="parent.meizzNextM()" ';
strFrame+='             onfocus="this.blur()" title="���ᷭ 1 ��" class=button style="font-size: 12px; height: 20px"><input ';
strFrame+='             Author=meizz type=button class=button value=">>" title="���ᷭ 1 ��" onclick="parent.meizzNextY()"';
strFrame+='             onfocus="this.blur()" style="font-size: 12px; height: 20px"></td>';
strFrame+='</tr></table></td></tr></table></div>';

window.frames.meizzDateLayer.document.writeln(strFrame);
window.frames.meizzDateLayer.document.close();		//��Qie�M�ȗl���Y���Ć��}

//==================================================== WEB ����@ʾ���� ======================================================
var outObject;
var outButton;		//�c���İ��o
var outDate="";		//��Ō��������
var odatelayer=window.frames.meizzDateLayer.document.all;		//����Օь���
function setday(tt,obj) //���{����
{
	if (arguments.length >  2){alert("�����𣡂��뱾����헵ą���̫�࣡");return;}
	if (arguments.length == 0){alert("���������]�Ђ��ر�������κ΅�����");return;}
	var dads  = document.all.meizzDateLayer.style;
	var th = tt;
	var ttop  = tt.offsetTop;     //TT����헵Ķ�λ�c��
	var thei  = tt.clientHeight;  //TT����헱���ĸ�
	var tleft = tt.offsetLeft;    //TT����헵Ķ�λ�c��
	var ttyp  = tt.type;          //TT����헵����
	while (tt = tt.offsetParent){ttop+=tt.offsetTop; tleft+=tt.offsetLeft;}
	dads.top  = (ttyp=="image")? ttop+thei : ttop+thei+6;
	dads.left = tleft;
	outObject = (arguments.length == 1) ? th : obj;
	outButton = (arguments.length == 1) ? null : th;	//�O���ⲿ�c���İ��o
	//������ǰݔ���������@ʾ�Օѵ�����
	var reg = /^(\d+)-(\d{1,2})-(\d{1,2})$/;
	var r = outObject.value.match(reg);
	if(r!=null){
		r[2]=r[2]-1;
		var d= new Date(r[1], r[2],r[3]);
		if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3]){
			outDate=d;		//�����ⲿ���������
		}
		else outDate="";
			meizzSetDay(r[1],r[2]+1);
	}
	else{
		outDate="";
		meizzSetDay(new Date().getFullYear(), new Date().getMonth() + 1);
	}
	dads.display = '';

	event.returnValue=false;
}

var MonHead = new Array(12);    		   //���xꖕ���ÿ���µ�����씵
    MonHead[0] = 31; MonHead[1] = 28; MonHead[2] = 31; MonHead[3] = 30; MonHead[4]  = 31; MonHead[5]  = 30;
    MonHead[6] = 31; MonHead[7] = 31; MonHead[8] = 30; MonHead[9] = 31; MonHead[10] = 30; MonHead[11] = 31;

var meizzTheYear=new Date().getFullYear(); //���x���׃���ĳ�ʼֵ
var meizzTheMonth=new Date().getMonth()+1; //���x�µ�׃���ĳ�ʼֵ
var meizzWDay=new Array(39);               //���x�����ڵ����

function document.onclick() //�����c���r�P�]ԓ�����	//ie6����r������������ГQ���c̎�����
{
  with(window.event)
  { if (srcElement.getAttribute("Author")==null && srcElement != outObject && srcElement != outButton)
    closeLayer();
  }
}

function document.onkeyup()		//��Esc�I�P�]���ГQ���c�P�]
  {
    if (window.event.keyCode==27){
		if(outObject)outObject.blur();
		closeLayer();
	}
	else if(document.activeElement)
		if(document.activeElement.getAttribute("Author")==null && document.activeElement != outObject && document.activeElement != outButton)
		{
			closeLayer();
		}
  }

function meizzWriteHead(yy,mm)  //�� head �Ќ��뮔ǰ�����c��
  {
	odatelayer.meizzYearHead.innerText  = yy + " ��";
    odatelayer.meizzMonthHead.innerText = mm + " ��";
  }

function tmpSelectYearInnerHTML(strYear) //��ݵ��������
{
  if (strYear.match(/\D/)!=null){alert("���ݔ�녢�����ǔ��֣�");return;}
  var m = (strYear) ? strYear : new Date().getFullYear();
  if (m < 1000 || m > 9999) {alert("���ֵ���� 1000 �� 9999 ֮�g��");return;}
  var n = m -70;
  if (n < 1000) n = 1000;
  if (n + 100 > 9999) n = 9974;
  var s = "<select Author=meizz name=tmpSelectYear style='font-size: 12px' "
     s += "onblur='document.all.tmpSelectYearLayer.style.display=\"none\"' "
     s += "onchange='document.all.tmpSelectYearLayer.style.display=\"none\";"
     s += "parent.meizzTheYear = this.value; parent.meizzSetDay(parent.meizzTheYear,parent.meizzTheMonth)'>\r\n";
  var selectInnerHTML = s;
  for (var i = n; i < n + 100; i++)
  {
    if (i == m)
       {selectInnerHTML += "<option Author=wayx value='" + i + "' selected>" + i + "��" + "</option>\r\n";}
    else {selectInnerHTML += "<option Author=wayx value='" + i + "'>" + i + "��" + "</option>\r\n";}
  }
  selectInnerHTML += "</select>";
  odatelayer.tmpSelectYearLayer.style.display="";
  odatelayer.tmpSelectYearLayer.innerHTML = selectInnerHTML;
  odatelayer.tmpSelectYear.focus();
}

function tmpSelectMonthInnerHTML(strMonth) //�·ݵ��������
{
  if (strMonth.match(/\D/)!=null){alert("�·�ݔ�녢�����ǔ��֣�");return;}
  var m = (strMonth) ? strMonth : new Date().getMonth() + 1;
  var s = "<select Author=meizz name=tmpSelectMonth style='font-size: 12px' "
     s += "onblur='document.all.tmpSelectMonthLayer.style.display=\"none\"' "
     s += "onchange='document.all.tmpSelectMonthLayer.style.display=\"none\";"
     s += "parent.meizzTheMonth = this.value; parent.meizzSetDay(parent.meizzTheYear,parent.meizzTheMonth)'>\r\n";
  var selectInnerHTML = s;
  for (var i = 1; i < 13; i++)
  {
    if (i == m)
       {selectInnerHTML += "<option Author=wayx value='"+i+"' selected>"+i+"��"+"</option>\r\n";}
    else {selectInnerHTML += "<option Author=wayx value='"+i+"'>"+i+"��"+"</option>\r\n";}
  }
  selectInnerHTML += "</select>";
  odatelayer.tmpSelectMonthLayer.style.display="";
  odatelayer.tmpSelectMonthLayer.innerHTML = selectInnerHTML;
  odatelayer.tmpSelectMonth.focus();
}

function closeLayer()               //�@���ӵ��P�]
  {
    document.all.meizzDateLayer.style.display="none";
  }

function IsPinYear(year)            //�Д��Ƿ��cƽ��
  {
    if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
  }

function GetMonthCount(year,month)  //�c�����29��
  {
    var c=MonHead[month-1];if((month==2)&&IsPinYear(year)) c++;return c;
  }
function GetDOW(day,month,year)     //��ĳ������ڎ�
  {
    var dt=new Date(year,month-1,day).getDay()/7; return dt;
  }

function meizzPrevY()  //��ǰ�� Year
  {
    if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear--;}
    else{alert("��ݳ���������1000-9999����");}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzNextY()  //���ᷭ Year
  {
    if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear++;}
    else{alert("��ݳ���������1000-9999����");}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzToday()  //Today Button
  {
	var today;
    meizzTheYear = new Date().getFullYear();
    meizzTheMonth = new Date().getMonth()+1;
    today=new Date().getDate();
    //meizzSetDay(meizzTheYear,meizzTheMonth);
    if(outObject){
		outObject.value=meizzTheYear + "-" + meizzTheMonth + "-" + today;
    }
    closeLayer();
  }
function meizzPrevM()  //��ǰ���·�
  {
    if(meizzTheMonth>1){meizzTheMonth--}else{meizzTheYear--;meizzTheMonth=12;}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzNextM()  //���ᷭ�·�
  {
    if(meizzTheMonth==12){meizzTheYear++;meizzTheMonth=1}else{meizzTheMonth++}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }

function meizzSetDay(yy,mm)   //��Ҫ�Č���ʽ**********
{
  meizzWriteHead(yy,mm);
  //�O�î�ǰ���µĹ���׃�������ֵ
  meizzTheYear=yy;
  meizzTheMonth=mm;

  for (var i = 0; i < 39; i++){meizzWDay[i]=""};  //���@ʾ��ă���ȫ�����
  var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();  //ĳ�µ�һ������ڎ�
  for (i=0;i<firstday;i++)meizzWDay[i]=GetMonthCount(mm==1?yy-1:yy,mm==1?12:mm-1)-firstday+i+1	//�ς��µ��������
  for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++){meizzWDay[i]=day1;day1++;}
  for (i=firstday+GetMonthCount(yy,mm);i<39;i++){meizzWDay[i]=day2;day2++}
  for (i = 0; i < 39; i++)
  { var da = eval("odatelayer.meizzDay"+i)     //�����µ�һ���µ�������������
    if (meizzWDay[i]!="")
      {
		//��ʼ��߅��
		da.borderColorLight="#FF9900";
		da.borderColorDark="#FFFFFF";
		if(i<firstday)		//�ς��µĲ���
		{
			da.innerHTML="<b><font color=gray>" + meizzWDay[i] + "</font></b>";
			da.title=(mm==1?12:mm-1) +"��" + meizzWDay[i] + "��";
			da.onclick=Function("meizzDayClick(this.innerText,-1)");
			if(!outDate)
				da.style.backgroundColor = ((mm==1?yy-1:yy) == new Date().getFullYear() &&
					(mm==1?12:mm-1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ? "#FFD700":"#e0e0e0";
			else
			{
				da.style.backgroundColor =((mm==1?yy-1:yy)==outDate.getFullYear() && (mm==1?12:mm-1)== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())? "#00ffff" :
				(((mm==1?yy-1:yy) == new Date().getFullYear() && (mm==1?12:mm-1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ? "#FFD700":"#e0e0e0");
				//���x�е������@ʾ�鰼��ȥ
				if((mm==1?yy-1:yy)==outDate.getFullYear() && (mm==1?12:mm-1)== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())
				{
					da.borderColorLight="#FFFFFF";
					da.borderColorDark="#FF9900";
				}
			}
		}
		else if (i>=firstday+GetMonthCount(yy,mm))		//���µĲ���
		{
			da.innerHTML="<b><font color=gray>" + meizzWDay[i] + "</font></b>";
			da.title=(mm==12?1:mm+1) +"��" + meizzWDay[i] + "��";
			da.onclick=Function("meizzDayClick(this.innerText,1)");
			if(!outDate)
				da.style.backgroundColor = ((mm==12?yy+1:yy) == new Date().getFullYear() &&
					(mm==12?1:mm+1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ?	 "#FFD700":"#e0e0e0";
			else
			{
				da.style.backgroundColor =((mm==12?yy+1:yy)==outDate.getFullYear() && (mm==12?1:mm+1)== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())? "#00ffff" :
				(((mm==12?yy+1:yy) == new Date().getFullYear() && (mm==12?1:mm+1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ? "#FFD700":"#e0e0e0");
				//���x�е������@ʾ�鰼��ȥ
				if((mm==12?yy+1:yy)==outDate.getFullYear() && (mm==12?1:mm+1)== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())
				{
					da.borderColorLight="#FFFFFF";
					da.borderColorDark="#FF9900";
				}
			}
		}
		else		//���µĲ���
		{
			da.innerHTML="<b>" + meizzWDay[i] + "</b>";
			da.title=mm +"��" + meizzWDay[i] + "��";
			da.onclick=Function("meizzDayClick(this.innerText,0)");		//�otd�x��onclick�¼���̎��
			//����Ǯ�ǰ�x������ڣ��t�@ʾ���{ɫ�ı���������Ǯ�ǰ���ڣ��t�@ʾ���Sɫ����
			if(!outDate)
				da.style.backgroundColor = (yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate())?	"#FFD700":"#e0e0e0";
			else
			{
				da.style.backgroundColor =(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())? "#00ffff":((yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate())? 	"#FFD700":"#e0e0e0");
				//���x�е������@ʾ�鰼��ȥ
				if(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())
				{
					da.borderColorLight="#FFFFFF";
					da.borderColorDark="#FF9900";
				}
			}
		}
        da.style.cursor="hand"
      }
    else{da.innerHTML="";da.style.backgroundColor="";da.style.cursor="default"}
  }
}

function meizzDayClick(n,ex)  //�c���@ʾ���xȡ���ڣ���ݔ�뺯��*************
{
  var yy=meizzTheYear;
  var mm = parseInt(meizzTheMonth)+ex;	//ex��ʾƫ����������x���ς��·ݺ����·ݵ�����
	//�Д��·ݣ��K�M�Ќ�����̎��
	if(mm<1){
		yy--;
		mm=12+mm;
	}
	else if(mm>12){
		yy++;
		mm=mm-12;
	}
	
  if (mm < 10){mm = "0" + mm;}
  if (outObject)
  {
    if (!n) {//outObject.value="";
      return;}
    if ( n < 10){n = "0" + n;}
    outObject.value= yy + "-" + mm + "-" + n ; //ע�����@�Y�����ݔ���ĳ�����Ҫ�ĸ�ʽ
    closeLayer();
  }
  else {closeLayer(); alert("����Ҫݔ���Ŀ��������K�����ڣ�");}
}