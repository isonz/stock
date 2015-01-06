<{include file="header.tpl"}>
<br><br>
<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr align="center">
    <td bgcolor="#FFFFFF" style="padding-left:10px;">
	<form name="form1" method="get" action="">
	 Day Number:<input type="text" name="n" value="<{$n}>">
	<input type="submit" class="btn-primary btn-large btn" value="Search" />
	</from>
    </td>
  </tr>
</table>
<br><br>

<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF">
    	<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
    	 <tr bgcolor="#FFFFFF" align="center">
            <td width="25%">Ticker</td>
		    <td width="25%" style="color:#fff">名称</td>
		    <td width="25%">更新时间</td>
		    <td width="25%">创建时间</td>
          </tr>
          <{foreach from=$data item=dt}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><a href="stock?ticker=<{$dt.ticker}>" target="_blank"><{$dt.ticker}></a></td>
		    <td><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank" style="color:#fff"><{$dt.name}></a></td>
		    <td><{$dt.update_at}></td>
		    <td><{$dt.created_at}></td>
          </tr>
          <{/foreach}>
        </table>
    </td>
  </tr>
</table>

<table width="98%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee" style="position:fixed;left:1%;bottom:0;">
 <tr bgcolor="#FFFFFF" align="center">
    <td width="25%">Ticker</td>
    <td width="25%" style="color:#fff">名称</td>
    <td width="25%">更新时间</td>
    <td width="25%">创建时间</td>
 </tr>
</table>
<a class="btn-primary btn-large btn" href="/?run=1">RUN</a>

<{include file="footer.tpl"}>
