<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(2) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(3) {
    width: 30%
  }

  #sai-inbox tr td:nth-child(4) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(5) {
    width: 15%
  }

  #sai-inbox tr td:nth-child(6) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(7) {
    width: 10%
  }

  .r {
    text-align: right;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Stock, Plan and Budget Inquiry (SPBI) / Query</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="row">
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">SPBI Control No.:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="SPBIControlNo">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Project Code:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="ProjectCode">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">PPMP Year:</label>
          <div class="col-sm-8">
            <input type="number" class="form-control criteria" name="PPMPYear">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Purpose:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria" name="Purpose">
          </div>
        </div>
      </div>
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Reponsibility Center:</label>
          <div class="col-sm-8">
            <select class="form-control criteria" name="RespoCenter" id="respo-center">
              <option value="">...</option>
              @foreach($RespoList ?? [] as $center)
                <option value="{{ $center['RCCD'] }}">{{ $center['RCCD'] . " | " . $center['RCDesc'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Division:</label>
          <div class="col-sm-8">
            <select class="form-control criteria" name="DivCode" id="div-code">
              <option value="">...</option>
              @foreach($Divisions ?? [] as $div)
                <option value="{{ $div['DivCode'] }}">{{ $div['DivCode'] . " | " . $div['DivName'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Prepared By:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control criteria search-employee" name="PreparedBy" autocomplete="off">
            <div class="option-employee preparedby"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Status:</label>
          <div class="col-sm-8">
            <select class="form-control criteria" name="Status" id="">
              <option value="">...</option>
              <option value="N">New</option>
              <option value="D">Done</option>
              <option value="R">Returned</option>
              <option value="E">Evaluated</option>
              <option value="A">Approved</option>
              <option value="X">Cancelled</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-2 col-form-label">Date From:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateFrom" readonly="">
          </div>
          <label for="" class="col-sm-1 col-form-label">To:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control criteria" name="DateTo" readonly="">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col col-button">
        <button id="btn-search" class="btn btn-info pull-right">Search</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Results</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="sai-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>SPBI Control No.</th>
            <th>Project Code</th>
            <th>Purpose</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
  (function() {
    var spbi = {
      headers: [],
      ajaxReq: null,
      init: function() {
        this.cacheDOM();
        this.bindEvents();
      },
      cacheDOM: function() {
        this.$spbicontrolno = $('#control-no');
        this.$btnsearch = $('#btn-search');
        this.$table = $('#sai-inbox');
        this.$txtSearchEmployee = $('[name="PreparedBy"]');
        this.$txtOptionEmployee = $('.option-employee');
        this.$txtDateFrom = $('[name="DateFrom"]');
        this.$txtDateTo = $('[name="DateTo"]');
        this.$txtCriteria = $('.criteria');
        this.$txtDateFrom.datepicker({
          dateFormat: 'yy-mm-dd'
        });
        this.$txtDateTo.datepicker({
          dateFormat: 'yy-mm-dd'
        });
      },
      bindEvents: function() {
        this.$btnsearch.on('click', this.get.bind(this));
        this.$table.on('click', 'tbody tr', this.open);
        this.$txtSearchEmployee.on('keyup', this.getEmployees.bind(this));
        this.$txtOptionEmployee.on('click', 'div', this.setEmployee.bind(this));
      },
      getEmployees: function(e) {
        Employee.Search(e);
        return;
      },
      setEmployee: function(e) {
        Employee.Set(e);
        return;
      },
      get: function() {
        var criteria = {};
        this.$txtCriteria.each(function() {
          if (this.value == '') return;
          criteria[this.name] = this.value;
          if (this.name == 'PreparedBy') criteria[this.name] = $(this).data('id')
        });
        var me = this;
        if (this.ajaxReq != null) this.ajaxReq.abort();
        TableLoading.On('#sai-inbox');
        this.ajaxReq = $.ajax({
          type: "post",
          url: "{{ url('spbi/get_headers') }}",
          data: {
            criteria: criteria
          },
          dataType: 'json',
          success: function(response) {
            me.headers = response || [];
            me.render();
            me.ajaxReq = null;
          },
          error: function(xhr, status, error) {
            me.ajaxReq = null;
            console.error("AJAX Error:", status, error);
            alert("Failed to load data. Please try again.");
          }
        });
      },
      open: function() {
        var prep_link = 'preparation_uncommon';
        if ($(this).data('common') == 1) prep_link = 'preparation_common';
        window.open('{{ url('spbi') }}/' + prep_link + '/SPBIControlNo/' + $(this).data('controlno'), '_blank');
      },
      render: function() {
        var row = '';
        $('#sai-inbox tbody').html('');
        for (var i in this.headers) {
          this.headers[i].Purpose = this.headers[i].Purpose || '';
          row +=
            '<tr data-controlno="' + this.headers[i].SPBIControlNo + '" data-common="' + this.headers[i].Common + '">' +
            '<td>' + this.headers[i].SPBIControlNo + '</td>' +
            '<td>' + this.headers[i].ProjectCode + '</td>' +
            '<td>' + this.headers[i].Purpose + '</td>' +
            '<td>' + numberFormat(this.headers[i].TotalAmount) + '</td>' +
            '<td>' + this.headers[i].EncodedBy_Name + '</td>' +
            '<td>' + this.headers[i].StatusName + '</td>' +
            '<td>' + this.headers[i].DateCreated + '</td>' +
            '</tr>';
        }
        $('#sai-inbox').dataTable().fnClearTable();
        $('#sai-inbox').dataTable().fnDraw();
        $('#sai-inbox').dataTable().fnDestroy();
        $('#sai-inbox tbody').html(row);
        $('#sai-inbox').dataTable();
      }
    }
    spbi.init();
  })();
</script>
