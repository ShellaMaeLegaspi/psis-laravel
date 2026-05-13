@extends('layouts.layout')

@section('title', 'PAR Preparation')

@push('styles')
<style type="text/css">
   #item-table td {
     cursor: pointer;
   }

   #item-table tr .col:nth-child(1) {
     width: 5%;
   }

   #item-table tr .col:nth-child(2) {
     width: 10%;
   }

   #item-table tr .col:nth-child(3) {
     width: 35%;
   }

   #item-table tr .col:nth-child(4) {
     width: 7.5%;
   }

   #item-table tr .col:nth-child(5) {
     width: 7.5%;
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

   #sum-table tr .col:nth-child(1) {
     width: 5%;
   }

   #sum-table tr .col:nth-child(2) {
     width: 10%;
   }

   #sum-table tr .col:nth-child(3) {
     width: 25%;
   }

   #sum-table tr .col:nth-child(4) {
     width: 10%;
   }

   #sum-table tr .col:nth-child(5) {
     width: 10%;
   }

   #sum-table tr .col:nth-child(6) {
     width: 10%;
   }

   #sum-table tr .col:nth-child(7) {
     width: 10%;
   }

   #sum-table tr .col:nth-child(8) {
     width: 15%;
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
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Property Acknowledgement Receipt (PAR) / Preparation</h1>
  </div>
  <div class="col-sm-12">
    <div class="row">
      <div class="col col-button">
        <button id="btn-print" class="btn btn-default pull-right">Print</button>
        <button id="btn-history" class="btn btn-default pull-right">History</button>
        <button id="btn-edit" class="btn btn-danger pull-right" value="{{ $PARControlNo ?? '' }}" style="display: inline-block;">Edit</button>
        <button id="btn-save" class="btn btn-primary pull-right">Save</button>
        <button id="btn-done" class="btn btn-primary pull-right">Done</button>
        <button id="btn-accept" class="btn btn-primary pull-right">Accept</button>
        <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
        <button id="btn-receive" class="btn btn-primary pull-right">Receive</button>
        <button id="btn-generate" class="btn btn-warning pull-right">Generate No.</button>
        <button id="btn-return" class="btn btn-danger pull-right">Return</button>
        <button id="btn-cancel" class="btn btn-danger pull-right">Cancel</button>
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
          <label for="" class="col-sm-4 col-form-label">PAR Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $PARControlNo ?? '' }}" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PAR No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="no" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Sector:</label>
          <div class="col-sm-8">
            <select class="form-control" name="FundClass" id="txt-sector">
              <option value="">...</option>
              <option value="A">Admin</option>
              <option value="R">Research</option>
              <option value="D">Development</option>
            </select>
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
          <label for="" class="col-sm-4 col-form-label">Responsibility Center:</label>
          <div class="col-sm-8">
            <select class="form-control" id="respo-center" disabled="">
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
            <textarea class="form-control" rows="4" id="remarks"></textarea>
          </div>
        </div>
      </div>

      <div class="col col-sm-3">
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
          <label for="" class="col col-sm-4 col-form-label">Accountable Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="accountable-officer" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Signed:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="ac-officer-date-signed" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Received (Physical Docs):</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="ac-date-received-physical-docs" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Co-Accountable Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="co-accountable-officer" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Signed:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="coac-date-signed" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Property Officer:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control search-employee" id="property-officer" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
      </div>

      <div class="col col-sm-3">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Created:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="date-created" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Total Amount:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="total-amount" value="{{ isset($header['TotalAmount']) ? number_format($header['TotalAmount'], 2) : '0.00' }}" disabled>
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

      <div class="col col-sm-2">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <input type="radio" name="type" value="O" checked> Original <br>
            <input type="radio" name="type" value="R"> Renewal <br>
            <input type="radio" name="type" value="T"> Transfer
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
  <div class="tab-pane active" id="details" role="tabpanel" aria-labelledby="details-tab">
    <div class="row">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" id="btn-select-item">Select PPE</button>
      </div>
    </div>
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">Property No.</th>
          <th class="col">
            <label class="show-additional-specs">
              <input type="checkbox"> Show additional specs
            </label>
            <br>Description
          </th>
          <th class="col">Unit</th>
          <th class="col">Qty</th>
          <th class="col">Cost</th>
          <th class="col">Amount</th>
          <th class="col">Project Code</th>
          <th class="col">Previous PAR No.</th>
          <th class="col">Remarks</th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="text-right"><strong>Total Amount:</strong></td>
          <td id="total-amount-footer" class="text-right"><strong>0.00</strong></td>
          <td colspan="3"></td>
        </tr>
      </tfoot>
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

   // Calculate Total Amount from table data after page loads
   $(document).ready(function() {
     // Wait for DataTable to be initialized
     setTimeout(function() {
       var total = 0;
       if (typeof TableDetails !== 'undefined' && TableDetails.rows) {
         TableDetails.rows().every(function() {
           var data = this.data();
           // Quantity is at index 5, Cost/UnitPrice at index 6
           var qty = parseFloat(data[5]) || 0;
           var price = parseFloat(data[6]) || 0;
           total += qty * price;
         });
         $('#total-amount').val(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
         $('#total-amount-footer').text(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
       }
     }, 500);
   });
</script>
<script type="text/javascript" src="{{ asset('js/par_prep.js') }}?v={{ time() }}"></script>
@endpush
@endsection
