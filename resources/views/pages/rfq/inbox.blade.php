@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(2) {
    width: 30%
  }

  #sai-inbox tr td:nth-child(3) {
    /*purpose*/
    width: 10%
  }

  #sai-inbox tr td:nth-child(4) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(5) {
    /* name */
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
    <h1>Request for Quotation (RFQ) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->route()->getName() == 'rfq.preparation_inbox')
      <a class="btn btn-primary pull-right" href="{{ url('rfq/preparation') }}" role="button">New</a>
    @endif
  </div>
</div>
<div class="card mb-3">
  <div class="card-header">
    Inbox
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="sai-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>RFQ Control No.</th>
            <th>Particulars</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers as $header)
            <tr data-id="{{ $header['RFQHeaderID'] }}" data-controlno="{{ $header['RFQControlNo'] }}">
              <td>{{ $header['RFQControlNo'] }}</td>
              <td>{{ $header['Particulars'] ?? '' }}</td>
              <td class="r">{{ number_format($header['TotalAmount'] ?? 0, 2) }}</td>
              <td>{{ $header['PreparedBy_Name'] ?? '' }}</td>
              <td>{{ $header['StatusName'] ?? '' }}</td>
              <td>{{ $header['DateCreated'] ?? '' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  (function() {

    $('tbody tr').on('click', 'td:not(:first-child)', function() {
      window.location = base_url + 'rfq/preparation/RFQControlNo/' + $(this).parent().data('controlno');
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
