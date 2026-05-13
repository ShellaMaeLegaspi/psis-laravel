@extends('layouts.layout')

@section('title', 'PR Inbox')

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
    <h1>Purchase Request (PR) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->segment(2) == 'preparation_inbox')
      <a class="btn btn-primary pull-right" href="{{ url('/pr/preparation_uncommon') }}" role="button">Uncommon</a>
    @endif

    @if(request()->segment(2) == 'preparation_inbox')
      <a class="btn btn-primary pull-right" href="{{ url('/pr/preparation_common') }}" role="button">Common</a>
    @endif

    @if(request()->segment(2) == 'preparation_inbox' && hasAccess(22))
      <a class="btn btn-primary pull-right" href="{{ url('/pr/preparation_qpr') }}" role="button">New QPR</a>
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
            <th>PR Control No.</th>
            <th>Project Code</th>
            <th>Purpose</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($pr))
            @foreach($pr as $header)
              <tr data-id="{{ $header['PRHeaderID'] }}" data-controlno="{{ $header['PRControlNo'] }}" data-qpr="{{ $header['QPR'] }}">
                <td>{{ $header['PRControlNo'] }}</td>
                <td>{{ $header['ProjectCode'] }}</td>
                <td>{{ $header['Purpose'] }}</td>
                <td class="r">{{ number_format($header['TotalAmount'], 2) }}</td>
                <td>{{ $header['PreparedBy_Name'] }}</td>
                <td>{{ $header['StatusName'] }}</td>
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
      var prep_link = 'preparation';
      if ($(this).parent().data('qpr') == 1) prep_link = 'preparation_qpr';
      window.location = '{{ url('/pr/') }}' + prep_link + '/PRControlNo/' + $(this).parent().data('controlno');
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
