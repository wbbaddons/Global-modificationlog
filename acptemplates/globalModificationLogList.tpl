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
				<dl>
					<dt>{lang}wcf.acp.modification.log.filter.action{/lang}</dt>
					<dd>
						<select id="objectAction" name="action">
						</select>
					</dd>
					<script data-relocate="true" src="{@$__wcf->getPath()}acp/js/be.bastelstu.josh.globalmodificationlog.js?v={$__wcfVersion}"></script>
					<script data-relocate="true">
						$action = new be.bastelstu.josh.globalmodificationlog.Handler("#objectAction", "#objectType");
						
						WCF.Language.addObject({
							'wcf.acp.modification.log.action.close': '{lang}wcf.acp.modification.log.action.close{/lang}',
							'wcf.acp.modification.log.action.open': '{lang}wcf.acp.modification.log.action.open{/lang}',					
							'wcf.acp.modification.log.action.delete': '{lang}wcf.acp.modification.log.action.delete{/lang}',
							'wcf.acp.modification.log.action.trash': '{lang}wcf.acp.modification.log.action.trash{/lang}',
							'wcf.acp.modification.log.action.restore': '{lang}wcf.acp.modification.log.action.restore{/lang}',
							'wcf.acp.modification.log.action.done': '{lang}wcf.acp.modification.log.action.done{/lang}',
							'wcf.acp.modification.log.action.undone': '{lang}wcf.acp.modification.log.action.undone{/lang}',
							'wcf.acp.modification.log.action.move': '{lang}wcf.acp.modification.log.action.move{/lang}',
							'wcf.acp.modification.log.action.setLabel': '{lang}wcf.acp.modification.log.action.setLabel{/lang}',
							'wcf.acp.modification.log.action.enable': '{lang}wcf.acp.modification.log.action.enable{/lang}',
							'wcf.acp.modification.log.action.disable': '{lang}wcf.acp.modification.log.action.disable{/lang}',
							'wcf.acp.modification.log.action.edit': '{lang}wcf.acp.modification.log.action.edit{/lang}'
						});
						
						// add options
						{literal}$action.addOption("all",{"com.woltlab.wbb.post":!0,"com.woltlab.wbb.thread":!0}),$action.addOption("close",{"com.woltlab.wbb.post":!0,"com.woltlab.wbb.thread":!0}),$action.addOption("delete",{"com.woltlab.wbb.post":!0,"com.woltlab.wbb.thread":!0}),$action.addOption("trash",{"com.woltlab.wbb.post":!0,"com.woltlab.wbb.thread":!0}),$action.addOption("restore",{"com.woltlab.wbb.post":!0,"com.woltlab.wbb.thread":!0}),$action.addOption("done",{"com.woltlab.wbb.thread":!0}),$action.addOption("undone",{"com.woltlab.wbb.thread":!0}),$action.addOption("move",{"com.woltlab.wbb.thread":!0}),$action.addOption("setLabel",{"com.woltlab.wbb.thread":!0}),$action.addOption("enable",{"com.woltlab.wbb.thread":!0,"com.woltlab.wbb.post":!0}),$action.addOption("disable",{"com.woltlab.wbb.thread":!0,"com.woltlab.wbb.post":!0}),$action.addOption("edit",{"com.woltlab.wbb.post":!0});{/literal}
						
						{if $action}
							$action.selectAction("{$action}");
						{/if}
						
						{event name='globalmodificationlogJS'}
					</script>
				</dl>
				<dl>
					<dt><label for="timeAfter">{lang}wcf.acp.modification.log.filter.timeframe{/lang}</label></dt>
					<dd>
						<input type="date" id="startDate" name="startDate" value="{$startDate}" placeholder="{lang}wcf.acp.modification.log.filter.timeframe.start{/lang}" />
						<input type="date" id="endDate" name="endDate" value="{$endDate}" placeholder="{lang}wcf.acp.modification.log.filter.timeframe.end{/lang}" />
					</dd>
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