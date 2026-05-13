@extends('layouts.layout')

@section('title', 'PO Preparation')

@push('styles')
<style type="text/css">
#item-table tr .col:nth-child(1) {
  width: 5%;
}
#item-table tr .col:nth-child(2) {
  width: 10%;
}
#item-table tr .col:nth-child(3) {
  width: 25%;
}
#item-table tr .col:nth-child(4) {
  width: 10%;
}
#item-table tr .col:nth-child(5) {
  width: 10%;
}
#item-table tr .col:nth-child(6) {
  width: 10%;
}
#item-table tr .col:nth-child(7) {
  width: 10%;
}
#item-table tr .col:nth-child(8) {
  width: 15%;
}

.search-item {
  width: 30px;
  height: 30px;
}

.row-template {
  display: none;
}

#item-table tbody tr:hover {
  cursor: pointer;
}

.option-employee div:hover{
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
}
#btn-save, #btn-done, #btn-print {
  display: block;
}

.more-specs {
  display: none;
}
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-sm-6">
    <h1>Purchase Order (PO) / Preparation</h1>
  </div>
  <div class="col-sm-6">
    <div class="row">
    <div class="col col-button">
      <button id="btn-print" class="btn btn-default pull-right">Print</button>
      <button id="btn-history" class="btn btn-default pull-right">History</button>
      <button id="btn-save" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Save</button>
      <button id="btn-done" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Done</button>
      <button id="btn-approve" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Approve</button>
      <button id="btn-certify" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Certify</button>
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
        <label for="" class="col-sm-4 col-form-label">PO Control No.:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="control-no" value="{{ $POControlNo ?? '' }}" disabled>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">PO No.:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="po-no" disabled>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Project Code:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="project-code" readonly="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">PPMP Year:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="ppmp-year" readonly="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">PR No.:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="pr-control-no" readonly="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Place of Delivery:</label>
        <div class="col-sm-8">
          <select class="form-control" id="place-of-delivery">
            <option value="ATI">ATI</option>
            <option value="PhilRice Warehouse">PhilRice Warehouse</option>
            <option value="Supplier's Address">Supplier's Address</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Date of Delivery:</label>
        <div class="col-sm-8">
          <input type="number" class="form-control" id="date-of-delivery" placeholder="No. of days after conforme">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label"></label>
        <div class="col-sm-8">
          <input type="radio" name="DateOfDelivery_CountType" value="W" checked> Working Days &nbsp;&nbsp;
          <input type="radio" name="DateOfDelivery_CountType" value="C" > Calenday Days
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
        <label for="" class="col col-sm-4 col-form-label">Authorized By:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control search-employee" id="evaluated-by">
          <div class="option-employee evaluator"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Requested By:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control search-employee" id="requested-by" >
          <div class="option-employee requestor"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Funds Available By:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control search-employee" id="funds-available-by">
          <div class="option-employee evaluator"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Supplied By:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control" id="supplier-name">
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
        <label for="" class="col-sm-4 col-form-label">BUR No.:</label>
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

<div class="row mb-3">
<div class="col-sm-6">
  <div class="card">
    <div class="card-header">Supplier Information</div>
    <div class="card-body">
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Supplier:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="supplier" readonly="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">TIN:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="sup-tin" disabled="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Address:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="sup-add" disabled="">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-sm-6">
  <div class="card">
    <div class="card-header">Terms</div>
    <div class="card-body">
            <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Mode of Procurement:</label>
        <div class="col col-sm-8">
          <select class="form-control" id="mode">
            <option value="">...</option>
            @if(isset($ModesOfProcurement))
              @foreach($ModesOfProcurement as $mop)
                <option value="{{ $mop['ModeID'] }}">{{ $mop['MocDesc'] }}</option>
              @endforeach
            @endif
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Shipping Term:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control" id="shipping-term" value="FOB Shipping Point">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col col-sm-4 col-form-label">Payment Term:</label>
        <div class="col col-sm-8">
          <input type="text" class="form-control" id="payment-term" value="Payable n/30">
        </div>
      </div>
    </div>
  </div>
</div>  
</div>

<div class="card mb-3">
<div class="card-body">
  <div class="form-group row">
  <div class="table-responsive">
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col"></th>
          <th class="col">
            <label class="show-additional-specs">
              <input type="checkbox"> Show additional specs (print specs only)
            </label>
                
            <br>Item Code</th>
          <th class="col">Item Description</th>
          <th class="col">Unit</th>
          <th class="col">Cost</th>
          <th class="col">Quantity</th>
          <th class="col">Amount</th>
          <th class="col">Remarks</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <td colspan="5"></td>
          <td>Total Amount:</td>
          <td class="col-num-1">0.00</td>
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
        </tr>
    </table>
  </div>

</div>
</div>

<!-- Search PR Item -->
<div class="modal fade" id="pr-items-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select items from purchase request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-striped" id="sai-table">
            <thead>
              <tr>
                <td style="width: 3%;"></td>
                <td style="width: 20%;">Item Code</td>
                <td style="width: 40%;">Description</td>
                <td style="width: 20%;">Unit</td>
                <td style="width: 17%;">Price</td>
                <td style="width: 17%;">Requested</td>
                <td style="width: 17%;">Amount</td>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
var base_url = "{{ url('/') }}";

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
<script type="text/javascript" src="{{ asset('js/po_prep.js') }}"></script>
@endpush
@endsection
