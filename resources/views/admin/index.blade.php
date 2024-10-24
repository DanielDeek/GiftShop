<!DOCTYPE html>
<html>

<head>
  @include('admin.head')
</head>

<body>
  <div class="hero_area">
    @include('admin.header')
  </div>
  
  <div class="container">
    @yield('content')
  </div>
</body>
</html>
