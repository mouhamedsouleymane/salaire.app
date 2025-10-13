@extends('auth.layouts.template')

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="container-fluid px-4">
                <h1 class="app-page-title">Employers</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">Edition</h3>
                        <div class="section-intro">Editer un employer</div>

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
                                <form class="settings-form" method="POST"
                                    action="{{ route('employer.update', $employer->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <!-- Départements -->
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Département</label>
                                        <select name="departement_id" id="departement_id" class="form-control">
                                            <option value=""></option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->id }}" {{ $employer->departement_id == $departement->id ? 'selected' : '' }}>
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
                                            placeholder="Entrer le nom de l'employer" value="{{ $employer->nom }}">
                                        @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Prénom -->
                                    <div class="mb-3">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" name="prenom" class="form-control" placeholder=""
                                            value="{{ $employer->prenom }}">
                                        @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $employer->email }}">
                                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Contact -->
                                    <div class="mb-3">
                                        <label class="form-label">Contact</label>
                                        <input type="text" name="contact" class="form-control"
                                            value="{{ $employer->contact }}">
                                        @error('contact') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Montant journalier -->
                                    <div class="mb-3">
                                        <label class="form-label">Montant journalier</label>
                                        <input type="number" name="montant_journalier" class="form-control"
                                            value="{{ $employer->montant_journalier }}">
                                        @error('montant_journalier') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn app-btn-primary">Mettre à jour</button>
                                </form>
                            </div>

                        </div><!--//app-card-->
                    </div>
                </div><!--//row-->
            </div>
        </div>
    </div>
@endsection