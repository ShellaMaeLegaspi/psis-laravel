@extends('layouts.layout')

@section('title', 'IIRUP Preparation')

@push('styles')
<style type="text/css">
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
    width: 10%;
  }

  #item-table tr .col:nth-child(6) {
    width: 5%;
  }

  #item-table tr .col:nth-child(7) {
    width: 10%;
  }

  #item-table tr .col:nth-child(8) {
    width: 15%;
  }

  #item-table tr .col:nth-child(9) {
    width: 10%;
  }

  .more-specs {
    display: none;
  }

  .col-button button {
    display: none;
  }

  button {
    margin-left: 3px;
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
<div class="row">
  <div class="col-sm-12">
    <h1>Inventory and Inspection Report of Unserviceable Property (IIRUP) / Preparation</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-button">
    <button id="btn-print" class="btn btn-default pull-right">Print</button>
    <button id="btn-history" class="btn btn-default pull-right">History</button>
    <button id="btn-save" class="btn btn-primary pull-right">Save</button>
    <button id="btn-done" class="btn btn-primary pull-right">Done</button>
    <button id="btn-inspect" class="btn btn-primary pull-right">Inspect</button>
    <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
    <button id="btn-witness" class="btn btn-primary pull-right">Witness</button>
    <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
    <button id="btn-generate" class="btn btn-warning pull-right">Generate No.</button>
    <button id="btn-return" class="btn btn-danger pull-right">Return</button>
    <button id="btn-cancel" class="btn btn-danger pull-right d-none">Cancel</button>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Header</div>
  <div class="card-body">
    <div class="row">
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">IIRUP Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $IIRUPControlNo ?? '' }}" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">IIRUP No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="no" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Division:</label>
          <div class="col-sm-8">
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
          <label for="" class="col-sm-4 col-form-label">Responsibility Center:</label>
          <div class="col-sm-8">
            <select class="form-control" id="respo-center" disabled>
              <option value="">...</option>
              @if(isset($RespoList))
                @foreach($RespoList as $center)
                  <option value="{{ $center['RCCD'] }}" data-divcode="{{ $center['Acronym'] }}">{{ $center['RCDesc'] . " | " . $center['RCCD'] }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Remarks:</label>
          <div class="col-sm-8">
            <textarea class="form-control" rows="8" id="remarks"></textarea>
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
            <input type="text" class="form-control" id="prepared-by" disabled="" value="{{ $default['PreparedBy_Name'] ?? '' }}" data-id="{{ $default['PreparedBy'] ?? '' }}">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Requested By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="requested-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Approved By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="approved-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Inspected By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="inspected-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Witness By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="witness-by" autocomplete="off">
            <div class="option-employee"></div>
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
          <label for="" class="col col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="tracking-no">
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer">
      <div class="row">
        <div class="col-sm-1">Note:</div>
        <div class="col-sm-11">
          1) This form shall be prepared in three (3) copies with duly accomplished pre-repair and post repair inspection report.<br>
          2) This form shall be basis in the preparation of Inventory and Inspection Report of Unserviceable Property (IIRUP).
        </div>
      </div>
    </div>
  </div>
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane active" id="details" role="tabpanel" aria-labelledby="details-tab">
    <div class="row">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" id="btn-select-item">Select PPE</button>
      </div>
    </div>
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th colspan=""></th>
          <th colspan="10" class="text-center">Inventory</th>
          <th colspan="8" class="text-center">Inspection and Disposal</th>
        </tr>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">Date Acquired</th>
          <th class="col">Description</th>
          <th class="col">Property No.</th>
          <th class="col">Qty</th>
          <th class="col">U. Cost</th>
          <th class="col">T. Cost</th>
          <th class="col">Accum. Dep.</th>
          <th class="col">Accum. Impairment Losses</th>
          <th class="col">Carrying Amt.</th>
          <th class="col">Remarks</th>
          <th class="col">Sale</th>
          <th class="col">Transfer</th>
          <th class="col">Destruction</th>
          <th class="col">Others</th>
          <th class="col">Total</th>
          <th class="col">Appraised Val.</th>
          <th class="col">OR No.</th>
          <th class="col">Amount</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

@push('scripts')
<script type="text/javascript" src="{{ asset('js/iirup_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
