<?php
use App\Objects\Enums\{FormFieldsEnum, MatFieldsEnum};
use Illuminate\Support\Facades\Session;
?>

@extends('layout')

@section('scripts')
  <script rel="text/javascript" src="{{ asset('js/materials.js') }}"></script>
@endsection

@section('title', 'Matériels')

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

<h1 class="">Liste des matériels</h1>

<!-- Variables and operations to build the form -->
<?php
  $getVal;

  // _FILIALE's default select
  $getVal = (isset($_GET[FormFieldsEnum::_FILIALE])) ? intval($_GET[FormFieldsEnum::_FILIALE], 10) : 1;
  $_FILIALE_select = ($getVal >= 1 && $getVal <= 3) ? $getVal : 1;

  // _KEYWORDS's value
  $_KEYWORDS_val = (isset($_GET[FormFieldsEnum::_KEYWORDS])) ? $_GET[FormFieldsEnum::_KEYWORDS] : "";

  // _SORT_BY's default select
  $_SORT_BY_select = (isset($_GET[FormFieldsEnum::_SORT_BY])) ? $_GET[FormFieldsEnum::_SORT_BY] : MatFieldsEnum::_DEFAULT;
  
  // _ORDER's value
  $getVal = (isset($_GET[FormFieldsEnum::_ORDER])) ? $_GET[FormFieldsEnum::_ORDER] : MatFieldsEnum::_ASCENDING;
  $_ORDER_val = ($getVal != MatFieldsEnum::_ASCENDING && $getVal != MatFieldsEnum::_DESCENDING) ? MatFieldsEnum::_ASCENDING : $getVal;

  // _OFFSET's value
  $getVal = (isset($_GET[FormFieldsEnum::_OFFSET])) ? intval($_GET[FormFieldsEnum::_OFFSET], 10) : FormFieldsEnum::_DEF_OFFSET;
  $_OFFSET_val = ($getVal < 1) ? 1 : (($getVal > 100) ? 100 : $getVal);

  // _PAGE's value
  $getVal = (isset($_GET[FormFieldsEnum::_PAGE])) ? intval($_GET[FormFieldsEnum::_PAGE], 10) : 1;
  $_PAGE_val = ($getVal < 0) ? 0 : $getVal;

  function isSelected($currentVal, $expectedVal) {
    return (($currentVal == $expectedVal) ? "selected" : "");
  }

?>

<!-- search bar -->
<form class="mt-4 mb-4 form-inline">
  <input type="hidden" name="{{ FormFieldsEnum::_PAGE }}" value="0">
  <input type="hidden" name="{{ FormFieldsEnum::_OFFSET }}" value="{{ $_OFFSET_val }}">
  

  <!-- _FILIALE select input -->
  <div class="form-group mr-2">
    <label class="sr-only" for="filiale-select">Sélectionnez une filiale:</label>
    <!-- input name based on the correspondant ENUM -->
    <select class="custom-select" name="{{ FormFieldsEnum::_FILIALE }}" id="filiale-select">
      <option value="1" {{ isSelected($_FILIALE_select, 1) }}>Filiale 1</option>
      <option value="2" {{ isSelected($_FILIALE_select, 2) }}>Filiale 2</option>
      <option value="3" {{ isSelected($_FILIALE_select, 3) }}>Filiale 3</option>
    </select>
  </div>


  <!-- _KEYWORDS input -->
  <div class="form-group mr-2">
    <label class="sr-only" for="keywords">Mots clés</label>
    <!-- input name based on the correspondant ENUM -->
    <input type="text" class="form-control" id="keywords" name="{{ FormFieldsEnum::_KEYWORDS }}" value="{{ $_KEYWORDS_val }}" placeholder="Mots clés séparés par ;" autocomplete="off">
  </div>


  <!-- _SORT_BY select input -->
  <div class="form-group mr-2">
    <label class="sr-only" for="sort-select">Trier par:</label>
    <!-- input name based on the correspondant ENUM -->
    <select class="custom-select" name="{{ FormFieldsEnum::_SORT_BY }}" id="sort-select">
      <option value="{{ MatFieldsEnum::_DEFAULT }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_DEFAULT) }}>Trier par ...</option>
      <option value="{{ MatFieldsEnum::_IDENT }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_IDENT) }}>Id</option>
      <option value="{{ MatFieldsEnum::_TYPE }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_TYPE) }}>Type</option>
      <option value="{{ MatFieldsEnum::_REGION }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_REGION) }}>Région</option>
      <option value="{{ MatFieldsEnum::_SITE }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_SITE) }}>Site</option>
      <option value="{{ MatFieldsEnum::_STATUS }}" {{ isSelected($_SORT_BY_select, MatFieldsEnum::_STATUS) }}>Status</option>
    </select>
  </div>


  <!-- _ORDER input -->
  <div class="form-group mr-2">
    <label class="sr-only" for="order-select">Par ordre:</label>
    <!-- input name based on the correspondant ENUM -->
    <select class="custom-select" name="{{ FormFieldsEnum::_ORDER }}" id="order-select">
      <option value="{{ MatFieldsEnum::_ASCENDING }}" {{ isSelected($_ORDER_val, MatFieldsEnum::_ASCENDING) }}>Croissant</option>
      <option value="{{ MatFieldsEnum::_DESCENDING }}" {{ isSelected($_ORDER_val, MatFieldsEnum::_DESCENDING) }}>Décroissant</option>
    </select>
  </div>

  <button type="submit" class="form-group mr-2 btn btn-primary">Rechercher</button>
</form>

<!-- table to display the current entries -->
<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Type</th>
      <th scope="col">Région</th>
      <th scope="col">Site</th>
      <th scope="col">Status</th>
      <th scope="col">Modifier</th>
    </tr>
  </thead>
  <tbody>
    @foreach($lightMaterials as $lightMaterial)
        <tr>
            <th>{{ $lightMaterial->identification }}</a></th>
            <td>{{ $lightMaterial->type }}</td>
            <td>{{ $lightMaterial->region }}</td>
            <td>{{ $lightMaterial->site }}</td>
            <td>{{ $lightMaterial->status }}</td>
            <td class="position-relative">
              <a href="edit?{{ FormFieldsEnum::_FILIALE }}={{ $_FILIALE_select }}&{{ MatFieldsEnum::_DEFAULT }}={{ $lightMaterial->id }}">
                <img src="{{ asset('imgs/modify.png') }}" alt="modify" class="position-absolute h-50">
              </a>
            </td>
        </tr>
    @endforeach
  </tbody>
</table>

<!-- Setting page buttons href -->
<?php

  $backPageLink = updateUrlParam(FormFieldsEnum::_PAGE, $_PAGE_val - 1);
  $nextPageLink = updateUrlParam(FormFieldsEnum::_PAGE, $_PAGE_val + 1);

  function updateUrlParam($param, $newVal) {
      $query = $_GET;
      $query[$param] = $newVal;
      return ($_SERVER['PHP_SELF'] . "?" . http_build_query($query));
    }
?>

<!-- entries per page controller, and page buttons, built with consideration of the current page -->
<div class="mt-4 mb-4 form-inline justify-content-center">
  @if($_PAGE_val > 0)
  <div class="form-group mr-2">
    <a class="btn btn-info" href="{{ $backPageLink }}">Page précedente</a>
  </div>
  @endif
  <div class="form-group mr-2">
    <label class="sr-only" for="offset-select">Nombre de lignes par page:</label>
    <!-- input name based on the correspondant ENUM -->
    <select class="custom-select" name="{{ FormFieldsEnum::_OFFSET }}" id="offset-select" onchange="updateOffset('{{ FormFieldsEnum::_OFFSET }}', this.value, '{{ FormFieldsEnum::_PAGE }}')">
      <option value="1" {{ isSelected($_OFFSET_val, 1) }}>1 ligne</option>
      <option value="10" {{ isSelected($_OFFSET_val, 10) }}>10 lignes</option>
      <option value="25" {{ isSelected($_OFFSET_val, 25) }}>25 lignes</option>
      <option value="50" {{ isSelected($_OFFSET_val, 50) }}>50 lignes</option>
      <option value="100" {{ isSelected($_OFFSET_val, 100) }}>100 lignes</option>
    </select>
  </div>
  @if(count($lightMaterials) >= $_OFFSET_val)
  <div class="form-group mr-2">
    <a class="btn btn-info" href="{{ $nextPageLink }}">Page suivante</a>
  </div>
  @endif
</div>
@endsection