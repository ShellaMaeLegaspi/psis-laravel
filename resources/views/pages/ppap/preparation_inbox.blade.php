@extends('layouts.layout')

@section('title', 'PPAP Preparation Inbox')

@section('content')
<style type="text/css">
  tbody tr {
    cursor: pointer;
  }
</style>

<!-- Example Tables Card -->
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>PPMP Augmentation and Supplemental / Inbox</h1>
  </div>
  <div class="col-sm-4 col-button">
    <a class="btn btn-primary pull-right" href="{{ url('/ppap/preparation') }}" role="button">New Augmentation</a>
  </div>
</div>
<div class="card mb-3">
  <div class="card-header">
    {{ $inbox_title ?? 'Preparation Inbox' }}
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="dataTable" cellspacing="0">
        <thead>
          <tr>
            <th></th>
            <th>PPAP Control No.</th>
            <th>Project Code</th>
            <th>PPMP Year</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Approved By</th>
            <th>Certified By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers as $header)
            <tr data-controlno="{{ $header['PPAPControlNo'] }}" data-supplemental="{{ $header['Supplemental'] ?? '' }}">
              <td>
                @if(in_array($header['Status'] ?? '', ['N', 'R']))
                  <!-- <button class="fa fa-trash btn-delete"></button> -->
                @endif
              </td>
              <td>{{ $header['PPAPControlNo'] ?? '' }}</td>
              <td>{{ $header['ProjectCode'] ?? '' }}</td>
              <td>{{ $header['PPMPYear'] ?? '' }}</td>
              <td>{{ number_format($header['TotalAmount'] ?? 0, 2) }}</td>
              <td>{{ $header['PreparedBy_Name'] ?? '' }}</td>
              <td>{{ $header['ApprovedBy_Name'] ?? '' }}</td>
              <td>{{ $header['CertifiedBy_Name'] ?? '' }}</td>
              <td>{{ $header['StatusName'] ?? '' }}</td>
              <td>{{ $header['DateCreated'] ?? '' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function() {

    $('tbody tr').on('click', 'td:not(:first-child)', function() {
      window.location = "{{ url('/') }}" + "ppap/preparation/PPAPControlNo/" + $(this).parent().data('controlno');
    });
  })();
</script>
@endpush
@endsection
