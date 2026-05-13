<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function modalConfirm(message, callback, callback2) {
  $('#confirm-modal').find('.modal-body').text(message);
  $('#confirm-modal').modal('toggle');
  $('#confirm-modal').find('.btn-primary').off('click').on('click', callback);
  $('#confirm-modal').find('.btn-secondary').off('click').on('click', callback2);
}
</script>
