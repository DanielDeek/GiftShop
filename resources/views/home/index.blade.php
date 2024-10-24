<!DOCTYPE html>
<html>

<head>
  @include('home.head')
</head>

<body>
  <div class="hero_area">

    @include('home.header')
  
    
  </div>
  
  <div class="container">
    @yield('content')
  </div>
  
 

  <br><br><br>

  @include('home.footer')
</body>

</html>
