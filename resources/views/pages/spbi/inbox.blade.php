<style type="text/css">
  tbody tr {
    cursor: pointer;
  }

  #sai-inbox tr td:nth-child(1) {
    width: 5%
  }

  #sai-inbox tr td:nth-child(2) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(3) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(4) {
    width: 30%
  }

  #sai-inbox tr td:nth-child(5) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(6) {
    width: 15%
  }

  #sai-inbox tr td:nth-child(7) {
    width: 10%
  }

  #sai-inbox tr td:nth-child(8) {
    width: 10%
  }

  .r {
    text-align: right;
  }
</style>

<div class="row page-content-title">
  <div class="col-sm-12">
    <h1>Stock, Plan and Budget Inquiry (SPBI) / {{ $inbox_title ?? 'Inbox' }}</h1>
  </div>
  <div class="col-sm-12 col-button">
    @if(request()->route()->getActionMethod() == 'preparationInbox')
      <a class="btn btn-primary pull-right" href="{{ url('spbi/preparation_uncommon') }}" role="button">New SPBI for Uncommon Items</a>
    @endif

    @if(request()->route()->getActionMethod() == 'preparationInbox')
      <a class="btn btn-primary pull-right" href="{{ url('spbi/preparation_common') }}" role="button">New SPBI for Common Items</a>
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
            <th></th>
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
          @foreach($headers ?? [] as $header)
            <tr data-id="{{ $header['SPBIHeaderID'] }}" data-controlno="{{ $header['SPBIControlNo'] }}" data-common="{{ $header['Common'] ?? 0 }}">
              <td>
                @if(hasAccess(29))
                  <button class="fa fa-times btn-delete" title="Cancel SPBI"></button>
                @endif
              </td>
              <td>{{ $header['SPBIControlNo'] }}</td>
              <td>{{ $header['ProjectCode'] ?? '' }}</td>
              <td>{{ $header['Purpose'] ?? '' }}</td>
              <td class="r">{{ number_format($header['TotalAmount'] ?? 0, 2) }}</td>
              <td>{{ $header['EncodedBy_Name'] ?? '' }}</td>
              <td>{{ $header['StatusName'] ?? '' }}</td>
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
      if ($(this).parent().data('common') == 1)
        window.location = '{{ url('spbi/preparation_common/SPBIControlNo') }}/' + $(this).parent().data('controlno');
      else
        window.location = '{{ url('spbi/preparation_uncommon/SPBIControlNo') }}/' + $(this).parent().data('controlno');
    });

    $('.btn-delete').on('click', function() {
      var row = $(this).parent().parent();
      var remarks = '';

      modalPrompt('Are you sure you want to CANCEL this SPBI?', function() {
        remarks = $('#message-text').val();
        remarks = Sanitize.Text(remarks);
        $.ajax({
          type: "post",
          url: "{{ url('spbi/cancel_spbi') }}",
          data: {
            headerid: row.data('id'),
            remarks: remarks
          },
          success: function(response) {
            var res;
            try {
              res = JSON.parse(response);
            } catch (err) {
              modalAlert('Something went wrong...');
              return;
            }

            if (res.Status == 'ROLLBACK') {
              modalAlert(res.Message);
              return;
            }

            row.remove();
            modalAlert('Your SPBI is successfully cancelled.');
          },
          error: function() {
            modalAlert("Something went wrong...");
          }
        });
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
