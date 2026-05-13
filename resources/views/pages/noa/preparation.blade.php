@extends('layouts.layout')

@section('title', 'NOA Preparation')

@push('styles')
<style type="text/css">
  #item-table tr .col:nth-child(1) {
    width: 5%;
  }

  #item-table tr .col:nth-child(2) {
    width: 5%;
  }

  #item-table tr .col:nth-child(3) {
    width: 5%;
  }

  #item-table tr .col:nth-child(4) {
    width: 5%;
  }

  #item-table tr .col:nth-child(5) {
    width: 30%;
  }

  #item-table tr .col:nth-child(6) {
    width: 10%;
  }

  #item-table tr .col:nth-child(7) {
    width: 10%;
  }

  #item-table tr .col:nth-child(8) {
    width: 10%;
  }

  #item-table tr .col:nth-child(9) {
    width: 10%;
  }

  .search-item {
    width: 30px;
    height: 30px;
  }

  .row-template {
    display: none;
  }

  #sai-table tbody tr:hover {
    background-color: #0071bc !important;
    color: #fff;
    cursor: pointer;
  }

  .option-employee div:hover {
    color: #FFF;
    background-color: #0071bc;
    cursor: pointer;
  }

  .option-employee div {
    padding: 3px;
  }

  .option-employee {
    position: absolute;
    background: white;
    width: 100%;
    z-index: 1;
  }

  .col-button button {
    margin-left: 3px;
    display: none;
  }

  #btn-save,
  #btn-done,
  #btn-print {
    display: block;
  }

  .table {
    font-size: 12px !important;
  }

  #tbl-utilization-header {
    width: 100%;
    font-size: 14px;
    position: absolute;
    z-index: 1;
  }

  #tbl-utilization-header th {
    text-align: center;
  }

  #tbl-utilization {
    width: 100%;
    font-size: 14px;
  }

  #tbl-utilization th {
    text-align: center;
  }

  #tbl-utilization td {
    text-align: right;
    width: 20%;
  }

  #tbl-utilization td:first-child {
    text-align: center;
  }

  #tbl-utilization tbody tr:hover {
    background: black;
    color: white;
  }

  #tbl-utilization th,
  #tbl-utilization-header th {
    width: 20% !important;
  }

  .card-body-items {
    height: 85vh;
    overflow: scroll;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-12">
    <h1>Notice of Award (NOA) / Preparation</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-button">
    <button id="btn-history" class="btn btn-default pull-right">History</button>
    <button id="btn-print" class="btn btn-default pull-right">Print</button>
    <button id="btn-export" class="btn btn-default pull-right">Export</button>
    <button id="btn-save" class="btn btn-primary pull-right">Save</button>
    <button id="btn-done" class="btn btn-primary pull-right">Done</button>
    <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
    <button id="btn-evaluate" class="btn btn-primary pull-right">Evaluate</button>
    <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
    <button id="btn-return" class="btn btn-danger pull-right">Return</button>
    <button id="btn-cancel" class="btn btn-danger pull-right">Cancel</button>
    <button id="btn-update-status" class="btn btn-success pull-right">Update Status</button>
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
              <label for="" class="col-sm-4 col-form-label">NOA Control No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="control-no" value="{{ $NOAControlNo ?? '' }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Supplier:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="supplier" value="" data-id="" readonly="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Contact Name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="supplier-contact-name">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Contact Designation:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="supplier-contact-designation">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Particulars:</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="4" id="remarks"></textarea>
              </div>
            </div>
          </div>

          <div class="col col-sm-4">
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Status:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="status" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Prepared By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="prepared-by" value="{{ $default['Canvasser_Name'] ?? '' }}" data-id="{{ $default['CanvasserID'] ?? '' }}" disabled="">
                <div class="option-employee approver"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Email:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="email" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Division:</label>
              <div class="col col-sm-8">
                <select class="form-control" id="div-code">
                  <option value="">...</option>
                  @if(isset($Divisions))
                    @foreach($Divisions as $div)
                      <option value="{{ $div['DivCode'] }}">{{ $div['DivName'] . " | " . $div['DivCode'] }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Tel No.:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="tel-no" value="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Approved By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee" id="approved-by" autocomplete="off">
                <div class="option-employee approver"></div>
              </div>
            </div>
          </div>

          <div class="col col-sm-4">
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Date Created:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="date-created" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Total Amount:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="total-amount" value="" disabled="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Tracking No.:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="tracking-no" value="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">PhilGEPS Award Number:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="philgeps-award-no" value="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
    <div class="row">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" style="margin-right: 5px;" id="btn-select-abstract">Abstract</button>
        <button class="btn btn-info pull-right btn-sm" style="margin-right: 5px;" id="btn-select-pr">PR</button>
        <label class="pull-right">Get items from:</label>
      </div>
    </div>
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">Item No.</th>
          <th class="col">Quantity</th>
          <th class="col">Unit</th>
          <th class="col">Item Description <br> Quotation</th>
          <th class="col">Depart/Office</th>
          <th class="col">Unit Price</th>
          <th class="col">Total Price</th>
          <th class="col">Abstract Control No.</th>
          <th class="col">PR No.</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  // allow user to cancel item
  @if(hasAccess(30))
    const allowCancelItem = true;
  @else
    const allowCancelItem = false;
  @endif

  // update status
  @if(hasAccess(31))
    const allowUpdateStatus = true;
  @else
    const allowUpdateStatus = false;
  @endif

  // admin access
  @if(hasAccess(32))
    const allowAdminAccess = true;
  @else
    const allowAdminAccess = false;
  @endif
</script>
<script type="text/javascript" src="{{ asset('js/noa_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
