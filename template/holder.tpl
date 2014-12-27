<{include file="header.tpl"}>
<br><br>

<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF" style="padding-left:10px;">
	<form name="form1" method="get" action="">
	Name:<input type="text" name="name">
	<input type="submit" class="btn-primary btn-large btn" value="Search" />
	</from>
    </td>
  </tr>
</table>


<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF">
    	<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
    	 <tr bgcolor="#FFFFFF" align="center">
            <td width="10%">ID</td>
            <td width="10%">时间</td>
            <td width="10%">代码</td>
            <td width="30%">名字</td>
            <td width="10%">数量</td>
            <td width="10%">比例</td>
            <td width="10%">性质</td>
            <td width="10%">类型</td>
          </tr>
          <{foreach from=$data item=dt}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><{$dt.id}></td>
		    <td><{$dt.days}></td>
		    <td><a href="http://finance.sina.com.cn/realstock/company/<{$dt.ticker}>/nc.shtml" target="_blank"><{$dt.ticker}></a></td>
		    <td><{$dt.holder}></td>
		    <td><{$dt.shares}></td>
		    <td><{$dt.stake}></td>
		    <td><{$dt.nature}></td>
		    <td><{$dt.type}></td>
          </tr>
          <{/foreach}>
        </table>
    </td>
  </tr>
</table>

<table width="98%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee" style="position:fixed;left:1%;bottom:0;">
 <tr align="center">
	<td width="10%">ID</td>
    <td width="10%">时间</td>
    <td width="10%">代码</td>
    <td width="30%">名字</td>
    <td width="10%">数量</td>
    <td width="10%">比例</td>
    <td width="10%">性质</td>
    <td width="10%">类型</td>
 </tr>
</table>

<{include file="footer.tpl"}>
