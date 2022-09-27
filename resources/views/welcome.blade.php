<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>iSYS APP</title>
        <link rel="stylesheet" href="{{mix('/css/app.css')}}">
        <script src="https://kit.fontawesome.com/a2bd4cae4b.js" crossorigin="anonymous"></script>
    </head>
    <body>
       
       <div id="app">
        <product-component></product-component>
       </div>
       <script src="{{mix('/js/app.js')}}"></script>
    </body>
</html>
