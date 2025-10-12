@extends('auth.layouts.template')

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="container-fluid px-4">
                <h1 class="app-page-title">Employers</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">Nouvel Employer</h3>
                        <div class="section-intro">Ajouter ici un nouvel Employer</div>

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
                                <form class="settings-form" method="POST" action="{{ route('employer.store') }}">
                                    @csrf
                                    @method('POST')
                                    <!-- Départements -->
                                    <div class="mb-3">
                                        <label class="form-label">Département</label>
                                        <select name="departement_id" class="form-control">
                                            <option value="">Sélectionner un département</option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                                    {{ $departement->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departement_id') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Nom -->
                                    <div class="mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control"
                                            placeholder="Entrer le nom de l'employer" value="{{ old('nom') }}">
                                        @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Prénom -->
                                    <div class="mb-3">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" name="prenom" class="form-control"
                                            placeholder="Entrer le prennom de l'employer" value="{{ old('prenom') }}">
                                        @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Entrer le mail de l'employer" value="{{ old('email') }}">
                                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Contact -->
                                    <div class="mb-3">
                                        <label class="form-label">Contact</label>
                                        <input type="text" name="contact" class="form-control"
                                            placeholder="Entrer le contact de l'employer" value="{{ old('contact') }}">
                                        @error('contact') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Montant journalier -->
                                    <div class="mb-3">
                                        <label class="form-label">Montant journalier</label>
                                        <input type="number" name="montant_journalier" class="form-control"
                                            placeholder="Entrer le montant montant journalier de l'employer"
                                            value="{{ old('montant_journalier') }}">
                                        @error('montant_journalier') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn app-btn-primary">Enregistrer</button>
                                </form>
                            </div>

                        </div><!--//app-card-->
                    </div>
                </div><!--//row-->

@endsection