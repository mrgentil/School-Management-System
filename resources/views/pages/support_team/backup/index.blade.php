@extends('layouts.master')
@section('page_title', 'Sauvegarde Base de Données')

@section('content')
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="icon-database mr-2"></i> Sauvegarde Base de Données</h4>
                <small class="opacity-75">Gérez les sauvegardes de votre système</small>
            </div>
            <form action="{{ route('backup.create') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-light btn-lg" onclick="return confirm('Créer une nouvelle sauvegarde ?')">
                    <i class="icon-plus3 mr-1"></i> Nouvelle Sauvegarde
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <i class="icon-stack icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['total_backups'] }}</h3>
                <small>Sauvegardes</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <i class="icon-database icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['total_size'] > 0 ? round($stats['total_size'] / 1024 / 1024, 2) : 0 }} MB</h3>
                <small>Espace utilisé</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <i class="icon-history icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['last_backup'] ?? 'Aucun' }}</h3>
                <small>Dernière sauvegarde</small>
            </div>
        </div>
    </div>
</div>

{{-- Liste des backups --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Liste des Sauvegardes</h6>
    </div>
    <div class="card-body p-0">
        @if(count($backups) > 0)
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Fichier</th>
                            <th>Taille</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr>
                                <td>
                                    <i class="icon-file-text text-primary mr-2"></i>
                                    <strong>{{ $backup['name'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $backup['size'] }}</span>
                                </td>
                                <td>{{ $backup['date'] }}</td>
                                <td class="text-center">
                                    {{-- Télécharger --}}
                                    <a href="{{ route('backup.download', $backup['name']) }}" 
                                       class="btn btn-sm btn-success" title="Télécharger">
                                        <i class="icon-download"></i>
                                    </a>
                                    
                                    {{-- Restaurer --}}
                                    <form action="{{ route('backup.restore', $backup['name']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning" title="Restaurer"
                                                onclick="return confirm('⚠️ ATTENTION!\n\nRestaurer cette sauvegarde va REMPLACER toutes les données actuelles.\n\nÊtes-vous sûr de vouloir continuer ?')">
                                            <i class="icon-history"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Supprimer --}}
                                    <form action="{{ route('backup.destroy', $backup['name']) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"
                                                onclick="return confirm('Supprimer cette sauvegarde ?')">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="icon-database text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3 text-muted">Aucune sauvegarde</h5>
                <p class="text-muted">Cliquez sur "Nouvelle Sauvegarde" pour créer votre première sauvegarde</p>
            </div>
        @endif
    </div>
</div>

{{-- Aide --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-question3 mr-2"></i> Conseils</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-success"><i class="icon-checkmark mr-1"></i> Bonnes pratiques</h6>
                <ul class="list-unstyled text-muted">
                    <li>• Sauvegardez régulièrement</li>
                    <li>• Gardez plusieurs versions</li>
                    <li>• Téléchargez les backups importants</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-warning"><i class="icon-warning mr-1"></i> Attention</h6>
                <ul class="list-unstyled text-muted">
                    <li>• La restauration écrase les données</li>
                    <li>• Faites un backup avant de restaurer</li>
                    <li>• Ne supprimez pas le dernier backup</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-info"><i class="icon-info22 mr-1"></i> Information</h6>
                <ul class="list-unstyled text-muted">
                    <li>• Format: SQL complet</li>
                    <li>• Stockage: serveur local</li>
                    <li>• Accès: Super Admin uniquement</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
