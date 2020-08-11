<?php
use App\Objects\Enums\{FormFieldsEnum, MatFieldsEnum, MatStatusEnum};
?>

@extends('layout')

@section('title', 'Modifier')

@section('scripts')
  <script rel="text/javascript" src="{{ asset('js/edit.js') }}"></script>
@endsection

@section('content')

<!-- to display critical ERROR messages, shouldn't happen -->
@if($errors->routeParams->first(FormFieldsEnum::_FILIALE) || $errors->routeParams->first(MatFieldsEnum::_DEFAULT))
    <p class="mb-5 alert alert-danger" role="alert">Filiale ou id incorrecte, matériel introuvable</p>
@enderror

<!-- to display ERROR session messages -->
@if(Session::has('error'))
    <p class="mb-5 alert alert-danger" role="alert">{{ Session::get('error') }}</p>
@endif

<!-- to display SUCCESS session messages -->
@if(Session::has('success'))
    <p class="mb-5 alert alert-success" role="alert">{{ Session::get('success') }}</p>
@endif

<h1 class="mb-5">Modifier le matériel</h1>

<form class="" method="post" action="/update/{{ $matFiliale }}/{{ $material->id }}">

    <!-- CSRF protection -->
    @csrf

    <!-- IDENTIFICATION input -->
    <div class="form-group">
        <label class="" for="identification">Id:</label>
        <input type="text" class="form-control" id="identification" name="{{ MatFieldsEnum::_IDENT }}" value="{{ $material->identification }}" readonly>
    </div>

    <!-- MAT TYPE input -->
    <div class="form-group">
        <label class="" for="type">Type:</label>
        <input type="text" class="form-control" id="type" name="{{ MatFieldsEnum::_TYPE }}" value="{{ $material->type }}" readonly>
    </div>

    <!-- SERIAL NUMBER input -->
    <div class="form-group">
        <label class="" for="serial-number">Numéro de série:</label>
        <input type="text" class="form-control" id="serial-number" name="{{ MatFieldsEnum::_SERIE }}" value="{{ $material->serie }}" readonly>
    </div>

    <!-- STATUS input -->
    <div class="form-group">
        <label class="{{ $errors->has(MatFieldsEnum::_STATUS) ? 'text-danger' : '' }}" for="status">Status:</label>
        <select class="custom-select {{ $errors->has(MatFieldsEnum::_STATUS) ? 'is-invalid' : '' }}" name="{{ MatFieldsEnum::_STATUS }}" id="status" onchange="updateLost('{{ MatStatusEnum::_LOST }}', this.value)">
            @foreach (MatStatusEnum::_AS_TAB as $status)
                <option value="{{ $status }}" <?php echo ($status == old(MatStatusEnum::_LOST, strtoupper(trim($material->status)))) ? "selected" : "" ?>>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    @error(MatFieldsEnum::_STATUS)
        <p class="alert alert-danger">Status incorrect</p>
    @enderror

    <!-- LOST input -->
    <div class="form-group <?php echo ($material->status != MatStatusEnum::_LOST) ? 'd-none' : '' ?>" id="lost-input">
        <label class="{{ $errors->has(MatFieldsEnum::_LOST) ? 'text-danger' : '' }}" for="lost">Perdu le:</label>
        <input class="form-control {{ $errors->has(MatFieldsEnum::_LOST) ? 'is-invalid' : '' }}" type="date" value="{{ old(MatFieldsEnum::_LOST, $material->lost) }}" max="{{ date('Y-m-d') }}" id="lost" name="{{ MatFieldsEnum::_LOST }}">
    </div>
    @error(MatFieldsEnum::_LOST)
        <p class="alert alert-danger">La date doit être au plus tard aujourd'hui</p>
    @enderror

    <!-- REGION input -->
    <div class="form-group">
        <label class="" for="region">Région:</label>
        <input type="text" class="form-control" id="region" name="{{ MatFieldsEnum::_REGION }}" value="{{ old(MatFieldsEnum::_REGION, $material->region) }}">
    </div>

    <!-- SITE input -->
    <div class="form-group">
        <label class="{{ $errors->has(MatFieldsEnum::_SITE) ? 'text-danger' : '' }}" for="site">Site:</label>
        <input class="form-control {{ $errors->has(MatFieldsEnum::_SITE) ? 'is-invalid' : '' }}" list="sites" name="{{ MatFieldsEnum::_SITE }}" id="site" value="{{ old(MatFieldsEnum::_SITE, $material->site) }}" autocomplete="off">
        <datalist id="sites">
            @foreach ($sites as $site)
                <option value="{{ $site->name }}" >
            @endforeach
        </datalist>
    </div>
    @error(MatFieldsEnum::_SITE)
        <p class="alert alert-danger">Ce site n'existe pas</p>
    @enderror

    <!-- ID CHANTIER input -->
    <div class="form-group">
        <label class="{{ $errors->has(MatFieldsEnum::_ID_CHANT) ? 'text-danger' : '' }}" for="id_chantier">Id de chantier:</label>
        <input type="number" min="1" class="form-control {{ $errors->has(MatFieldsEnum::_ID_CHANT) ? 'is-invalid' : '' }}" id="id_chantier" name="{{ MatFieldsEnum::_ID_CHANT }}" value="{{ old(MatFieldsEnum::_ID_CHANT, $material->id_chantier) }}">
    </div>
    @error(MatFieldsEnum::_ID_CHANT)
        <p class="alert alert-danger">La valeur doit être positive</p>
    @enderror

    <!-- ID ZONE STOCKAGE input -->
    <div class="form-group">
        <label class="{{ $errors->has(MatFieldsEnum::_ID_Z_S) ? 'text-danger' : '' }}" for="id_zone_stockage">Id de la zone de stockage:</label>
        <input type="number" min="1" class="form-control {{ $errors->has(MatFieldsEnum::_ID_Z_S) ? 'is-invalid' : '' }}" id="id_zone_stockage" name="{{ MatFieldsEnum::_ID_Z_S }}" value="{{ old(MatFieldsEnum::_ID_Z_S, $material->id_zone_stockage) }}">
    </div>
    @error(MatFieldsEnum::_ID_Z_S)
        <p class="alert alert-danger">La valeur doit être positive</p>
    @enderror

    <!-- PRICE input -->
    <div class="form-group">
        <label class="{{ $errors->has(MatFieldsEnum::_PRICE) ? 'text-danger' : '' }}" for="price">Prix:</label>
        <input type="number" min="0" step="0.01" class="form-control {{ $errors->has(MatFieldsEnum::_ID_Z_S) ? 'is-invalid' : '' }}" id="price" name="{{ MatFieldsEnum::_PRICE }}" value="{{ old(MatFieldsEnum::_PRICE, floatval($material->price)) }}">
    </div>
    @error(MatFieldsEnum::_PRICE)
        <p class="alert alert-danger">La valeur doit être positive</p>
    @enderror

    <!-- OBSERVATION input -->
    <div class="form-group">
        <label class="" for="observation">Observations:</label>
        <textarea class="form-control" id="observation" name="{{ MatFieldsEnum::_OBSERVATION }}" autocomplete="off">{{ old(MatFieldsEnum::_OBSERVATION, $material->observation) }}</textarea>
    </div>

    <button type="submit" class="form-group mr-2 btn btn-primary">Enregistrer</button>

</form>

<!-- to DELETE entry -->
<form method="post" action="/destroy/{{ $matFiliale }}/{{ $material->id }}">
    @csrf
    <button class="form-group mr-2 btn btn-danger">Supprimer</a>
</form>

@endsection