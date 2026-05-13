@extends('layouts.layout')

@section('title', 'Abstract Preparation')

@push('styles')
<style type="text/css">
  #item-table td,
  #sum-table .supplier-cell {
    cursor: pointer;
  }

  #item-table tr .col:nth-child(1) {
    width: 5%;
  }

  #item-table tr .col:nth-child(2) {
    width: 10%;
  }

  #item-table tr .col:nth-child(3) {
    width: 30%;
  }

  #item-table tr .col:nth-child(4) {
    width: 5%;
  }

  #item-table tr .col:nth-child(5) {
    width: 15%;
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

  #item-table tr .col:nth-child(10) {
    width: 10%;
  }

  #sum-table tr .col:nth-child(1) {
    width: 50px;
  }

  #sum-table tr .col:nth-child(2) {
    width: 50px;
  }

  #sum-table tr .col:nth-child(3) {
    width: 50px;
  }

  #sum-table tr .col:nth-child(4) {
    width: 70px;
  }

  #sum-table tr .col:nth-child(5) {
    width: 100px;
  }

  #sum-table tr .col:nth-child(6) {
    width: 200px;
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

  .winning-bid-1 {
    font-weight: bold;
    background-color: cornsilk;
  }

  .not-agree-1 {
    font-weight: bold;
    background-color: LightSalmon;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-12">
    <h1>Abstract / Preparation</h1>
  </div>
</div>

@if(session('EmployeeID') == '25-1010')
  <button id="btn-edit" class="btn btn-success pull-right" value="{{ $AbstractControlNo ?? '' }}">Edit</button>
@endif

<div class="row">
  <div class="col-sm-12 col-button">
    <button id="btn-history" class="btn btn-default pull-right" data-group_id="{{ session('GroupID') }}">History</button>
    <button id="btn-print" class="btn btn-default pull-right">Print</button>
    <button id="btn-export" class="btn btn-default pull-right">Export</button>
    <button id="btn-save" class="btn btn-primary pull-right">Save</button>
    <button id="btn-done" class="btn btn-primary pull-right">Done</button>
    <button id="btn-certify" class="btn btn-primary pull-right">Certify</button>
    <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
    <button id="btn-evaluate" class="btn btn-primary pull-right">Evaluated</button>
    <button id="btn-evaluating" class="btn btn-primary pull-right">Evaluating</button>
    <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
    <button id="btn-generate" class="btn btn-warning pull-right" data-user="{{ session('EmployeeID') }}">Generate No.</button>
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
              <label for="" class="col-sm-4 col-form-label">Abstract Control No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="control-no" value="{{ $AbstractControlNo ?? '' }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Abstract No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="no" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">BAC:</label>
              <div class="col col-sm-8">
                <select type="text" class="form-control" id="bac">
                  <option value=""></option>
                  <option value="BACCW">Civil Works</option>
                  <option value="BACGS">Goods and Services</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Recommendation/Resolution/Remarks:</label>
              <div class="col col-sm-8">
                <textarea class="form-control" rows="6" id="remarks"></textarea>
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
              <label for="" class="col col-sm-4 col-form-label">Encoded By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="encoded-by" disabled="" value="{{ $default['EncodedBy_Name'] ?? '' }}" data-id="{{ $default['EncodedBy'] ?? '' }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Prepared By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee" id="prepared-by" autocomplete="off" value="{{ $default['PreparedBy_Name'] ?? '' }}" data-id="{{ $default['PreparedBy'] ?? '' }}">
                <div class="option-employee preparer"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Evaluated By (TWG):</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee M4" id="twg-evaluated-by" autocomplete="off">
                <div class="option-employee M4" data-link-to="M4"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Evaluated By (End-user):</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee M5" id="enduser-evaluated-by" autocomplete="off">
                <div class="option-employee M5" data-link-to="M5"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Certified/ Recommended By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee CP" id="certified-by" autocomplete="off">
                <div class="option-employee certified"></div>
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
                <input type="text" class="form-control" id="total-amount" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Tracking No.:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="tracking-no" value="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label"></label>
              <div class="col col-sm-8">
                <input type="radio" name="classification" value="OC" checked> Open Canvass <br>
                <input type="radio" name="classification" value="SB"> Sealed Bids <br>
                <input type="radio" name="classification" value="PB"> Public Bids
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <h6>BAC Committee</h6>
          <br>

          <div class="row bac-committee">
            <div class="col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Chairperson:</label>
                <div class="col col-sm-8">
                  <select class="form-control" id="CP"></select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Member:</label>
                <div class="col col-sm-8">
                  <select class="form-control" id="M1"></select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Member (TWG):</label>
                <div class="col col-sm-8">
                  <input type="text" class="form-control search-employee M4" id="M4" autocomplete="off">
                  <div class="option-employee M4" data-link-to="M4"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Vice-Chairperson:</label>
              <div class="col col-sm-8">
                <select class="form-control" id="VCP"></select>
              </div>
            </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Member:</label>
                <div class="col col-sm-8">
                  <select class="form-control" id="M2"></select>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group row">
                <label for="" class="col col-sm-4 col-form-label">Member (End-user):</label>
                <div class="col col-sm-8">
                  <input type="text" class="form-control search-employee M5" id="M5" autocomplete="off">
                  <div class="option-employee M5" data-link-to="M5"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group row" style="opacity: 0;">
              <label for="" class="col col-sm-4 col-form-label"></label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Member:</label>
              <div class="col col-sm-8">
                <select class="form-control" id="M3"></select>
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
    <a class="nav-link active" id="summary-tab1" data-toggle="tab" href="#summary1" role="tab" aria-controls="summary1" aria-selected="true">Summary</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="details-tab1" data-toggle="tab" href="#details1" role="tab" aria-controls="details1" aria-selected="false">Details</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="summary1" role="tabpanel" aria-labelledby="summary-tab1">
    <div class="row">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" style="margin-right: 5px;" id="btn-select-bid" title="Select item from Bid Doc">Bid Doc</button>
        <button class="btn btn-info pull-right btn-sm" style="margin-right: 5px;" id="btn-select-rfq" title="Select item from RFQ">RFQ</button>
        <button class="btn btn-info pull-right btn-sm" style="margin-right: 5px;" id="btn-search-supplier" title="Select Supplier">Supplier</button>
      </div>
    </div>
    <table id="sum-table" class="table-bordered" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class=""></th>
          <th class="">Item No.</th>
          <th class="">Qty</th>
          <th class="">Unit</th>
          <th class="">Unit Price</th>
          <th class="">Specification</th>
          <th class="">RFQ</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="tab-pane fade" id="details1" role="tabpanel" aria-labelledby="details-tab1">
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">Project Code</th>
          <th class="col">Item Description</th>
          <th class="col">Quantity</th>
          <th class="col">Supplier</th>
          <th class="col">Requested By</th>
          <th class="col">PR No.</th>
          <th class="col">RFQ/Bid Control No.</th>
          <th class="col">PO No.</th>
          <th class="col">IAR No.</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  const bac = {};
  bac.BACCW = {{ $BACCW ?? '[]' }};
  bac.BACGS = {{ $BACGS ?? '[]' }};

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
<script type="text/javascript" src="{{ asset('js/abstract_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
