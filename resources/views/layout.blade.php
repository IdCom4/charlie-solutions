<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Charlie - @yield('title')</title>
  
    <!-- imports CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600">
    @yield('css')

    <!-- imports JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    @yield('scripts')

  </head>
  <body class="pt-5 bg-dark">

    <!-- common navbar -->
    <nav class="fixed-top navbar navbar-expand-md navbar-dark bg-secondary">
      <a href="#" class="navbar-brand">Charlie Solutions</a>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
      </button>

      <div class="ml-5 collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav">
              <a href="/" class="nav-item nav-link {{ (request()->is('/')) ? 'active' : '' }}">Home</a>
              <a href="materials" class="nav-item nav-link {{ (request()->is('materials')) ? 'active' : '' }}">Matériels</a>
          </div>
      </div>
    </nav>
  
    <div class="mt-5 mb-5 container text-light">
      @yield('content')
    <div>
  </body>
</html>