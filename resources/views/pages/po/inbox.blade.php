@extends('layouts.layout')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 15%
  }

  #sai-inbox tr td:nth-child(2) {
    /*purpose*/
    width: 30%
  }

  #sai-inbox tr td:nth-child(3) {
    width: 15%
  }

  #sai-inbox tr td:nth-child(4) {
    /* name */
    width: 10%
  }

  #sai-inbox tr td:nth-child(5) {
    width: 20%
  }

  #sai-inbox tr td:nth-child(6) {
    width: 10%
  }

  .r {
    text-align: right;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Purchase Order (PO) / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->route()->getName() == 'po.preparation_inbox')
      <a class="btn btn-primary pull-right" href="{{ url('po/preparation') }}" role="button">New</a>
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
            <th>PO Control No.</th>
            <th>Supplier Name</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($po as $header)
            <tr data-id="{{ $header['POHeaderID'] }}" data-controlno="{{ $header['POControlNo'] }}">
              <td>{{ $header['POControlNo'] }}</td>
              <td>{{ $header['SupplierName'] ?? '' }}</td>
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
      window.location = base_url + 'po/preparation/POControlNo/' + $(this).parent().data('controlno');
    });

    $('.btn-delete').on('click', function() {
      var res = confirm("Are you sure you want to DELETE this SAI?");
      if (res == false) return;

      var row = $(this).parent().parent();

      $.ajax({
        type: "post",
        url: base_url + "po/delete",
        data: {
          id: row.data('id'),
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          row.remove();
        },
        error: function() {
          alert("Something went wrong...");
        }
      });
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
