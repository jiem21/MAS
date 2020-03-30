<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('login_assets/assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{ asset('login_assets/assets/img/icon2.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    Meal Allowance
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="{{ asset('login_assets/assets/css/font-awesome.css') }}">
  <!-- CSS Files -->
  <link href="{{ asset('login_assets/assets/css/material-kit.css?v=2.0.6')}}" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('login_assets/assets/demo/demo.css')}}" rel="stylesheet" />

  <style type="text/css">
    .card .card-header-primary{
      background: linear-gradient(60deg, #82B6DF, #0078D7);
      box-shadow: 0 5px 20px 0px rgba(0, 0, 0, 0.2), 0 13px 24px -11px rgba(0, 120, 215, 0.7);
    }
    a{
      color: #82B6DF;
    }
    .btn.btn-primary.btn-link{
      color: #82B6DF;
    }
    .btn.btn-primary.btn-link:hover, a:hover{
      background-color: transparent;
      color: #0078D7;
    }
    .form-control, .is-focused .form-control{
          background-image: linear-gradient(to top, #0078D7 2px, rgba(156, 39, 176, 0) 2px), linear-gradient(to top, #d2d2d2 1px, rgba(210, 210, 210, 0) 1px);
    }
    .card{
      opacity: 0.8;
    }
  </style>
</head>

<body class="login-page sidebar-collapse">
  <nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="{{url('/login2')}}">
          Meal Allowance </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <div class="copyright float-right">
              &copy;
              <script>
                document.write(new Date().getFullYear())
              </script>, made with <i class="material-icons">favorite</i> by
              <a href="http://192.168.53.248:85/ipi/it/index.php?option=com_content&view=article&id=96&Itemid=107" target="_blank">IT SysDev</a> for a better web.
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="page-header header-filter" style="background-image: url('{{ asset('login_assets/assets/img/bg9.jpg')}}'); background-size: cover; background-position: top center;">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
          <div class="card card-login">
            <form class="form" id="form-login" role="form">
              {{ csrf_field() }}
              <div class="card-header card-header-primary text-center">
                <h4 class="card-title">{{ isset($title) ? $title : 'Login' }}</h4>
              </div>
              <div class="card-body">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                  </div>
                  <input type="text" name="user_id" class="form-control" placeholder="ID Number">
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="fas fa-user-lock"></i>
                    </span>
                  </div>
                  <input type="password" name="password" class="form-control" placeholder="Password...">
                </div>
              </div>
              <div class="footer text-center">
                <button class="btn btn-primary btn-link btn-wd btn-lg login-btn">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<!--     <footer class="footer">
      <div class="container">
        <div class="copyright float-right">
          &copy;
          <script>
            document.write(new Date().getFullYear())
          </script>, made with <i class="material-icons">favorite</i> by
          <a href="https://www.creative-tim.com" target="_blank">IT SysDev</a> for a better web.
        </div>
      </div>
    </footer> -->
  </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('login_assets/assets/js/core/jquery.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('login_assets/assets/js/core/popper.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('login_assets/assets/js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('login_assets/assets/js/plugins/moment.min.js') }}"></script>
  <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
  <script src="{{ asset('login_assets/assets/js/plugins/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="{{ asset('login_assets/assets/js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
  <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('login_assets/assets/js/material-kit.js?v=2.0.6') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

  <script type="text/javascript">

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $('.login-btn').click(function(event) {
      event.preventDefault();
      var data = $('#form-login').serialize();
      $.ajax({
        type: 'POST',
        url:"{{route('validate')}}",
        data:data,
        dataType:'json',
        success:function(response) {
          
            if(response.error){
            $.each(response.message, function(index,value){
             toastr.error(value, 'Login');
              });
           }else{
            toastr.success(response.message, 'Login');
             setTimeout(function(){window.location.href="{{route('dashboard')}}"} , 1500);
           }
        
        }
      });
    })
  </script>
</body>

</html>
