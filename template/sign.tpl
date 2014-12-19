<{include file="header.tpl"}>

<{if $user|default:0}>
	<{$user}>
    <a href="/sign?out">Sign Out</a>
<{else}>
	<br /><br /><br /><br /><br />
	<form name="form1" method="post" action="/sign?in">
	<table align="center" cellspacing="1" bgcolor="#cccccc">
	  <tr>
	    <td bgcolor="#FFFFFF">
	    	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fafafa">
	          <tr>
	            <td width="248" height="100" align="center">Username:<input type="text" name="user" /></td>
	            <td width="231" align="center">Password:<input type="password" name="passwd" /></td>
	            <td width="121" align="center"><input type="submit" name="submit" value="Sign in" style="width:60px; height:30px" /></td>
	          </tr>
	        </table>
	    </td>
	  </tr>
	</table>
	</form>
<{/if}>

<{include file="footer.tpl"}>
