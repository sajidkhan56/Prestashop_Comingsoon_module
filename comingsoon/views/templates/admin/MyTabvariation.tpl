 
<div>
  <p>{l s='Product name:' mod='comingsoon'}{Product::getProductName($id_product)|escape:'htmlall':'UTF-8'}</p>
</div>
<div class="form-check">
  <input type="hidden" name="id_product" value="{$id_product|escape:'htmlall':'UTF-8'}">
  <table class="table">
   <thead>
     <tr>
       <th scope="col">{l s='Combinations' mod='comingsoon'}</th>
       <th hidden id="tablehead" scope="col">{l s='Arrival time' mod='comingsoon'}</th>
     </tr>
    </thead>
    <tbody>
    {foreach $combinationdata as $data}
      <tr class="parent-row">
       <td>
        <input class="form-check-input cs" type="checkbox" name="id_product_array[]" id='flexCheckDefault{$data.id_product_attribute|escape:'htmlall':'UTF-8' }' value="{$data.id_product_attribute|escape:'htmlall':'UTF-8' }">
        <label class="form-check-label" for="flexCheckDefault{$data.id_product_attribute|escape:'htmlall':'UTF-8' }">
          {Product::getProductName($id_product, $data.id_product_attribute)|escape:'htmlall':'UTF-8'}
        </label>
      </td> 
       <td>
        <div class="input-group datepicker a-date" id="form_hooks_availabledate">
          <input type="text" class="form-control" data-format="YYYY-MM-DD" name="availabledate[{$data.id_product_attribute|escape:'htmlall':'UTF-8'}]" placeholder="YYYY-MM-DD" >
              <div class="input-group-append">
                <div class="input-group-text">
                      <i class="material-icons">date_range</i>
                </div>
              </div>
        </div>
      </td>
      </tr>
    {/foreach}
    </tbody>
  </table>
</div>
 