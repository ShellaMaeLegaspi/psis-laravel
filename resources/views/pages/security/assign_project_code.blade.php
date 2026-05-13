@extends('layouts.layout')

@section('content')
<style>
  .option-employee div { color: #000 !important; }
</style>

<div class="row page-content-title">
  <div class="col">
    <h1>Security / Assign Project Code</h1>
  </div>
</div>

<div class="row">
  <div class="col-lg-4">
    <div class="card text-white bg-dark mb-3 change-signatory">
      <div class="card-header toggable">Add PPMP User Access</div>
      <div class="card-body">

        <div id="ppmp-add-user-access">
          <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label">Project Code:</label>
            <div class="col-sm-8">
              <input type="type" name="ProjectCode" class="form-control" placeholder="Enter project code" id="project-code" list="projects" autocomplete="off">
              <datalist id="projects"></datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label">Employee:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control search-employee" name="EmployeeID" id="employee-id" placeholder="Enter name" autocomplete="off">
              <div class="option-employee"></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="" class="col-sm-8 col-form-label"></label>
            <div class="col-sm-4">
              <button class="form-control btn btn-primary" id="btn-save">Save</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@include('templates.modal-alert')
@include('templates.modal-confirm')

<script type="text/javascript">
(function(){

var pageJS = {
  ajaxReq: null,

  init: function(){
    this.cacheDOM();
    this.bindEvents();
  },

  cacheDOM: function(){
    this.$txtProjectCode = $('#ppmp-add-user-access #project-code');
    this.$txtEmployeeID   = $('#ppmp-add-user-access #employee-id');
    this.$btnSave          = $('#ppmp-add-user-access #btn-save');
    this.$txtEmployees     = $('#ppmp-add-user-access .search-employee');
    this.$txtOptions       = $('#ppmp-add-user-access .option-employee');
    this.$listProjects     = $('#projects');
  },

  bindEvents: function(){
    this.$btnSave.on('click', this.save.bind(this));
    this.$txtEmployees.on('keyup', this.getEmployees.bind(this));
    this.$txtOptions.on('click', 'div', this.setEmployee.bind(this));
  },

  getEmployees: function(e){
    if (e.which == 38 || e.which == 40 || e.which == 13) return;
    var val = $.trim(this.$txtEmployees.val());
    if (val.length < 2) { this.$txtOptions.html(''); return; }
    var me = this;
    if (this.ajaxReq != null) this.ajaxReq.abort();
    this.ajaxReq = $.ajax({
      type: 'post',
      url: base_url + 'employees/getEmployees',
      data: { criteria: { EmployeeName: val } },
      success: function(response){
        var employees = JSON.parse(response);
        var html = '';
        for (var i in employees) {
          html += '<div data-id="' + employees[i].EmployeeID + '">' + employees[i].EmployeeName + '</div>';
        }
        me.$txtOptions.html(html);
        me.ajaxReq = null;
      },
      error: function(){ me.ajaxReq = null; }
    });
  },

  setEmployee: function(e){
    var $el = $(e.currentTarget);
    this.$txtEmployeeID.val($el.text());
    this.$txtEmployeeID.data('id', $el.data('id'));
    this.$txtOptions.html('');
  },

  save: function(){
    var criteria = {};
    criteria.ProjectCode = $.trim(this.$txtProjectCode.val());
    criteria.EmployeeID  = $.trim(this.$txtEmployeeID.data('id') || '');

    if (criteria.ProjectCode == '') { alert('Please enter a Project Code.'); return; }
    if (criteria.EmployeeID == '') { alert('Please enter an ID No.'); return; }

    var me = this;
    if (this.ajaxReq != null) this.ajaxReq.abort();
    this.ajaxReq = $.ajax({
      type: "post",
      url: base_url + "security/ppmp_add_user_access",
      data: { criteria: criteria },
      success: function(response) {
        try {
          var res = JSON.parse(response);
          alert(res.Message);
        } catch (er) { alert(er.message || er); }
        me.ajaxReq = null;
      },
      error: function() {
        me.ajaxReq = null;
        alert("Something went wrong...");
      }
    });
  }
};

pageJS.init();

})();
</script>
@endsection
