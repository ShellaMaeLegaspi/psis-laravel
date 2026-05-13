<style type="text/css">
.input-dropdown div:hover { color: #FFF; background-color: #0071bc; cursor: pointer; }
.input-dropdown div { padding: 3px; }
.input-dropdown { position: absolute; background: white; width: 95%; z-index: 1; }
</style>

<div class="modal fade" id="access-form-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Access Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Access ID:</label>
          <div class="col-sm-3"><input type="text" class="form-control" name="AccessID" disabled="" /></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Access Name:</label>
          <div class="col-sm-9"><input type="text" class="form-control" name="AccessDescription" /></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label"></label>
          <div class="col-sm-9"><input type="checkbox" name="InActive" value="1"> InActive</div>
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
var accessFormModal = (function(){
  var el = {}, header = {};

  function init(){ cacheDOM(); bindEvents(); }
  function cacheDOM(){
    el.$modal = $('#access-form-modal');
    el.$txtID = el.$modal.find('input[name="AccessID"]');
    el.$txtDesc = el.$modal.find('input[name="AccessDescription"]');
    el.$chkInActive = el.$modal.find('input[name="InActive"]');
    el.$btnSave = el.$modal.find('#btn-save');
  }
  function bindEvents() { el.$btnSave.on('click', save); }
  function render() {
    el.$txtID.val(header.AccessID || 0).prop('disabled', true);
    el.$txtDesc.val(header.AccessDescription || '');
    el.$chkInActive.prop('checked', header.InActive == 1);
  }
  function save(){
    header.AccessDescription = el.$txtDesc.val();
    header.InActive = el.$chkInActive.is(':checked') ? 1 : 0;
    $.ajax({
      type:"post", url: base_url + "security/save_access",
      data: { header : header, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function() { modalAlert("Record saved successfully."); toggleModal(); },
      error: function() { modalAlert("Something went wrong..."); }
    });
  }
  function toggleModal(data) {
    el.$modal.modal('toggle');
    if (data == undefined) return;
    header.AccessID = data.AccessID || 0;
    header.AccessDescription = data.AccessDescription || '';
    header.InActive = data.InActive || 0;
    render();
  }
  return { init: init, toggle: toggleModal };
})();
accessFormModal.init();
</script>
