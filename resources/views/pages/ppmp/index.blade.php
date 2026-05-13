@extends('layouts.layout')

@section('content')
<style type="text/css">
  .show-additional-specs {
    font-size: 11px;
    font-style: italic;
    font-weight: normal;
  }

  .table td,
  .table th {
    padding: 3px;
    font-size: 12px;
  }

  .more-specs {
    display: none;
  }

  .oecode-row {
    font-weight: bold;
    cursor: pointer;
  }

  tfoot td {
    font-weight: bold;
  }

  .input-row:hover {
    background: #0071bc !important;
    color: white;
  }

  .col-month,
  .col-tqty {
    width: 6% !important;
  }

  .col-unit {
    width: 7% !important;
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

  .col-action {
    width: 4% !important;
  }

  .col-num {
    text-align: right;
  }

  .page-content-title {
    margin-bottom: 10px;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-7">
    <h1>My PPMP</h1>
  </div>
  <div class="col-sm-3">
    <select class="form-control pull-right" id="ppmp">
      @forelse ($ppmp as $row)
        <option data-controlno="{{ $row['PreparatoryFormat'] }}" value="{{ $row['PPMPHeaderID'] }}">{{ $row['ProjectCode'] }} | {{ $row['PPMPYear'] }}</option>
      @empty
        <option value="">No PPMP available</option>
      @endforelse
    </select>
  </div>
  <div class="col-sm-2">
    <button class="btn btn-info pull-right" id="btn-export">Export</button>
    <button class="btn btn-primary pull-right mr-3" id="btn-go">Go</button>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped" width="100%" id="ppmp-table" cellspacing="0">
    <thead>
      <tr>
        <th class="col-action">A.#</th>
        <th class="col-specs" style="position: relative;">
          <label class="show-additional-specs">
            <input type="checkbox"> &nbsp;Show additional specs
          </label>
          <br>
          Item Specification
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
        <td></td>
        <td colspan="14" id="no-of-items"></td>
        <td colspan="2">Total Amount:</td>
        <td id="ppmp-amount" class="col-num">0.00</td>
      </tr>
    </tfoot>
    <tbody>
    </tbody>
  </table>
</div>

<script type="text/javascript">
  var pageJS = (function() {
    var details = [];
    var cache = {};
    var ajaxReq = null;

    function init() {
      cacheDOM();
      bindEvents();
    }

    function cacheDOM() {
      this.$txtPPMP = $('#ppmp');
      this.$tblDetails = $('#ppmp-table');
      this.$btnGo = $('#btn-go');
      this.$btnExport = $('#btn-export');
    }

    function bindEvents() {
      this.$btnGo.on('click', get.bind(this));
      this.$btnExport.on('click', exportPPMP.bind(this));
      this.$tblDetails.on('click', '.show-additional-specs input', showHideSpecs.bind(this));
      this.$tblDetails.on('click', '.oecode-row', showHideGroupItems.bind(this));
    }

    function get() {
      var criteria = {};
      criteria.PPMPHeaderID = this.$txtPPMP.val();
      var me = this;
      if (ajaxReq != null) ajaxReq.abort();
      me.$tblDetails.find('tbody').html('<tr><td colspan="18" class="text-center">Loading...</td></tr>');
      ajaxReq = $.ajax({
        type: "post",
        url: base_url + "ppmp/get_updated_ppmp_items",
        data: {
          criteria: criteria,
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          try {
            details = JSON.parse(response);
          } catch (e) {
            details = response;
          }
          render.call(me);
          ajaxReq = null;
        },
        error: function() {
          ajaxReq = null;
          me.$tblDetails.find('tbody').html('<tr><td colspan="18" class="text-center text-danger">Something went wrong...</td></tr>');
        }
      });
    }

    function exportPPMP() {
      var controlno = this.$txtPPMP.find('option:selected').data('controlno');
      if (!controlno) {
        alert('Please select a PPMP.');
        return;
      }
      window.open(base_url + 'ppmp/export_ppmp/' + controlno + '/1', '_blank');
    }

    function showHideSpecs(e) {
      if ($(e.target).is(':checked')) this.$tblDetails.find('.more-specs').show();
      else this.$tblDetails.find('.more-specs').hide();
    }

    function showHideGroupItems(e) {
      var oecode = $(e.currentTarget).data('oecode');
      if ($('.input-row.' + oecode).find(':visible').length > 0)
        $('.input-row.' + oecode).hide();
      else
        $('.input-row.' + oecode).show();
    }

    function render() {
      var m1 = 0, m2 = 0, m3 = 0, m4 = 0, m5 = 0, m6 = 0;
      var m7 = 0, m8 = 0, m9 = 0, m10 = 0, m11 = 0, m12 = 0, m13 = 0;
      var itemRow = {};
      var cost = 0;
      var totalAmount = 0;

      for (var i in details) {
        cost = details[i].UnitCost || 0;
        m1 = +(details[i].Qty_Jan * cost).toFixed(2);
        m2 = +(details[i].Qty_Feb * cost).toFixed(2);
        m3 = +(details[i].Qty_Mar * cost).toFixed(2);
        m4 = +(details[i].Qty_Apr * cost).toFixed(2);
        m5 = +(details[i].Qty_May * cost).toFixed(2);
        m6 = +(details[i].Qty_Jun * cost).toFixed(2);
        m7 = +(details[i].Qty_Jul * cost).toFixed(2);
        m8 = +(details[i].Qty_Aug * cost).toFixed(2);
        m9 = +(details[i].Qty_Sep * cost).toFixed(2);
        m10 = +(details[i].Qty_Oct * cost).toFixed(2);
        m11 = +(details[i].Qty_Nov * cost).toFixed(2);
        m12 = +(details[i].Qty_Dec * cost).toFixed(2);
        m13 = +m1 + +m2 + +m3 + +m4 + +m5 + +m6 + +m7 + +m8 + +m9 + +m10 + +m11 + +m12;
        details[i].Amount_Tot = m13;
        totalAmount += +m13;

        if (!itemRow[details[i].OECode]) itemRow[details[i].OECode] = '';
        itemRow[details[i].OECode] += '<tr class="input-row row-record item-row ' + details[i].OECode + '" data-id="' + i + '" data-oecode="' + details[i].OECode + '" data-ppmpdetailsid="' + details[i].PPMPDetailsID + '" style="display: table-row;">' +
          '<td class="col-action">' + (details[i].AugmentationSeries || '') + '</td>' +
          '<td class="col-specs">' +
          '<div class="item-code">' + (details[i].ItemCode || '') + '</div>' +
          '<div class="specs">' + (details[i].SpecDetails || '') + '</div>' +
          '<div class="more-specs">' + (details[i].MoreSpecs || '') + '</div>' +
          '</td>' +
          '<td class="col-unit">' + (details[i].UnitName || '') + '</td>' +
          '<td class="col-num col-unitprice">' + numberFormat(cost) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Jan || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Feb || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Mar || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Apr || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_May || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Jun || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Jul || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Aug || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Sep || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Oct || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Nov || 0) + '</td>' +
          '<td class="col-num col-month">' + (details[i].Qty_Dec || 0) + '</td>' +
          '<td class="col-num col-tqty">' + (details[i].Qty_Tot || 0) + '</td>' +
          '<td class="col-num col-amount">' + numberFormat((details[i].Amount_Tot).toFixed(2)) + '</td>' +
          '</tr>';
      }

      var tblRow = '';
      for (var i in itemRow) {
        if (!itemRow[i]) continue;
        tblRow += itemRow[i];
      }

      this.$tblDetails.find('tbody').html(tblRow || '<tr><td colspan="18" class="text-center">No items found.</td></tr>');
      this.$tblDetails.find('tfoot #no-of-items').text((details.length || 0) + ' item(s)');
      this.$tblDetails.find('tfoot #ppmp-amount').text(numberFormat(totalAmount.toFixed(2)));
    }

    function numberFormat(num) {
      return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
