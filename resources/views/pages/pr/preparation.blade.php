@extends('layouts.layout')

@section('title', 'PR Preparation')

@push('styles')
<style type="text/css">
  #item-table tr .col:nth-child(1) {
    width: 3%;
  }

  #item-table tr .col:nth-child(2) {
    width: 10%;
  }

  #item-table tr .col:nth-child(3) {
    width: 20%;
  }

  #item-table tr .col:nth-child(4) {
    width: 5%;
  }

  #item-table tr .col:nth-child(5) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(6) {
    width: 7%;
  }

  #item-table tr .col:nth-child(7) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(8) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(9) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(10) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(11) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(12) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(13) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(14) {
    width: 7.5%;
  }

  #item-table tr .col:nth-child(15) {
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

  .more-specs {
    display: none;
  }
</style>
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-12">
    <h1>Purchase Request (PR) / Preparation</h1>
  </div>
</div>

@if(session('EmployeeID') == '25-1010')
  <button id="btn-edit" class="btn btn-danger pull-right" value="{{ $PRControlNo ?? '' }}">Edit</button>
@endif

<div class="row">
  <div class="col-sm-12 col-button">
    <button id="btn-update-status" class="btn btn-success pull-right">Update Status</button>
    <button id="btn-history" class="btn btn-default pull-right">History</button>
    <button id="btn-print" class="btn btn-default pull-right">Print</button>
    <button id="btn-export" class="btn btn-default pull-right">Export</button>
    <button id="btn-save" class="btn btn-primary pull-right">Save</button>
    <button id="btn-done" class="btn btn-primary pull-right">Done</button>
    <button id="btn-evaluate" class="btn btn-primary pull-right">Evaluate</button>
    <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
    <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
    <button id="btn-return" class="btn btn-danger pull-right">Return</button>
    <button id="btn-return-spbi" class="btn btn-danger pull-right">Return SPBI</button>
    <button id="btn-cancel" class="btn btn-danger pull-right">Cancel</button>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">Header</div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12 col-button">
            <div class="alert alert-info" role="alert" id="status-alert"></div>
          </div>
        </div>
        <div class="row">
          <div class="col col-sm-4">
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">PR Control No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="control-no" value="{{ $PRControlNo ?? '' }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">PR No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="pr-no" value="" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Project Code:</label>
              <div class="col-sm-8">
                <input type="hidden" class="form-control" id="ppmp" value="" readonly="">
                <input type="text" class="form-control" id="project-code" value="{{ $default['ProjectCode'] ?? '' }}" readonly="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Program Code:</label>
              <div class="col-sm-8">
                <input type="hidden" class="form-control" id="ppmp_pcode" value="" readonly="">
                <input type="text" class="form-control" id="program-code" value="{{ $default['ProgramCode'] ?? '' }}" readonly="">
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">Year:</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" id="year" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-4 col-form-label">SPBI Control No.:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="spbi-control-no" disabled>
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
                <select class="form-control" id="respo-center">
                  <option value="">...</option>
                  @if(isset($RespoList))
                    @foreach($RespoList as $center)
                      <option value="{{ $center['RCCD'] }}" data-divcode="{{ $center['Acronym'] }}">{{ $center['RCDesc'] . " | " . $center['RCCD'] }}</option>
                    @endforeach
                  @endif
                </select>
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
                <input type="text" class="form-control" id="prepared-by" value="{{ $default['PreparedBy_Name'] ?? '' }}" data-id="{{ $default['PreparedBy'] ?? '' }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Requested By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee" id="requested-by">
                <div class="option-employee requestor"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Evaluated By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee" id="evaluated-by">
                <div class="option-employee evaluator"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col col-sm-4 col-form-label">Approved By:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control search-employee" id="approved-by">
                <div class="option-employee approver"></div>
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
              <label for="" class="col col-sm-4 col-form-label">RFF No.:</label>
              <div class="col col-sm-8">
                <input type="text" class="form-control" id="rff-no">
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
                <input type="checkbox" class="criteria" id="div-handle"> Division Handle
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Details</div>
  <div class="card-body">
    <div class="form-group row">
      <div class="table-responsive">
        <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
          <thead>
            <tr style="background-color: #B3B6B7;">
              <th class="col">
                @if(isset($QPR) && $QPR == 1)
                  <button class="fa fa-search search-item"></button>
                @endif
                <label class="show-additional-specs">
                  <input type="checkbox"> Show additional specs
                </label>
                <br>Item Code
              </th>
              <th class="col">Item Description</th>
              <th class="col">Unit</th>
              <th class="col">Cost</th>
              <th class="col">Requested</th>
              <th class="col">Amount</th>
              <th class="col">RFQ Control No.</th>
              <th class="col">Abstract No.</th>
              <th class="col">NOA Control No.</th>
              <th class="col">PO No.</th>
              <th class="col">IAR No.</th>
              <th class="col">RIS No.</th>
              <th class="col">Remarks</th>
              <th class="col">Accountable</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td>Total Amount:</td>
              <td class="col-num-1">0.00</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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
              <td class="col"></td>
              <td class="col"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  // is qpr or not
  const QPR = {{ $QPR ?? 0 }};

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

  // isApprovedAndReturned
  const isApprovedAndReturned = {{ $isApprovedAndReturned ?? 0 }};
</script>
<script type="text/javascript" src="{{ asset('js/pr_prep.js') }}?v={{ time() }}"></script>
@if($QPR == 1)
  <script type="text/javascript" src="{{ asset('js/qpr_prep.js') }}?v={{ time() }}"></script>
@endif
@endpush
@endsection
