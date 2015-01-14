<{include file="head.tpl"}>
<{if $user|default:0}>
<div id="header">
	<ul id="nav">
		<li><a href="/">Home</a></li>
		<li><a href="/new">New</a></li>
		<li><a href="/susp">Susp</a></li>
		<li><a href="/stock">Stock</a></li>
		<li><a href="/holder">Holder</a></li>
		<li><a href="/mflow">MFlow</a></li>
		<li class="li-last"><{$user}> <a href="/sign?out">Sign out</a></li>
	</ul>
	<div class="green-line"></div>
</div>
<{/if}>