<{include file="header.tpl"}>
<br><br>
<table width="600" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr align="center">
    <td bgcolor="#FFFFFF" style="padding-left:10px;">
	<form name="form1" method="get" action="">
	From:<input type="text" name="from" value="<{$from}>">
	To:<input type="text" name="to" value="<{$to}>">
	<input type="submit" class="btn-primary btn-large btn" value="Search" />
	</from>
    </td>
  </tr>
</table>
<br><br>

<table width="600" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF">
    	<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
    	 <tr bgcolor="#FFFFFF" align="center">
            <td width="20%">ID</td>
		    <td width="40%">时间</td>
		    <td width="40%">代码</td>
          </tr>
          <{foreach from=$data item=dt}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><{$dt.id}></td>
		    <td><{date('Y-m-d', $dt.days)}></td>
		    <td><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank"><{$dt.ticker}></a></td>
          </tr>
          <{/foreach}>
        </table>
    </td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee" style="position:fixed;left:1%;bottom:0;">
 <tr align="center"><td>
 	<table width="600" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
    	<tr bgcolor="#FFFFFF" align="center">
          <td width="20%">ID</td>
		  <td width="40%">时间</td>
		  <td width="40%">代码</td>
        </tr>
	</table>
 </td></tr>
</table>
<a class="btn-primary btn-large btn" href="/?run=1">RUN</a>

<{include file="footer.tpl"}>
