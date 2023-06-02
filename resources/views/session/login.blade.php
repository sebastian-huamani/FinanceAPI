@extends('layout.app')

@section('body')
    <div class="flex h-80vh items-center justify-center ">
        <form class="mx-auto w-full sm:w-3/5 md:w-2/5" method="POST">
            @csrf
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email:</label>
                <input type="email" id="email" name="email" class="input-form" placeholder="name@gmail.com"
                    value="{{ old('email') }}" autofocus required>
                <p class="text-sm text-red-600">
                    @error('email')
                        *{{ $message }}
                    @enderror
                </p>
            </div>
            <div class="mb-6">
                <label for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña:</label>
                <input type="password" id="password" name="password" class="input-form">
                <p class="text-sm text-red-600" required>
                    @error('password')
                        *{{ $message }}
                    @enderror
                </p>

            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" name="remember" class="checkbox-form">
                </div>
                <label for="terms" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recordar
                    credenciales</label>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>

            <p class="text-sm text-red-600 mt-5">
                @error('credencials')
                    *{{ $message }}
                @enderror
            </p>
        </form>
    </div>
@endsection
