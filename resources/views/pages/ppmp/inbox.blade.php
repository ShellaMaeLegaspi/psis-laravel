@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Project Procurement Management Plan (PPMP) / Inbox</h1>
  </div>
  <div class="col-sm-4">
    @if(request()->route()->getName() == 'ppmp.preparation_inbox')
      <button class="btn btn-primary pull-right" id="new">New</button>
    @endif
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">
    {{ $inbox_title }}
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="dataTable" cellspacing="0">
        <thead>
          <tr>
            <th></th>
            <th>Preparatory Format</th>
            <th>Project Code</th>
            <th>PPMP Year</th>
            <th>Total Budget</th>
            <th>Prepared By</th>
            <th>Approved By</th>
            <th>Certified By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($headers) && count($headers) > 0)
            @foreach($headers as $header)
              <tr data-controlno="{{ $header['PreparatoryFormat'] }}" data-id="{{ $header['PPMPHeaderID'] }}">
                <td>
                  @if(in_array($header['Status'], ['N']) && in_array(session('EmployeeID'), [$header['EncodedBy'], $header['PreparedBy']]))
                    <button class="fa fa-trash btn-delete"></button>
                  @endif
                </td>
                <td>{{ $header['PreparatoryFormat'] }}</td>
                <td>{{ $header['ProjectCode'] }}</td>
                <td>{{ $header['PPMPYear'] }}</td>
                <td>{{ number_format($header['TotalBudget'], 2) }}</td>
                <td>{{ $header['PreparedBy_Name'] ?? '' }}</td>
                <td>{{ $header['ApprovedBy_Name'] ?? '' }}</td>
                <td>{{ $header['CertifiedBy_Name'] ?? '' }}</td>
                <td>{{ $header['StatusName'] ?? '' }}</td>
                <td>{{ $header['DateCreated'] }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

<script type="text/javascript">
  var pageJS = (function() {

    function init() {
      bindEvents();

      $('#dataTable').DataTable({
        "order": [
          [1, "desc"]
        ]
      });
    }

    function bindEvents() {
      $('#dataTable tbody').on('click', 'tr', function() {
        var controlNo = $(this).data('controlno');
        if (controlNo) {
          window.location.href = base_url + 'ppmp/preparation/' + controlNo;
        }
      });

      $('#new').on('click', function() {
        window.location.href = base_url + 'ppmp/preparation';
      });
    }

    return {
      init: init
    };
  })();

  $(document).ready(function() {
    pageJS.init();
  });
</script>

@include('templates.modal-alert')
@include('templates.modal-confirm')

@if(request()->route()->getName() == 'ppmp.preparation_inbox')
  @include('templates.modal-ppmp-new-form')
  @include('templates.modal-ppmp-change-item-details')
  @include('templates.modal-item-list')
@endif
@endsection
