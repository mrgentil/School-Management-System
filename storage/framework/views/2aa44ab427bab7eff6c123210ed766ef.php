
<?php $__env->startSection('page_title', 'Calendrier Scolaire'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar mr-2"></i> Calendrier Scolaire
                </h5>
                <a href="<?php echo e(route('calendar.create')); ?>" class="btn btn-light btn-sm">
                    <i class="icon-plus2 mr-1"></i> Nouvel Événement
                </a>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3">
        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="icon-alarm mr-2"></i> Prochains Événements
                </h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-2 border-bottom d-flex align-items-center">
                        <div class="mr-2" style="width: 4px; height: 40px; background: <?php echo e($event->color ?? '#2196F3'); ?>; border-radius: 2px;"></div>
                        <div class="flex-grow-1">
                            <strong><?php echo e($event->title); ?></strong>
                            <br><small class="text-muted"><?php echo e($event->formatted_date); ?></small>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-center text-muted">
                        Aucun événement à venir
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="icon-palette mr-2"></i> Légende
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-2 border-bottom">
                    <span class="badge" style="background: #4CAF50; color: white;">🏖️ Congé/Vacances</span>
                </div>
                <div class="p-2 border-bottom">
                    <span class="badge" style="background: #F44336; color: white;">📝 Examen</span>
                </div>
                <div class="p-2 border-bottom">
                    <span class="badge" style="background: #9C27B0; color: white;">👥 Réunion</span>
                </div>
                <div class="p-2 border-bottom">
                    <span class="badge" style="background: #FF9800; color: white;">⏰ Date limite</span>
                </div>
                <div class="p-2 border-bottom">
                    <span class="badge" style="background: #00BCD4; color: white;">🎉 Activité</span>
                </div>
                <div class="p-2">
                    <span class="badge" style="background: #2196F3; color: white;">📅 Événement</span>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="icon-lightning mr-2"></i> Actions Rapides
                </h6>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('calendar.create')); ?>?type=holiday" class="btn btn-success btn-sm btn-block mb-2">
                    🏖️ Ajouter Congé
                </a>
                <a href="<?php echo e(route('calendar.create')); ?>?type=exam" class="btn btn-danger btn-sm btn-block mb-2">
                    📝 Ajouter Examen
                </a>
                <a href="<?php echo e(route('calendar.create')); ?>?type=meeting" class="btn btn-purple btn-sm btn-block">
                    👥 Ajouter Réunion
                </a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="eventType"></p>
                <p><strong>Date:</strong> <span id="eventDate"></span></p>
                <p id="eventDescription"></p>
            </div>
            <div class="modal-footer">
                <a href="#" id="eventEdit" class="btn btn-primary">Modifier</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        events: '<?php echo e(route("calendar.events")); ?>',
        eventClick: function(info) {
            fetch('<?php echo e(url("calendar")); ?>/' + info.event.id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('eventTitle').textContent = data.title;
                    document.getElementById('eventType').innerHTML = data.type_badge;
                    document.getElementById('eventDate').textContent = data.start_date + (data.end_date ? ' - ' + data.end_date : '');
                    document.getElementById('eventDescription').textContent = data.description || 'Pas de description';
                    document.getElementById('eventEdit').href = '<?php echo e(url("calendar")); ?>/' + data.id + '/edit';
                    $('#eventModal').modal('show');
                });
        },
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        },
        dayMaxEvents: 3,
        height: 'auto',
    });
    
    calendar.render();
});
</script>

<style>
    .fc-event { cursor: pointer; }
    .fc-toolbar-title { font-size: 1.2rem !important; }
    .btn-purple { background: #9C27B0; color: white; }
    .btn-purple:hover { background: #7B1FA2; color: white; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/calendar/index.blade.php ENDPATH**/ ?>