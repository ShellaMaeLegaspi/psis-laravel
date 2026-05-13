@extends('layouts.layout')

@section('title', 'PSIS Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">PSIS Dashboard</h1>
  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-tachometer-alt"></i> Quick Actions
      </div>
      <div class="card-body">
        <div class="list-group">
          <a href="{{ url('/ics') }}" class="list-group-item">
            <i class="fas fa-file-alt"></i> Inventory Custodian Slip (ICS)
          </a>
          <a href="{{ url('/ppmp') }}" class="list-group-item">
            <i class="fas fa-file-alt"></i> Project Procurement Management Plan (PPMP)
          </a>
          <a href="{{ url('/ptr') }}" class="list-group-item">
            <i class="fas fa-file-alt"></i> Property Transfer Request (PTR)
          </a>
          <a href="{{ url('/spbi') }}" class="list-group-item">
            <i class="fas fa-file-alt"></i> Stock and Property Inventory (SPBI)
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-9 col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-chart-bar"></i> System Overview
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card text-center bg-primary text-white">
              <div class="card-body">
                <h3>{{ $totalICS ?? 0 }}</h3>
                <p>ICS Records</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card text-center bg-success text-white">
              <div class="card-body">
                <h3>{{ $totalPPMP ?? 0 }}</h3>
                <p>PPMP Records</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <i class="fas fa-cogs"></i> Recent Activities
              </div>
              <div class="card-body">
                <p>Recent system activities and transactions will appear here.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Sample data - replace with actual data from controllers
  const systemData = {
    totalICS: {{ $totalICS ?? 0 }},
    totalPPMP: {{ $totalPPMP ?? 0 }},
    recentActivities: []
  };

  // Initialize tooltips
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endpush
@endsection
