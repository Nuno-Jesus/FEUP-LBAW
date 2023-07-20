@extends('layouts.app')

@section('content')
    <div class="aboutus-section">
        <h1>About Us</h1>
        Grab n' Build is a project devolopped for the course unit of <a href="https://sigarra.up.pt/feup/en/ucurr_geral.ficha_uc_view?pv_ocorrencia_id=484433">Database and Web Applications 
        Laboratory</a> at the Engineering Faculty of University of Porto.
        <p>Grab n' Build is an online store that aims to serve the growing market of computer parts, users will find a wide variety of products that they can choose from and buy.</p>
        <p><a href = "{{ route('login') }}">Sign-in/Register</a> to have full access to the store.</p>
    </div>
    
    <div class="aboutus-row">
        @include('partials.static.about_card', [
            'name' => 'Carlos Veríssimo',
            'text' => '',
            'email' => 'up201907716@up.pt',
            'image' => 'images/profile1.jpg'
        ])

        @include('partials.static.about_card', [
            'name' => 'Duarte Sardão',
            'text' => '',
            'email' => 'up201905497@up.pt',
            'image' => 'images/profile2.jpg'
            ])

        @include('partials.static.about_card', [
            'name' => 'Nuno Jesus',
            'text' => '',
            'email' => 'up201905477@up.pt',
            'image' => 'images/profile3.jpg'
            ])

        @include('partials.static.about_card', [
            'name' => 'Tomás Torres',
            'text' => '',
            'email' => 'up201800700@up.pt',
            'image' => 'images/profile4.jpg'
            ])
    </div>

@endsection