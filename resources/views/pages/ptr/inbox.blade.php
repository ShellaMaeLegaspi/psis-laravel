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

<div class="row page-content-title">
  <div class="col-sm-8">
    <h1>Property Transfer Report (PTR) / {{ $inbox_title ?? 'Inbox' }}</h1>
  </div>
  <div class="col-sm-4 col-button">
    @if(request()->route()->getActionMethod() == 'preparationInbox' && hasAccess(22))
      <a class="btn btn-primary pull-right" href="{{ url('ptr/preparation') }}" role="button">New</a>
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
            <th>PTR Control No.</th>
            <th>PTR No.</th>
            <th>Reason for Transfer</th>
            <th>Status</th>
            <th>Prepared By</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody>
          @foreach($headers ?? [] as $header)
            <tr data-id="{{ $header['PTRHeaderID'] }}" data-controlno="{{ $header['PTRControlNo'] }}">
              <td>{{ $header['PTRControlNo'] }}</td>
              <td>{{ $header['PTRNo'] ?? '' }}</td>
              <td>{{ $header['Remarks'] ?? '' }}</td>
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

<script type="text/javascript">
  (function() {
    $('tbody tr').on('click', 'td:not(:first-child)', function() {
      var prep_link = 'preparation';
      window.location = '{{ url('ptr') }}/' + prep_link + '/PTRControlNo/' + $(this).parent().data('controlno');
    });

    $(document).ready(function() {
      $('#sai-inbox').dataTable({
        destroy: true
      });
      $('#sai-inbox').dataTable();
    });
  })();
</script>
