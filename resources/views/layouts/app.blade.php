<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ asset('login_assets/assets/img/icon2.png')}}">

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

  <title>Meal Allowance</title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('login_assets/assets/css/font-awesome.css') }}">

  <!-- CSS Files -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/light-bootstrap-dashboard.css?v=2.0.0 ') }}" rel="stylesheet" />

  <link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet">
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />

  <style>
    body {
      font-family: 'Lato';
    }

    .fa-btn {
      margin-right: 6px;
    }
    .navbar{
      max-height: none;
    }
    button:hover{
      cursor: pointer;
    }
    span.notification{
      background-color: #FB404B;
      text-align: center;
      border-radius: 10px;
      min-width: 18px;
      padding: 0 5px;
      height: 18px;
      font-size: 12px;
      color: #FFFFFF;
      font-weight: bold;
      line-height: 18px;
    }
    .main-loader{
      width: 100%;
      height: 100%;   
    }
    .loader {
      display: none;
      justify-content: center;
      align-items: center;
      height: 100%;
      width: 100%;
      background-color: rgba(0,0,0,0.5);
      position: fixed;
      z-index: 9999;
      padding: 50vh 50vw;
    }

    .stick1, .stick2, .stick3, .stick4 {
      width: 50px;
      height: 3px;
      -webkit-animation: sk-cubemove 1.8s infinite linear;
      animation: sk-cubemove 1.8s infinite linear;
    }

    .stick1 {
      background-color: #e74c3c;
    }

    .stick2 {
      background-color: #3498db;
      -webkit-animation-delay: -0.9s;
      animation-delay: -0.9s;
    }

    .stick3 {
      background-color: #2ecc71;
      -webkit-animation-delay: -1.35s;
      animation-delay: -1.35s;
    }

    .stick4 {
      background-color: #f1c40f;
      -webkit-animation-delay: -.45s;
      animation-delay: -.45s;
    }

    @-webkit-keyframes sk-cubemove {
      25% { -webkit-transform: translateX(42px) rotate(-90deg) scale(0.9) }
      50% { -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg) }
      75% { -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.9) }
      100% { -webkit-transform: rotate(-360deg) }
    }

    @keyframes sk-cubemove {
      25% { 
        transform: translateX(42px) rotate(-90deg) scale(0.9);
        -webkit-transform: translateX(42px) rotate(-90deg) scale(0.9);
        } 50% { 
          transform: translateX(42px) translateY(42px) rotate(-179deg);
          -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
          } 50.1% { 
            transform: translateX(42px) translateY(42px) rotate(-180deg);
            -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
            } 75% { 
              transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.9);
              -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.9);
              } 100% { 
                transform: rotate(-360deg);
                -webkit-transform: rotate(-360deg);
              }
            }
          </style>
          @yield('customcss')
        </head>
        <body id="app-layout">
          <div class="main-loader">
            <div class="loader">
              <div class="stick1"></div>
              <div class="stick2"></div>
              <div class="stick3"></div>
              <div class="stick4"></div>
            </div>
          </div>
          <div class="wrapper">
            @include('layouts.nav')
          </div>

          <!-- Change Password Modal -->
          <div class="modal fade change_pass" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="save_password">
                  {{ csrf_field() }}
                  <div class="modal-header">
                    <h3 class="modal-title">Change Password</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12 px-1">
                        <div class="form-group">
                          <label>Current Password</label>
                          <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                          <input name="curr_pass" type="password" class="form-control" required placeholder="Password">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 px-1">
                        <div class="form-group">
                          <label>New Password</label>
                          <input name="new_pass" type="password" class="form-control" required placeholder="Password">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 px-1">
                        <div class="form-group">
                          <label>Confirm Password</label>
                          <input name="con_pass" type="password" class="form-control" required placeholder="Password">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary save_pass">Change Password</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End modal -->




          <!--   Core JS Files   -->
          <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
          <script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
          <script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
          <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->

          <script src="{{ asset('assets/js/plugins/bootstrap-switch.js') }}"></script>
          <!--  Chartist Plugin  -->
          <script src="{{ asset('assets/js/plugins/chartist.min.js') }}"></script>
          <!--  Notifications Plugin    -->
          <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
          <!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
          <script src="{{ asset('assets/js/light-bootstrap-dashboard.js?v=2.0.0 ') }}"></script>
          <!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->

          <!-- Datatable -->
          <script src="{{ asset('assets/js/jquery-datatables.js') }}"></script>
          <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

          <script src="{{ asset('assets/js/demo.js') }}"></script>

          <script type="text/javascript">
            window.onload = function(){
              $.ajax({
                type:"GET",
                url:"{{route('notif')}}",
                beforeSend:function() {
                  $('#notif').hide();
                },
                success: function(response){
                  $('#notif').text(response);
                }
                ,
                complete:function(){
                  $('#notif').show();
                }
              });
            }

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $('.save_pass').on('click',function() {
              event.preventDefault();
              var data = $('#save_password').serialize();
              $.ajax({
                type:'POST',
                url:'{{  route('change-pass') }}',
                data:data,
                success:function(data) {
                  $('#save_password input').css('border-color','#E3E3E3');
                  $.each(data.message, function(index,value){
                    if(data.error){
                      $('#save_password input[name='+index+']').css('border-color','red');
                      toastr.error(value, 'Password');
                    }
                    else if(data.error_pass){
                      $('#save_password input[name="curr_pass"]').css('border-color','red');
                      toastr.error(value, 'Password');
                    }
                    else if(data.error_new){
                      $('#save_password input[name="new_pass"]').css('border-color','red');
                      $('#save_password input[name="con_pass"]').css('border-color','red');
                      toastr.error(value, 'Password');
                    }
                    else{
                     toastr.success(value, 'Password');
                     window.setTimeout(function(){location.reload()},2000)
                   }
                 });
                }
              });
            })

            toastr.options = {
              "closeButton": true,
              "debug": true,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "1000",
              "hideDuration": "1500",
              "timeOut": "2000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
          </script>

          @yield('customjs')
        </body>
        </html>
