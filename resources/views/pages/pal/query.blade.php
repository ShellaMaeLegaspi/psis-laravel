@extends('layouts.layout')

@section('title', 'PAL Query')

@push('styles')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(2) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(3) {
    width: 30%
  }

  #sai-inbox tr td:nth-child(4) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(5) {
    width: 30%
  }

  #sai-inbox tr td:nth-child(6) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(7) {
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
    <h1>Property and Article List (PAL) / Query</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">PAL No.:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="PALNo">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Item No.:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="ItemNo">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Description:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="Description">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Barcode:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="Barcode">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Category:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="Category">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Unit:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="Unit">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Unit Cost:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="UnitCost">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Quantity:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control criteria" name="Quantity">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Status:</label>
          <div class="col-sm-10">
            <select class="form-control criteria" name="Status">
              <option value="">...</option>
              <option value="N">New</option>
              <option value="D">Done</option>
              <option value="R">Returned</option>
              <option value="A">Approved</option>
              <option value="I">Received</option>
              <option value="X">Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Date From:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateFrom" readonly="">
          </div>
          <label for="" class="col-sm-1 col-form-label">To:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateTo" readonly="">
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
            <th>PAL No.</th>
            <th>Item No.</th>
            <th>Description</th>
            <th>Barcode</th>
            <th>Category</th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th>Quantity</th>
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
    var spbi = {
      headers: [],
      ajaxReq: null,
      init: function() {
        this.cacheDOM();
        this.bindEvents();
      },
      cacheDOM: function() {
        this.$palcontrolno = $('[name="PALNo"]');
        this.$btnsearch = $('#btn-search');
        this.$table = $('#sai-inbox');

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
      },
      get: function() {
        var criteria = {};

        this.$txtCriteria.each(function() {
          if (this.value == '') return;
          criteria[this.name] = this.value;
        });

        var me = this;

        if (this.ajaxReq != null) ajaxReq.abort();
        TableLoading.On('#sai-inbox');
        this.ajaxReq = $.ajax({
          type: "post",
          url: "{{ url('/property/get_pal_headers') }}",
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
        window.open('{{ url('/property/preparation') }}/PALNo/' + $(this).data('controlno'), '_blank');
      },
      render: function() {
        var row = '';
        $('#sai-inbox tbody').html('');
        for (var i in this.headers) {
          row +=
            '<tr data-controlno="' + this.headers[i].PALNo + '">' +
            '<td>' + this.headers[i].PALNo + '</td>' +
            '<td>' + this.headers[i].ItemNo + '</td>' +
            '<td>' + this.headers[i].Description + '</td>' +
            '<td>' + this.headers[i].Barcode + '</td>' +
            '<td>' + this.headers[i].Category + '</td>' +
            '<td>' + this.headers[i].Unit + '</td>' +
            '<td>' + this.headers[i].UnitCost + '</td>' +
            '<td>' + this.headers[i].Quantity + '</td>' +
            '<td>' + this.headers[i].StatusName] + '</td>' +
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

    spbi.init();
  })();
</script>
@endpush
@endsection
