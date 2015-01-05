<{include file="header.tpl"}>
<br><br>

<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr align="center">
    <td bgcolor="#FFFFFF" style="padding-left:10px;">
	<form name="form1" method="get" action="">
	Ticker:<input type="text" name="ticker" value="<{$ticker}>">
	Date:<input type="text" name="days" value="<{$days}>">
	<input type="submit" class="btn-primary btn-large btn" value="Search" />
	</from>
    </td>
  </tr>
</table>

<br><br>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><table width="98%" border="0" cellspacing="1" cellpadding="0" bgcolor="#DCE5F4">
      <tr>
        <td height="30" colspan="5" align="center"><a href="http://vip.stock.finance.sina.com.cn/moneyflow/#!ssfx!sh600702" target="_blank">主力、散户资金流向</a></td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="20%" bgcolor="#FFFFFF">主力买入</td>
        <td width="20%" bgcolor="#FFFFFF">主力卖出</td>
        <td width="20%" bgcolor="#FFFFFF">散户买入</td>
        <td width="20%" bgcolor="#FFFFFF">散户卖出</td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">金额（万元）</td>
        <td bgcolor="#FFFFFF"><{if isset($mains.mainin.i)}><{$mains.mainin.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.mainout.i)}><{$mains.mainout.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.retailin.i)}><{$mains.retailin.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.retailout.i)}><{$mains.retailout.i}><{/if}></td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">比例</td>
        <td bgcolor="#FFFFFF"><{if isset($mains.mainin.p)}><{$mains.mainin.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.mainout.p)}><{$mains.mainout.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.retailin.p)}><{$mains.retailin.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($mains.retailout.p)}><{$mains.retailout.p * 100}>%<{/if}></td>
      </tr>
    </table></td>
  </tr>
</table>

<br><br><br><br>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><table width="98%" border="0" cellspacing="1" cellpadding="0" bgcolor="#DCE5F4">
      <tr>
        <td height="30" colspan="5" align="center"><a href="http://vip.stock.finance.sina.com.cn/moneyflow/#!ssfx!sh600702" target="_blank">分类资金净流入额</a></td>
      </tr>
      <tr align="center">
        <td width="20%" height="30" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="20%" bgcolor="#FFFFFF">散单</td>
        <td width="20%" bgcolor="#FFFFFF">小单</td>
        <td width="20%" bgcolor="#FFFFFF">大单</td>
        <td bgcolor="#FFFFFF">特大单</td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">净流入（万元）</td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r3.i)}><{$cates.r3.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r2.i)}><{$cates.r2.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r1.i)}><{$cates.r1.i}><{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r0.i)}><{$cates.r0.i}><{/if}></td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">占流通盘比例</td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r3.p)}><{$cates.r3.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r2.p)}><{$cates.r2.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r1.p)}><{$cates.r1.p * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r0.p)}><{$cates.r0.p * 100}>%<{/if}></td>
      </tr>
      <tr align="center">
        <td height="30" bgcolor="#FFFFFF">占换手率比例</td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r3.t)}><{$cates.r3.t * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r2.t)}><{$cates.r2.t * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r1.t)}><{$cates.r1.t * 100}>%<{/if}></td>
        <td bgcolor="#FFFFFF"><{if isset($cates.r0.t)}><{$cates.r0.t * 100}>%<{/if}></td>
      </tr>
    </table></td>
  </tr>
</table>

<br><br><br><br>
<{include file="footer.tpl"}>
