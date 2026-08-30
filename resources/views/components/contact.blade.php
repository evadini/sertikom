<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Team Web Developer</title>
</head>
<body class="bg-blue-900 text-white min-h-screen p-8">

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-12">
            <div class="flex gap-4">
                <div class="w-16 h-16 bg-white rounded-lg"></div> <div class="w-16 h-16 bg-white rounded-full"></div> </div>
            <div class="w-32 h-16 bg-white rounded-lg"></div> </div>

        <div class="flex flex-col md:flex-row items-center gap-8 mb-16">
            <h1 class="text-3xl font-bold leading-tight flex-1">
                UNTUK INFORMASI LAINNYA <br> SILAHKAN HUBUNGI TEAM <br> WEB DEVELOPER
            </h1>
            <div class="w-48 h-48 bg-white rounded-lg flex-shrink-0"></div> </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for ($i = 0; $i < 3; $i++)
            <div class="flex flex-col items-center gap-4">
                <div class="w-40 h-40 bg-gray-400 rounded-full"></div> <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-green-500 rounded-full"></div> <span>0878-4008-7034</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-pink-500 rounded-full"></div> <span>@username</span>
                </div>
            </div>
            @endfor
        </div>
    </div>

</body>
</html>
