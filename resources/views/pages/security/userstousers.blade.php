@extends('layouts.layout')

@section('content')

<style type="text/css">
tbody tr { cursor: pointer; }
.r { text-align: right; }
</style>

<div class="row page-content-title">
  <div class="col"><h1>Security / Allow user to access my account</h1></div>
  <div class="col">
    <button class="btn btn-primary pull-right" id="btn-create">New</button>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <div id="sec-users-to-users">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Allow this user:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control search-employee criteria" name="ToEmployeeID" id="prepared-by">
              <div class="option-employee"></div>
            </div>
          </div>
          <div class="form-group row d-none">
            <label class="col-sm-4 col-form-label">Access to this User:</label>
            <div class="col-sm-8">
              <input type="hidden" class="form-control search-employee criteria" name="FromEmployeeID" id="approved-by" data-id="{{ session('EmployeeID') }}">
              <div class="option-employee"></div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-10 col-form-label"></label>
            <div class="col-sm-2">
              <button class="form-control btn btn-primary" id="btn-save">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-lg-6">
  <div class="card mb-3">
    <div class="card-header">Results</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" width="100%" id="table-inbox" cellspacing="0">
          <thead>
            <tr><th></th><th>Allowed users</th></tr>
          </thead>
          <tbody>
            @foreach($switchAccounts as $row)
            <tr>
              <td><button class="btn btn-danger btn-remove" data-fromemployeeid="{{ $row['FromEmployeeID'] }}" data-toemployeeid="{{ $row['ToEmployeeID'] }}"><i class="fa fa-trash"></i></button></td>
              <td>{{ $row['EmployeeName'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
(function(){
var pageJS = {
  ajaxReq: null,
  init: function(){
    this.cacheDOM();
    this.bindEvents();
  },
  cacheDOM: function(){
    this.$txtEmployees = $('#sec-users-to-users .search-employee');
    this.$txtOptions = $('#sec-users-to-users .option-employee');
    this.$txtCriteria = $('#sec-users-to-users .criteria');
    this.$btnSave = $('#sec-users-to-users #btn-save');
    this.$btnRemove = $('.btn-remove');
  },
  bindEvents: function(){
    this.$btnSave.on('click', this.save.bind(this));
    this.$btnRemove.on('click', this.remove.bind(this));
    this.$txtEmployees.on('keyup', this.getEmployees.bind(this));
    this.$txtOptions.on('click', this.setEmployee.bind(this));
  },
  getEmployees: function(e){ Employee.Search(e); },
  setEmployee: function(e){ Employee.Set(e); },
  save: function(){
    var criteria = {};
    this.$txtCriteria.each(function(){
      if ($(this).hasClass('search-employee') == true) {
        criteria[this.name] = $(this).data('id');
        return;
      }
      criteria[this.name] = $.trim(Sanitize.Text(this.value));
    });
    for (var i in criteria) {
      if (criteria[i]=='') { alert('Fill-up all fields.'); return; }
    }
    var me = this;
    if (this.ajaxReq != null) this.ajaxReq.abort();
    this.ajaxReq = $.ajax({
      type:"post", url: base_url + "security/sec_save_users_to_users",
      data: { criteria: criteria, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        try { var res = JSON.parse(response); alert(res.Message); window.location.reload(); }
        catch (er) { alert(er.Message); }
        me.ajaxReq = null;
      },
      error: function() { me.ajaxReq = null; alert("Something went wrong..."); }
    });
  },
  remove: function(e){
    var criteria = {};
    criteria.FromEmployeeID = $(e.currentTarget).data('fromemployeeid');
    criteria.ToEmployeeID = $(e.currentTarget).data('toemployeeid');
    var me = this;
    if (this.ajaxReq != null) this.ajaxReq.abort();
    this.ajaxReq = $.ajax({
      type:"post", url: base_url + "security/sec_delete_users_to_users",
      data: { criteria: criteria, _token: $('meta[name="csrf-token"]').attr('content') },
      success:function(response) {
        try { var res = JSON.parse(response); alert(res.Message); window.location.reload(); }
        catch (er) { alert(er.Message); }
        me.ajaxReq = null;
      },
      error: function() { me.ajaxReq = null; alert("Something went wrong..."); }
    });
  }
};
pageJS.init();
})();
</script>

@include('templates.modal-alert')
@include('templates.modal-confirm')
@include('templates.modal-prompt')

@endsection
