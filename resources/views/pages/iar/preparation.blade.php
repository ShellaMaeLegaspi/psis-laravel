<style type="text/css">
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
  .modal {
    overflow: auto;
  }
</style>

<div class="row">
  <div class="col-sm-12">
    <h1>
      Inspection and Acceptance Report (IAR) / Preparation
    </h1>
  </div>
  <div class="col-sm-12">
    <div class="row">
      
      @if(session('EmployeeID') == '25-1010')
        <button id="btn-edit" class="btn btn-success pull-right" value="{{ $IARControlNo }}">Edit</button>
        &nbsp;&nbsp;
        <button id="btn-repost" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Repost Stock</button>
      @endif

      <div class="col col-button">
        <button id="btn-update-status" class="btn btn-success pull-right" data-user="{{ session('EmployeeID') }}">Update</button>
        <button id="btn-print" class="btn btn-default pull-right">Print</button>
        <button id="btn-history" class="btn btn-default pull-right">History</button>
        <button id="btn-save" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Save</button>
        <button id="btn-done" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Done</button>
        <button id="btn-inspect" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Inspect</button>
        <button id="btn-accept" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Accept</button>
        <button id="btn-receive" class="btn btn-primary pull-right" data-user="{{ session('EmployeeID') }}">Receive</button>
        <button id="btn-return" class="btn btn-danger pull-right" data-user="{{ session('EmployeeID') }}">Return</button>
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
          <label for="" class="col-sm-4 col-form-label">IAR Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $IARControlNo }}" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">IAR No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="iar-no" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Supplier:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="supplier" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Division:</label>
          <div class="col col-sm-8">
            <select class="form-control" id="div-code">
              <option value="">...</option>
              @foreach($Divisions as $div)
                <option value="{{ $div['DivCode'] }}">{{ $div['DivName'] . " | " . $div['DivCode'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Responsibility Center:</label>
          <div class="col col-sm-8">
            <select class="form-control" id="respo-center" disabled="">
              <option value="">...</option>
              @foreach($RespoList as $center)
                <option value="{{ $center['RCCD'] }}" data-divcode="{{ $center['Acronym'] }}">{{ $center['RCDesc'] . " | " . $center['RCCD'] }}</option>
              @endforeach
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
            <input type="text" class="form-control" id="prepared-by" disabled="" value="{{ $default['PreparedBy_Name'] }}" data-id="{{ $default['PreparedBy'] }}">
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
          <label for="" class="col col-sm-4 col-form-label">Date Inspected:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-inspected" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Accepted By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="accepted-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Date Signed:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-accepted" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Supply/Property Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="sp-officer" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Date Signed:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-accepted-2" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Accepted Delivery By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="accepted-delivery-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Date Delivery Accepted:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-accepted-delivery" readonly="">
          </div>
        </div>
      </div>

      <div class="col col-sm-3">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Date Created:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="date-created" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Total Amount:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="total-amount" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">ORS Control No.:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="bur-no" disabled="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="tracking-no">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Invoice No.:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="invoice-no">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Invoice Date:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="invoice-date" readonly="">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Delivery Receipt:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="delivery-receipt">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Delivery Receipt Date:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="delivery-date" readonly="">
          </div>
        </div>
        <div class="form-group row" hidden>
          <label for="" class="col-sm-4 col-form-label">SemiExpendableNos</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="semiexpendablenos">
          </div>
        </div>
        <div class="form-group row" hidden>
          <label for="" class="col-sm-4 col-form-label">PropertyNos</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="propertynos">
          </div>
        </div>
      </div>

      <div class="col col-sm-2">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <input type="radio" name="delivery" value="C" checked> Complete Delivery <br>
            <input type="radio" name="delivery" value="P"> Partial Delivery
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <input type="checkbox" id="app"> APP Source Purchases <br>
            <input type="checkbox" id="direct"> Direct-To-End-User <br>
            <input type="checkbox" id="reimbursement"> Reimbursement
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <input type="checkbox" name="AccountsPayable" value="1"> Accounts Payable
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <input type="checkbox" name="Liquidation" value="1"> Liquidation
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="hiddenContent" hidden></div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
    <div class="row">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" id="btn-select-others">Others</button>
        <button class="btn btn-info pull-right btn-sm" id="btn-select-po">PO</button>
        <button class="btn btn-info pull-right btn-sm" id="btn-select-pr">PR</button>
        <label class="pull-right">Get items from:</label>
      </div>
    </div>
    <table id="sum-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px; table-layout: fixed;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">
            <label class="show-additional-specs">
              <input type="checkbox"> Show additional specs
            </label>
            <br>
            Item Code
          </th>
          <th>Item Description</th>
          <th>Unit</th>
          <th>Cost</th>
          <th>Delivery</th>
          <th>Stock Card</th>
          <th>Amount</th>
          <th>Remarks</th>
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
      <tbody></tbody>
    </table>
  </div>
  
  <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
    <table id="item-table" class="table table-bordered table-striped" width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr style="background-color: #B3B6B7;">
          <th class="col action-col"></th>
          <th class="col">Project Code</th>
          <th class="col">Item Description</th>
          <th class="col">PR/PO Quantity</th>
          <th class="col">IAR Quantity</th>
          <th class="col">PR No.</th>
          <th class="col">PO No.</th>
          <th class="col">RIS No.</th>
          <th class="col">SemiExpendable No/s</th>
          <th class="col">Property No/s</th>
          <th class="col">QR codes</th>
          <th class="col">Assign Property/ Semi-Expendable</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  // allow user to cancel item
  @if(hasAccess(41))
    const allowInspection = true;
  @else
    const allowInspection = false;
  @endif

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

  // allow to receivied
  @if(hasAccess(43))
    const allowReceive = true;
  @else
    const allowReceive = false;
  @endif
});
</script>

@push('scripts')
<script src="{{ asset('js/iar_prep.js') }}"></script>
@endpush
