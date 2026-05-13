@extends('layouts.layout')

@section('styles')
<style type="text/css">
    /*
     * Specific styles of signin component
     */
    /*
     * General styles
     */
    body,
    html {
        height: 100%;
        background-repeat: no-repeat;
        overflow: auto !important;
    }

    .card-container.card {
        max-width: 350px;
        padding: 40px 40px;
    }

    .btn {
        font-weight: 700;
        height: 36px;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
        cursor: default;
    }

    /*
     * Card component
     */
    .card {
        background-color: #F7F7F7;
        /* just in case there no content*/
        padding: 20px 25px 30px;
        margin: 0 auto 25px;
        margin-top: 50px;
        /* shadows and rounded borders */
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    }

    .profile-img-card {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }

    /*
     * Form styles
     */
    .profile-name-card {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 10px 0 0;
        min-height: 1em;
    }

    .reauth-email {
        display: block;
        color: #404040;
        line-height: 2;
        margin-bottom: 10px;
        font-size: 14px;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .form-signin #inputEmail,
    .form-signin #inputPassword {
        direction: ltr;
        height: 44px;
        font-size: 16px;
    }

    .form-signin input[type=email],
    .form-signin input[type=password],
    .form-signin input[type=text],
    .form-signin button,
    #fundclass {
        width: 100%;
        display: block;
        margin-bottom: 10px;
        z-index: 1;
        position: relative;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .form-signin .form-control:focus {
        border-color: rgb(104, 145, 162);
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
    }

    .btn.btn-signin {
        /*background-color: #4d90fe; */
        background-color: rgb(104, 145, 162);
        /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
        padding: 0px;
        font-weight: 700;
        font-size: 14px;
        height: 36px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        border: none;
        -o-transition: all 0.218s;
        -moz-transition: all 0.218s;
        -webkit-transition: all 0.218s;
        transition: all 0.218s;
    }

    .btn.btn-signin:hover,
    .btn.btn-signin:active,
    .btn.btn-signin:focus {
        background-color: #007bff;
    }

    .forgot-password {
        color: rgb(104, 145, 162);
    }

    .forgot-password:hover,
    .forgot-password:active,
    .forgot-password:focus {
        color: #007bff;
    }
</style>
@endsection

@section('content')
<!--
    you can substitue the span of reauth email for a input with the email and
    include the remember me checkbox
    -->
<div class="container">
    <div class="card card-container">
        <p id="profile-name" class="profile-name-card" style="font-size: 100px;">PSIS</p>
        <form class="form-signin">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" class="form-control" placeholder="Employee ID" name="EmployeeID" value="{{ $emp_idno }}" required autofocus>

            <select class="form-control" id="fundclass">
                <option value="">Select a fund class...</option>
                <option value="CORPORATE">CORPORATE</option>
                <option value="BDD">BDD</option>
                <option value="TRUST">TRUST</option>
                <option value="RCEP">RCEF</option>
            </select>


            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" id="btn-sign-in">Proceed</button>
        </form><!-- /form -->
    </div><!-- /card-container -->
</div><!-- /container -->
@endsection

@section('scripts')
<script type="text/javascript">
    localStorage.Station = JSON.stringify(@json($Station));
    localStorage.StationFundClass = JSON.stringify(@json($StationFundClass));

    $(document).ready(function() {
        if ($('input[name="EmployeeID"]').val() != '') $('input[name="EmployeeID"]').prop('disabled', true);
    });

    $('#btn-sign-in').on('click', function(e) {
        if ($('input[name="EmployeeID"]').val() == "") {
            alert("Please enter your employee ID.");
            return;
        }

        if ($('#fundclass').val() == "") {
            alert("Please select a fund class.");
            return;
        }

        e.preventDefault();
        $.ajax({
            type: "get",
            url: "{{ url('/login/get-user') }}",
            data: {
                EmployeeID: $('input[name="EmployeeID"]').val(),
                Station: $('input[name="Station"]').val(),
                fundclass: $('#fundclass').val()
            },
            success: function(response) {
                var res = (typeof response === 'string') ? JSON.parse(response) : response;

                if (res.invalid == 1) {
                    alert(res.message);
                    return;
                }

                localStorage.SwitchAccounts = JSON.stringify(res.SwitchAccounts);

                window.location.href = base_url + 'ppmp';

            },
            error: function(e) {
                alert("Something went wrong!");
            }
        });
    });
</script>
@endsection
