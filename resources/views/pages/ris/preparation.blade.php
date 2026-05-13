@extends('layouts.layout')

@section('title', 'RIS Preparation')

@push('styles')
<style type="text/css">
  #ris-table tr .col:nth-child(1) {
    width: 3% !important;
  }

  #ris-table tr .col:nth-child(2) {
    width: 11% !important;
  }

  #ris-table tr .col:nth-child(3) {
    width: 20% !important;
  }

  #ris-table tr .col:nth-child(4) {
    width: 5% !important;
  }

  #ris-table tr .col:nth-child(5) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(6) {
    width: 5% !important;
  }

  #ris-table tr .col:nth-child(7) {
    width: 5% !important;
  }

  #ris-table tr .col:nth-child(8) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(9) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(10) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(11) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(12) {
    width: 7.5% !important;
  }

  #ris-table tr .col:nth-child(13) {
    width: 7.5% !important;
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
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-sm-6">
    <h1>Requisition and Issuance Slip (RIS) / Preparation</h1>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col col-button">
        <button id="btn-print" class="btn btn-default pull-right">Print</button>
        <button id="btn-history" class="btn btn-default pull-right">History</button>
        <button id="btn-save" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Save</button>
        <button id="btn-done" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Done</button>
        <button id="btn-approve" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Approve</button>
        <button id="btn-issue" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Issue</button>
        <button id="btn-receive" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Receive</button>
        <button id="btn-return" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Return</button>
        <button id="btn-return-spbi" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Return SPBI</button>
        <button id="btn-cancel" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Cancel</button>
        <button id="btn-generate" class="btn btn-warning pull-right" data-user="{{ session('EmployeeID') }}">Generate No.</button>
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
          <label for="" class="col-sm-4 col-form-label">RIS Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $RISControlNo ?? '' }}" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">RIS No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="ris-no" value="" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PPMP:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="ppmp" value="" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">SPBI Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="spbi-control-no" readonly="">
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
          <label for="" class="col col-sm-4 col-form-label">Responsibility Center:</label>
          <div class="col col-sm-8">
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
          <label for="" class="col col-sm-4 col-form-label">Purpose:</label>
          <div class="col col-sm-8">
            <textarea class="form-control" id="purpose" rows="4"></textarea>
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
          <label for="" class="col col-sm-4 col-form-label">Approved By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="approved-by" autocomplete="off">
            <div class="option-employee approver"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Issued By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="issued-by" autocomplete="off">
            <div class="option-employee issuer"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Received By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="received-by" autocomplete="off">
            <div class="option-employee reciever"></div>
          </div>
        </div>
      </div>

      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Created:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="date-created" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Total Amount:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="total-amount" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">ORS No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="bur-no">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="tracking-no">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">
    Details
    <button class="btn btn-info pull-right btn-sm" id="btn-select-items">Get from IAR</button>
  </div>
  <div class="card-body">
    <div class="form-group row">
      <div class="table-responsive">
        <table id="ris-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col"></th>
              <th class="col">Item Code</th>
              <th class="col">Item Description</th>
              <th class="col">Unit</th>
              <th class="col">Cost</th>
              <th class="col">Requested</th>
              <th class="col">Issued</th>
              <th class="col">Amount</th>
              <th class="col">SPBI No.</th>
              <th class="col">PR No.</th>
              <th class="col">PO No.</th>
              <th class="col">IAR No.</th>
              <th class="col">Remarks</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td>Total Amount:</td>
              <td class="col-num-1">0.00</td>
              <td class="col-num-2">0.00</td>
              <td class="col-num-3">0.00</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
          <tbody>
            <tr class="row-template">
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
              <td class="col"></td>
            </tr>
        </table>
      </div>
    </div>
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
    const allowRISIssue = true;
</script>
<script type="text/javascript" src="{{ asset('js/ris_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
