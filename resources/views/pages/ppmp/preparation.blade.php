@extends('layouts.layout')

@section('content')

@include('templates.modal-ppmp-item-details')
@include('templates.modal-item-list')
@include('templates.modal-alert')
@include('templates.modal-confirm')
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

  .col-remarks {
    width: 8% !important;
  }

  .col-action {
    width: 4% !important;
  }

  .btn-add {
    width: 70px;
  }

  .input-row input,
  .input-row select {
    width: 100%;
  }

  .option-employee div:hover,
  .option div:hover {
    color: #FFF;
    background-color: #0071bc;
    cursor: pointer;
  }

  .option-employee div,
  .option div {
    padding: 3px;
  }

  .option-employee,
  .option {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-6">
    <h1>PPMP / Preparation</h1>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col col-button text-right">
        <button class="btn btn-primary" id="save">Save</button>
        <button class="btn btn-success" id="submit">Submit</button>
        <button class="btn btn-secondary" id="cancel">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Header Information</div>
  <div class="card-body">
    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Preparatory Format:</label>
      <div class="col-sm-2">
        <input type="text" class="form-control" id="preparatory-format" value="{{ $PreparatoryFormat }}" readonly>
      </div>

      <label for="" class="col-sm-1 col-form-label">Project Code:</label>
      <div class="col-sm-2">
        <select class="form-control" id="project-code">
          <option value="">...</option>
        </select>
      </div>

      <label for="" class="col-sm-1 col-form-label">PPMP Year:</label>
      <div class="col-sm-1">
        <input type="text" class="form-control" id="ppmp-year" value="{{ $date['Year'] + 1 }}">
      </div>
    </div>

    <div class="form-group row">
      <label for="" class="col-sm-1 col-form-label">Station:</label>
      <div class="col-sm-2">
        <select class="form-control" id="station">
          @if(isset($Stations) && is_array($Stations))
            @foreach($Stations as $station)
              <option value="{{ $station }}">{{ $station }}</option>
            @endforeach
          @endif
        </select>
      </div>

      <label for="" class="col-sm-1 col-form-label">Division:</label>
      <div class="col-sm-2">
        <select class="form-control" id="division">
          @if(isset($Divisions) && is_array($Divisions))
            @foreach($Divisions as $division)
              <option value="{{ $division }}">{{ $division }}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Details</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="details-table">
        <thead>
          <tr>
            <th class="col-month">Month</th>
            <th class="col-generic">Item Description</th>
            <th class="col-specs">Specifications</th>
            <th class="col-unit">Unit</th>
            <th class="col-tqty">Qty</th>
            <th class="col-unitprice">Unit Price</th>
            <th class="col-amount">Amount</th>
            <th class="col-remarks">Remarks</th>
            <th class="col-action"></th>
          </tr>
        </thead>
        <tbody id="details-body">
        </tbody>
      </table>
    </div>
    <button class="btn btn-primary btn-add" id="add-row">Add</button>
  </div>
</div>

<script type="text/javascript">
  var pageJS = (function() {

    function init() {
      bindEvents();
    }

    function bindEvents() {
      $('#add-row').on('click', function() {
        addRow();
      });

      $('#save').on('click', function() {
        save();
      });

      $('#submit').on('click', function() {
        submit();
      });

      $('#cancel').on('click', function() {
        window.location.href = base_url + 'ppmp/preparation_inbox';
      });
    }

    function addRow() {
      var html = '<tr class="input-row">';
      html += '<td><input type="text" class="form-control month"></td>';
      html += '<td><input type="text" class="form-control item-desc"></td>';
      html += '<td><input type="text" class="form-control specs"></td>';
      html += '<td><input type="text" class="form-control unit"></td>';
      html += '<td><input type="number" class="form-control qty"></td>';
      html += '<td><input type="number" class="form-control unit-price"></td>';
      html += '<td><input type="number" class="form-control amount" readonly></td>';
      html += '<td><input type="text" class="form-control remarks"></td>';
      html += '<td><button class="btn btn-danger btn-sm btn-remove">X</button></td>';
      html += '</tr>';
      $('#details-body').append(html);
      bindRowEvents();
    }

    function bindRowEvents() {
      $('.btn-remove').off('click').on('click', function() {
        $(this).closest('tr').remove();
      });

      $('.qty, .unit-price').off('input').on('input', function() {
        var row = $(this).closest('tr');
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.unit-price').val()) || 0;
        row.find('.amount').val((qty * price).toFixed(2));
      });
    }

    function save() {
      alert('Save functionality will be implemented.');
    }

    function submit() {
      alert('Submit functionality will be implemented.');
    }

    return {
      init: init
    };
  })();

  $(document).ready(function() {
    pageJS.init();
  });
</script>
@endsection
