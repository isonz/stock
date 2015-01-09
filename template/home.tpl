<{include file="header.tpl"}>
<br><br>

<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF">
    	<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
    	 <tr bgcolor="#FFFFFF" align="center">
            <td width="10%">ID</td>
		    <td width="15%">时间</td>
		    <td width="5%">次数</td>
		    <td width="15%" class="hidename" id="hidename">名称</td>
		    <td width="15%">代码</td>
		    <td width="10%">百分比</td>
		    <td width="10%">当前值</td>
		    <td width="10%">建议值</td>
		    <td width="10%">振幅</td>
          </tr>
          <{foreach from=$data item=dt}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><{$dt.id}></td>
		    <td<{if $date == $dt.days}> style="background:#ff0"<{/if}>><{$dt.days}></td>
		    <td><a href="stock?ticker=<{$dt.ticker}>" target="_blank"><{$dt.numb}></a></td>
		    <td class="tdahidenname"><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank" class="hidename"><{$dt.sname}></a></td>
		    <td><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank"><{$dt.ticker}></a></td>
		    <td><a href="mflow?ticker=<{$dt.ticker}>&days=<{$dt.days}>" target="_blank"><{$dt.changepercent}></a></td>
		    <td><{$dt.trade}></td>
		    <td<{if $date == $dt.days}> style="background:#ff0"<{/if}>><{$dt.trade * 1.1}></td>
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
    <td width="5%">次数</td>
    <td width="15%" class="hidename" id="hidename1">名称</td>
    <td width="15%">代码</td>
    <td width="10%">百分比</td>
    <td width="10%">当前值</td>
	<td width="10%">建议值</td>
    <td width="10%">振幅</td>
 </tr>
</table>
<a class="btn-primary btn-large btn" href="##">RUN</a>
<br><br>
<div class="hidename" style="line-height:30px; padding-left:20%;">
1、主力买入不超过 70%。<br>
2、主力买入与散户买入相差要大，最好 30%以上。<br>
3、主力卖出不超过 10%。<br>
4、开盘价要远大于前一天的收盘价。<br>
5、成交量不要超过 10 万手。<br>
6、起点好，或是之前有过很久停牌。<br>
</div>
<br>
<{include file="footer.tpl"}>
