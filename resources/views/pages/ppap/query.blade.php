@extends('layouts.layout')

@section('title', 'PPAP Query')

@push('styles')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-6">
    <h1>PPAP / Query</h1>
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
    </div>

    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Project Code:</label>
      <div class="col-sm-2">
        <select class="form-control" id="project-code">
          <option value="">...</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
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
    </div>

    <div class="form-group row">
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
      <label for="" class="col-sm-1 col-form-label">Status:</label>
      <div class="col-sm-2">
        <select class="form-control" id="status">
          <option value=""></option>
          <option value="N">N | New</option>
          <option value="D">D | Done</option>
          <option value="O">O | Processed</option>
          <option value="E">E | Evaluated</option>
          <option value="A">A | Approved</option>
          <option value="C">C | Budget Certified</option>
          <option value="I">I | Received</option>
          <option value="R">R | Returned</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col col-button">
        <button id="btn-search" class="btn btn-info pull-right">Search</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Results</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="ppmp-result" cellspacing="0">
        <thead>
          <tr>
            <th>Preparatory Format</th>
            <th>Project Code</th>
            <th>PPMP Year</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Approved By</th>
            <th>Certified By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  (function() {
    var List = {};
    List.Programs = [];
    List.Projects = [];

    $(document).ready(function() {
      $.ajax({
        type: "post",
        url: "{{ url('/fmis/get_programs') }}",
        success: function(response) {
          if (response == 0) {
            return false;
          }
          List.Programs = JSON.parse(response);
          if (List.Programs.length == undefined) List.Programs = [List.Programs];

          $('#program-code').append($('<option>', {
            value: '',
            text: 'Select a program...'
          }));
          for (i in List.Programs) {
            $('#program-code').append($('<option>', {
              value: List.Programs[i].ProgramCode,
              text: List.Programs[i].ProgramCode
            }));
          }
        },
        error: function() {
          alert("Something went wrong...");
        }
      });

      $.ajax({
        type: "post",
        url: "{{ url('/fmis/get_projects') }}",
        success: function(response) {
          if (response == 0) {
            return false;
          }
          List.Projects = JSON.parse(response);
        },
        error: function() {
          alert("Something went wrong...");
        }
      });

      $('#btn-search').on('click', function() {
        var criteria = {};

        if ($('#program-code').val() != '') criteria['ProgramCode'] = $('#program-code').val();
        if ($('#project-code').val() != '') criteria['ProjectCode'] = $('#project-code').val();
        if ($('#ppmp-year').val() != '') criteria['PPMPYear'] = $('#ppmp-year').val();
        if ($('#preparatory-format').val() != '') criteria['PPAPControlNo'] = $('#preparatory-format').val();
        if ($('#date-from').val() != '') criteria['DateFrom'] = $('#date-from').val();
        if ($('#date-to').val() != '') criteria['DateTo'] = $('#date-to').val();
        if ($('#tracking-no').val() != '') criteria['TrackingNo'] = $('#tracking-no').val();
        if ($('#status').val() != '') criteria['Status'] = [$('#status').val()];

        $('#ppmp-result').dataTable().fnClearTable();
        $('#ppmp-result').dataTable().fnDraw();
        $('#ppmp-result').dataTable().fnDestroy();

        TableLoading.On('#ppmp-result');
        $.ajax({
          type: "post",
          url: "{{ url('/ppap/query_ppap') }}",
          data: {
            criteria: criteria
          },
          success: function(response) {
            $('#ppmp-result tbody').html(response);
            $('#ppmp-result').dataTable();
          },
          error: function() {
            alert("Something went wrong...");
          }
        });
      });

      $('tbody').on('click', 'tr', function() {
        window.location = '{{ url('/ppap/preparation') }}/PPAPControlNo/' + $(this).data('controlno');
      });

      $('#program-code').on('change', function() {
        $('#project-code').html('');
        $('#project-code').append($('<option>', {
          value: '',
          text: 'Select a project...'
        }));
        for (i in List.Projects) {
          if (this.value != List.Projects[i].ProgramCode) continue;
          $('#project-code').append($('<option>', {
            value: List.Projects[i].ProjectCode,
            text: List.Projects[i].ProjectCode
          }));
        }
      });

      $('#date-from').datepicker({
        dateFormat: "yy-mm-dd"
      });
      $('#date-to').datepicker({
        dateFormat: "yy-mm-dd"
      });
    });
  })();
</script>
@endpush
@endsection
