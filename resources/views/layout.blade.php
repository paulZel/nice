<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- favicon icon -->

    <title>France</title>

    <!-- common css -->
    <link rel="stylesheet" href="/css/front.css">

    <!-- HTML5 shim and Respond.js IE9 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.js"></script>
    <![endif]-->

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/favicon.png">

</head>

<body>

<nav class="navbar main-menu navbar-default">
    <div class="container">
        <div class="menu-content">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>


            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav text-uppercase">
                    <li><a href="/"><h2>France</h2></a></li>
                </ul>


                <ul class="nav navbar-nav text-uppercase pull-right">
                    @if(Auth::check())
                        <li><a href="/profile">My profile</a></li>
                        <li><a href="/logout">Logout</a></li>
                        @if(Auth::user()->is_admin)
                            <li><a href="{{route('dashboard.show')}}">Admin</a></li>
                        @endif
                    @else
                        <li><a href="/">Home</a></li>
                        <li><a href="/register">Register</a></li>
                        <li><a href="/login">Login</a></li>
                    @endif
                </ul>

            </div>
            <!-- /.navbar-collapse -->


            <div class="show-search">
                <form role="search" method="get" id="searchform" action="#">
                    <div>
                        <input type="text" placeholder="Search and hit enter..." name="s" id="s">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>
<style>
    .nav h2 {
        background: #F4F7EE;
        padding: 50px 20px;
        text-align: center;
        font-family: 'Merriweather', serif;
        position: relative;
        color: #C44737;
        font-size: 50px;
        font-weight: normal;
        padding: 8px 20px 7px 20px;
        border-top: 4px solid;
        border-left: 4px solid;
        display: inline-block;
        margin: 0;
        line-height: 1;
    }
    .nav h2:before {
        content: "";
        position: absolute;
        width: 28px;
        height: 28px;
        top: -28px;
        left: -28px;
        border: 4px solid #C44737;
        box-sizing: border-box;
    }
    @media (max-width: 450px) {
        .nav h2 {font-size: 36px;}
        .nav h2:before {
            width: 20px;
            height: 20px;
            top: -20px;
            left: -20px;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(session('status'))
                <div class="alert alert-info">
                    {{session('status')}}
                </div>
            @endif
        </div>
    </div>
</div>

@yield('content')



<footer class="footer-widget-section">
    <div class="footer-copy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">&copy; 2022 <a href="#">Blog, </a> Designed with <i
                            class="fa fa-heart"></i> by <a href="#">Paul</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- js files -->
<script src="/js/front.js"></script>
</body>
</html>
