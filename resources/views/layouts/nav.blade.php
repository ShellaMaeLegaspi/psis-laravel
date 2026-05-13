<style type="text/css">
  .nav-top-right li {
    color: white;
    margin-bottom: 0;
    height: 40px;
  }
  .badge {
    padding: 5px 5px;
    border-radius: 50%;
    background: red;
    color: white;
    -webkit-animation: breathing 5s ease-out infinite normal;
    animation: breathing 5s ease-out infinite normal;
  }
  @-webkit-keyframes breathing {
    0% { -webkit-transform: scale(0.9); transform: scale(0.9); }
    25% { -webkit-transform: scale(1); transform: scale(1); }
    60% { -webkit-transform: scale(0.9); transform: scale(0.9); }
    100% { -webkit-transform: scale(0.9); transform: scale(0.9); }
  }
  @keyframes breathing {
    0% { -webkit-transform: scale(0.9); -ms-transform: scale(0.9); transform: scale(0.9); }
    25% { -webkit-transform: scale(1); -ms-transform: scale(1); transform: scale(1); }
    60% { -webkit-transform: scale(0.9); -ms-transform: scale(0.9); transform: scale(0.9); }
    100% { -webkit-transform: scale(0.9); -ms-transform: scale(0.9); transform: scale(0.9); }
  }
  .nav-top-right li>div {
    margin-right: 20px;
    border-right: 1px solid rgba(255, 255, 255, .5);
  }
  .navbar-nav a {
    padding-right: 10px !important;
    padding-top: 10px !important;
    padding-bottom: 10px !important;
  }
  .modal-header {
    background-image: linear-gradient(to right, #343a40, #343a40);
    color: #fff;
  }
</style>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
  <img src="{{ asset('assets/images/psis-logo.png') }}" class="mr-2" style="width: 30px;">
  <a class="navbar-brand" href="{{ url('/') }}" style="font-weight: bold;">
    COREMIS - PSIS
  </a>

  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarResponsive">
    <ul class="navbar-nav navbar-sidenav">

      @if(hasAccess(2) || hasAccess(3) || hasAccess(6) || hasAccess(7))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PPMP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePPMP">
            <i class="fa fa-fw fa-calendar-o"></i>
            <span class="nav-link-text">PPMP</span>
            <span class='badge' id='ppmp-parent-link'></span>
          </a>
          @if(Request::is('ppmp*'))
          <ul class="sidenav-second-level collapsed" id="collapsePPMP">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePPMP">
          @endif
            @if(hasAccess(2))
              <li>
                <a href="{{ url('/ppmp/preparation_inbox') }}">Preparation Inbox <span class="badge" id='nav-prep-inbox'></span></a>
              </li>
            @endif
            @if(hasAccess(58))
              <li>
                <a href="{{ url('/ppmp/evaluation_inbox') }}">Evaluation Inbox <span class="badge" id='nav-eval-inbox'></span></a>
              </li>
            @endif
            @if(hasAccess(3))
              <li>
                <a href="{{ url('/ppmp/approval_inbox') }}">Approval Inbox <span class="badge" id='nav-approv-inbox'></span></a>
              </li>
            @endif
            @if(hasAccess(6))
              <li>
                <a href="{{ url('/ppmp/certification_inbox') }}">Certification Inbox <span class="badge" id='nav-certify-inbox'></span></a>
              </li>
            @endif
            @if(hasAccess(61))
              <li>
                <a href="{{ url('/ppmp/receiving_inbox') }}">Receiving Inbox <span class="badge" id='nav-receiving-inbox'></span></a>
              </li>
            @endif
            @if(hasAccess(7))
              <li>
                <a href="{{ url('/ppmp/query') }}">Query</a>
              </li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(57) || hasAccess(58) || hasAccess(59) || hasAccess(60) || hasAccess(61) || hasAccess(62))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PPAP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePPAP">
            <i class="fa fa-fw fa-calendar-plus-o"></i>
            <span class="nav-link-text">PPAP</span>
            <span class='badge' id='ppap-parent-link'></span>
          </a>
          @if(Request::is('ppap*'))
          <ul class="sidenav-second-level collapsed" id="collapsePPAP">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePPAP">
          @endif
            @if(hasAccess(57))
              <li><a href="{{ url('/ppap/preparation_inbox') }}">Preparation Inbox <span class="badge" id='nav-preparation-ppap'></span></a></li>
            @endif
            @if(hasAccess(58))
              <li><a href="{{ url('/ppap/processing_inbox') }}">Processing Inbox<span class="badge" id='nav-processing-ppap'></span></a></li>
            @endif
            @if(hasAccess(58))
              <li><a href="{{ url('/ppap/evaluation_inbox') }}">Evaluation Inbox<span class="badge" id='nav-evaluation-ppap'></span></a></li>
            @endif
            @if(hasAccess(59))
              <li><a href="{{ url('/ppap/approval_inbox') }}">Approval Inbox<span class="badge" id='nav-approval-ppap'></span></a></li>
            @endif
            @if(hasAccess(60))
              <li><a href="{{ url('/ppap/certification_inbox') }}">Certification Inbox<span class="badge" id='nav-certification-ppap'></span></a></li>
            @endif
            @if(hasAccess(61))
              <li><a href="{{ url('/ppap/receive_inbox') }}">Receiving Inbox<span class="badge" id='nav-receiving-ppap'></span></a></li>
            @endif
            @if(hasAccess(62))
              <li><a href="{{ url('/ppap/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(17) || hasAccess(18))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="SPBI">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSPBI">
            <i class="fa fa-fw fa-search"></i>
            <span class="nav-link-text">SPBI</span>
          </a>
          @if(Request::is('spbi*'))
          <ul class="sidenav-second-level collapsed" id="collapseSPBI">
          @else
          <ul class="sidenav-second-level collapse" id="collapseSPBI">
          @endif
            @if(hasAccess(17))
              <li><a href="{{ url('/spbi/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(18))
              <li><a href="{{ url('/spbi/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(24) || hasAccess(25) || hasAccess(26) || hasAccess(27))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePR">
            <i class="fa fa-fw fa-cart-arrow-down"></i>
            <span class="nav-link-text">PR</span>
            <span class='badge' id='pr-parent-link'></span>
          </a>
          @if(Request::is('pr*'))
          <ul class="sidenav-second-level collapsed" id="collapsePR">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePR">
          @endif
            @if(hasAccess(24))
              <li><a href="{{ url('/pr/preparation_inbox') }}">Preparation Inbox <span class="badge" id='nav-preparation-pr'></span></a></li>
            @endif
            @if(hasAccess(25))
              <li><a href="{{ url('/pr/evaluation_inbox') }}">Evaluation Inbox <span class="badge" id='nav-evaluation-pr'></span></a></li>
            @endif
            @if(hasAccess(26))
              <li><a href="{{ url('/pr/approval_inbox') }}">Approval Inbox <span class="badge" id='nav-approval-pr'></span></a></li>
            @endif
            @if(hasAccess(37))
              <li><a href="{{ url('/pr/receive_inbox') }}">Receiving Inbox <span class="badge" id='nav-receiving-pr'></span></a></li>
            @endif
            @if(hasAccess(27))
              <li><a href="{{ url('/pr/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(121) || hasAccess(122) || hasAccess(123))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="RFQ">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRFQ">
            <i class="fa fa-fw fa-quote-left"></i>
            <span class="nav-link-text">RFQ</span>
          </a>
          @if(Request::is('rfq*'))
          <ul class="sidenav-second-level collapsed" id="collapseRFQ">
          @else
          <ul class="sidenav-second-level collapse" id="collapseRFQ">
          @endif
            @if(hasAccess(121))
              <li><a href="{{ url('/rfq/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(122))
              <li><a href="{{ url('/rfq/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(123))
              <li><a href="{{ url('/rfq/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(124) || hasAccess(142) || hasAccess(143))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Bid Doc">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseBidDoc">
            <i class="fa fa-fw fa-quote-right"></i>
            <span class="nav-link-text">Bid Doc</span>
          </a>
          @if(Request::is('bid*'))
          <ul class="sidenav-second-level collapsed" id="collapseBidDoc">
          @else
          <ul class="sidenav-second-level collapse" id="collapseBidDoc">
          @endif
            @if(hasAccess(124))
              <li><a href="{{ url('/bid/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(142))
              <li><a href="{{ url('/bid/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(143))
              <li><a href="{{ url('/bid/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(127) || hasAccess(128) || hasAccess(129) || hasAccess(130) || hasAccess(131) || hasAccess(132))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Abstract">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseAbstract">
            <i class="fa fa-fw fa-list"></i>
            <span class="nav-link-text">Abstract</span>
            <span class='badge' id='abst-parent-link'></span>
          </a>
          @if(Request::is('abst*'))
          <ul class="sidenav-second-level collapsed" id="collapseAbstract">
          @else
          <ul class="sidenav-second-level collapse" id="collapseAbstract">
          @endif
            @if(hasAccess(127))
              <li><a href="{{ url('/abst/preparation_inbox') }}">Preparation Inbox <span class="badge" id='nav-preparation-abst'></span></a></li>
            @endif
            @if(hasAccess(128))
              <li><a href="{{ url('/abst/evaluation_inbox') }}">Evaluation Inbox <span class="badge" id='nav-evaluation-abst'></span></a></li>
            @endif
            @if(hasAccess(129))
              <li><a href="{{ url('/abst/certification_inbox') }}">Certification Inbox <span class="badge" id='nav-certification-abst'></span></a></li>
            @endif
            @if(hasAccess(130))
              <li><a href="{{ url('/abst/approval_inbox') }}">Approval Inbox <span class="badge" id='nav-approval-abst'></span></a></li>
            @endif
            @if(hasAccess(131))
              <li><a href="{{ url('/abst/receive_inbox') }}">Receiving Inbox <span class="badge" id='nav-receiving-abst'></span></a></li>
            @endif
            @if(hasAccess(132))
              <li><a href="{{ url('/abst/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(133) || hasAccess(134) || hasAccess(135))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="NOA">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseNOA">
            <i class="fa fa-fw fa-envelope-open"></i>
            <span class="nav-link-text">NOA</span>
          </a>
          @if(Request::is('noa*'))
          <ul class="sidenav-second-level collapsed" id="collapseNOA">
          @else
          <ul class="sidenav-second-level collapse" id="collapseNOA">
          @endif
            @if(hasAccess(133))
              <li><a href="{{ url('/noa/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(134))
              <li><a href="{{ url('/noa/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(135))
              <li><a href="{{ url('/noa/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(34) || hasAccess(35) || hasAccess(36) || hasAccess(37) || hasAccess(38))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PO">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePO">
            <i class="fa fa-fw fa-handshake-o"></i>
            <span class="nav-link-text">PO</span>
            <span class='badge' id='po-parent-link'></span>
          </a>
          @if(Request::is('po*'))
          <ul class="sidenav-second-level collapsed" id="collapsePO">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePO">
          @endif
            @if(hasAccess(34))
              <li><a href="{{ url('/po/preparation_inbox') }}">Preparation Inbox <span class="badge" id='nav-preparation-po'></span></a></li>
            @endif
            @if(hasAccess(35))
              <li><a href="{{ url('/po/approval_inbox') }}">Approval Inbox <span class="badge" id='nav-approval-po'></span></a></li>
            @endif
            @if(hasAccess(36))
              <li><a href="{{ url('/po/certification_inbox') }}">Certification Inbox <span class="badge" id='nav-certification-po'></span></a></li>
            @endif
            @if(hasAccess(37))
              <li><a href="{{ url('/po/receive_inbox') }}">Receiving Inbox <span class="badge" id='nav-receiving-po'></span></a></li>
            @endif
            @if(hasAccess(38))
              <li><a href="{{ url('/po/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(136) || hasAccess(137) || hasAccess(138))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="NTP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseNTP">
            <i class="fa fa-fw fa-envelope-open-o"></i>
            <span class="nav-link-text">NTP</span>
          </a>
          @if(Request::is('ntp*'))
          <ul class="sidenav-second-level collapsed" id="collapseNTP">
          @else
          <ul class="sidenav-second-level collapse" id="collapseNTP">
          @endif
            @if(hasAccess(136))
              <li><a href="{{ url('/ntp/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(137))
              <li><a href="{{ url('/ntp/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(138))
              <li><a href="{{ url('/ntp/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(40) || hasAccess(41) || hasAccess(42) || hasAccess(43) || hasAccess(44))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="IAR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseIAR">
            <i class="fa fa-fw fa-truck"></i>
            <span class="nav-link-text">IAR</span>
          </a>
          @if(Request::is('iar*'))
          <ul class="sidenav-second-level collapsed" id="collapseIAR">
          @else
          <ul class="sidenav-second-level collapse" id="collapseIAR">
          @endif
            @if(hasAccess(40))
              <li><a href="{{ url('/iar/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(41))
              <li><a href="{{ url('/iar/approval_inbox') }}">Inspection Inbox</a></li>
            @endif
            @if(hasAccess(42))
              <li><a href="{{ url('/iar/acceptance_inbox') }}">Acceptance Inbox</a></li>
            @endif
            @if(hasAccess(43))
              <li><a href="{{ url('/iar/receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(44))
              <li><a href="{{ url('/iar/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(76) || hasAccess(75) || hasAccess(77) || hasAccess(78))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PAR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePAR">
            <i class="fa fa-fw fa-qrcode"></i>
            <span class="nav-link-text">PAR</span>
          </a>
          @if(Request::is('par*'))
          <ul class="sidenav-second-level collapsed" id="collapsePAR">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePAR">
          @endif
            @if(hasAccess(76))
              <li><a href="{{ url('/par/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(75))
              <li><a href="{{ url('/par/acceptance_inbox') }}">Acceptance Inbox</a></li>
            @endif
            @if(hasAccess(76))
              <li><a href="{{ url('/par/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(77))
              <li><a href="{{ url('/par/receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(78))
              <li><a href="{{ url('/par/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(79) || hasAccess(80) || hasAccess(81) || hasAccess(82) || hasAccess(83))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="ICS">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseICS">
            <i class="fa fa-fw fa-tag"></i>
            <span class="nav-link-text">ICS</span>
          </a>
          @if(Request::is('ics*'))
          <ul class="sidenav-second-level collapsed" id="collapseICS">
          @else
          <ul class="sidenav-second-level collapse" id="collapseICS">
          @endif
            @if(hasAccess(79))
              <li><a href="{{ url('/ics/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(80))
              <li><a href="{{ url('/ics/acceptance_inbox') }}">Acceptance Inbox</a></li>
            @endif
            @if(hasAccess(81))
              <li><a href="{{ url('/ics/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(82))
              <li><a href="{{ url('/ics/receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(83))
              <li><a href="{{ url('/ics/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(46) || hasAccess(47) || hasAccess(48) || hasAccess(49))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="RIS">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRIS">
            <i class="fa fa-fw fa-shopping-basket"></i>
            <span class="nav-link-text">RIS</span>
          </a>
          @if(Request::is('ris*'))
          <ul class="sidenav-second-level collapsed" id="collapseRIS">
          @else
          <ul class="sidenav-second-level collapse" id="collapseRIS">
          @endif
            @if(hasAccess(46))
              <li><a href="{{ url('/ris/preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(47))
              <li><a href="{{ url('/ris/approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(48))
              <li><a href="{{ url('/ris/issuance_inbox') }}">Issuance Inbox</a></li>
            @endif
            @if(hasAccess(48))
              <li><a href="{{ url('/ris/receive_inbox') }}">Receive Inbox</a></li>
            @endif
            @if(hasAccess(49))
              <li><a href="{{ url('/ris/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(84) || hasAccess(85) || hasAccess(86) || hasAccess(87) || hasAccess(88))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="RPI">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRPI">
            <i class="fa fa-fw fa-magic"></i>
            <span class="nav-link-text">RPI</span>
          </a>
          @if(Request::is('property/rpi*'))
          <ul class="sidenav-second-level collapsed" id="collapseRPI">
          @else
          <ul class="sidenav-second-level collapse" id="collapseRPI">
          @endif
            @if(hasAccess(84))
              <li><a href="{{ url('/property/rpi_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(85))
              <li><a href="{{ url('/property/rpi_inspection_inbox') }}">Inspection Inbox</a></li>
            @endif
            @if(hasAccess(86))
              <li><a href="{{ url('/property/rpi_evaluation_inbox') }}">Evaluation Inbox</a></li>
            @endif
            @if(hasAccess(87))
              <li><a href="{{ url('/property/rpi_receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(88))
              <li><a href="{{ url('/property/rpi_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(89) || hasAccess(90) || hasAccess(91) || hasAccess(92) || hasAccess(141))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PRISUP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePRISUP">
            <i class="fa fa-fw fa-mail-reply"></i>
            <span class="nav-link-text">PRISUP</span>
          </a>
          @if(Request::is('property/prisup*'))
          <ul class="sidenav-second-level collapsed" id="collapsePRISUP">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePRISUP">
          @endif
            @if(hasAccess(89))
              <li><a href="{{ url('/property/prisup_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(90))
              <li><a href="{{ url('/property/prisup_approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(91))
              <li><a href="{{ url('/property/prisup_inspection_inbox') }}">Inspection Inbox</a></li>
            @endif
            @if(hasAccess(92))
              <li><a href="{{ url('/property/prisup_receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(141))
              <li><a href="{{ url('/property/prisup_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(94) || hasAccess(95) || hasAccess(96) || hasAccess(97) || hasAccess(98) || hasAccess(140))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="IIRUP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseIIRUP">
            <i class="fa fa-fw fa-mail-reply"></i>
            <span class="nav-link-text">IIRUP</span>
          </a>
          @if(Request::is('property/iirup*'))
          <ul class="sidenav-second-level collapsed" id="collapseIIRUP">
          @else
          <ul class="sidenav-second-level collapse" id="collapseIIRUP">
          @endif
            @if(hasAccess(94))
              <li><a href="{{ url('/property/iirup_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(95))
              <li><a href="{{ url('/property/iirup_approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(96))
              <li><a href="{{ url('/property/iirup_inspection_inbox') }}">Inspection Inbox</a></li>
            @endif
            @if(hasAccess(97))
              <li><a href="{{ url('/property/iirup_certifying_inbox') }}">Witness Inbox</a></li>
            @endif
            @if(hasAccess(140))
              <li><a href="{{ url('/property/iirup_receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(98))
              <li><a href="{{ url('/property/iirup_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(99) || hasAccess(100) || hasAccess(101) || hasAccess(102) || hasAccess(103) || hasAccess(104))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="PTR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePTR">
            <i class="fa fa-fw fa-mail-forward"></i>
            <span class="nav-link-text">PTR</span>
          </a>
          @if(Request::is('property/ptr*'))
          <ul class="sidenav-second-level collapsed" id="collapsePTR">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePTR">
          @endif
            @if(hasAccess(99))
              <li><a href="{{ url('/property/ptr_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(100))
              <li><a href="{{ url('/property/ptr_approval_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(101))
              <li><a href="{{ url('/property/ptr_issuance_inbox') }}">Issuance Inbox</a></li>
            @endif
            @if(hasAccess(102))
              <li><a href="{{ url('/property/ptr_accept_inbox') }}">Acceptance Inbox</a></li>
            @endif
            @if(hasAccess(103))
              <li><a href="{{ url('/property/ptr_receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(104))
              <li><a href="{{ url('/property/ptr_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(105) || hasAccess(106) || hasAccess(107) || hasAccess(108) || hasAccess(109))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="RLSDDP">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRLSDDP">
            <i class="fa fa-fw fa-gavel"></i>
            <span class="nav-link-text">RLSDDP</span>
          </a>
          @if(Request::is('property/rlsddp*'))
          <ul class="sidenav-second-level collapsed" id="collapseRLSDDP">
          @else
          <ul class="sidenav-second-level collapse" id="collapseRLSDDP">
          @endif
            @if(hasAccess(105))
              <li><a href="{{ url('/property/rlsddp_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(106))
              <li><a href="{{ url('/property/rlsddp_approve_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(107))
              <li><a href="{{ url('/property/rlsddp_certify_inbox') }}">Certifying Inbox</a></li>
            @endif
            @if(hasAccess(108))
              <li><a href="{{ url('/property/rlsddp_receive_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(109))
              <li><a href="{{ url('/property/rlsddp_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(110) || hasAccess(111) || hasAccess(112) || hasAccess(113) || hasAccess(114) || hasAccess(115) || hasAccess(116))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="WMR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseWMR">
            <i class="fa fa-fw fa-recycle"></i>
            <span class="nav-link-text">WMR</span>
          </a>
          @if(Request::is('property/wmr*'))
          <ul class="sidenav-second-level collapsed" id="collapseWMR">
          @else
          <ul class="sidenav-second-level collapse" id="collapseWMR">
          @endif
            @if(hasAccess(110))
              <li><a href="{{ url('/property/wmr_preparation_inbox') }}">Preparation Inbox</a></li>
            @endif
            @if(hasAccess(111))
              <li><a href="{{ url('/property/wmr_certify_inbox') }}">Certifying Inbox</a></li>
            @endif
            @if(hasAccess(112))
              <li><a href="{{ url('/property/wmr_approve_inbox') }}">Approval Inbox</a></li>
            @endif
            @if(hasAccess(113))
              <li><a href="{{ url('/property/wmr_inspect_inbox') }}">Inspection Inbox</a></li>
            @endif
            @if(hasAccess(114))
              <li><a href="{{ url('/property/wmr_witness_inbox') }}">Witness Inbox</a></li>
            @endif
            @if(hasAccess(115))
              <li><a href="{{ url('/property/wmr_receiving_inbox') }}">Receiving Inbox</a></li>
            @endif
            @if(hasAccess(116))
              <li><a href="{{ url('/property/wmr_query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(63))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Stock Card">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSC">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text">Stock Card</span>
          </a>
          @if(Request::is('stockcard*'))
          <ul class="sidenav-second-level collapsed" id="collapseSC">
          @else
          <ul class="sidenav-second-level collapse" id="collapseSC">
          @endif
            @if(hasAccess(63))
              <li><a href="{{ url('/stockcard/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(117))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Property Card">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePC">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text">Property Card</span>
          </a>
          @if(Request::is('property/query*'))
          <ul class="sidenav-second-level collapsed" id="collapsePC">
          @else
          <ul class="sidenav-second-level collapse" id="collapsePC">
          @endif
            @if(hasAccess(117))
              <li><a href="{{ url('/property/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(152))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="SPC Low">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSPC">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text">SPC Low</span>
          </a>
          @if(Request::is('spc*') && !Request::is('spc2*'))
          <ul class="sidenav-second-level collapsed" id="collapseSPC">
          @else
          <ul class="sidenav-second-level collapse" id="collapseSPC">
          @endif
            @if(hasAccess(152))
              <li><a href="{{ url('/spc/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(152))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="SPC High">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSPC2">
            <i class="fa fa-fw fa-book"></i>
            <span class="nav-link-text">SPC High</span>
          </a>
          @if(Request::is('spc2*'))
          <ul class="sidenav-second-level collapsed" id="collapseSPC2">
          @else
          <ul class="sidenav-second-level collapse" id="collapseSPC2">
          @endif
            @if(hasAccess(152))
              <li><a href="{{ url('/spc2/query') }}">Query</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(hasAccess(8) || hasAccess(9) || hasAccess(28) || hasAccess(55) || hasAccess(51) || hasAccess(52) || hasAccess(53) || hasAccess(64) || hasAccess(65) || hasAccess(67) || hasAccess(71))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Reports">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseReports">
            <i class="fa fa-fw fa-bar-chart"></i>
            <span class="nav-link-text">Reports</span>
          </a>
          @if(Request::is('reports*') || Request::is('app*') || Request::is('rpcppe*') || Request::is('stockcard/rpci*') || Request::is('myPSISController*'))
          <ul class="sidenav-second-level collapsed" id="collapseReports">
          @else
          <ul class="sidenav-second-level collapse" id="collapseReports">
          @endif

            @if(hasAccess(8) || hasAccess(9))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseAPP" aria-expanded="true">APP</a>
                <ul class="sidenav-third-level collapse" id="collapseAPP">
                  @if(hasAccess(8))
                    <li><a href="{{ url('/app/gppb_query') }}">GPPB</a></li>
                  @endif
                  @if(hasAccess(9))
                    <li><a href="{{ url('/app/dbm_query') }}">CUS</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(28))
              <li><a href="{{ url('/ppmp/utilization') }}">PPMP Utilization</a></li>
            @endif

            @if(hasAccess(66))
              <li><a href="{{ url('/reports/procurement_monitoring') }}">PPMP Utilization Per Details</a></li>
            @endif

            @if(hasAccess(68))
              <li><a href="{{ url('/ppmp/utilization_per_object') }}">PPMP Utilization Per Object</a></li>
            @endif

            @if(hasAccess(55))
              <li><a href="{{ url('/ris/rsmi_query') }}">RSMI</a></li>
            @endif

            @if(hasAccess(50) || hasAccess(51) || hasAccess(52) || hasAccess(53))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseRPCI" aria-expanded="true">RPCI</a>
                @if(Request::is('stockcard/rpci*'))
                <ul class="sidenav-third-level collapsed" id="collapseRPCI">
                @else
                <ul class="sidenav-third-level collapse" id="collapseRPCI">
                @endif
                  @if(hasAccess(51))
                    <li><a href="{{ url('/stockcard/rpci_preparation_inbox') }}">Preparation Inbox</a></li>
                  @endif
                  @if(hasAccess(52))
                    <li><a href="{{ url('/stockcard/rpci_approval_inbox') }}">Approval Inbox</a></li>
                  @endif
                  @if(hasAccess(53))
                    <li><a href="{{ url('/stockcard/rpci_query') }}">Query</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(114) || hasAccess(115) || hasAccess(116))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseRPCPPE" aria-expanded="true">RPCPPE</a>
                <ul class="sidenav-third-level collapse" id="collapseRPCPPE">
                  @if(hasAccess(114))
                    <li><a href="{{ url('/rpcppe/preparation_inbox') }}">Preparation Inbox</a></li>
                  @endif
                  @if(hasAccess(115))
                    <li><a href="{{ url('/rpcppe/approval_inbox') }}">Approval Inbox</a></li>
                  @endif
                  @if(hasAccess(116))
                    <li><a href="{{ url('/rpcppe/query') }}">Query</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(71) || hasAccess(139))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseReportPR" aria-expanded="true">PR</a>
                <ul class="sidenav-third-level collapse" id="collapseReportPR">
                  @if(hasAccess(71))
                    <li><a href="{{ url('/reports/pr_monitoring') }}">PR Monitoring Report</a></li>
                  @endif
                  @if(hasAccess(139))
                    <li><a href="{{ url('/reports/pmr') }}">PMR</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(71) || hasAccess(139))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseReportAbstract" aria-expanded="true">Abstract</a>
                <ul class="sidenav-third-level collapse" id="collapseReportAbstract">
                  @if(hasAccess(71))
                    <li><a href="{{ url('/reports/abstract_monitoring') }}">Abstract Monitoring</a></li>
                  @endif
                  @if(hasAccess(71))
                    <li><a href="{{ url('/reports/abstract_monitoring_per_details') }}">Abstract Per Details</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(64))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseReportPO" aria-expanded="true">PO</a>
                <ul class="sidenav-third-level collapse" id="collapseReportPO">
                  <li><a href="{{ url('/reports/po') }}">Extract PO Details</a></li>
                  <li><a href="{{ url('/reports/po_monitoring') }}">PO Monitoring Report</a></li>
                </ul>
              </li>
            @endif

            @if(hasAccess(65))
              <li><a href="{{ url('/reports/iar') }}">IAR</a></li>
            @endif

            @if(hasAccess(148))
              <li><a href="{{ url('/reports/ris') }}">RIS</a></li>
            @endif

            @if(hasAccess(149))
              <li><a href="{{ url('/reports/ics') }}">ICS</a></li>
            @endif

            @if(hasAccess(150))
              <li><a href="{{ url('/reports/par') }}">PAR</a></li>
            @endif

            @if(hasAccess(67))
              <li><a href="{{ url('/reports/stock_balance_inquiry') }}">Stock Balance Inquiry</a></li>
            @endif

            @if(hasAccess(71))
              <li><a href="{{ url('/myPSISController/history_log') }}">History Log</a></li>
            @endif

          </ul>
        </li>
      @endif

      @if(hasAccess(10) || hasAccess(11) || hasAccess(12) || hasAccess(54) || hasAccess(119) || hasAccess(120))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Maintenance">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMaintenance">
            <i class="fa fa-wrench"></i>
            <span class="nav-link-text">Maintenance</span>
          </a>
          @if(Request::is('items*') || Request::is('supplier*') || Request::is('parameters*') || Request::is('stockcard/stock*') || Request::is('property/property*') || Request::is('semiexpendable*'))
          <ul class="sidenav-second-level collapsed" id="collapseMaintenance">
          @else
          <ul class="sidenav-second-level collapse" id="collapseMaintenance">
          @endif

            @if(hasAccess(10) || hasAccess(11) || hasAccess(12) || hasAccess(54) || hasAccess(119) || hasAccess(120))
              <li>
                <a class="nav-link-collapse" data-toggle="collapse" href="#collapseItems" aria-expanded="true">Items</a>
                <ul class="sidenav-third-level collapse" id="collapseItems">
                  @if(hasAccess(10))
                    <li><a href="{{ url('/items/major_articles') }}">Major Articles</a></li>
                  @endif
                  @if(hasAccess(12))
                    <li><a href="{{ url('/items/main_articles') }}">Main Articles</a></li>
                  @endif
                  @if(hasAccess(118))
                    <li><a href="{{ url('/stockcard/stock_items') }}">Stock Items</a></li>
                  @endif
                  @if(hasAccess(119))
                    <li><a href="{{ url('/property/property_items') }}">Property Items</a></li>
                  @endif
                  @if(hasAccess(120))
                    <li><a href="{{ url('/semiexpendable/semiexpendable_items') }}">Semi-Expandable Items</a></li>
                  @endif
                </ul>
              </li>
            @endif

            @if(hasAccess(54))
              <li><a href="{{ url('/supplier') }}">Suppliers</a></li>
            @endif

          </ul>
        </li>
      @endif

      @if(hasAccess(13) || hasAccess(14) || hasAccess(15))
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Security">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSecurity">
            <i class="fa fa-lock"></i>
            <span class="nav-link-text">Security</span>
          </a>
          @if(Request::is('security*'))
          <ul class="sidenav-second-level collapsed" id="collapseSecurity">
          @else
          <ul class="sidenav-second-level collapse" id="collapseSecurity">
          @endif
            @if(hasAccess(13))
              <li><a href="{{ url('/security/user_access') }}">Access</a></li>
            @endif
            @if(hasAccess(14))
              <li><a href="{{ url('/security/user_groups') }}">Groups</a></li>
            @endif
            @if(hasAccess(15))
              <li><a href="{{ url('/security/users') }}">Users</a></li>
            @endif
            @if(hasAccess(147))
              <li><a href="{{ url('/security/assign_project_code') }}">Assign Project Code</a></li>
            @endif
            @if(hasAccess(15))
              <li><a href="{{ url('/security/spbi_common_preparers') }}">SPBI Common Prep</a></li>
            @endif
          </ul>
        </li>
      @endif

      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Help">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseHelp">
          <i class="fa fa-ticket"></i>
          <span class="nav-link-text">Help</span>
        </a>
        @if(Request::is('datafix*'))
        <ul class="sidenav-second-level collapsed" id="collapseHelp">
        @else
        <ul class="sidenav-second-level collapse" id="collapseHelp">
        @endif
          <li><a href="{{ url('/datafix/viewlist') }}">Request for Data Fix</a></li>
        </ul>
      </li>

    </ul>
    <ul class="navbar-nav sidenav-toggler">
      <li class="nav-item">
        <a class="nav-link text-center" id="sidenavToggler">
          <i class="fa fa-fw fa-angle-left"></i>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto nav-top-right">
      <li class="nav-item">
        <div class="form-group row">
          <label for="" class="col-sm-12 col-form-label">
            @if(hasAccess(69))
              <a href="{{ url('/krmp/admin') }}" style="text-decoration: none; color: white;">
            @endif
            Hi, {{ session('BaseEmployeeName', 'Guest') }}
            @if(hasAccess(69))
              </a>
            @endif
          </label>
        </div>
      </li>
      <li class="nav-item" style="width: 400px;">
        <div class="form-group row">
          <label for="" class="col-sm-4 col-form-label">Logged in as:</label>
          <div class="col-sm-8">
            <select class="form-control" id="switch-account">
              @foreach(session('SwitchAccounts', []) as $row)
                <option value="{{ $row['FromEmployeeID'] }}" {{ session('EmployeeID') == $row['FromEmployeeID'] ? 'selected' : '' }}>{{ $row['EmployeeName'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </li>

      <li class="nav-item" style="width: 300px;">
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Station:</label>
          <div class="col-sm-9">
            <select class="form-control" id="sess-station">
            </select>
          </div>
        </div>
      </li>

      <li class="nav-item" style="width: 300px;">
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Fund:</label>
          <div class="col-sm-9">
            <select class="form-control" id="fund-class">
              <option value="CORPORATE" {{ session('FundClass') == 'CORPORATE' ? 'selected' : '' }}>CORPORATE</option>
              <option value="BDD" {{ session('FundClass') == 'BDD' ? 'selected' : '' }}>BDD</option>
              <option value="TRUST" {{ session('FundClass') == 'TRUST' ? 'selected' : '' }}>TRUST</option>
              <option value="RCEP" {{ session('FundClass') == 'RCEP' ? 'selected' : '' }}>RCEF</option>
            </select>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" id="logout" href="{{ url('/login') }}">
          <i class="fa fa-fw fa-sign-out"></i>
          Logout</a>
      </li>
    </ul>
  </div>
</nav>

<script type="text/javascript">
  $(document).ready(function() {
    $('#switch-account option').remove();
    var htm = '';
    var sel = '';
    var accs = JSON.parse(localStorage.getItem('SwitchAccounts') || '[]');
    var empid = '{{ session('EmployeeID', '') }}';
    for (var i in accs) {
      if (accs[i].FromEmployeeID == empid)
        sel = 'selected';
      else
        sel = '';
      htm += '<option value="' + accs[i].FromEmployeeID + '" ' + sel + '>' + accs[i].EmployeeName + '</option>';
    }
    $('#switch-account').html(htm);

    htm = '';
    sel = '';
    var stations = JSON.parse(localStorage.getItem('Station') || '[]');
    var station = '{{ session('Station', '') }}';
    for (var i in stations) {
      if (stations[i] == station)
        sel = 'selected';
      else
        sel = '';
      htm += '<option value="' + stations[i] + '" ' + sel + '>' + stations[i] + '</option>';
    }
    $('#sess-station').html(htm);

    htm = '';
    sel = '';
    var stationFundClass = JSON.parse(localStorage.getItem('StationFundClass') || '{}');
    var fundclass = '{{ session('FundClass', '') }}';
    var curStation = station;
    if (stationFundClass[curStation]) {
      for (var i in stationFundClass[curStation]) {
        if (stationFundClass[curStation][i] == fundclass)
          sel = 'selected';
        else
          sel = '';
        htm += '<option value="' + stationFundClass[curStation][i] + '" ' + sel + '>' + stationFundClass[curStation][i] + '</option>';
      }
      $('#fund-class').html(htm);
    }

    // Fetch PPMP inbox badge counts
    function fetchBadgeCounts() {
      $.get(base_url + 'ppmp/count_preparation_inbox', function(res) {
        var count = (typeof res === 'object') ? res.count : 0;
        $('#nav-prep-inbox').text(count || '');
      });
      $.get(base_url + 'ppmp/count_evaluation_inbox', function(res) {
        var count = (typeof res === 'object') ? res.count : 0;
        $('#nav-eval-inbox').text(count || '');
      });
      $.get(base_url + 'ppmp/count_approval_inbox', function(res) {
        var count = (typeof res === 'object') ? res.count : 0;
        $('#nav-approv-inbox').text(count || '');
      });
      $.get(base_url + 'ppmp/count_certification_inbox', function(res) {
        var count = (typeof res === 'object') ? res.count : 0;
        $('#nav-certify-inbox').text(count || '');
      });
      $.get(base_url + 'ppmp/count_receiving_inbox', function(res) {
        var count = (typeof res === 'object') ? res.count : 0;
        $('#nav-receiving-inbox').text(count || '');
      });
    }
    fetchBadgeCounts();
  });
</script>

<style type="text/css">
  .content-wrapper.py-3 {
    min-height: -webkit-fill-available;
  }
</style>
<div class="content-wrapper py-3" id="page-content">
  <div class="container-fluid">
