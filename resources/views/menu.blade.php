<!DOCTYPE html>
<html lang="en">

<head>
    @include('livewire.partials.header')
    @section('title', 'Menu & Checkout')
    @stack('styles')
</head>

<body>

    <main>
        @include('livewire.partials.navbar')

        @livewire('menu-checkout')
        {{-- @include('section2')
        @include('section3') --}}
    </main>

    @include('livewire.partials.foot')
</body>

</html>
