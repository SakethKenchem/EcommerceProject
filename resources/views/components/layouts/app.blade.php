<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'ECommerce' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-200 dark:bg-slate-900 text-gray-900 dark:text-gray-100">
        @livewire('partials.navbar')
        
        <div class="flex justify-end p-4">
            <label class="swap swap-rotate">
                <!-- this hidden checkbox controls the state -->
                <input type="checkbox" id="theme-toggle" />

                <!-- sun icon -->
                <svg class="swap-on fill-current w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64 17.36A9 9 0 1 0 12 3a9 9 0 0 0-6.36 14.36zm6.36 4.64h2v2h-2zM4.22 4.22l1.42-1.42 1.42 1.42-1.42 1.42zM1 11h2v2H1zm1.64 7.64l1.42 1.42 1.42-1.42-1.42-1.42zM11 21h2v2h-2zm7.64-1.64l1.42 1.42 1.42-1.42-1.42-1.42zM21 11h2v2h-2zm-4.22-6.78l1.42-1.42 1.42 1.42-1.42 1.42z"/></svg>

                <!-- moon icon -->
                <svg class="swap-off fill-current w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.86 15.47A9 9 0 0 1 8.53 2.14a9.001 9.001 0 1 0 13.33 13.33z"/></svg>
            </label>
        </div>

        <main>
            {{ $slot }}
        </main>
        
        @livewireScripts
        <script>
            document.getElementById('theme-toggle').addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });

            // Load theme from localStorage
            document.addEventListener('DOMContentLoaded', function() {
                const theme = localStorage.getItem('theme');
                if (theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    document.getElementById('theme-toggle').checked = theme === 'dark';
                }
            });
        </script>
    </body>
    @livewire('partials.footer')
</html>
