<div id="alert-component" class="fixed bottom-4 right-4 bg-white drop-shadow rounded flex">
    <span  {{ $attributes->class(['rounded-l w-2 ', 'bg-green-400' => $status == 'ok', 'bg-blue-600' => $status == 'info', 'bg-red-600' => $status == 'error' ])    }}></span>
    <div class="w-72 h-8 flex items-center pl-3 py-6">
        <p class="mr-6 text-2xl"><i class="fa-solid fa-circle-check"></i></p>
        <p>{{$message}}</p>
    </div>
</div>

<script>
    setTimeout(() => {
        $("#alert-component").fadeTo(2000, 0);
        setTimeout(() => {
            $('#alert-component').addClass('hidden');
        }, 2000);
    }, 5000);
</script>