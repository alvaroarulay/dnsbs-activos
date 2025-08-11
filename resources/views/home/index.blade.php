@extends('layouts.app')
@section('content')
@if(Auth::check())
    @if (Auth::user()->idrol == 1)
    <template v-if="menu=='profile'">
    <profile></profile>
    </template>
    <template v-if="menu==0">
        <escritorio></escritorio>
    </template>

    <template v-if="menu==1">
        <bbdd></bbdd>
    </template>
    <template v-if="menu==2">
        <actuales></actuales>
    </template>
    <template v-if="menu==23">
      
    </template>

    <template v-if="menu==3">
        <inventrap></inventrap>  
    </template>

    <template v-if="menu==4">
        <codcont></codcont>
    </template>
        
    <template v-if="menu==5">
        <auxiliares></auxiliares>        
    </template>

    <template v-if="menu==6">
       
    </template>



 
    <template v-if="menu==22">
        <newresponsable></newresponsable>
    </template>
    <template v-if="menu==9">
        <asignaciones></asignaciones>  
    </template>

  

    <template v-if="menu==11">
        <usuarios></usuarios>
    </template>

    <template v-if="menu==12">
    </template>

    <template v-if="menu==13">
        <rol></rol>
    </template>
    <template v-if="menu==14">
        <repasignaciones></repasignaciones>
    </template>

   
    <template v-if="menu==16">
        <replogs></replogs>
    </template>

    <template v-if="menu==17">
        <articulos></articulos>
    </template>

    <template v-if="menu==18">
        <represponsables></represponsables>
    </template>

    <template v-if="menu==19">
       <historialasignaciones></historialasignaciones>
    </template>

    <template v-if="menu==20">
        <main class="app-content">
            <div class="row justify-content-center">
                <iframe width="800" height="600" src="https://www.youtube.com/embed/fZ0CCdhxDKI?si=bdHb_5IsB9ugnFSu" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </main>
    </template>

    <template v-if="menu==21">
       <main class="app-content">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg border-info rounded-lg mt-12">
                        <div class="card-header bg-info">
                          <strong>Sistema de Activos Fijos</strong>
                        </div>
                        <div class="card-body">
                            <div class="logo text-center">
                                <img src="/img/selabi.png" alt="" style="width: 200px; ">
                            </div>
                            <p>🧩 <strong>Versión:</strong> &nbsp; 1.0.0.0</p>
                            <p>©️ <strong>Copyright:</strong> &nbsp; 2025</p>
                            <p>🏢 <strong>Empresa:</strong> &nbsp; Selabí Technologies</p>
                        </div>
                        <div class="card-footer bg-light">
                            <p>⚠️ <strong>Advertencia Legal:</strong><br>
                            Este programa está protegido por las leyes de propiedad intelectual.<br>
                            La reproducción o distribución ilícita está penada por ley y puede dar lugar a sanciones civiles y penales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </template>
    @elseif (Auth::user()->idrol == 2)
    <template v-if="menu=='profile'">
    <profile></profile>
    </template>
    <template v-if="menu==0">
        <escritorio></escritorio>
    </template>

    <template v-if="menu==2">
        <actuales></actuales>
    </template>

    <template v-if="menu==3">
        <inventrap></inventrap>  
    </template>

    <template v-if="menu==4">
        <codcont></codcont>
    </template>
        
    <template v-if="menu==5">
        <auxiliares></auxiliares>        
    </template>

    
    <template v-if="menu==22">
        <newresponsable></newresponsable>
    </template>
    
    <template v-if="menu==9">
        <asignaciones></asignaciones>  
    </template>

    <template v-if="menu==14">
        <repasignaciones></repasignaciones>
    </template>

   
    <template v-if="menu==16">
        <replogs></replogs>
    </template>

    <template v-if="menu==17">
        <articulos></articulos>
    </template>

    <template v-if="menu==18">
        <represponsables></represponsables>
    </template>

    <template v-if="menu==19">
       <historialasignaciones></historialasignaciones>
    </template>

    <template v-if="menu==20">
        <main class="app-content">
            <div class="row justify-content-center">
                <iframe width="800" height="600" src="https://www.youtube.com/embed/fZ0CCdhxDKI?si=bdHb_5IsB9ugnFSu" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </main>
    </template>

    <template v-if="menu==21">
        <main class="app-content">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg border-info rounded-lg mt-12">
                        <div class="card-header bg-info">
                          <strong>Sistema de Activos Fijos</strong>
                        </div>
                        <div class="card-body">
                            <div class="logo text-center">
                                <img src="/img/selabi.png" alt="" style="width: 200px; ">
                            </div>
                            <p>🧩 <strong>Versión:</strong> &nbsp; 1.0.0.0</p>
                            <p>©️ <strong>Copyright:</strong> &nbsp; 2025</p>
                            <p>🏢 <strong>Empresa:</strong> &nbsp; Selabí Technologies</p>
                        </div>
                        <div class="card-footer bg-light">
                            <p>⚠️ <strong>Advertencia Legal:</strong><br>
                            Este programa está protegido por las leyes de propiedad intelectual.<br>
                            La reproducción o distribución ilícita está penada por ley y puede dar lugar a sanciones civiles y penales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </template><template v-if="menu==23">
        <bajas></bajas>
    </template>
@else
@endif
@else
<template>
    <main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
</template>
<template v-if="menu==2">
<main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
    </template>
    <template v-if="menu==23">
    <main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
    </template>
@endif
@endsection