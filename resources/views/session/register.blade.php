@extends('layout.app')

@section('body')
    <div class="flex h-80vh items-center justify-center mt-5">
        <form class="mx-auto w-full sm:w-3/5 md:w-2/5" method="post">
            <p class="text-4xl font-bold mb-10">Registro :</p>
            @csrf
            <div class="grid grid-cols-2 gap-4 items-center">
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre:</label>
                    <input type="name" id="name" name="name" class="input-form" autofocus required>
                   <p class="text-sm text-red-600"> @error('name')
                        *{{ $message }}
                    @enderror</p>
                </div>
                <div class="mb-6">
                    <label for="lastname"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido:</label>
                    <input type="lastname" id="lastname" name="lastname" class="input-form" required>
                   <p class="text-sm text-red-600"> @error('lastname')
                        *{{ $message }}
                    @enderror</p>
                </div>
            </div>
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email:</label>
                <input type="email" id="email" name="email" class="input-form" placeholder="name@gmail.com" required>
               <p class="text-sm text-red-600"> @error('email')
                    *{{ $message }}
                @enderror</p>
            </div>
            <div class="mb-6">
                <label for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña:</label>
                <input type="password" id="password" name="password" class="input-form" required>
               <p class="text-sm text-red-600"> @error('password')
                    *{{ $message }}
                @enderror</p>
            </div>
            <div class="mb-6">
                <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repite tu
                    contraseña:</label>
                <input type="password" id="repeat-password" name="repeatpassword" class="input-form" required>
               <p class="text-sm text-red-600"> @error('repeatpassword')
                    *{{ $message }}
                @enderror</p>
            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" value="1" name="accept" class="checkbox-form" required>
                </div>
                <label for="terms" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Acepto los <a
                        href="#" class="text-blue-600 hover:underline dark:text-blue-500">terminos y
                        condiciones</a></label>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear
                Cuenta</button>
        </form>
    </div>
@endsection
