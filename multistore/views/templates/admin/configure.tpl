<div class="bootstrap">
	<div class="col-lg-2">
		<div class="list-group config">
			<a id="parameterconfig" href="#parameter" class="menu_tab list-group-item" data-toggle="tab"><i class="icon-cogs"></i> {l s='Parameters' mod='multistore'}</a>
			<a href="#store_management" class="menu_tab list-group-item" data-toggle="tab"><i class="icon-user"></i> {l s='Store management' mod='multistore'}</a>
		</div>
		<div class="list-group">
			<a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='multistore'} {$module_version|escape:'htmlall':'UTF-8'}</a>
		</div>
	</div>
	<div class="tab-content col-lg-10">
		<div class="tab-pane panel active" id="parameter">
			{include file="./tabs/parameter.tpl"}
		</div>
		<div class="tab-pane panel" id="store_management">
			{include file="./tabs/store_management.tpl"}
		</div>
	</div>
</div>