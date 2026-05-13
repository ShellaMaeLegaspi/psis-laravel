"use strict";

/*
 * Page Content Loading
 */
const ContentLoading = {};
ContentLoading.On = function(element){
  if ($(element).find('#cover').length > 0 ) return;
  $(element).append('<div id="cover"></div>');
}
ContentLoading.Off = function(){
  $('#cover').remove();
}

/*
 * Table Loading 
 */
const TableLoading = {};
TableLoading.On = function(element){
  var gif = '<img src="'+ base_url +'assets/images/hourglass.svg">';
  $(element).find('tbody').html('<tr class="odd"><td valign="top" colspan="'+ $(element).find('thead tr th').length +'" class="dataTables_empty" style="text-align: center;">'+ gif +'</td></tr>');
}
TableLoading.Off = function(element){
  $(element).find('tbody').html('');
}

/*
 * Buttons Disable/Enable
 */
const Button = {};
Button.On = function(parent){
  $(parent).find('button').prop('disabled', false);
}
Button.Off = function(parent){
  $(parent).find('button').prop('disabled', true);
}

/*
 * Returns cleaned user input
 */
const Sanitize = {};
Sanitize.Text = function (text){
  return $("<div/>").html(text).text();
}

/*
 * Returns number with format
 */
function numberFormat(val, decimalPlace=true, decimalCount=2){
  if (val == 0 || val == undefined ) {
    if (decimalPlace) return '0.00';
    return '0';
  }

  val = val.toString().split('.');
  var deci = '';
  if (val.length > 1) {
    deci = val[1];
    val = val[0];
  }
  else {
    val = val[0];
    if (decimalPlace) {
      val = val + '.00';
    }
  }

  while (/(\d+)(\d{3})/.test(val.toString())){
    val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
  }

  if (deci != '') {
    val = val+ '.' + deci.substring(0,decimalCount);
  }

  return val;
}

function numberFormat2(val, decimalPlace=true){
  if (val == 0 || val == undefined ) {
    if (decimalPlace) return '0.00';
    return '0';
  }

  val = val.toString().split('.');
  if (val.length > 1) val = val[0]+ '.' + val[1].substring(0,4);
  else {
    val = val[0];
    if (decimalPlace) {
      val = val + '.00';
    }
  }

  val = val.toString().split('.');
  while (/(\d+)(\d{3})/.test(val[0].toString())){
    val[0] = val[0].toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
  }

  return val[0] + '.'  + val[1];
}

/*
 * Get current loggedin user id
 */
var user = (function(){

  function get(callback){
    $.ajax({
      type:"post",
      url: base_url + "session/get_current_user",
      success:function(response) {
        if (typeof callback == 'function') callback($.trim(response));
      },
      error: function() {
        alert("Something went wrong...");
      }
    });
  }

  return {
    getCurrentUser: get
  }

})();

/*
 * Get unit of measurement
 */
var Units = (function(){

  function get(callback){
    $.ajax({
      type:"post",
      url: base_url + "items/get_units",
      success:function(response) {
        var res = JSON.parse(response);
        if (typeof callback == 'function') callback(res.header);
      },
      error: function() {
        alert("Something went wrong...");
      }
    });
  }

  return {
    get: get
  }

})();

/*
 * Get object of expend
 */
var FMIS_ObjectOfExpend = (function(){

  function get(callback, database = 'corporate'){
    $.ajax({
        type:"post",
        url: base_url + "fmis/get_oecode",
        data: {
          db: database
        },
        success:function(response) {
          if ( response == 0 ) {return false;}
          callback(JSON.parse(response));
        },
        error: function() {
          modalAlert("Something went wrong...");
        }
      });
  }

  return {
    get: get
  }

})();

/*
 * Get account codes
 */
const FMIS_AccountCode = (function(){

  function get(OECode, acdesc, callback){
    $.ajax({
      type:"post",
      url: base_url + "fmis/get_accode",
      data: { 
        OECode: OECode,
        keyword: acdesc
      },
      success:function(response) {
        callback(JSON.parse(response));
      },
      error: function() {
        alert("Something went wrong...");
      }
    });
  }

  return {
    get: get
  }

})();

/*
 * Get object of expend for items
 */
var Item_ObjectOfExpend = (function(){

  function get(callback, database = 'corporate'){
    $.ajax({
        type:"post",
        url: base_url + "items/get_item_oecodes",
        data: {
          db: database
        },
        success:function(response) {
          if ( response == 0 ) {return false;}
          callback(JSON.parse(response));
        },
        error: function() {
          console.log("Something went wrong...");
        }
      });
  }

  return {
    get: get
  }

})();

/*
 * Get account codes for items
 */
const Item_AccountCode = (function(){

  function get(OECode, acdesc, callback){
    $.ajax({
      type:"post",
      url: base_url + "items/get_item_accodes",
      data: { 
        OECode: OECode,
        keyword: acdesc
      },
      success:function(response) {
        callback(JSON.parse(response));
      },
      error: function() {
        console.log("Something went wrong...");
      }
    });
  }

  return {
    get: get
  }

})();

const FMIS = (function(){

  function getPrograms(callback, criteria = '', database = 'corporate'){

    $.ajax({
        type:"post",
        url: base_url + "fmis/get_programs",
        data:{
          db: database,
          criteria: criteria
        },
        success:function(response) {
          if ( response == 0 ) {return false;}
          callback(JSON.parse(response));

        },
        error: function() {
          console.log("Something went wrong...");
        }
    });
  }

  function getProjects(callback, programcode = '', database = 'corporate'){
    $.ajax({
        type:"post",
        url: base_url + "fmis/get_projects",
        data: {
          db: database,
          ProgramCode: programcode
        },
        success:function(response) {
          if ( response == 0 ) {return false;}
          callback(JSON.parse(response));
        },
        error: function() {
          console.log("Something went wrong...");
        }
    });
  }

  return {
    getPrograms:getPrograms,
    getProjects:getProjects
  }

})();

var PPMP = (function(){

  function get(callback){
    $.ajax({
      type:"post",
      url: base_url + "ppmp/ajx_get_headers",
      success:function(response) {
        var res = JSON.parse(response);
        if (typeof callback == 'function') callback(res.headers);
      },
      error: function() {
        alert("Something went wrong...");
      }
    });
  }

  function calcRow(row){
    var res = {};
    res.Amount_Tot  = 0;
    res.UnitCost = row.UnitCost;

    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthTotal = 0;

    for (var i in month) {
      monthTotal = parseFloat(parseFloat(row['Qty_'+month[i]] * res.UnitCost).toFixed(2)); 
      res['Amount_'+month[i]] = monthTotal;
      res.Amount_Tot += monthTotal;
    }

    return res;
  }

  return {
    get: get,
    calcRow: calcRow,
  }

})();

/*
 * Get and set user text with dropdown
 */
const Employee = (function(){
  var ajaxReq = null;

  function get(employeeName, callback){
    var criteria = {};
    criteria.EmployeeName = employeeName;

    if ( ajaxReq != null ) ajaxReq.abort();
    ajaxReq = $.ajax({
      type:"post",
      url: base_url + "philrice/get_employees",
      data: { 
        criteria: criteria
      },
      success:function(response) {
        var res = JSON.parse(response);
        if (typeof callback == 'function') callback(res);
        ajaxReq = null;
      },
      error: function() {
        ajaxReq = null;
        console.log("Something went wrong...");
      }
    });
  }

  function render(res, dropdownDiv){
    dropdownDiv.html('');
    for (var i in res) {
      dropdownDiv.append('<div data-id="'+ res[i].EmployeeID +'">'+ res[i].EmployeeName +'</div>');  
    }
          
    dropdownDiv.css('box-shadow','1px 1px 5px 1px');
  }

  function search(e){
    $(e.target).data('id','');
    $(e.target).css('border-color','red');
    var dropdownDiv = $(e.target).parent().find('.option-employee');

    dropdownDiv.html('<div data-id="">Searching employee list...</div>');  
      
    if ($.trim($(e.target).val()) == "") {
      dropdownDiv.html("");
      dropdownDiv.css('box-shadow','none');
      return;
    }

    get($(e.target).val(), function(res){
      render(res, dropdownDiv);
    });
  }

  function set(e){
    var row = $(e.target).parent().parent();

    row.find('.search-employee').val($(e.target).text());
    row.find('.search-employee').css('border-color','rgba(0,0,0,.15)');
    row.find('.search-employee').data('id', $(e.target).data('id'));
    row.find('.option-employee').html("");
    row.find('.option-employee').css('box-shadow','none');
  }

  return {
    Search : search,
    Set    : set
  }
})();

/*
 * Get item specs
 */
const Item = (function(){

  function getSpecs(criteria="", callback){

    $.ajax({
      type:"post",
      url: base_url + "items/get_items_specs",
      data: { 
        criteria: criteria,
      },
      success:function(response) {
        callback(JSON.parse(response));
      },
      error: function() {
        console.log("Something went wrong...");
      }
    });
  }

  return {
    getSpecs: getSpecs
  }

})();

$(document).on('click', '.card-header.toggable', function(e){
  if ( $(e.currentTarget).parent().find('.card-body').is(':visible') ) {
    $(e.currentTarget).parent().find('.card-body').hide();
  }
  else {
    $(e.currentTarget).parent().find('.card-body').show();
  }
});

String.prototype.limit = function(len = 30){
  if (this.length < len) return this;
  return this.substring(0,len) + '...';
}

String.prototype.NL2BR = function(){
  return this.replace(/\r\n|\r|\n/g,"<br />");
}
