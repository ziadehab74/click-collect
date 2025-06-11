<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        nav .navbar {
            position: fixed;
        }
    </style>

    @include('livewire.partials.header')
    <title>@yield('title', 'My Laravel App')</title>
    @stack('styles')
</head>

<body>
    @include('livewire.partials.navbar')

    <main>
        @include('livewire.section1')
        @include('livewire.section2')
        @include('livewire.section3')
    </main>

    @include('livewire.partials.foot')
</body>


</html>
