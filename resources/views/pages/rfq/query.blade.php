@extends('layouts.layout')

@section('title', 'RFQ Query')

@push('styles')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(2) {
    width: 45%
  }

  #sai-inbox tr td:nth-child(3) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(4) {
    width: 15%
  }

  #sai-inbox tr td:nth-child(5) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(6) {
    width: 10%
  }

  .r {
    text-align: right;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Request for Quotation (RFQ) / Query</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="row">
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">RFQ Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="RFQControlNo">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Procurement Code:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="ProcurementCode">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PR No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="PRNo">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Particulars:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="Particulars">
          </div>
        </div>
      </div>
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Prepared By:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria search-employee" name="PreparedBy">
            <div class="option-employee preparedby"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Approved By:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria search-employee" name="ApprovedBy">
            <div class="option-employee approvedby"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Status:</label>
          <div class="col-sm-8">
            <select class="form-control criteria" name="Status" id="">
              <option value="">...</option>
              <option value="N">New</option>
              <option value="D">Done</option>
              <option value="R">Returned</option>
              <option value="A">Approved</option>
              <option value="X">Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Date From:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateFrom" readonly="">
          </div>
          <label for="" class="col-sm-1 col-form-label">To:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateTo" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria search-employee" name="TrackingNo">
          </div>
        </div>
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
      <table class="table table-bordered table-striped" width="100%" id="sai-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>RFQ Control No.</th>
            <th>Particulars</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
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
    var query = {
      headers: [],
      ajaxReq: null,
      init: function() {
        this.cacheDOM();
        this.bindEvents();
      },
      cacheDOM: function() {
        this.$spbicontrolno = $('#control-no');
        this.$btnsearch = $('#btn-search');
        this.$table = $('#sai-inbox');

        this.$txtSearchEmployee = $('.search-employee');
        this.$txtOptionEmployee = $('.option-employee');

        this.$txtDateFrom = $('[name="DateFrom"]');
        this.$txtDateTo = $('[name="DateTo"]');

        this.$txtCriteria = $('.criteria');

        this.$txtDateFrom.datepicker({
          dateFormat: 'yy-mm-dd'
        });
        this.$txtDateTo.datepicker({
          dateFormat: 'yy-mm-dd'
        });
      },
      bindEvents: function() {
        this.$btnsearch.on('click', this.get.bind(this));
        this.$table.on('click', 'tbody tr', this.open);

        this.$txtSearchEmployee.on('keyup', this.getEmployees.bind(this));
        this.$txtOptionEmployee.on('click', 'div', this.setEmployee.bind(this));
      },

      getEmployees: function(e) {
        Employee.Search(e);
        return;
      },
      setEmployee: function(e) {
        Employee.Set(e);
        return;
      },

      get: function() {
        var criteria = {};

        this.$txtCriteria.each(function() {
          if (this.value == '') return;

          criteria[this.name] = this.value;

          if (this.name == 'PreparedBy') criteria[this.name] = $(this).data('id')
          if (this.name == 'ApprovedBy') criteria[this.name] = $(this).data('id')
        });

        var me = this;

        if (this.ajaxReq != null) ajaxReq.abort();
        TableLoading.On('#sai-inbox');
        this.ajaxReq = $.ajax({
          type: "post",
          url: "{{ url('/rfq/get_headers') }}",
          data: {
            criteria: criteria
          },
          success: function(response) {
            me.headers = JSON.parse(response);
            me.render();
            me.ajaxReq = null;
          },
          error: function() {
            me.ajaxReq = null;
            console.log("Something went wrong...");
          }
        });
      },
      open: function() {
        var prep_link = 'qpreparation';
        window.open(base_url + 'rfq/' + prep_link + '/RFQControlNo/' + $(this).data('controlno'), '_blank');
      },
      render: function() {
        var row = '';
        $('#sai-inbox tbody').html('');
        for (var i in this.headers) {
          this.headers[i].Remarks = this.headers[i].Remarks || '';
          row +=
            '<tr data-controlno="' + this.headers[i].RFQControlNo + '">' +
            '<td>' + this.headers[i].RFQControlNo + '</td>' +
            '<td>' + this.headers[i].Particulars + '</td>' +
            '<td>' + numberFormat(this.headers[i].TotalAmount) + '</td>' +
            '<td>' + this.headers[i].PreparedBy_Name + '</td>' +
            '<td>' + this.headers[i].StatusName + '</td>' +
            '<td>' + this.headers[i].DateCreated + '</td>' +
            '</tr>';
        }
        $('#sai-inbox').dataTable().fnClearTable();
        $('#sai-inbox').dataTable().fnDraw();
        $('#sai-inbox').dataTable().fnDestroy();
        $('#sai-inbox tbody').html(row);
        $('#sai-inbox').dataTable();
      }
    }

    query.init();
  })();
</script>
@endpush
@endsection
