<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <meta name="language" content="english">
    <meta name="description" content="PSIS - Procurement Supply Information System">
    <meta name="author" content="PSIS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/images/psis-logo.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CoreMIS - PSIS</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- SB Admin theme -->
    <link href="{{ asset('sb-admin.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Popper + Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <style type="text/css">
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.9) !important;
        }

        .content-wrapper {
            padding: 2em;
            height: 95vh;
            overflow-y: scroll;
            padding-bottom: 10rem !important;
        }

        thead {
            font-weight: bold;
        }

        .card-header {
            padding: 5px 10px;
        }

        .card-body {
            padding: 10px 25px;
        }

        .form-group {
            font-size: 14px !important;
            margin-bottom: 5px;
        }

        .table {
            font-size: 12px !important;
        }

        .table>thead {
            background-color: rgba(0, 0, 0, .03);
        }

        .align-right {
            text-align: right;
        }

        .nav-link.active {
            background: #0071bc !important;
            color: #fff !important;
        }

        .nav-link {
            color: gray;
        }

        .input-dropdown div:hover {
            color: #FFF;
            background-color: #0071bc;
            cursor: pointer;
        }

        .input-dropdown div {
            padding: 3px;
        }

        .input-dropdown {
            position: absolute;
            background: white;
            width: 95%;
            z-index: 1;
        }

        .page-content-title {
            margin-bottom: 10px;
        }

        .col-button .btn {
            margin: 3px !important;
        }

        #cover {
            background: url("{{ asset('assets/images/hourglass.svg') }}") no-repeat scroll center center #f0f1f2;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 1029;
            top: 0;
            opacity: 0.8;
        }

        #cover-table {
            background: url("{{ asset('assets/images/hourglass.svg') }}") no-repeat scroll center center #f0f1f2;
        }

        .option-employee div:hover {
            color: #FFF;
            background-color: #0071bc;
            cursor: pointer;
        }

        .option-employee div {
            padding: 3px;
        }

        .option-employee {
            position: absolute;
            background: white;
            width: 100%;
            z-index: 1;
        }

        .action-col {
            width: 5% !important;
        }

        .tab-content {
            max-height: 87vh;
            overflow: scroll;
        }
    </style>

    <script type="text/javascript">
        const base_url = "{{ url('/') }}/";
        const prep_status = [undefined, 'N', 'R'];

        @if(session('EmployeeID'))
            const fund = '{{ session('FundClass') }}';

            // access 
            const UserAccess = function(rights) {
                var access = [];

                access['ADMIN_ACCESS'] = {{ hasAccess(32) ? 'true' : 'false' }};
                access['RETURN_PPMP'] = {{ hasAccess(70) ? 'true' : 'false' }};
                access['UPDATE_DOCUMENT'] = {{ hasAccess(32) ? 'true' : 'false' }};
                access['UPDATE_STATUS'] = {{ hasAccess(31) ? 'true' : 'false' }};
                access['CANCEL_DOCUMENT'] = {{ hasAccess(30) ? 'true' : 'false' }};

                return access[rights] || false;
            };
        @endif

        const SPOfficer = null; // TODO: Get from Param_Model

        // Attach CSRF token to all jQuery AJAX requests (Laravel expects this).
        $(function () {
            const token = $('meta[name="csrf-token"]').attr('content');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
            }
        });

        // Table Loading utility
        const TableLoading = {
            On: function(element) {
                var gif = '<img src="' + base_url + 'assets/images/hourglass.svg">';
                $(element).find('tbody').html('<tr class="odd"><td valign="top" colspan="' + $(element).find('thead tr th').length + '" class="dataTables_empty" style="text-align: center;">' + gif + '</td></tr>');
            },
            Off: function(element) {
                $(element).find('tbody').html('');
            }
        };
    </script>

    <!-- Load React -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

    <!-- Global JavaScript Utilities -->
    <script src="{{ asset('js/global.js') }}"></script>

    @yield('styles')
</head>

@php
    $isLoggedIn = session('EmployeeID') && !Request::routeIs('login');
@endphp

<body class="{{ $isLoggedIn ? 'fixed-nav' : '' }}" id="page-top" style="{{ $isLoggedIn ? 'overflow: hidden;' : '' }}">

    @if($isLoggedIn)
        @include('layouts.nav')
    @endif

    @yield('content')

    @yield('scripts')

    @if($isLoggedIn)
        @include('layouts.footer')
    @else
    </body>
</html>
    @endif
