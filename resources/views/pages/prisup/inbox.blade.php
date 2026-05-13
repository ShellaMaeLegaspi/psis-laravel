@extends('layouts.layout')

@section('title', 'PRISUP Inbox')

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
    <h1>Property Return and Inspection Slip For Excess or Unserviceable Property (PRISUP) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->segment(2) == 'prisup_preparation_inbox' && hasAccess(22))
      <a class="btn btn-primary pull-right" href="{{ url('/property/prisup_preparation') }}" role="button">New</a>
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
            <th>PRISUP Control No.</th>
            <th>PRISUP No.</th>
            <th>Division</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($headers))
            @foreach($headers as $header)
              <tr data-id="{{ $header['PRISUPHeaderID'] }}" data-controlno="{{ $header['PRISUPControlNo'] }}">
                <td>{{ $header['PRISUPControlNo'] }}</td>
                <td>{{ $header['PRISUPNo'] }}</td>
                <td>{{ $header['DivCode'] }}</td>
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
      window.location = '{{ url('/property/prisup_preparation') }}/PRISUPControlNo/' + $(this).parent().data('controlno');
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
