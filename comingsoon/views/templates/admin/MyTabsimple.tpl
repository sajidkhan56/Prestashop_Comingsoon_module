 
<div>
  <p>{l s='Product name:' mod='comingsoon'}{Product::getProductName($id_product)|escape:'htmlall':'UTF-8'}</p>
</div>
<div class="form-check">
   <input type="hidden" name="id_product" value="{$id_product|escape:'htmlall':'UTF-8'}">
  <table class="table">
   <thead>
     <tr>
       <th scope="col">{l s='Simple Product' mod='comingsoon'}</th>
       <th hidden id="tablehead" scope="col">{l s='Arrival time' mod='comingsoon'}</th>
     </tr>
    </thead>
    <tbody>
      <tr>
       <td><input class="form-check-input cs" type="checkbox" name= 'id_product_array[]' id='flexCheckDefault' value="0"><label class="form-check-label" for="flexCheckDefault" >{Product::getProductName($id_product)|escape:'htmlall':'UTF-8'}</label></td> 
       <td>
        <div class="input-group datepicker remove" id="form_hooks_availabledate">
          <input type="text" class="form-control" data-format="YYYY-MM-DD"  name="availabledate[0]" placeholder="YYYY-MM-DD">
              <div class="input-group-append">
                <div class="input-group-text">
                      <i class="material-icons">date_range</i>
                </div>
              </div>
        </div>
      </td>
      </tr>
    </tbody>
  </table>
</div>
 