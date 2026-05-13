<style type="text/css">
  .input-dropdown {
    width: 150%;
  }
</style>
<!-- New PPMP Form Modal -->
<div class="modal fade" id="new-ppmp-form-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New PPMP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Program Code:</label>
          <div class="col-sm-8">
            <select class="form-control" id="program-code"></select>
          </div>
        </div>

        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Project Code:</label>
          <div class="col-sm-8">
            <select type="text" class="form-control" name="ProjectCode" id="modal-project-code">
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PPMP Year:</label>
          <div class="col-sm-8">
            <select type="text" class="form-control" name="PPMPYear" id="modal-ppmp-year">
              <option value="">...</option>
              <option value="{{ date('Y') }}">{{ date('Y') }}</option>
              <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Budget:</label>
          <div class="col-sm-8">
            <input type="number" class="form-control" name="TotalBudget" id="modal-total-budget"/>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-save-new-ppmp">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#btn-save-new-ppmp').on('click', function() {
      var header = {
        ProjectCode: $('#modal-project-code').val(),
        PPMPYear: $('#modal-ppmp-year').val(),
        TotalBudget: $('#modal-total-budget').val(),
      };

      $.ajax({
        type: "post",
        url: base_url + "ppmp/save_initial_header",
        data: {
          header: btoa(JSON.stringify(header)),
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.Status === 'OK') {
            $('#new-ppmp-form-modal').modal('hide');
            window.location.href = base_url + 'ppmp/preparation/' + response.PreparatoryFormat;
          } else {
            alert(response.Message || 'Something went wrong.');
          }
        },
        error: function() {
          alert('Something went wrong!');
        }
      });
    });
  });
</script>
