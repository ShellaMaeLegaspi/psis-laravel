<div class="modal fade" id="prompt-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Prompt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="message-text" class="form-control-label">...</label>
          <textarea class="form-control" id="message-text" style="margin-top: 0px; margin-bottom: 0px; height: 92px;"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function modalPrompt(message, callback) {
  $('#prompt-modal').find('.modal-body .form-control-label').text(message);
  $('#prompt-modal').modal('toggle');
  $('#prompt-modal').find('.btn-primary').off('click').on('click', callback);
}
</script>
