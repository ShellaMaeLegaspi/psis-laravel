@extends('layouts.layout')

@section('title', 'IIRUP Inbox')

@push('styles')
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
@endpush

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Inventory and Inspection Report of Unserviceable Property (IIRUP) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->segment(2) == 'iirup_preparation_inbox' && hasAccess(22))
      <a class="btn btn-primary pull-right" href="{{ url('/property/iirup_preparation') }}" role="button">New</a>
    @endif
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Inbox</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="sai-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>IIRUP Control No.</th>
            <th>IIRUP No.</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($headers))
            @foreach($headers as $header)
              <tr data-id="{{ $header['IIRUPHeaderID'] }}" data-controlno="{{ $header['IIRUPControlNo'] }}">
                <td>{{ $header['IIRUPControlNo'] }}</td>
                <td>{{ $header['IIRUPNo'] }}</td>
                <td>{{ $header['Remarks'] }}</td>
                <td>{{ $header['StatusName'] }}</td>
                <td>{{ $header['PreparedBy_Name'] }}</td>
                <td>{{ $header['DateCreated'] }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  (function() {
    $('tbody tr').on('click', 'td:not(:first-child)', function() {
      window.location = '{{ url('/property/iirup_preparation') }}/IIRUPControlNo/' + $(this).parent().data('controlno');
    });

    $(document).ready(function() {
      $('#sai-inbox').dataTable({
        destroy: true
      });
      $('#sai-inbox').dataTable();
    });
  })();
</script>
@endpush
@endsection
