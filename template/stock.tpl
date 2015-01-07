<{include file="header.tpl"}>
<br><br>
<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr align="center">
    <td bgcolor="#FFFFFF" style="padding-left:10px;">
	<form name="form1" method="get" action="">
	Ticker:<input type="text" name="ticker" value="<{$ticker}>">
	From:<input type="text" name="from" value="<{$from}>">
	To:<input type="text" name="to" value="<{$to}>">
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
            <td width="10%">ID</td>
		    <td width="15%">时间</td>
		    <td width="15%" class="hidename" id="hidename">名称</td>
		    <td width="20%">代码</td>
		    <td width="10%">百分比</td>
		    <td width="10%">当前值</td>
		    <td width="10%">振幅</td>
          </tr>
          <{foreach from=$data item=dt}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><{$dt.id}></td>
		    <td><{$dt.date}></td>
		    <td class="tdahidenname"><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank" class="hidename"><{$dt.sname}></a></td>
		    <td><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank"><{$dt.ticker}></a></td>
		    <td><a href="mflow?ticker=<{$dt.ticker}>&days=<{$dt.days}>" target="_blank"><{$dt.changepercent}></a></td>
		    <td><{$dt.trade}></td>
		    <td><{$dt.pricechange}></td>
          </tr>
          <{/foreach}>
        </table>
    </td>
  </tr>
</table>

<table width="98%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee" style="position:fixed;left:1%;bottom:0;">
 <tr align="center">
	<td width="10%">ID</td>
    <td width="15%">时间</td>
    <td width="15%" style="color:#fff">名称</td>
    <td width="20%">代码</td>
    <td width="10%">百分比</td>
    <td width="10%">当前值</td>
    <td width="10%">振幅</td>
 </tr>
</table>
<a class="btn-primary btn-large btn" href="/?run=1">RUN</a>

<{include file="footer.tpl"}>
