<style type="text/css">
.input-dropdown div:hover { color: #FFF; background-color: #0071bc; cursor: pointer; }
.input-dropdown div { padding: 3px; }
.input-dropdown { position: absolute; background: white; width: 95%; z-index: 1; }
</style>

<div class="modal fade" id="user-form-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">User ID:</label>
          <div class="col-sm-3"><input type="text" class="form-control" name="UserID" disabled="" /></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Employee:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control input-search" name="EmployeeID" />
            <div class="input-dropdown"></div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Group:</label>
          <div class="col-sm-9"><select class="form-control" name="GroupID"></select></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Group (RCEF):</label>
          <div class="col-sm-9"><select class="form-control" name="GroupID_RCEF"></select></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">User Level:</label>
          <div class="col-sm-3"><input type="number" class="form-control" name="UserLevel"></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label"></label>
          <div class="col-sm-9"><input type="checkbox" name="CanViewAll" value="1"> Can View All</div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label"></label>
          <div class="col-sm-9"><input type="checkbox" name="Locked" value="1"> Locked</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var userFormModal = (function(){
  var el = {}, header = {}, cache = {};

  function init(){ cacheDOM(); bindEvents(); getGroups(); }
  function cacheDOM(){
    el.$modal = $('#user-form-modal');
    el.$txtUserID = el.$modal.find('input[name="UserID"]');
    el.$txtEmployee = el.$modal.find('input[name="EmployeeID"]');
    el.$txtGroup = el.$modal.find('select[name="GroupID"]');
    el.$txtGroup2 = el.$modal.find('select[name="GroupID_RCEF"]');
    el.$txtUserLevel = el.$modal.find('input[name="UserLevel"]');
    el.$chkCanViewAll = el.$modal.find('input[name="CanViewAll"]');
    el.$chkLocked = el.$modal.find('input[name="Locked"]');
    el.$dropDiv = el.$modal.find('.input-dropdown');
    el.$btnSave = el.$modal.find('#btn-save');
  }
  function bindEvents() {
    el.$btnSave.on('click', save);
    el.$txtEmployee.on('keyup', getEmployees);
    el.$dropDiv.on('click', '> div', setEmployee);
  }
  function render() {
    el.$txtUserID.val(header.UserID || 0).prop('disabled', true);
    el.$txtEmployee.data('id', header.EmployeeID || '').val((header.EmployeeID ? header.EmployeeID + ' | ' + header.EmployeeName : '')).prop('disabled', !!(header.EmployeeID));
    el.$txtGroup.val(header.GroupID || '');
    el.$txtGroup2.val(header.GroupID_RCEF || '');
    el.$txtUserLevel.val(header.UserLevel || 1);
    el.$chkCanViewAll.prop('checked', header.CanViewAll == 1);
    el.$chkLocked.prop('checked', header.Locked == 1);
  }
  function save(){
    header.EmployeeID = el.$txtEmployee.data('id') || '';
    header.GroupID = el.$txtGroup.val();
    header.GroupID_RCEF = el.$txtGroup2.val();
    header.UserLevel = el.$txtUserLevel.val();
    header.CanViewAll = el.$chkCanViewAll.is(':checked') ? 1 : 0;
    header.Locked = el.$chkLocked.is(':checked') ? 1 : 0;
    $.ajax({
      type:"get", url: base_url + "security/save_user",
      data: { header : header },
      success:function() { modalAlert("Record saved successfully."); toggleModal(); },
      error: function() { modalAlert("Something went wrong..."); }
    });
  }
  function getEmployees(e){
    var row = $(e.target).closest('.form-group');
    $(e.target).data('id','');
    if (e.which != 13) return;
    var val = $.trim($(e.target).val());
    if (val == "") { row.find('.input-dropdown').html("").css('box-shadow','none'); return; }
    $.ajax({
      type:"post", url: base_url + "employees/getEmployees",
      data: { criteria: { EmployeeName: val }, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        var res = JSON.parse(response);
        var html = '';
        for (var i in res) { html += '<div data-id="'+ res[i].EmployeeID +'">'+ res[i].EmployeeID +' | '+ res[i].EmployeeName +'</div>'; }
        row.find('.input-dropdown').html(html).css('box-shadow','1px 1px 5px 1px');
      },
      error: function() { alert("Something went wrong..."); }
    });
  }
  function setEmployee(e){
    var row = $(e.target).closest('.form-group');
    row.find('.input-search').val($(e.target).text()).data('id', $(e.target).data('id'));
    row.find('.input-dropdown').html("").css('box-shadow','none');
  }
  function getGroups(){
    $.ajax({
      type:"post", url: base_url + "security/get_groups",
      data: { _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        cache.Groups = JSON.parse(response);
        el.$txtGroup.html(''); el.$txtGroup2.html('');
        for (var i in cache.Groups) {
          el.$txtGroup.append($('<option>', {value:cache.Groups[i].GroupID, text:cache.Groups[i].GroupName}));
          el.$txtGroup2.append($('<option>', {value:cache.Groups[i].GroupID, text:cache.Groups[i].GroupName}));
        }
      },
      error: function() { modalAlert("Something went wrong..."); }
    });
  }
  function toggleModal(data) {
    el.$modal.modal('toggle');
    if (data == undefined) return;
    header = data;
    header.UserID = header.UserID || 0;
    header.EmployeeID = header.EmployeeID || '';
    header.EmployeeName = header.EmployeeName || '';
    header.UserLevel = header.UserLevel || 1;
    render();
  }
  return { init: init, toggle: toggleModal };
})();
userFormModal.init();
</script>
