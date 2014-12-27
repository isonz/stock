<{include file="head.tpl"}>
<{if $user|default:0}>
<div id="header">
	<ul id="nav">
		<li><a href="/">Home</a></li>
		<li class="li-last"><{$user}> <a href="/sign?out">Sign out</a></li>
	</ul>
	<div class="green-line"></div>
</div>
<{/if}>