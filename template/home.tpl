<{include file="header.tpl"}>
<br><br>
<form name="form1" method="post" action="/">
<table width="98%" align="center" cellspacing="1" bgcolor="#cccccc">
  <tr>
    <td bgcolor="#FFFFFF">
    	<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" bgcolor="#eeeeee">
          <tr bgcolor="#FFFFFF"><td height="50" align="center" colspan="6">
      		<a class="btn-primary btn-large btn" href="/?run=1">RUN</a>
			ship=0,tradeNum=100,commend=20,ratesum=11,goodRate=99.5,dsrScore=4.8
			<div style="float:right; margin-top:4px;"><{$paging}></div>
          </td></tr>
		  <tr bgcolor="#FFFFFF" align="center">
            <td width="30">ID</td>
            <td width="80">Catelog ID</td>
            <td width="100">Title</td>
            <td>URL</td>
            <td width="30">Status</td>
            <td width="150">Condition</td>
          </tr>
          <{foreach from=$data item=start_url}>
          <tr bgcolor="#FFFFFF" align="center">
          	<td><{$start_url.id}></td>
		    <td><{$start_url.catelog_id}></td>
		    <td><{$start_url.title}></td>
		    <td style="word-wrap:break-word;word-break:break-all;"><a href="<{$start_url.url}>" target="_blank"><{$start_url.url}></a></td>
		    <td><{$start_url.status}></td>
		    <td style="word-wrap:break-word;word-break:break-all;"><{$start_url.conditions}></td>
          </tr>
          <{/foreach}>
          <tr bgcolor="#FFFFFF"><td height="50" align="center" colspan="6">
			<div style="float:right; margin-top:4px;"><{$paging}></div>
      		<a class="btn-primary btn-large btn" href="/?run=1">RUN</a>
          </td></tr>
        </table>
    </td>
  </tr>
</table>
</form>

<{include file="footer.tpl"}>
