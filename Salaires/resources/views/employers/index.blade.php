@extends('auth.layouts.template')

@section('content')
    <div class="row">

        <div class="col-12">
            <div class="container-fluid px-4">

                <!-- Titre + Bouton -->
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Liste des employers</h1>
                    </div>
                    <div class="col-auto">
                        <a class="btn app-btn-secondary" href="{{ route('employer.create') }}">
                            ➕ Ajouter un nouvel employer
                        </a>
                    </div>
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


                <!-- Tableau -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="orders-all">
                        <div class="app-card app-card-orders-table shadow-sm mb-5">
                            <div class="app-card-body">
                                <div class="table-responsive overflow-x-auto">
                                    <table class="table app-table-hover mb-0 text-left">
                                        <thead class="bg-gray-100 dark:bg-gray-800">
                                            <tr>
                                                <th class="cell">#</th>
                                                <th class="cell">Nom</th>
                                                <th class="cell">Prenom</th>
                                                <th class="cell">Email</th>
                                                <th class="cell">Contact</th>
                                                <th class="cell">Salaire</th>
                                                <th class="cell">Département</th>
                                                <th class="cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($employers as $employer)
                                                <tr>
                                                    <td>{{ $employer->id }}</td>
                                                    <td>{{ $employer->nom }}</td>
                                                    <td>{{ $employer->prenom }}</td>
                                                    <td class="truncate max-w-[150px]">{{ $employer->email }}</td>
                                                    <td>{{ $employer->contact }}</td>
                                                    <td>{{ number_format($employer->montant_journalier * 31, 0, ',', ' ') }}
                                                        F CFA</td>
                                                    <td>{{ $employer->departement->name ?? '-' }}</td>
                                                    <td>
                                                        <a class="btn btn-sm app-btn-secondary"
                                                            href="{{ route('employer.edit', $employer->id)}}">Éditer</a>
                                                        <a class="btn btn-sm app-btn-secondary"
                                                            href="{{ route('employer.delete', $employer->id)}}">Supprimer</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="cell text-center" colspan="8">Aucun employer ajouté</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="mt-3">
                                    {{ $employers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- //tab-content -->
            </div>
        </div>
    </div>
@endsection