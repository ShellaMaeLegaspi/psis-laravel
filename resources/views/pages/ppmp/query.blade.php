@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-6">
    <h1>PPMP / Query</h1>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col col-button">

      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Program Code:</label>
      <div class="col-sm-2">
        <select class="form-control" id="program-code"></select>
      </div>

      <label for="" class="col-sm-1 col-form-label">Project Code:</label>
      <div class="col-sm-2">
        <select class="form-control" id="project-code">
          <option value="">...</option>
        </select>
      </div>

      <label for="" class="col-sm-1 col-form-label">PPMP Year:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="ppmp-year">
      </div>
    </div>

    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Preparatory Format:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="preparatory-format">
      </div>

      <label for="" class="col-sm-1 col-form-label">Date From:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="date-from" readonly="true">
      </div>

      <label for="" class="col-sm-1 col-form-label">To:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="date-to" readonly="true">
      </div>
    </div>

    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Tracking No.:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="tracking-no">
      </div>
    </div>

    <div class="form-group row">
      <div class="col-sm-12">
        <button class="btn btn-primary" id="search">Search</button>
        <button class="btn btn-secondary" id="clear">Clear</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Results</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="dataTable" cellspacing="0">
        <thead>
          <tr>
            <th>Preparatory Format</th>
            <th>Project Code</th>
            <th>Program Code</th>
            <th>PPMP Year</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Approved By</th>
            <th>Certified By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody id="results-body">
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
  var pageJS = (function() {

    function init() {
      bindEvents();
    }

    function bindEvents() {
      $('#search').on('click', function() {
        search();
      });

      $('#clear').on('click', function() {
        clearForm();
      });

      $('#results-body').on('click', 'tr', function() {
        var controlNo = $(this).data('controlno');
        if (controlNo) {
          window.location.href = base_url + 'ppmp/preparation/' + controlNo;
        }
      });
    }

    function search() {
      var criteria = {
        programCode: $('#program-code').val(),
        projectCode: $('#project-code').val(),
        ppmpYear: $('#ppmp-year').val(),
        preparatoryFormat: $('#preparatory-format').val(),
        dateFrom: $('#date-from').val(),
        dateTo: $('#date-to').val(),
        trackingNo: $('#tracking-no').val()
      };

      $.ajax({
        type: "post",
        url: base_url + "ppmp/query_ppmp",
        data: { criteria: criteria },
        success: function(response) {
          $('#results-body').html(response);
        },
        error: function() {
          alert("Something went wrong!");
        }
      });
    }

    function clearForm() {
      $('#program-code').val('');
      $('#project-code').val('');
      $('#ppmp-year').val('');
      $('#preparatory-format').val('');
      $('#date-from').val('');
      $('#date-to').val('');
      $('#tracking-no').val('');
      $('#results-body').html('');
    }

    return {
      init: init
    };
  })();

  $(document).ready(function() {
    pageJS.init();
  });
</script>
@endsection
