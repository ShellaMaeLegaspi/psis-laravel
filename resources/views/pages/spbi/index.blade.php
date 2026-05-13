@extends('layouts.layout')

@section('title', 'SPBI')

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Stock, Plan and Budget Inquiry (SPBI)</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">SPBI List</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="spbi-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>SPBI Control No.</th>
            <th>Project Code</th>
            <th>Purpose</th>
            <th>Total Amount</th>
            <th>Prepared By</th>
            <th>Status</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers as $header)
            <tr>
              <td>{{ $header['SPBIControlNo'] ?? '' }}</td>
              <td>{{ $header['ProjectCode'] ?? '' }}</td>
              <td>{{ $header['Purpose'] ?? '' }}</td>
              <td>{{ $header['TotalAmount'] ?? '' }}</td>
              <td>{{ $header['EncodedBy_Name'] ?? '' }}</td>
              <td>{{ $header['Status'] ?? '' }}</td>
              <td>{{ $header['DateCreated'] ?? '' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
