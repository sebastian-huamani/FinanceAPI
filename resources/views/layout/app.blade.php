<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FinanceMeApi</title>
    <link rel="icon" type="image/png" sizes="16x16" href="finan16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="finan32.png">

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 relative">
    <x-navigation />
    <div class="px-8">
        @section('body')
        @show
    </div>

    <div class="mt-20">
        <x-footer/>
    </div>

</body>
</html>
