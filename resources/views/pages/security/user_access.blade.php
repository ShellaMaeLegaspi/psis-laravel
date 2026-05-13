@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr { cursor: pointer; }
  .r { text-align: right; }
</style>

<div class="row page-content-title">
  <div class="col">
    <h1>Security / Access</h1>
  </div>
  <div class="col">
    <button class="btn btn-primary pull-right" id="btn-create">New</button>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Search Criteria</div>
  <div class="card-body">
    <div class="form-group row">
      <label for="" class="col-sm-2 col-form-label">Access ID:</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" id="code">
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-sm-2 col-form-label">Access Name:</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" id="desc">
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
            <th>Access ID</th>
            <th>Access Name</th>
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
    this.$txtCode = $('#code');
    this.$txtDesc = $('#desc');
    this.$btnCreate = $('#btn-create');
    this.$btnSearch = $('#btn-search');
  },

  bindEvents: function(){
    this.$btnCreate.on('click', this.onClickCreate.bind(this));
    this.$btnSearch.on('click', this.onClickSearch.bind(this));
    this.$tbl.on('click', 'tbody tr', this.onClickDetails.bind(this));
  },

  onClickCreate: function(){
    if (typeof accessFormModal !== 'undefined') accessFormModal.toggle({});
    else modalAlert('Form modal not available.');
  },

  onClickSearch: function(e){
    var me = this;
    var criteria = {};
    if (me.$txtCode.val() != '') criteria.AccessID = me.$txtCode.val();
    if (me.$txtDesc.val() != '') criteria.AccessDescription = me.$txtDesc.val();

    $.ajax({
      type: "post",
      url: base_url + "security/get_access",
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
    if (typeof accessFormModal !== 'undefined') accessFormModal.toggle(this.headers[idx]);
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
          '<td>' + h.AccessID + '</td>' +
          '<td>' + h.AccessDescription + '</td>' +
          '<td>' + (h.CreatedBy_Name || '') + '</td>' +
          '<td>' + h.DateCreated + '</td>' +
          '<td>' + (h.UpdatedBy_Name || '') + '</td>' +
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

@include('templates.modal-user-access-form')
@endsection
