<style type="text/css">
#ppmp-change-item-details-modal label { font-weight: bold; }
#ppmp-change-item-details-modal table { text-align: center; }
#ppmp-change-item-details-modal table thead td { font-weight: bold; background-color: #ddd; border-color: #fff; }
#ppmp-change-item-details-modal table tbody td { padding: 0; }
#ppmp-change-item-details-modal table tbody input { width: 100%; }
#ppmp-change-item-details-modal .col-sm-4, #item-price { text-align: right; }
.option-item { position: absolute; background: white; border: 1px solid; z-index: 10; width: 96%; max-height: 500px; overflow-y: scroll; }
.option-item > div { padding: 5px; cursor: pointer; }
.option-item > div:hover { color: white; background: #0071bc; }
</style>

<div class="modal fade" id="ppmp-change-item-details-modal" data-year="{{ $date['Year'] ?? date('Y') }}" data-month="{{ $date['Month'] ?? date('n') }}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change PPMP Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Item Code:</label>
          <div class="col-sm-10"><input type="text" class="form-control" id="item-code" disabled=""></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Item Description:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="item-desc">
            <div class="option-item"></div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Additional Specification:</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="additional-specs"></textarea>
            <div style="text-align: right;"><span id="additional-specs-length">0</span>/4,000 character limit</div>
          </div>
        </div>
        <table class="table table-bordered table-striped" style="text-align: center;" id="quantity-requirements">
          <thead>
            <tr><td colspan="12">Quantity Requirement</td></tr>
            <tr>
              <td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Jun</td>
              <td>Jul</td><td>Aug</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dec</td>
            </tr>
          </thead>
          <tbody>
            <td><input type="number" min="0" id="month-1" name="Qty_Jan" data-order="1" class="months"/></td>
            <td><input type="number" min="0" id="month-2" name="Qty_Feb" data-order="2" class="months"/></td>
            <td><input type="number" min="0" id="month-3" name="Qty_Mar" data-order="3" class="months"/></td>
            <td><input type="number" min="0" id="month-4" name="Qty_Apr" data-order="4" class="months"/></td>
            <td><input type="number" min="0" id="month-5" name="Qty_May" data-order="5" class="months"/></td>
            <td><input type="number" min="0" id="month-6" name="Qty_Jun" data-order="6" class="months"/></td>
            <td><input type="number" min="0" id="month-7" name="Qty_Jul" data-order="7" class="months"/></td>
            <td><input type="number" min="0" id="month-8" name="Qty_Aug" data-order="8" class="months"/></td>
            <td><input type="number" min="0" id="month-9" name="Qty_Sep" data-order="9" class="months"/></td>
            <td><input type="number" min="0" id="month-10" name="Qty_Oct" data-order="10" class="months"/></td>
            <td><input type="number" min="0" id="month-11" name="Qty_Nov" data-order="11" class="months"/></td>
            <td><input type="number" min="0" id="month-12" name="Qty_Dec" data-order="12" class="months"/></td>
          </tbody>
        </table>
        <div class="row" style="{{ session('FundClass') == 'TRUST' ? '' : 'display:none;' }}">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Object Of Expend:</label>
          <div class="col-sm-4"><select class="form-control" id="oecode"></select></div>
        </div>
        <div class="row">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Unit:</label>
          <div class="col-sm-4" id="item-unit"></div>
        </div>
        <div class="row">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Unit Price:</label>
          <div class="col-sm-4"><input type="number" min="0" class="form-control" id="item-price" /></div>
        </div>
        <div class="row">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Total Quantity:</label>
          <div class="col-sm-4" id="total-qty"></div>
        </div>
        <div class="row">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Contingency:</label>
          <div class="col-sm-4" id="vat">10%</div>
        </div>
        <div class="row">
          <div class="col-sm-6"></div>
          <label class="col-sm-2 col-form-label">Amount:</label>
          <div class="col-sm-4" id="item-amount"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-ok">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var ppmpChangeDetailsModal = (function(){
  var items = [], details = {}, el = {}, year = 0, status = '', param = {}, ajaxReq = null;

  function init(){ cacheDOM(); bindEvents(); }
  function cacheDOM(){
    el.$modal=$('#ppmp-change-item-details-modal'); el.$btnOK=el.$modal.find('#btn-ok');
    el.$txtItemCode=el.$modal.find('#item-code'); el.$txtItemDesc=el.$modal.find('#item-desc');
    el.$txtMoreSpecs=el.$modal.find('#additional-specs'); el.$txtUnitPrice=el.$modal.find('#item-price');
    el.$txtMonths=el.$modal.find('.months'); el.$txtOECode=el.$modal.find('#oecode'); el.$optItem=el.$modal.find('.option-item');
  }
  function bindEvents(){
    el.$modal.on('change','#quantity-requirements input, #item-price',function(){getItem();render();});
    el.$txtMoreSpecs.on('keyup',function(){ var t=$(this).val(); if(t.length>4000){t=t.substring(0,4000);$(this).val(t);alert('Exceeded maximum characters.');} $('#additional-specs-length').text(t.length); });
    el.$txtItemDesc.on('keyup',searchItem); el.$optItem.on('click','div',setItem); el.$btnOK.on('click',save);
  }
  function get(id,header){
    year=header.PPMPYear||0; status=header.Status||''; param=header.Param||{};
    if(ajaxReq!=null)ajaxReq.abort();
    if(typeof ContentLoading!=='undefined')ContentLoading.On('#ppmp-change-item-details-modal .modal-content');
    ajaxReq=$.ajax({type:"post",url:base_url+"ppmp/get_ppmp_item",data:{ppmpdetailsid:id},
      success:function(response){ try{var res=JSON.parse(response);if(res.Status=='ROLLBACK'){alert(res.Message);return;} details=res;render();}catch(er){alert(er.message||er);} ajaxReq=null; if(typeof ContentLoading!=='undefined')ContentLoading.Off('#ppmp-change-item-details-modal .modal-content'); },
      error:function(){ ajaxReq=null; if(typeof ContentLoading!=='undefined')ContentLoading.Off('#ppmp-change-item-details-modal .modal-content'); alert("Something went wrong..."); }
    });
  }
  function searchItem(e){ if(e.which!=13&&e.type=='keyup')return; var criteria={InActive:0,ACCode:$(e.currentTarget).data('accode')||'',SpecDetails:$(e.currentTarget).val()||''}; if(ajaxReq!=null)ajaxReq.abort(); $('.option-item').html('<div data-id="">Searching item...</div>'); ajaxReq=$.ajax({type:"post",url:base_url+"items/search_main_articles",data:{criteria:criteria}, success:function(response){ items=JSON.parse(response); var row=''; $('.option-item').html(''); for(var i in items){ row+='<div data-id="'+i+'">'+items[i].ItemCode+' | '+items[i].SpecDetails+'</div>'; } if(row=='')row='<div data-id="">No matched item found.</div>'; $('.option-item').html(row); ajaxReq=null; }, error:function(){ajaxReq=null;} }); }
  function setItem(e){ if($(e.currentTarget).data('id')==='')return; var item=items[$(e.currentTarget).data('id')]; el.$txtItemCode.val(item.ItemCode); el.$txtItemDesc.val(item.SpecDetails); el.$txtItemDesc.data('itemid',item.ItemID); $('.option-item').html(''); }
  function save(){ if(!confirm('Are you sure you want to update this record?'))return; getItem(); if(typeof ContentLoading!=='undefined')ContentLoading.On('#ppmp-change-item-details-modal .modal-content'); $.ajax({type:"post",url:base_url+"ppmp/save_change_item_details",data:{criteria:details}, success:function(response){ try{var res=JSON.parse(response);alert(res.Message);}catch(er){alert(er.message||er);} if(typeof ContentLoading!=='undefined')ContentLoading.Off('#ppmp-change-item-details-modal .modal-content'); }, error:function(){ if(typeof ContentLoading!=='undefined')ContentLoading.Off('#ppmp-change-item-details-modal .modal-content'); alert("Something went wrong..."); } }); }
  function getItem(){
    details.ItemID=el.$txtItemDesc.data('itemid')||0; details.MoreSpecs=$('#additional-specs').val()||''; details.OECode=el.$txtOECode.val()||'';
    details.Qty_Jan=Math.abs(parseInt($('#month-1').val())||0); details.Qty_Feb=Math.abs(parseInt($('#month-2').val())||0); details.Qty_Mar=Math.abs(parseInt($('#month-3').val())||0); details.Qty_Apr=Math.abs(parseInt($('#month-4').val())||0); details.Qty_May=Math.abs(parseInt($('#month-5').val())||0); details.Qty_Jun=Math.abs(parseInt($('#month-6').val())||0); details.Qty_Jul=Math.abs(parseInt($('#month-7').val())||0); details.Qty_Aug=Math.abs(parseInt($('#month-8').val())||0); details.Qty_Sep=Math.abs(parseInt($('#month-9').val())||0); details.Qty_Oct=Math.abs(parseInt($('#month-10').val())||0); details.Qty_Nov=Math.abs(parseInt($('#month-11').val())||0); details.Qty_Dec=Math.abs(parseInt($('#month-12').val())||0); details.UnitPrice=Math.abs(parseFloat($('#item-price').val())||0);
  }
  function render(){
    var q=0, m=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    for(var i=1;i<=12;i++){ q+=details['Qty_'+m[i-1]]||0; $('#month-'+i).val(details['Qty_'+m[i-1]]||0); }
    details.Qty_Tot=q;
    el.$modal.find('#item-code').val(details.ItemCode||'');
    el.$modal.find('#item-desc').data('accode',details.ACCode||'');
    el.$modal.find('#item-desc').data('itemid',details.ItemID||0);
    el.$modal.find('#item-desc').val(details.SpecDetails||'');
    el.$modal.find('#additional-specs').val(details.MoreSpecs||'');
    el.$modal.find('#oecode').find('option[value="'+(details.OECode||'')+'"]').prop('selected',true);
    el.$modal.find('#item-unit').text(details.UnitName||'');
    el.$modal.find('#item-price').val(details.UnitPrice||0);
    el.$modal.find('#vat').text(details.Contingency||'10%');
    el.$modal.find('#total-qty').text(numberFormat(details.Qty_Tot)+'('+numberFormat(details.UtilQuantity||0)+')');
    el.$modal.find('#item-amount').text(numberFormat(Number(((details.Qty_Tot*(details.UnitPrice||0))*(details.Contingency||1)).toFixed(2)))+'('+numberFormat(details.UtilAmount||0)+')');
    if(details.Common==1){ el.$modal.find('#additional-specs').prop('disabled',true); el.$modal.find('#item-price').prop('disabled',true); } else { el.$modal.find('#additional-specs').prop('disabled',false); el.$modal.find('#item-price').prop('disabled',false); }
    onKeyupMoreSpecs();
  }
  function toggleModal(){ el.$modal.modal('toggle'); el.$optItem.html(''); }
  return { init: init, load: get, toggle: toggleModal, get: getItem };
})();
ppmpChangeDetailsModal.init();
</script>
