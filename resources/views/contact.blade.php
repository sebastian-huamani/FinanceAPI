@extends('layout.app')

@section('body')
    <div class="h-80vh flex items-center justify-center">
        <form action="" method="POST" class="w-full sm:w-4/5 md:w-2/5">
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre:</label>
                <input type="password" id="password" class="input-form" placeholder="nombre" required>
            </div>
            <div class="mb-4">
                <label for="email-address-icon"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                    </div>
                    <input type="text" id="email-address-icon" class="pl-10 input-form" placeholder="nombre@gmail.com">
                </div>
            </div>
            <div class="mb-4">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mensaje:</label>
                <textarea id="message" rows="4" class="input-form" placeholder="Tu mensaje..."></textarea>
            </div>
            <div class="flex justify-center mt-7 w-full">
                <button type="submit"
                    class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear Cuenta</button>
            </div>
        </form>
    </div>
@endsection
