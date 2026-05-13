@extends('layouts.layout')

@section('title', 'PPAP Preparation')

@push('styles')
<style type="text/css">
  .col-month,
  .col-tqty {
    width: 6% !important;
  }

  .col-unit {
    width: 7% !important;
  }

  .col-generic {
    width: 13% !important;
  }

  .col-specs {
    width: 20% !important;
    position: relative;
  }

  .col-unitprice {
    width: 8% !important;
  }

  .col-amount {
    width: 10% !important;
  }

  .col-inc {
    width: 5% !important;
    font-size: 11px;
  }

  .col-action {
    width: 3%;
  }

  .btn-add {
    width: 70px;
  }

  .input-row input,
  .input-row select {
    width: 100%;
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

  .more-spec {
    width: 100%;
    resize: none;
    margin-top: 3px;
  }

  .oecode-row,
  tfoot td {
    font-weight: bold;
  }

  .table-bordered td {
    border-color: #ddd;
  }

  .toggle-spec:hover {
    cursor: pointer;
    color: #0071bc;
  }

  .more-spec {
    display: none;
  }

  .acc-row {
    background-color: #F2F3F4;
  }

  .col-button button {
    margin-left: 3px;
  }

  .col-num {
    text-align: right;
  }

  #ppmp-table tbody tr.input-row:hover {
    background-color: #0071bc !important;
    color: #fff;
    cursor: pointer;
  }

  .btn-edit-row,
  .btn-remove-row {
    width: 25px;
    height: 20px;
    cursor: pointer;
  }

  .search-item {
    width: 30px;
    height: 30px;
    position: absolute;
    right: 10px;
  }

  .show-additional-specs {
    font-weight: normal;
  }

  .more-specs {
    display: none;
  }

  .col-button button:not(#btn-search) {
    /* display: none;*/
  }

  #ppmp-header {
    cursor: pointer;
  }

  .original {
    background: yellow;
  }

  th {
    position: sticky;
    background: #f7f7f7;
    text-align: left;
  }

  thead {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f7f7f7;
    text-align: left;
    top: -10px;
  }

  thead>tr:nth-child(2) th {
    top: -10px;
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-sm-6">
    <h2>
      Project Procurement Augmentation Plan (PPAP) / <br>
      Project Procurement Supplemental Plan (PPSP)
    </h2>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col col-button">
        <button id="btn-edit" class="btn btn-warning pull-right">Edit</button>
        <button id="btn-print" class="btn btn-default pull-right">Print</button>
        <button id="btn-history" class="btn btn-default pull-right">History</button>
        <button id="btn-update-history" class="btn btn-info pull-right" data-user="{{ session('EmployeeID') }}">Update History</button>
        <button id="btn-save" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Save</button>
        <button id="btn-done" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Done</button>
        <button id="btn-process" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Process</button>
        <button id="btn-evaluate" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Evaluate</button>
        <button id="btn-approve" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Approve</button>
        <button id="btn-certify" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Certify</button>
        <button id="btn-receive" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Receive</button>
        <button id="btn-return" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Return</button>
        <button id="btn-cancel" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Header</div>
  <div class="card-body">
    <div class="row">
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PPAPControl No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $PPAPControlNo ?? '' }}" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Project Code:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="project-code" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Program Code:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="program-code" readonly="">
          </div>
        </div>

        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PPMP Year:</label>
          <div class="col-sm-8">
            <input type="number" class="form-control" id="ppmp-year" disabled>
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
          <label for="" class="col col-sm-4 col-form-label">Date Created:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-created" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Encoded By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="encoded-by" value="{{ $default['EncodedBy_Name'] ?? '' }}" data-id="{{ $default['EncodedBy'] ?? '' }}" disabled>
          </div>
        </div>
      </div>

      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Total Amount:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="total-amount" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="tracking-no">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label"></label>
          <div class="col col-sm-8">
            <input type="checkbox" class="criteria" name="Supplemental" id="supplemental" value="1"> Supplemental
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Signatories</div>
  <div class="card-body">
    <div class="row">
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Prepared By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="prepared-by" placeholder="" autocomplete="off">
            <div class="option-employee preparer"></div>
          </div>
        </div>
      </div>

      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Approving Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="approved-by" placeholder="" autocomplete="off">
            <div class="option-employee approving"></div>
          </div>
        </div>
      </div>

      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Certifying Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="certified-by" placeholder="" autocomplete="off">
            <div class="option-employee certifying"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">PPMP Details <i>(Existing Items)</i></div>
  <div class="card-body">
    <div class="form-group row">
      <div class="table-responsive">
        <table id="ppmp-details" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col-action"></th>
              <th class="col-specs" style="position: relative;">
                <label class="show-additional-specs">
                  <input type="checkbox"> Show additional spec
                </label>
                <button class="fa fa-search search-item"></button>
                <br>Item Specification
              </th>
              <th class="col-unit">Unit</th>
              <th class="col-unitprice">Unit Price</th>
              <th class="col-month">Jan</th>
              <th class="col-month">Feb</th>
              <th class="col-month">Mar</th>
              <th class="col-month">Apr</th>
              <th class="col-month">May</th>
              <th class="col-month">Jun</th>
              <th class="col-month">Jul</th>
              <th class="col-month">Aug</th>
              <th class="col-month">Sep</th>
              <th class="col-month">Oct</th>
              <th class="col-month">Nov</th>
              <th class="col-month">Dec</th>
              <th class="col-tqty">Qty</th>
              <th class="col-amount">Amount</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="15"></td>
              <td colspan="2">Total Amount: (Unallocated)</td>
              <td id="ppmp-amount" class="col-num">0.00</td>
              <td></td>
            </tr>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">PPAP Details <i>(New Items)</i></div>
  <div class="card-body">
    <div class="form-group row">
      <div class="table-responsive">
        <table id="ppap-details" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col-action"></th>
              <th class="col-specs" style="position: relative;">
                <label class="show-additional-specs">
                  <input type="checkbox"> Show additional spec
                </label>
                <button class="fa fa-search search-item"></button>
                <br>Item Specification
              </th>
              <th class="col-unit">Unit</th>
              <th class="col-unitprice">Unit Price</th>
              <th class="col-month">Jan</th>
              <th class="col-month">Feb</th>
              <th class="col-month">Mar</th>
              <th class="col-month">Apr</th>
              <th class="col-month">May</th>
              <th class="col-month">Jun</th>
              <th class="col-month">Jul</th>
              <th class="col-month">Aug</th>
              <th class="col-month">Sep</th>
              <th class="col-month">Oct</th>
              <th class="col-month">Nov</th>
              <th class="col-month">Dec</th>
              <th class="col-tqty">Qty</th>
              <th class="col-amount">Amount</th>
              <th class="col-inc">Inc. in Supplemental APP</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="15"></td>
              <td colspan="2">Total Amount:</td>
              <td id="ppap-amount" class="col-num">0.00</td>
              <td></td>
            </tr>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  // admin access
  @if(hasAccess(32))
    const allowAdminAccess = true;
  @else
    const allowAdminAccess = false;
  @endif

  @if(hasAccess(72))
    const allowPPAPCancel = true;
  @else
    const allowPPAPCancel = false;
  @endif
</script>
<script type="text/javascript" src="{{ asset('js/ppap_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
