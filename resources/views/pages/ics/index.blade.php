@extends('layouts.layout')

@section('title', 'ICS')

@section('content')
<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Inventory Custodian Slip (ICS)</h1>
  </div>
  <div class="col-sm-4 col-button">
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">ICS List</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" width="100%" id="ics-inbox" cellspacing="0">
        <thead>
          <tr>
            <th>ICS Control No.</th>
            <th>ICS No.</th>
            <th>Division</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers as $header)
            <tr>
              <td>{{ $header['ICSControlNo'] ?? '' }}</td>
              <td>{{ $header['ICSNo'] ?? '' }}</td>
              <td>{{ $header['DivCode'] ?? '' }}</td>
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
