@extends('layouts.app')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900 py-8 lg:py-16">
    <div class="max-w-3xl px-4 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
        
        <div class="mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Registro de Usuario</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Complete los datos requeridos para registrar una nueva cuenta en el sistema.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid gap-4 mb-6 sm:grid-cols-2 sm:gap-6">
                
                <!-- Nombre de Usuario -->
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombre de Usuario <span class="text-red-500">*</span>
                    </label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="off" placeholder="Ej. Jose11"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('username') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('username')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Ingresa tu Correo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('email') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombres -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="off" placeholder="Tus nombres"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('name') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Apellidos <span class="text-red-500">*</span>
                    </label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="off" placeholder="Tus apellidos"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('last_name') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cédula de Identidad -->
                <div>
                    <label for="cedula" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Cédula de Identidad <span class="text-red-500">*</span>
                    </label>
                    <input id="cedula" type="text" name="cedula" value="{{ old('cedula') }}" required autocomplete="off" placeholder="solo numeros"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('cedula') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('cedula')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Teléfono
                    </label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="off" placeholder="no es obligatorio"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('phone') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="off"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password-confirm" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Confirmar Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="off" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600  dark:text-white">
                    
                    <!-- Feedback Dinámico de JS -->
                    <div class="dynamic-feedback text-bold text-sm text-red-600 dark:text-red-500 mt-1" style="display: none;"></div>
                </div>

            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    <b>Completar Registro</b>
                </button>
                <a href="{{ route('login') }}" class="text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
    <script src="{{ asset('js/core-validations.js') }}" defer></script>
    <script src="{{ asset('js/auth-validations.js') }}" defer></script>
@endsection