<div class="modal fade" id="history-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th style="width: 25%;">DateTime</th>
              <th style="width: 15%;">Status</th>
              <th style="width: 35%;">Remarks</th>
              <th style="width: 25%;">Employee</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var historyModal = (function(){
  var items = [], el = {};
  function init(){ cacheDOM(); }
  function cacheDOM(){ el.$modal = $('#history-modal'); }
  function get(controller, headerid) {
    TableLoading.On('#history-modal table');
    $.ajax({
      type:"post", url: base_url + controller + "/get_history",
      data: { headerid : headerid },
      success:function(response) {
        var data = JSON.parse(response);
        items = Array.isArray(data) ? data : (data && Array.isArray(data.header) ? data.header : []);
        render();
      },
      error: function() { alert("Something went wrong..."); }
    });
  }
  function render() {
    el.$modal.find('tbody').html('');
    for (var i in items) {
      items[i].Remarks = items[i].Remarks == null ? "" : items[i].Remarks;
      el.$modal.find('tbody').append(
        '<tr>'+
          '<td>'+ items[i].StatusDate +' '+ items[i].StatusTime +'</td>'+
          '<td>'+ items[i].StatusName +'</td>'+
          '<td>'+ items[i].Remarks +'</td>'+
          '<td>'+ items[i].EmployeeName +'</td>'+
        '</tr>');
    }
  }
  function toggleModal() { el.$modal.modal('toggle'); }
  return { init: init, toggle: toggleModal, load: get };
})();
historyModal.init();
</script>
