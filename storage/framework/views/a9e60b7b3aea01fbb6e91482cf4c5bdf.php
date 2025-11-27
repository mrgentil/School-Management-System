
<?php $__env->startSection('page_title', 'Informations Étudiants'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Filtrer les Étudiants</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form method="GET" action="<?php echo e(route('students.list', ['class_id' => 'dummy'])); ?>" onsubmit="return redirectToClass(event)">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="my_class_id">Classe</label>
                            <select name="my_class_id" id="my_class_id" class="form-control" required>
                                <option value="">-- Sélectionner une classe --</option>
                                <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->full_name ?: $class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="section_id">Section (optionnel)</label>
                            <select name="section_id" id="section_id" class="form-control">
                                <option value="">-- Toutes les sections --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="icon-search4 mr-1"></i>Afficher les étudiants
                </button>
            </form>
        </div>
    </div>

    <script>
        function redirectToClass(e) {
            e.preventDefault();
            var classId = document.getElementById('my_class_id').value;
            if (!classId) { return false; }
            var url = '<?php echo e(url('students/list')); ?>/' + classId;
            window.location.href = url;
            return false;
        }

        // Chargement dynamique des sections (si besoin)
        document.getElementById('my_class_id').addEventListener('change', function () {
            var classId = this.value;
            var sectionSelect = document.getElementById('section_id');
            sectionSelect.innerHTML = '<option value="">-- Toutes les sections --</option>';
            if (!classId) { return; }

            fetch('<?php echo e(route('attendance.get_sections', ['class_id' => 'CLASS_ID'])); ?>'.replace('CLASS_ID', classId))
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (Array.isArray(data)) {
                        data.forEach(function (section) {
                            var opt = document.createElement('option');
                            opt.value = section.id;
                            opt.textContent = section.name;
                            sectionSelect.appendChild(opt);
                        });
                    }
                })
                .catch(function () {
                    // On ignore les erreurs silencieusement
                });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/students/info.blade.php ENDPATH**/ ?>