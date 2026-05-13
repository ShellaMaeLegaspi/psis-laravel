<style type="text/css">
  #sum-table td {
    cursor: pointer;
  }

  #sum-table tr .col:nth-child(1) {
    width: 5% !important;
  }

  #sum-table tr .col:nth-child(2) {
    width: 15% !important;
  }

  #sum-table tr .col:nth-child(3) {
    width: 50% !important;
  }

  #sum-table tr .col:nth-child(4) {
    width: 10% !important;
  }

  #sum-table tr .col:nth-child(5) {
    width: 10% !important;
  }

  #sum-table tr .col:nth-child(6) {
    width: 10% !important;
  }

  #sum-table tr .col:nth-child(7) {
    width: 10% !important;
  }

  #sum-table tr .col:nth-child(8) {
    width: 10% !important;
  }

  #item-table td {
    cursor: pointer;
  }

  #item-table tr .col:nth-child(1) {
    width: 5% !important;
  }

  #item-table tr .col:nth-child(2) {
    width: 12% !important;
  }

  #item-table tr .col:nth-child(3) {
    width: 30% !important;
  }

  #item-table tr .col:nth-child(4) {
    width: 7% !important;
  }

  #item-table tr .col:nth-child(5) {
    width: 7% !important;
  }

  #item-table tr .col:nth-child(6) {
    width: 10% !important;
  }

  #item-table tr .col:nth-child(7) {
    width: 15% !important;
  }

  #item-table tr .col:nth-child(8) {
    width: 15% !important;
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

<div class="row">
  <div class="col-sm-12">
    <h1>
      Property Transfer Report (PTR) / Preparation
    </h1>
  </div>
  <div class="col-sm-12">
    <div class="row">
      <div class="col col-button">
        <button id="btn-print" class="btn btn-default pull-right">Print</button>
        <button id="btn-history" class="btn btn-default pull-right">History</button>
        <button id="btn-save" class="btn btn-primary pull-right">Save</button>
        <button id="btn-done" class="btn btn-primary pull-right">Done</button>
        <button id="btn-approve" class="btn btn-primary pull-right">Approve</button>
        <button id="btn-issue" class="btn btn-primary pull-right">Issue</button>
        <button id="btn-accept" class="btn btn-primary pull-right">Accept</button>
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
          <label for="" class="col-sm-4 col-form-label">PTR Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="control-no" value="{{ $PTRControlNo ?? '' }}" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PTR No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="no" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Division:</label>
          <div class="col col-sm-8">
            <select class="form-control" id="div-code">
              <option value="">...</option>
              @foreach($Divisions ?? [] as $div)
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
              @foreach($RespoList ?? [] as $center)
                <option value="{{ $center['RCCD'] }}" data-divcode="{{ $center['Acronym'] }}">{{ $center['RCDesc'] . " | " . $center['RCCD'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Reason for Transfer:</label>
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
          <label for="" class="col col-sm-4 col-form-label">Accountable Officer:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="accountable-officer" autocomplete="off">
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
          <label for="" class="col col-sm-4 col-form-label">Released/Issued By:</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="issued-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Received By (Accept if Philrice employee):</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control search-employee" id="received-by" autocomplete="off">
            <div class="option-employee"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Received By (Designation):</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="received-by-designation" autocomplete="off">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col col-sm-4 col-form-label">Received By (Agency):</label>
          <div class="col col-sm-8">
            <input type="text" class="form-control" id="received-by-agency" autocomplete="off">
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
          <label for="" class="col-sm-4 col-form-label">Tracking No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="tracking-no">
          </div>
        </div>

        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Transfer Type</label>
          <div class="col-sm-8">
            <input type="radio" name="type" value="donation" checked> Donation (Transfer to Other Agency) <br>
            <input type="text" class="form-control donation sub-field" id="donation">

            <input type="radio" name="type" value="reassignment"> Reassignment <br>
            <input type="text" class="form-control reassignment sub-field" id="reassignment">

            <input type="radio" name="type" value="relocate"> Relocation (Transfer to Branch and Branch to Branch) <br>
            <select class="form-control relocate sub-field" id="station">
              @foreach($Stations ?? [] as $row)
                <option value="{{ $row['StationCode'] }}">{{ $row['StationCode'] . " | " . $row['StationDesc'] }}</option>
              @endforeach
            </select>

            <input type="radio" name="type" value="reclassification"> Reclassification (Change of ownership) <br>
            <select class="form-control reclassification sub-field" id="fund">
              @foreach($FundCd ?? [] as $row)
                <option value="{{ $row['ParameterCode'] }}">{{ $row['ParameterCode'] . " | " . $row['ParameterValue'] }}</option>
              @endforeach
            </select>

            <input type="radio" name="type" value="others"> Others (Specify) <br>
            <input type="text" class="form-control others sub-field" id="typeothers">
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
    <div class="row p-2">
      <div class="col-sm-12">
        <button class="btn btn-info pull-right btn-sm" id="btn-select-item">Select PPE</button>
      </div>
    </div>
    <table id="item-table" class="table-bordered" width="100%" cellspacing="0" style="font-size: 12px;">
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
          <th class="col">Quantity</th>
          <th class="col">Acquisition Cost</th>
          <th class="col">Date Aquired</th>
          <th class="col">Condition of PPE</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

@push('scripts')
<script src="{{ asset('js/ptr_prep.js') }}"></script>
@endpush
