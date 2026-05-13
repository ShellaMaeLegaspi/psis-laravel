@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr { cursor: pointer; }
  .r { text-align: right; }
</style>

<div class="row page-content-title">
  <div class="col">
    <h1>Security / SPBI Preparers</h1>
  </div>
  <div class="col">
    <button class="btn btn-primary pull-right" id="btn-create">New</button>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="row">
      <div class="col-sm-4">
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Employee ID:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="EmployeeID">
          </div>
        </div>
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Employee Name:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="EmployeeName">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col col-sm-12 col-button">
        <button id="btn-search" class="btn btn-info pull-right">Search</button>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Results</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="table-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>Common Prep ID</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Created By</th>
            <th>Date Created</th>
            <th>Updated By</th>
            <th>Date Updated</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

@include('templates.modal-alert')
@include('templates.modal-confirm')
@include('templates.modal-prompt')
@include('templates.modal-history')

<script type="text/javascript">
(function(){

var pageJS = {
  headers: [],
  init: function(){
    this.cacheDOM();
    this.bindEvents();
    this.$tbl.DataTable({ destroy: true });
  },

  cacheDOM: function(){
    this.$tbl = $('#table-inbox');
    this.$txtCode = $('#EmployeeID');
    this.$txtDesc = $('input[name="EmployeeName"]');
    this.$btnCreate = $('#btn-create');
    this.$btnSearch = $('#btn-search');
  },

  bindEvents: function(){
    this.$btnCreate.on('click', this.onClickCreate.bind(this));
    this.$btnSearch.on('click', this.onClickSearch.bind(this));
    this.$tbl.on('click', 'tbody tr', this.onClickDetails.bind(this));
  },

  onClickCreate: function(){
    if (typeof userFormModal !== 'undefined') userFormModal.toggle({});
    else modalAlert('Form modal not available.');
  },

  onClickSearch: function(e){
    var me = this;
    var criteria = {};
    if (this.$txtCode.val() != '') criteria.EmployeeID = this.$txtCode.val();
    if (this.$txtDesc.val() != '') criteria.EmployeeName = this.$txtDesc.val();

    $.ajax({
      type: "post",
      url: base_url + "security/get_spbi_preparers",
      data: { criteria: criteria, _token: $('meta[name="csrf-token"]').attr('content') },
      success: function(response) {
        me.headers = JSON.parse(response);
        me.render();
      },
      error: function() {
        modalAlert("Something went wrong...");
      }
    });
  },

  onClickDetails: function(e){
    var idx = $(e.currentTarget).data('id');
    if (typeof userFormModal !== 'undefined') userFormModal.toggle(this.headers[idx]);
    else modalAlert('Form modal not available.');
  },

  render: function(){
    this.$tbl.DataTable().clear().destroy();
    this.$tbl.find('tbody').html('');

    for (var i in this.headers) {
      var h = this.headers[i];
      h.DateCreated = h.DateCreated == null ? '' : h.DateCreated;
      h.DateUpdated = h.DateUpdated == null ? '' : h.DateUpdated;

      this.$tbl.find('tbody').append(
        '<tr data-id="' + i + '">' +
          '<td>' + h.CommonPreparerID + '</td>' +
          '<td>' + h.EmployeeID + '</td>' +
          '<td>' + h.EmployeeName + '</td>' +
          '<td>' + (h.CreatedByName || '') + '</td>' +
          '<td>' + h.CreatedAt + '</td>' +
          '<td>' + (h.UpdatedByName || '') + '</td>' +
          '<td>' + h.DateUpdated + '</td>' +
        '</tr>'
      );
    }

    this.$tbl.DataTable();
  }
};

pageJS.init();

})();
</script>

@include('templates.modal-users-form')
@endsection
