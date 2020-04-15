    <h3 style="text-align: center;">{l s='Choose a store to collect your order' mod='multistore'}</h3>
    </br>
    </br>


<div class="card">
 {foreach from=$stores item=store name=top_3}
          {if $smarty.foreach.top_3.index < 3}
						<div class="card mb-3" style="">
							<div class="row no-gutters">
								<div class="col-md-4">
                <img class="card-img-top" src="https://img.icons8.com/cotton/2x/shop--v3.png" alt="Card image cap">
								</div>
								<div class="col-md-8">
									<div class="card-body">
                  <h4 class="card-title mt-1">{$store.name}</h4>
										<p class="card-text my-2">{$store.address1}</br>
                  {$store.postcode}</br>
                  {$store.city}</p>
									</div>
										<div class="product-info">
                    <small class="text-muted mb-2">{$store.hours}</small>
										</div>
										 <button type="submit" class="continue btn btn-primary float-xs-right" name="confirmDeliveryOption" value="1">
                  {l s='Select this shop' mod='multistore'}</button>
								</div>
							</div>
						</div>
{/if}
        {/foreach}
</div>