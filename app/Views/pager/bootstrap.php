<?php
/**
 * Tampilan nomor halaman (pagination) dengan gaya Bootstrap.
 */
$pager->setSurroundCount(2);
?>

<?php if ($pager->getPageCount() > 1): ?>
<nav class="d-flex flex-wrap justify-content-between align-items-center gap-2">

    <!-- Keterangan: sedang di halaman berapa -->
    <small class="text-muted">
        Halaman <strong><?= $pager->getCurrentPageNumber() ?></strong>
        dari <strong><?= $pager->getPageCount() ?></strong>
    </small>

    <ul class="pagination pagination-sm mb-0">

        <!-- Ke halaman pertama -->
        <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getFirst() ?>" title="Halaman pertama">&laquo;</a>
        </li>

        <!-- Sebelumnya -->
        <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getPrevious() ?? '#' ?>">Sebelumnya</a>
        </li>

        <!-- Nomor halaman -->
        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
            </li>
        <?php endforeach; ?>

        <!-- Berikutnya -->
        <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getNext() ?? '#' ?>">Berikutnya</a>
        </li>

        <!-- Ke halaman terakhir -->
        <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getLast() ?>" title="Halaman terakhir">&raquo;</a>
        </li>
    </ul>
</nav>
<?php endif; ?>
