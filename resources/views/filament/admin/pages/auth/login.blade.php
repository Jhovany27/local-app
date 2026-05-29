<x-filament-panels::page.simple>

<div class="flex items-center justify-center min-h-screen bg-[#edf3e3]">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        <!-- FORM -->
        <div class="p-8 md:p-12 flex flex-col justify-center">

            <h2 class="text-3xl font-extrabold text-center mb-8">
                Admin Login
            </h2>

            {{ $this->form }}

            <a href="{{ url('/portal') }}"
                class="mt-6 text-center text-sm text-gray-600 hover:underline">
                ← Volver al portal
            </a>

        </div>

        <!-- IMAGEN -->
        <div class="hidden md:flex bg-[#a8df11] items-center justify-center">
            <img src="{{ asset('images/Logo_local_app.png') }}"
                class="max-w-md w-full">
        </div>

    </div>

</div>

</x-filament-panels::page.simple>