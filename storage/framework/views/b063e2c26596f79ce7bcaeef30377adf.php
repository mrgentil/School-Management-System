<?php $__env->startSection('page_title', 'Gestion des Livres'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Liste des Livres</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a href="<?php echo e(route('librarian.books.create')); ?>" class="btn btn-primary">
                    <i class="icon-plus2 mr-2"></i> Ajouter un Livre
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtres -->
        <form method="GET" action="<?php echo e(route('librarian.books.index')); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher (titre, auteur, ISBN...)" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="book_type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="Manuel" <?php echo e(request('book_type') == 'Manuel' ? 'selected' : ''); ?>>Manuel</option>
                        <option value="Roman" <?php echo e(request('book_type') == 'Roman' ? 'selected' : ''); ?>>Roman</option>
                        <option value="Référence" <?php echo e(request('book_type') == 'Référence' ? 'selected' : ''); ?>>Référence</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="availability" class="form-control">
                        <option value="">Tous</option>
                        <option value="available" <?php echo e(request('availability') == 'available' ? 'selected' : ''); ?>>Disponibles</option>
                        <option value="unavailable" <?php echo e(request('availability') == 'unavailable' ? 'selected' : ''); ?>>Non disponibles</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="icon-search4"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Tableau des livres -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Type</th>
                        <th>Copies Totales</th>
                        <th>Copies Empruntées</th>
                        <th>Disponible</th>
                        <th>Localisation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($book->id); ?></td>
                        <td>
                            <strong><?php echo e($book->name); ?></strong>
                            <?php if($book->description): ?>
                                <br><small class="text-muted"><?php echo e(Str::limit($book->description, 50)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($book->author ?? 'N/A'); ?></td>
                        <td><?php echo e($book->book_type ?? 'N/A'); ?></td>
                        <td><?php echo e($book->total_copies ?? 0); ?></td>
                        <td><?php echo e($book->issued_copies ?? 0); ?></td>
                        <td>
                            <?php if($book->available): ?>
                                <span class="badge badge-success">Oui</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Non</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($book->location ?? 'N/A'); ?></td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="<?php echo e(route('librarian.books.show', $book->id)); ?>" class="dropdown-item">
                                            <i class="icon-eye"></i> Voir
                                        </a>
                                        <a href="<?php echo e(route('librarian.books.edit', $book->id)); ?>" class="dropdown-item">
                                            <i class="icon-pencil"></i> Modifier
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) document.getElementById('delete-form-<?php echo e($book->id); ?>').submit();" class="dropdown-item text-danger">
                                            <i class="icon-trash"></i> Supprimer
                                        </a>
                                        <form id="delete-form-<?php echo e($book->id); ?>" action="<?php echo e(route('librarian.books.destroy', $book->id)); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="icon-info22 mr-2"></i> Aucun livre trouvé
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($books->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/librarian/books/index.blade.php ENDPATH**/ ?>