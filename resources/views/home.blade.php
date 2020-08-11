@extends('layout')

@section('title', 'Bienvenue')

@section('content')

@if(Session::has('error'))
    <p class="mb-5 alert alert-danger" role="alert">{{ Session::get('error') }}</p>
@endif

<section class="jumbotron text-center bg-dark">
    <div class="container text-light">
        <h1 class="jumbotron-heading mt-5 mb-5">Charlie Solutions</h1>
        <p class="lead text-light">CHARLIE propose des solutions plug&play 🚀 en associant les meilleures technologies de système de traçabilité. Notre plateforme SaaS intelligente est personnalisable à votre métier et à votre besoin.</p>
        <p class="lead text-lightd">Nos systèmes IoT de traçabilités sont automatisés afin de limiter l’erreur humaine dans la remontée d’information, de gagner du temps et de responsabiliser vos équipes. ⏳</p>
        <p class="mt-5">
            <a href="#" class="btn btn-secondary">Home</a>
            <a href="/materials?page=0" class="btn btn-primary">Matériels</a>
        </p>
    </div>
</section>

@endsection
