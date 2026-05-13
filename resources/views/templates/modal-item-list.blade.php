<style type="text/css">
#items-modal .modal-lg {
  max-width: 60vw;
}
#inv-table {
  width: 100% !important;
}
#items-modal #alert-container {
  min-height: 65px;
}
#items-modal #alert-onclick-item {
  display: none;
}
#inv-table tbody tr {
  cursor: pointer;
}
</style>

<div class="modal fade" id="items-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Search Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Object of Expend:</label>
              <div class="col-sm-8">
                <select class="form-control criteria" id="OECode" name="OECode"></select>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Account Description:</label>
              <div class="col-sm-8">
                <select class="form-control criteria" id="ACCode" name="ACCode"></select>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Item Code:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control criteria" name="ItemCode">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Item Description:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control criteria" name="SpecDetails">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12" id="alert-container">
            <div class="alert alert-success" id="alert-onclick-item" role="alert">
              ...
            </div>
          </div>
        </div>

        <div class="loading-container" id="inv-table-container">
          <table class="table table-striped" id="inv-table">
            <thead>
              <tr>
                <th style="width: 10%;">Object</th>
                <th style="width: 20%;">Item Code</th>
                <th style="width: 40%;">Item Specification</th>
                <th style="width: 10%;">Unit</th>
                <th style="width: 15%;">Unit Price</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var itemsModal = (function(){
  var items = [];
  var el = {};
  var index;
  var alertTimeout;
  var ajaxReq = null;

  function init(){
    cacheDOM();
    bindEvents();

    if (typeof Item_ObjectOfExpend !== 'undefined') {
      Item_ObjectOfExpend.get(function(res){
        $('#OECode').html('<option value=""></option>');
        for ( var i in res ) {
          $('#OECode').append($('<option>', {
              value: res[i].OECode,
              text : res[i].OECode + ' | ' + res[i].OEDesc
          }));
        }
      });
    }
  }

  function cacheDOM(){
    el.$modal = $('#items-modal');
    el.$txtCriteria = el.$modal.find('.criteria');
    el.$txtOECode = el.$modal.find('#OECode');
    el.$txtACCode = el.$modal.find('#ACCode');
  }

  function bindEvents() {
    el.$modal.on('click', 'tbody tr', onClick_trItem);
    el.$txtOECode.on('change', onChange_OECode);
    el.$txtOECode.on('change', get);
    el.$txtACCode.on('change', get);
    el.$txtCriteria.on('keyup', get);
  }

  function get(e) {
    if (e.which != 13 && e.type=='keyup') return;

    var criteria = {};
    criteria.InActive = 0;
    el.$txtCriteria.each(function(){
      if (this.value != '') criteria[this.name] = this.value;
    });

    if (typeof TableLoading !== 'undefined') TableLoading.On('#inv-table');
    if (ajaxReq != null) ajaxReq.abort();

    ajaxReq = $.ajax({
        type:"post",
        url: base_url + "items/search_main_articles",
        data: {
          criteria : criteria
        },
        success:function(response) {
          items = JSON.parse(response);
          render();
          ajaxReq = null;
        },
        error: function(e) {
          ajaxReq = null;
        }
    });
  }

  function render() {
    el.$modal.find('tbody').html('');
    if ($.fn.DataTable.isDataTable('#inv-table')) {
      $('#inv-table').DataTable().clear().destroy();
    }

    for ( var i in items) {
      el.$modal.find('tbody').append(
      '<tr data-id="'+ i +'">'+
        '<td>'+ items[i].OECode +'</td>'+
        '<td>'+ items[i].ItemCode +'</td>'+
        '<td>'+ items[i].SpecDetails +'</td>'+
        '<td>'+items[i].UnitName+'</td>'+
        '<td>'+numberFormat(Number(items[i].UnitPrice).toFixed(2))+'</td>'+
      '</tr>');
    }

    $('#inv-table').DataTable();
  }

  function toggleModal(e) {
    el.$modal.modal('toggle');
    if (e == undefined) return;
    index = $(e.target).parent().data('id');
  }

  function onChange_OECode(){
    var oecode = el.$txtOECode.val();
    if (typeof Item_AccountCode !== 'undefined') {
      Item_AccountCode.get(oecode, '', function(res){
        el.$txtACCode.html('<option value=""></option>');
        for ( var i in res ) {
          el.$txtACCode.append($('<option>', {
              value: res[i].ACCode,
              text : res[i].ACCode + ' | ' + res[i].ACDesc
          }));
        }
      });
    }
  }

  function onClick_trItem(e) {
    clearTimeout(alertTimeout);
    $("#items-modal #alert-onclick-item").text(items[$(e.target).parent().data('id')].SpecDetails + ' has been added.').fadeIn();
    alertTimeout = setTimeout(function(){
      $("#items-modal #alert-onclick-item").fadeOut();
    }, 1000);

    if (el.$modal.data('next-modal') == 'itemDetailsModal') {
      if (typeof itemDetailsModal !== 'undefined') {
        itemDetailsModal.init(items[$(e.target).parent().data('id')]);
        itemDetailsModal.toggle(index);
      }
    }
    else if (el.$modal.data('next-modal') == 'ppmpDetailsModal') {
      if (typeof ppmpDetailsModal !== 'undefined') {
        ppmpDetailsModal.init(items[$(e.target).parent().data('id')]);
        ppmpDetailsModal.toggle(e);
      }
    }
  }

  function getItem(i){
    return items[i];
  }

  return {
    init: init,
    toggle: toggleModal,
    load: get,
    el: el,
    get: getItem
  }
})();

$(document).ready(function() {
  itemsModal.init();
});
</script>
