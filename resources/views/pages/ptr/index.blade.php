@extends('layouts.layout')

@section('title', 'PTR')

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Property Transfer Report (PTR)</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">PTR List</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="ptr-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>PTR Control No.</th>
            <th>PTR No.</th>
            <th>Reason for Transfer</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers as $header)
            <tr>
              <td>{{ $header['PTRControlNo'] ?? '' }}</td>
              <td>{{ $header['PTRNo'] ?? '' }}</td>
              <td>{{ $header['Remarks'] ?? '' }}</td>
              <td>{{ $header['Status'] ?? '' }}</td>
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
