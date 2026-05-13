<style type="text/css">
.input-dropdown div:hover { color: #FFF; background-color: #0071bc; cursor: pointer; }
.input-dropdown div { padding: 3px; }
.input-dropdown { position: absolute; background: white; width: 95%; z-index: 1; }
</style>

<div class="modal fade" id="group-form-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Group Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Group ID:</label>
          <div class="col-sm-3"><input type="text" class="form-control" name="GroupID" disabled="" /></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Group Name:</label>
          <div class="col-sm-9"><input type="text" class="form-control" name="GroupName" /></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label"></label>
          <div class="col-sm-9"><input type="checkbox" name="InActive" value="1"> InActive</div>
        </div>
        <table class="table table-bordered table-striped" width="100%" id="table-access" cellspacing="0">
          <thead>
            <tr><th></th><th>Access ID</th><th>Access Name</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var groupFormModal = (function(){
  var el = {}, header = {}, cache = {};

  function init(){ cacheDOM(); bindEvents(); getAccess(); }
  function cacheDOM(){
    el.$modal = $('#group-form-modal');
    el.$txtID = el.$modal.find('input[name="GroupID"]');
    el.$txtDesc = el.$modal.find('input[name="GroupName"]');
    el.$chkInActive = el.$modal.find('input[name="InActive"]');
    el.$tbl = el.$modal.find('#table-access');
    el.$btnSave = el.$modal.find('#btn-save');
  }
  function bindEvents() { el.$btnSave.on('click', save); }
  function render() {
    el.$txtID.val(header.GroupID || 0).prop('disabled', true);
    el.$txtDesc.val(header.GroupName || '');
    el.$chkInActive.prop('checked', header.InActive == 1);
  }
  function save(){
    header.GroupName = el.$txtDesc.val();
    header.InActive = el.$chkInActive.is(':checked') ? 1 : 0;
    header.GroupAccess = [];
    el.$tbl.find('.checkbox-access').each(function(){
      if ($(this).is(':checked')) {
        header.GroupAccess.push(cache.Access[$(this).closest('tr').data('id')].AccessID);
      }
    });
    $.ajax({
      type:"post", url: base_url + "security/save_group",
      data: { header : header, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function() { modalAlert("Record saved successfully."); toggleModal(); },
      error: function() { modalAlert("Something went wrong..."); }
    });
  }
  function getAccess(){
    $.ajax({
      type:"post", url: base_url + "security/get_access",
      data: { _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        cache.Access = JSON.parse(response);
        el.$tbl.find('tbody').html('');
        for (var i in cache.Access) {
          el.$tbl.find('tbody').append(
            '<tr data-id="'+ i +'">'+
              '<td><input type="checkbox" class="checkbox-access" /></td>'+
              '<td>'+ cache.Access[i].AccessID +'</td>'+
              '<td>'+ cache.Access[i].AccessDescription +'</td>'+
            '</tr>'
          );
        }
        el.$tbl.DataTable({ destroy: true });
      },
      error: function() { alert("Something went wrong..."); }
    });
  }
  function getGroupAccess(groupID, callback){
    $.ajax({
      type:"post", url: base_url + "security/get_group_access",
      data: { criteria: { GroupID: groupID }, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        cache.GroupAccess = JSON.parse(response);
        el.$tbl.find('.checkbox-access').each(function(){
          var accessID = cache.Access[$(this).closest('tr').data('id')].AccessID;
          $(this).prop('checked', cache.GroupAccess.find(x => x.AccessID == accessID) != undefined);
        });
        callback();
      },
      error: function() { alert("Something went wrong..."); }
    });
  }
  function toggleModal(data) {
    el.$modal.modal('toggle');
    if (data == undefined) return;
    header = data;
    header.GroupID = header.GroupID || 0;
    getGroupAccess(header.GroupID, function(){ render(); });
  }
  return { init: init, toggle: toggleModal };
})();
groupFormModal.init();
</script>
