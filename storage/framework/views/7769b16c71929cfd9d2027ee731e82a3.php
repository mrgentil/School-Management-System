
<?php $__env->startSection('page_title', 'Calendrier Scolaire'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar mr-2"></i> Calendrier Scolaire
                </h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="icon-alarm mr-2"></i> Prochains Événements
                </h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-start">
                            <div class="mr-3 text-center" style="min-width: 50px;">
                                <div class="bg-light rounded p-2">
                                    <strong class="text-primary" style="font-size: 18px;">
                                        <?php echo e(($event->start_date ?? $event->event_date)?->format('d')); ?>

                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo e(($event->start_date ?? $event->event_date)?->translatedFormat('M')); ?>

                                    </small>
                                </div>
                            </div>
                            <div>
                                <strong><?php echo e($event->title); ?></strong>
                                <br>
                                <?php echo $event->type_badge; ?>

                                <?php if($event->description): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($event->description, 50)); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 32px;"></i>
                        Aucun événement à venir
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-palette mr-2"></i> Légende</h6>
            </div>
            <div class="card-body py-2">
                <div class="d-flex flex-wrap">
                    <span class="badge mr-2 mb-1" style="background: #4CAF50; color: white;">🏖️ Congé</span>
                    <span class="badge mr-2 mb-1" style="background: #F44336; color: white;">📝 Examen</span>
                    <span class="badge mr-2 mb-1" style="background: #9C27B0; color: white;">👥 Réunion</span>
                    <span class="badge mr-2 mb-1" style="background: #FF9800; color: white;">⏰ Deadline</span>
                    <span class="badge mr-2 mb-1" style="background: #00BCD4; color: white;">🎉 Activité</span>
                </div>
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
                <p><i class="icon-calendar mr-2"></i><strong>Date:</strong> <span id="eventDate"></span></p>
                <p id="eventDescription"></p>
            </div>
            <div class="modal-footer">
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
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            list: 'Liste'
        },
        events: '<?php echo e(route("calendar.public.events")); ?>',
        eventClick: function(info) {
            fetch('<?php echo e(url("view-calendar")); ?>/' + info.event.id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('eventTitle').textContent = data.title;
                    document.getElementById('eventType').innerHTML = data.type_badge;
                    document.getElementById('eventDate').textContent = data.start_date + (data.end_date && data.end_date != data.start_date ? ' - ' + data.end_date : '');
                    document.getElementById('eventDescription').textContent = data.description || '';
                    $('#eventModal').modal('show');
                });
        },
        height: 'auto',
        dayMaxEvents: 3,
    });
    calendar.render();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/calendar/view.blade.php ENDPATH**/ ?>