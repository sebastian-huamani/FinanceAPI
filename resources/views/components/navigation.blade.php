<div class="px-8 h-14 flex justify-between items-center font-semibold">
    <div class="flex items-center gap-4">
        <i class="fa-solid fa-cloud text-xl"></i>
        <p class="font-bold text-2xl"><a href="\">Logo</a></p>
    </div>

    <div class="w-2/5 mx-auto">
        <div class="flex justify-between items-center">
            <a href="#">Contact</a>
            <a href="#">Blog</a>

            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                class="focus:ring-blue-300 inline-flex items-center " type="button">Productos <svg
                    class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                    </path>
                </svg></button>
            <!-- Dropdown menu -->
            <div id="dropdown"
                class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign
                            out</a>
                    </li>
                </ul>
            </div>

            <a href="#">Home</a>
        </div>
    </div>

    @auth
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdownUser"
            class="inline-flex gap-2 bg-black items-center text-white rounded py-1 px-4 capitalize" type="button">  <i class="fa-solid fa-circle-user"></i> {{ Auth::user()->name }} <svg
            class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
            </path>
        </svg></button>
        <!-- Dropdown menu -->
        <div id="dropdownUser"
            class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                    <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Perfil</a>
                </li>
                <li>
                    <a class="dropdown-item block px-4 py-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    @else
        <div class="flex items-center gap-10">
            <div class="{{ request()->is('register') ? 'bg-black rounded text-white py-1 px-4' : '' }}">
                <a href="register"> Register </a>
            </div>
            <div class="{{ request()->is(['login', '/']) ? 'bg-black rounded text-white py-1 px-4' : '' }}">
                <a href="login">Login</a>
            </div>
        </div>
    @endauth

</div>
