<?php $__env->startSection('page_title', 'Créer un Devoir'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-plus2 mr-2"></i>
            Créer un Nouveau Devoir
        </h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <form method="POST" action="<?php echo e(route('assignments.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="<?php echo e(old('title')); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required><?php echo e(old('description')); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" 
                                        data-section="<?php echo e($class->academicSection ? $class->academicSection->name : ''); ?>"
                                        data-option="<?php echo e($class->option ? $class->option->name : ''); ?>">
                                    <?php echo e($class->full_name ?: $class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted">La classe contient déjà la section et l'option</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière <span class="text-danger">*</span></label>
                        <select name="subject_id" id="subject_id" class="form-control select" required>
                            <option value="">Sélectionner une classe d'abord</option>
                        </select>
                        <small class="form-text text-muted">Matières liées à la classe sélectionnée</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Note Maximale <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="100" min="1" max="1000" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Période <span class="text-danger">*</span></label>
                        <select name="period" class="form-control select" required>
                            <option value="">Sélectionner une période</option>
                            <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($period['id']); ?>" <?php echo e(old('period') == $period['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($period['name']); ?> (Semestre <?php echo e($period['semester']); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date Limite <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="due_date" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-semibold">Fichier Joint (optionnel)</label>
                        <input type="file" name="file" class="form-control-file">
                        <small class="text-muted">PDF, DOC, DOCX, PPT, PPTX, ZIP (Max: 10MB)</small>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a href="<?php echo e(route('assignments.index')); ?>" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-success">
                    <i class="icon-checkmark3 mr-2"></i>
                    Créer le Devoir
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Données des matières
    const allSubjects = <?php echo json_encode($subjects, 15, 512) ?>;
    
    // Pour l'instant, utilisons un filtrage basé sur les mots-clés dans les noms de matières
    // au lieu de noms exacts qui n'existent pas dans la base
    const subjectKeywordsByType = {
        'Technique': ['math', 'physique', 'électron', 'mécan', 'inform', 'français', 'anglais', 'technique'],
        'Commercial': ['math', 'compt', 'économ', 'gest', 'français', 'anglais', 'commercial'],
        'Scientifique': ['math', 'physique', 'chim', 'bio', 'français', 'anglais', 'science'],
        'Sécondaire': ['math', 'français', 'anglais', 'histoire', 'géo', 'science'],
        'Litteraire': ['français', 'anglais', 'histoire', 'géo', 'philo', 'litt'],
        'Maternelle': ['jeux', 'éveil', 'motric', 'langage', 'éducatif'],
        'Primaire': ['math', 'français', 'science', 'histoire', 'géo', 'anglais']
    };
    
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var section = selectedOption.data('section');
        var option = selectedOption.data('option');
        
        var subjectOptions = '<option value="">Sélectionner</option>';
        
        if (classId) {
            // Déterminer le type de classe pour filtrer les matières
            var classType = 'Primaire'; // Par défaut
            
            // Priorité 1: Utiliser la section académique si disponible
            if (section && section.trim() !== '') {
                classType = section;
            } else {
                // Priorité 2: Détecter le type selon le nom de la classe
                var className = selectedOption.text().toLowerCase();
                if (className.includes('maternelle') || className.includes('crèche') || className.includes('pré-maternelle')) {
                    classType = 'Maternelle';
                } else if (className.includes('primaire')) {
                    classType = 'Primaire';
                } else if (className.includes('technique')) {
                    classType = 'Technique';
                } else if (className.includes('commercial')) {
                    classType = 'Commercial';
                } else if (className.includes('scientifique')) {
                    classType = 'Scientifique';
                } else if (className.includes('secondaire') || className.includes('sec ')) {
                    classType = 'Sécondaire';
                }
            }
            
            // Filtrer les matières selon le type avec mots-clés
            var relevantKeywords = subjectKeywordsByType[classType] || [];
            
            if (relevantKeywords.length === 0) {
                // Aucun filtre spécifique, afficher toutes les matières
                allSubjects.forEach(function(subject) {
                    subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                });
            } else {
                // Filtrer selon les mots-clés pertinents
                allSubjects.forEach(function(subject) {
                    var subjectName = subject.name.toLowerCase();
                    var isRelevant = relevantKeywords.some(function(keyword) {
                        return subjectName.includes(keyword);
                    });
                    
                    if (isRelevant) {
                        subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                    }
                });
                
                // Si aucune matière trouvée avec les mots-clés, afficher toutes
                var foundCount = subjectOptions.split('</option>').length - 2; // -2 pour l'option par défaut
                if (foundCount === 0) {
                    allSubjects.forEach(function(subject) {
                        subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                    });
                }
            }
            
            // Afficher les informations de la classe sélectionnée
            console.log('=== DEBUG CLASSE ===');
            console.log('Classe sélectionnée:', selectedOption.text());
            console.log('Section académique:', section || 'N/A');
            console.log('Option:', option || 'N/A');
            console.log('Type détecté:', classType);
            console.log('Mots-clés utilisés:', relevantKeywords);
            console.log('Nombre de matières trouvées:', subjectOptions.split('</option>').length - 1);
            console.log('==================');
            
            // Debug supprimé - système fonctionnel
        }
        
        $('#subject_id').html(subjectOptions);
    });
    
    // Debug initial
    console.log('Classes chargées:', <?php echo json_encode($my_classes->count(), 15, 512) ?>, 'classes');
    console.log('Matières disponibles:', allSubjects.length, 'matières');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/assignments/create.blade.php ENDPATH**/ ?>