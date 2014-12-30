{include file='header' pageTitle='wcf.acp.menu.link.log.modification'}

<header class="boxHeadline">
	<h1>{lang}wcf.acp.menu.link.log.modification{/lang}</h1>
</header>

<form method="post" action="{link controller='GlobalModificationLogList'}{/link}">
	<div class="container containerPadding marginTop">
			<fieldset>
				<legend>{lang}wcf.acp.modification.log.filter{/lang}</legend>

				<dl>
					<dt>{lang}wcf.acp.modification.log.filter.objectType{/lang}</dt>
					<dd>
						<select id="objectType" name="objectType">
							<option value="all">{lang}wcf.acp.modification.log.filter.objectType.all{/lang}</option>
							<option value="com.woltlab.wbb.post"{if $objectType == 'com.woltlab.wbb.post'} selected="selected"{/if}>{lang}wcf.acp.modification.log.filter.objectType.post{/lang}</option>
							<option value="com.woltlab.wbb.thread"{if $objectType == 'com.woltlab.wbb.thread'} selected="selected"{/if}>{lang}wcf.acp.modification.log.filter.objectType.thread{/lang}</option>

							{event name='filterObjectType'}
						</select>
					</dd>
				</dl>		
				<dl>
					<dt>{lang}wcf.acp.modification.log.filter.user{/lang}</dt>
					<dd><input type="text" id="username" name="username" value="{implode from=$usernames item=username glue=", "}{$username}{/implode}" class="long"></dd>
				</dl>
			</fieldset>
	</div>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
	</div>
</form>


<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="GlobalModificationLogList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$filter"}

	{hascontent}
	<nav>
		<ul>
			{content}
			{event name='contentNavigationButtonsTop'}
			{/content}
		</ul>
	</nav>
	{/hascontent}
</div>

{if $objects|count}
	<div class="tabularBox tabularBoxTitle marginTop">
		<header>
			<h2>{lang}wcf.acp.menu.link.log.modification{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
		</header>

		<table class="table">
			<thead>
				<tr>
					<th class="columnTitle columnUser" colspan="2">{lang}wcf.acp.modification.log.user{/lang}</th>
					<th class="columnTitle columnAction">{lang}wcf.acp.modification.log.action{/lang}</th>
					<th class="columnTitle columnObject">{lang}wcf.acp.modification.log.object{/lang}</th>
					<th class="columnDate columnTime{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link controller='GlobalModificationLogList'}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.modification.log.time{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>

			<tbody>
				{foreach from=$objects item="log"}
					<tr>
						<td class="columnIcon columnAvatar">
							{if $log->getUserProfile() && $log->getUserProfile()->getAvatar()}
								<div>
									<p class="framed">{@$log->getUserProfile()->getAvatar()->getImageTag(32)}</p>
								</div>
							{/if}
						</td>
						<td class="columnIcon columnUsername">
							{if $log->getUserProfile() && $log->getUserProfile()->getAvatar()}
								{if $log->getUserProfile()->userID != 0}<a href="{link controller='UserEdit' id=$log->getUserProfile()->userID}{/link}">{$log->username}</a>{else}<em>{$log->username}</em>{/if}
								{/if}
						</td>
						<td class="columnTitle columnAction">{@$log}</td>
						<td class="columnTitle columnObject">{if $log->getObject() && $log->getObject()->getObjectID() != 0}<a href="{$log->getObject()->getLink()}">{$log->getObject()->getTitle()}</a>{else}<em>{lang}wcf.acp.modification.log.object.unknown{/lang}</em>{/if}</td>
						<td class="columnDate columnTime">{@$log->time|time}</td>

						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

	<div class="contentNavigation">
		{@$pagesLinks}

		{hascontent}
		<nav>
			<ul>	
				{content}
				{event name='contentNavigationButtonsBottom'}
				{/content}
			</ul>
		</nav>
		{/hascontent}
	</div>
{else}
	<p class="info">{lang}wcf.acp.cronjob.log.noEntries{/lang}</p>
{/if}

{include file='footer'}