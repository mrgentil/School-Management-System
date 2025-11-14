<?php $__env->startSection('page_title', 'Emploi du Temps - Vue Calendrier'); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="card mb-3">
        <div class="card-body bg-primary-400 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1"><i class="icon-calendar3 mr-2"></i>Emploi du Temps - Vue Calendrier</h4>
                    <p class="mb-0 opacity-75">Visualisez vos cours de la semaine</p>
                </div>
                <div>
                    <a href="<?php echo e(route('student.timetable.index')); ?>" class="btn btn-light">
                        <i class="icon-list mr-2"></i> Vue Liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($message)): ?>
        <!-- Message d'information -->
        <div class="alert alert-info border-left-info">
            <div class="d-flex align-items-center">
                <i class="icon-info22 icon-2x mr-3"></i>
                <div>
                    <h6 class="alert-heading mb-1">Information</h6>
                    <p class="mb-0"><?php echo e($message); ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Calendrier -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar22 mr-2"></i>Calendrier Hebdomadaire
                </h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Légende des couleurs -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="icon-palette mr-2"></i>Légende</h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #3498db; padding: 0.5rem 1rem;">Lundi</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #2ecc71; padding: 0.5rem 1rem;">Mardi</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #f39c12; padding: 0.5rem 1rem;">Mercredi</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #9b59b6; padding: 0.5rem 1rem;">Jeudi</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #e74c3c; padding: 0.5rem 1rem;">Vendredi</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="badge badge-pill mr-2" style="background-color: #1abc9c; padding: 0.5rem 1rem;">Samedi</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        min-height: 600px;
        background: white;
    }
</style>

<script>
$(document).ready(function() {
    console.log('=== CALENDRIER ÉTUDIANT ===');
    console.log('jQuery:', typeof $ !== 'undefined' ? 'Chargé' : 'Non chargé');
    console.log('FullCalendar:', typeof FullCalendar !== 'undefined' ? 'Chargé' : 'Non chargé');
    
    var calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) {
        console.error('Élément #calendar introuvable !');
        return;
    }
    
    var events = <?php echo json_encode($events ?? [], 15, 512) ?>;
    console.log('Événements:', events);
    
    // Utiliser le FullCalendar qui est déjà chargé dans inc_bottom.blade.php
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        locale: 'fr',
        minTime: '07:00:00',
        maxTime: '18:00:00',
        allDaySlot: false,
        height: 'auto',
        events: events,
        eventClick: function(event) {
            alert('Matière: ' + event.title + '\n' +
                  'Horaire: ' + event.timeSlot);
        }
    });
    
    console.log('Calendrier initialisé !');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/timetable/calendar.blade.php ENDPATH**/ ?>