<h1 style="text-align: center;">{l s='Store management' mod='multistore'}</h1>

<div class="table-responsive-row clearfix">
    <table id="table-employee" class="table employee">
        <thead>
            <tr class="nodrag nodrop">
                <th class="">
                    <span class="title_box active">
                        {l s='#' mod='multistore'}
                    </span>
                </th>
                <th class="">
                    <span class="title_box">
                        {l s='shop' mod='multistore'}
                    </span>
                </th>
                <th class="">
                    <span class="title_box">
                        {l s='employee' mod='multistore'}
                    </span>
                </th>
            </tr>
            {foreach from=$stores item=store name=top_3}
            {if $smarty.foreach.top_3.index < 3} <tr>
                <td>{$store.id_store}</td>
                <td>{$store.name}</td>
                <td>
                    <select>
                        {foreach from=$employees item=employee}
                        <option>{$employee.lastname} {$employee.firstname}</option>
                        {/foreach}
                    </select>
                </td>
                </tr>
                {/if}
                {/foreach}
        </thead>
    </table>
</div>
<div class="panel-footer">
	<div class="btn-group pull-right">
		<button name="submitParameters" id="submitParameters" type="submit" class="btn btn-default">
			<i class="process-icon-save"></i>
			{l s='Save' mod='multistore'}
		</button>
	</div>
</div>