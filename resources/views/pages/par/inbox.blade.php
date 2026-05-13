@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 15%;
  }

  #sai-inbox tr td:nth-child(2) {
    width: 30%;
  }

  #sai-inbox tr td:nth-child(3) {
    width: 15%;
  }

  #sai-inbox tr td:nth-child(4) {
    /*purpose*/
    width: 10%;
  }

  #sai-inbox tr td:nth-child(5) {
    width: 20%;
  }

  #sai-inbox tr td:nth-child(6) {
    width: 10%;
  }

  .r {
    text-align: right;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Property Acknowledgement Receipt (PAR) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->route()->getName() == 'par.preparation_inbox')
      <a class="btn btn-primary pull-right" href="{{ url('par/preparation') }}" role="button">New</a>
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
            <th>PAR Control No.</th>
            <th>Remarks</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($row as $header)
            <tr data-id="{{ $header['PARHeaderID'] }}" data-controlno="{{ $header['PARControlNo'] }}">
              <td>{{ $header['PARControlNo'] }}</td>
              <td>{{ $header['Particulars'] ?? '' }}</td>
              <td class="r">{{ number_format($header['TotalAmount'] ?? 0, 2) }}</td>
              <td>{{ $header['StatusName'] ?? '' }}</td>
              <td>{{ $header['PreparedBy_Name'] ?? '' }}</td>
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
      window.location = base_url + 'par/preparation/PARControlNo/' + $(this).parent().data('controlno');
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
