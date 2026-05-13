@extends('layouts.layout')

@section('title', 'PAL Preparation')

@push('styles')
<style type="text/css">
  #summary-table tr .col:nth-child(1) {
    width: 5%;
  }

  #summary-table tr .col:nth-child(2) {
    width: 5%;
  }

  #summary-table tr .col:nth-child(3) {
    width: 10%;
  }

  #summary-table tr .col:nth-child(4) {
    width: 25%;
  }

  #summary-table tr .col:nth-child(5) {
    width: 25%;
  }

  #summary-table tr .col:nth-child(6) {
    width: 15%;
  }

  #summary-table tr .col:nth-child(7) {
    width: 15%;
  }

  #item-table td {
    cursor: pointer;
  }

  #item-table tr .col:nth-child(1) {
    width: 5%;
  }

  #item-table tr .col:nth-child(2) {
    width: 5%;
  }

  #item-table tr .col:nth-child(3) {
    width: 10%;
  }

  #item-table tr .col:nth-child(4) {
    width: 25%;
  }

  #item-table tr .col:nth-child(5) {
    width: 25%;
  }

  #item-table tr .col:nth-child(6) {
    width: 15%;
  }

  #item-table tr .col:nth-child(7) {
    width: 15%;
  }

  .more-specs {
    display: none;
  }

  .col-button button {
    display: none;
  }

  .toggable {
    background: #98FB98;
    cursor: pointer;
  }

  input:read-only:not(:disabled) {
    background-color: #98FB98 !important;
  }

  .card-body-items {
    height: 85vh;
    overflow: scroll;
  }

  .btn-cancel-item {
    display: none;
  }
</style>
@endpush

@section('content')
<form action="{{ url('/property/save_pal') }}" method="post">
  <div class="row">
    <div class="col-sm-12">
      <h1>PAL Preparation</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-button">
      <button id="btn-print" class="btn btn-default pull-right">Print</button>
      <button id="btn-history" class="btn btn-default pull-right">History</button>
      <button id="btn-save" class="btn btn-primary pull-right" type="submit">Save</button>
      <button id="btn-done" class="btn btn-primary pull-right">Done</button>
      <button id="btn-certify" class="btn btn-primary pull-right">Certify</button>
      <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
      <button id="btn-inspect" class="btn btn-primary pull-right">Inspect</button>
      <button id="btn-witness" class="btn btn-primary pull-right">Witness</button>
      <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
      <button id="btn-generate" class="btn btn-warning pull-right">Generate No.</button>
      <button id="btn-return" class="btn btn-danger pull-right">Return</button>
      <button id="btn-cancel" class="btn btn-danger pull-right d-none">Cancel</button>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card mb-3">
        <div class="card-header">Header</div>
        <div class="card-body">
          <div class="row">
            <div class="col col-sm-4">
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Item No.:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="control-no" name="ItemNo" value="{{ $ItemNo ?? '' }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Inventory Tag:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="InventoryTag">{{ $header['InventoryTag'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Article:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Article">{{ $header['Article'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Description:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Description">{{ $header['Description'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Barcode:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Barcode">{{ $header['Barcode'] ?? '' }}</textarea>
                </div>
              </div>
            </div>

            <div class="col col-sm-4">
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Acquisition Date:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="AcquisitionDate">{{ $header['AcquisitionDate'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Unit:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Unit">{{ $header['Unit'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Unit Cost:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="UnitCost">{{ $header['UnitCost'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Qty Per PropertyCard:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="QtyPerPropertyCard">{{ $header['QtyPerPropertyCard'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Qty PhysicalCount:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="QtyPhysicalCount">{{ $header['QtyPhysicalCount'] ?? '' }}</textarea>
                </div>
              </div>
            </div>

            <div class="col col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Accountable:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Accountable">{{ $header['Accountable'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Accum. Depreciation:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="AccumDepreciation">{{ $header['AccumDepreciation'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Accum. Impairment Losses:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="AccumImpairmentLosses">{{ $header['AccumImpairmentLosses'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Carrying Amt.:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="CarryingAmt">{{ $header['CarryingAmt'] ?? '' }}</textarea>
                </div>
              </div>
            </div>

            <div class="col col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Remarks:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" rows="8" id="remarks">{{ $header['Remarks'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Collector:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="Collector">{{ $header['Collector'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Date Collected:</label>
                <div class="col-sm-8">
                  <textarea class="form-control" name="DateCollected">{{ $header['DateCollected'] ?? '' }}</textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="col col-sm-4">
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Status:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="status" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Prepared By:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="prepared-by" value="{{ $default['PreparedBy_Name'] ?? '' }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Date Created:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="date-created" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Tracking No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="tracking-no">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="summary1-tab" data-toggle="tab" href="#summary1" role="tab" aria-controls="summary1" aria-selected="true">Summary</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="details1-tab" data-toggle="tab" href="#details1" role="tab" aria-controls="details1" aria-selected="false">Details</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
      <div class="tab-pane active" id="summary1" role="tabpanel" aria-labelledby="summary1-tab">
        <table id="summary-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col action-col"></th>
              <th class="col">Item No.</th>
              <th class="col">Qty</th>
              <th class="col">Component</th>
              <th class="col">Main Article Description</th>
              <th class="col">O.R. No.</th>
              <th class="col">Amount</th>
              <th class="col">Remarks</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="tab-pane" id="details1" role="tabpanel" aria-labelledby="details1-tab">
        <div class="row p-2">
          <div class="col-sm-12">
            <button class="btn btn-info pull-right btn-sm" id="btn-select-item">Select PPE</button>
          </div>
        </div>
        <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col action-col"></th>
              <th class="col">Item No.</th>
              <th class="col">Qty</th>
              <th class="col">Component</th>
              <th class="col">Main Article Description</th>
              <th class="col">O.R. No.</th>
              <th class="col">Amount</th>
              <th class="col">Remarks</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <input type="hidden" name="ItemNo" id="itemno">
  <input type="hidden" name="PALHeaderID" id="pal-header-id">

@push('scripts')
<script type="text/javascript">
  const itemno = {{ $ItemNo ?? '' }};

  function get(itemno) {
    var data = {};
    data.ItemNo = itemno;
    $.post('{{ url('/property/get_pal') }}', data, function(response) {
      var res = JSON.parse(response);
      if (res.header) {
        $('#pal-header-id').val(res.header.PALHeaderID);
        $('#control-no').val(res.header.ItemNo);
        $('#itemno').val(res.header.ItemNo);
        
        // Update summary table
        updateSummaryTable(res.details);
        
        // Update details table
        updateDetailsTable(res.details);
      }
    });
  }

  function updateSummaryTable(details) {
    // Clear existing rows
    $('#summary-table tbody').empty();
    
    if (!details || details.length === 0) return;
    
    var totalAmount = 0;
    var row = '';
    for (var i = 0; i < details.length; i++) {
      var item = details[i];
      totalAmount += parseFloat(item.Amount || 0);
      row += '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td>' + item.ItemNo + '</td>' +
        '<td>' + item.Qty + '</td>' +
        '<td>' + item.Component + '</td>' +
        '<td>' + (item.MainArticleDescription || '') + '</td>' +
        '<td>' + (item.ORNo || '') + '</td>' +
        '<td>' + numberFormat(item.Amount) + '</td>' +
        '<td>' + (item.Remarks || '') + '</td>' +
        '</tr>';
    }
    
    $('#summary-table tbody').append(row);
    $('#summary-table tfoot .total-amount').text(numberFormat(totalAmount));
  }

  function updateDetailsTable(details) {
    // Clear existing rows
    $('#item-table tbody').empty();
    
    if (!details || details.length === 0) return;
    
    var row = '';
    for (var i = 0; i < details.length; i++) {
      var item = details[i];
      row += '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td>' + item.ItemNo + '</td>' +
        '<td>' + item.Qty + '</td>' +
        '<td>' + item.Component + '</td>' +
        '<td>' + (item.MainArticleDescription || '') + '</td>' +
        '<td>' + (item.ORNo || '') + '</td>' +
        '<td>' + numberFormat(item.Amount) + '</td>' +
        '<td>' + (item.Remarks || '') + '</td>' +
        '</tr>';
    }
    
    $('#item-table tbody').append(row);
  }

  $(document).ready(function() {
    // Initialize with empty data
    if (itemno) {
      get(itemno);
    }
  });
  });
</script>
@endpush
@endsection
