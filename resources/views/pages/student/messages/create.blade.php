@extends('layouts.master')
@section('page_title', 'Nouveau message')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Nouveau message</h5>
        <div class="header-elements">
            <a href="{{ route('student.messages.index') }}" class="btn btn-light">
                <i class="icon-arrow-left8"></i> Retour aux messages
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('student.messages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Destinataires <span class="text-danger">*</span></label>
                <select name="recipients[]" class="form-control select-multiple" multiple="multiple" required>
                    <optgroup label="Enseignants">
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->email }})</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Administration">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                        @endforeach
                    </optgroup>
                </select>
                <span class="form-text text-muted">Maintenez la touche Ctrl (ou Cmd sur Mac) enfoncée pour sélectionner plusieurs destinataires.</span>
            </div>
            
            <div class="form-group">
                <label>Sujet <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
            </div>
            
            <div class="form-group">
                <label>Message <span class="text-danger">*</span></label>
                <textarea name="content" rows="8" class="form-control" required>{{ old('content') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="attachments">Pièces jointes (optionnel)</label>
                <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                <span class="form-text text-muted">Vous pouvez sélectionner plusieurs fichiers (max 10 Mo par fichier)</span>
            </div>
            
            <div class="text-right">
                <button type="reset" class="btn btn-light">
                    <i class="icon-undo2 mr-2"></i> Réinitialiser
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-paperplane mr-2"></i> Envoyer le message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('global_assets/js/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('global_assets/js/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('global_assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function() {
        // Initialisation du sélecteur multiple
        $('.select-multiple').select2({
            theme: 'bootstrap',
            placeholder: 'Sélectionnez un ou plusieurs destinataires',
            width: '100%',
            allowClear: true,
            closeOnSelect: false
        });
    });
</script>
@endpush
