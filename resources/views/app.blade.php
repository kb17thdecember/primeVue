<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @routes
    @vite("Modules/" . current_module() . "/resources/js/app.js")
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
