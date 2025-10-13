@extends('auth.layouts.template')

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="container-fluid px-4">
                <h1 class="app-page-title">Administrateurs</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">Nouvel administrateurs</h3>
                        <div class="section-intro">Ajouter ici un nouvel administrateurs</div>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success p-3 rounded shadow-sm mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger p-3 rounded shadow-sm mb-4">
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">

                            <div class="app-card-body">
                                <form class="settings-form" method="POST" action="{{ route('administrateurs.store') }}">
                                    @csrf
                                    @method('POST')
                                 
                                    <!-- Nom -->
                                    <div class="mb-3">
                                        <label class="form-label">Nom complet</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Entrer le nom de l'administrateur" value="{{ old('name') }}">
                                        @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                   
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Entrer le mail de l'employer" value="{{ old('email') }}">
                                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn app-btn-primary">Enregistrer</button>
                                </form>
                            </div>

                        </div><!--//app-card-->
                    </div>
                </div><!--//row-->

@endsection