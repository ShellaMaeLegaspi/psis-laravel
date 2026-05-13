@extends('layouts.layout')

@section('title', 'Abstract Monitoring Report')

@push('styles')
<style type="text/css">
  #sai-inbox {
    table-layout: fixed;
  }

  #sai-inbox th {
    width: 150px;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Reports / Abstract Monitoring</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <form action="{{ url('/reports/extract_abstract_monitoring') }}" target="_blank" method="post" id="form1">
      <div class="row">
        <div class="col col-sm-4">
          <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Date From:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control criteria" name="DateFrom" readonly>
            </div>
            <label for="" class="col-sm-1 col-form-label">To:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control criteria" name="DateTo" readonly>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col col-sm-4">
          <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label">PPMP Year:</label>
            <div class="col-sm-4">
              <input type="number" class="form-control criteria" name="PPMPYear" id="PPMPYear">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col col-button">
          <button type="submit" form="form1" id="btn-export" class="btn btn-info pull-right">Export to Excel</button>
          <button type="button" id="btn-search" class="btn btn-info pull-right">Search</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Results</div>
  <div class="card-body">
    <div class="table-responsive" style="height: 70vh; overflow: scroll;">
      <table class="table table-bordered table-striped" width="100%" id="sai-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>PMD (0)/ Division Handle(1)</th>
            <th>Abstract Control No.</th>
            <th>Abstract No.</th>
            <th>Tracking No.</th>
            <th>Division</th>
            <th>Date Received</th>
            <th>Abstract Date</th>
            <th>PR No.</th>
            <th>ABC</th>
            <th>Charging Code</th>
            <th>Posting Date</th>
            <th>Reference No.</th>
            <th>Closing Date</th>
            <th>Supplier</th>
            <th>Particular</th>
            <th>Delivery Date</th>
            <th>Date Numbered</th>
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
    var pageJS = {
      details: [],
      ajaxReq: null,
      init: function() {
        this.cacheDOM();
        this.bindEvents();
      },
      cacheDOM: function() {
        this.$btnExport = $('#btn-export');
        this.$btnSearch = $('#btn-search');

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
        this.$btnSearch.on('click', this.get.bind(this));
      },
      get: function() {
        var criteria = {};

        this.$txtCriteria.each(function() {
          if (this.value == '') return;
          criteria[this.name] = this.value;
        });

        var me = this;

        if (this.ajaxReq != null) ajaxReq.abort();
        ContentLoading.On('#page-content');
        this.ajaxReq = $.ajax({
          type: "post",
          url: "{{ url('/reports/get_extract_abstract_monitoring') }}",
          data: {
            criteria: criteria
          },
          success: function(res) {
            me.details = JSON.parse(res);
            me.render();
            me.ajaxReq = null;
            ContentLoading.Off();
          },
          error: function() {
            me.ajaxReq = null;
            ContentLoading.Off();
            console.log("Something went wrong...");
          }
        });
      },
      render: function() {
        $('#sai-inbox tbody').html('');
        var tr = '';
        for (i in this.details) {
          tr +=
            '<tr>' +
            '<td>' + this.details[i].DivHandle + '</td>' +
            '<td>' + this.details[i].AbstractControlNo + '</td>' +
            '<td>' + this.details[i].AbstractNo + '</td>' +
            '<td>' + this.details[i].TrackingNo + '</td>' +
            '<td>' + this.details[i].DivCode + '</td>' +
            '<td>' + this.details[i].DateReceived + '</td>' +
            '<td>' + this.details[i].DateCreated + '</td>' +
            '<td>' + this.details[i].PRNo + '</td>' +
            '<td>' + this.details[i].TotalAmount + '</td>' +
            '<td>' + this.details[i].ProjectCode + '</td>' +
            '<td>' + this.details[i].PostIBDate + '</td>' +
            '<td>' + this.details[i].PhilGEPSReferenceNo + '</td>' +
            '<td>' + this.details[i].OpenOfBidsDate + '</td>' +
            '<td>' + this.details[i].SupplierName + '</td>' +
            '<td>' + this.details[i].Particulars + '</td>' +
            '<td>' + this.details[i].ExpectedDateOfDelivery + '</td>' +
            '<td>' + this.details[i].DateNumbered + '</td>' +
            '</tr>';
        }
        $('#sai-inbox').dataTable().fnClearTable();
        $('#sai-inbox').dataTable().fnDraw();
        $('#sai-inbox').dataTable().fnDestroy();
        $('#sai-inbox').find('tbody').html(tr);
        $('#sai-inbox').dataTable({
          paging: false
        });
      }
    }

    pageJS.init();
  })();
</script>
@endpush
@endsection
