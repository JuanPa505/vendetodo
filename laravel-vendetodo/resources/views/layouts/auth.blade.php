<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vende Todo</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/d62c51cf16.js" crossorigin="anonymous"></script>
  </head>
  <body>
      @yield('style')
      <nav class="main-navbar">
          <div class="main-navbar-content">
              <img src="https://media.discordapp.net/attachments/1031018534416941168/1035346439989117049/logo.png?width=472&height=472" class="logo-img">
              <a href="{{route('products.index')}}" class="logo">Vende Todo</a>
          </div>
        </nav>
    <main>
          @yield('content')
    </main>
  </body>
</html>