<style type="text/css">
#ppmp-item-details-modal label { font-weight: bold; }
#ppmp-item-details-modal table { text-align: center; }
#ppmp-item-details-modal table thead td { font-weight: bold; background-color: #ddd; border-color: #fff; }
#ppmp-item-details-modal table tbody td { padding: 0; }
#ppmp-item-details-modal table tbody input { width: 100%; }
#ppmp-item-details-modal .col-sm-4, #item-price { text-align: right; }
</style>

<div data-backdrop="static" data-keyboard="false" class="modal fade" id="ppmp-item-details-modal" data-year="{{ $date['Year'] ?? date('Y') }}" data-month="{{ $date['Month'] ?? date('n') }}">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Procurement Code:</label>
          <div class="col-sm-2"><input type="text" class="form-control" id="pro-code"/></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Procurement Mode:</label>
          <div class="col-sm-10">
            <select class="form-control" id="pro-mode">
              <option value="">-- Select Option --</option>
              @if(isset($ModesOfProcurement) && is_array($ModesOfProcurement))
                @foreach($ModesOfProcurement as $row)
                  <option value="{{ $row['ModeID'] ?? '' }}">{{ $row['MocDesc'] ?? '' }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Expected Date of Delivery:</label>
          <div class="col-sm-10"><input type="date" class="form-control" min="{{ date('Y-m-d') }}" id="ExpectedDateOfDelivery"/></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Remarks:</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="Remarks"></textarea>
            <div style="text-align: right;"><span id="RemarksMeter">0</span> / 250 character limit</div>
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
          <label class="col-sm-2 col-form-label">Amount:</label>
          <div class="col-sm-4" id="item-amount"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-ok">OK</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var ppmpDetailsModal = (function(){
  var details = {}, el = {}, year = 0, status = '', param = {};

  function init(item, header){
    item = item || {}; header = header || {};
    year = header.PPMPYear || 0; status = header.Status || ''; param = header.Param || {};
    cacheDOM(); bindEvents();
    details = { ItemID: item.ItemID||0, SpecDetails: item.SpecDetails||'', UnitName: item.UnitName||'', UnitPrice: item.UnitPrice||0, UnitCost: item.UnitCost||0, Common: item.Common||0, Contingency: item.Contingency||1, OECode: item.OECode||'', ProCode: item.ProCode||'', MoreSpecs: item.MoreSpecs||'', Remarks: item.Remarks||'', Qty_Jan: item.Qty_Jan||0, Qty_Feb: item.Qty_Feb||0, Qty_Mar: item.Qty_Mar||0, Qty_Apr: item.Qty_Apr||0, Qty_May: item.Qty_May||0, Qty_Jun: item.Qty_Jun||0, Qty_Jul: item.Qty_Jul||0, Qty_Aug: item.Qty_Aug||0, Qty_Sep: item.Qty_Sep||0, Qty_Oct: item.Qty_Oct||0, Qty_Nov: item.Qty_Nov||0, Qty_Dec: item.Qty_Dec||0, UtilQuantity: item.UtilQuantity||0, UtilAmount: item.UtilAmount||0 };
    $('#pro-code').val(details.ProCode); $('#pro-mode').val(item.ProcurementMode||''); render();
  }
  function cacheDOM(){ el.$modal=$('#ppmp-item-details-modal'); el.$txtMonths=el.$modal.find('.months'); el.$txtUnitPrice=el.$modal.find('#item-price'); el.$txtMoreSpecs=el.$modal.find('#additional-specs'); }
  function bindEvents(){ el.$modal.on('change','#quantity-requirements input, #item-price',function(){getItem();render();}); el.$txtMoreSpecs.on('keyup',function(){ var t=$(this).val(); if(t.length>4000){t=t.substring(0,4000);$(this).val(t);alert('Exceeded maximum characters.');} $('#additional-specs-length').text(t.length); }); }
  function getItem(){ details.MoreSpecs=$('#additional-specs').val(); details.Remarks=$('#Remarks').val(); details.ProCode=$('#pro-code').val(); details.UnitCost=Math.abs(parseFloat($('#item-price').val())||0); details.Qty_Jan=Math.abs(parseInt($('#month-1').val())||0); details.Qty_Feb=Math.abs(parseInt($('#month-2').val())||0); details.Qty_Mar=Math.abs(parseInt($('#month-3').val())||0); details.Qty_Apr=Math.abs(parseInt($('#month-4').val())||0); details.Qty_May=Math.abs(parseInt($('#month-5').val())||0); details.Qty_Jun=Math.abs(parseInt($('#month-6').val())||0); details.Qty_Jul=Math.abs(parseInt($('#month-7').val())||0); details.Qty_Aug=Math.abs(parseInt($('#month-8').val())||0); details.Qty_Sep=Math.abs(parseInt($('#month-9').val())||0); details.Qty_Oct=Math.abs(parseInt($('#month-10').val())||0); details.Qty_Nov=Math.abs(parseInt($('#month-11').val())||0); details.Qty_Dec=Math.abs(parseInt($('#month-12').val())||0); details.ProcurementMode=$('#pro-mode').val(); }
  function render(){
    var q=0, m=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    for(var i=1;i<=12;i++){ q+=details['Qty_'+m[i-1]]||0; $('#month-'+i).val(details['Qty_'+m[i-1]]||0); }
    details.Qty_Tot=q;
    el.$modal.find('.modal-title').text(details.SpecDetails);
    el.$modal.find('#item-unit').text(details.UnitName);
    el.$modal.find('#item-price').val(details.UnitCost);
    el.$modal.find('#total-qty').text(numberFormat(details.Qty_Tot)+'('+numberFormat(details.UtilQuantity)+')');
    el.$modal.find('#item-amount').text(numberFormat(parseFloat((details.Qty_Tot*details.UnitCost).toFixed(2)))+'('+numberFormat(details.UtilAmount)+')');
    if(details.Common==1){ el.$modal.find('#additional-specs').prop('disabled',true); el.$modal.find('#item-price').prop('disabled',true); }
    else { el.$modal.find('#additional-specs').prop('disabled',false); el.$modal.find('#item-price').prop('disabled',false); }
  }
  function toggleModal(i){ el.$modal.modal('toggle'); if(i==undefined)return; $('#btn-ok').data('id',i); }
  return { init: init, toggle: toggleModal, get: getItem };
})();
</script>
