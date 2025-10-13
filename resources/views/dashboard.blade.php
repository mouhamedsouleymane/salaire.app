@extends('auth.layouts.template')

@section('content')
    <h1 class="app-page-title">ACCUEIL</h1>

    <div class="row mt-2 mb-2">
        @if ($paymentNotification)
            <div class="alert alert-warning"><strong><b>Attention: </b></strong>{{$paymentNotification}}</div>
        @endif
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1"><strong>Total Departements</strong></h4>
                    <div class="stats-figure">{{ $totalDepartements }}</div>
                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1"><strong>Total Employers</strong></h4>
                    <div class="stats-figure">{{ $totalEmployers }}</div>

                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1"><strong>Total Administrateurs</strong></h4>
                    <div class="stats-figure">{{ $totalAdministrateurs }}</div>

                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->
        <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-1"><strong>Retard de paiement</strong></h4>
                    <div class="stats-figure">0</div>

                </div><!--//app-card-body-->
                <a class="app-card-link-mask" href="#"></a>
            </div><!--//app-card-->
        </div><!--//col-->
    </div><!--//row-->
@endsection