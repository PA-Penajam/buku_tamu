<?php $pager->setSurroundCount(2) ?>

<ul class="pagination pagination-circle">
    <?php if ($pager->hasPrevious()) : ?>
        <li class="page-item first m-1">
            <a href="<?= $pager->getFirst() ?>" class="page-link px-0" aria-label="<?= lang('Pager.first') ?>">
                <i class="ki-duotone ki-double-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            </a>
        </li>
        <li class="page-item previous m-1">
            <a href="<?= $pager->getPrevious() ?>" class="page-link px-0" aria-label="<?= lang('Pager.previous') ?>">
                <i class="ki-duotone ki-left fs-2"></i>
            </a>
        </li>
    <?php else: ?>
        <li class="page-item first disabled m-1">
            <a href="#" class="page-link px-0" tabindex="-1" aria-disabled="true">
                <i class="ki-duotone ki-double-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            </a>
        </li>
        <li class="page-item previous disabled m-1">
            <a href="#" class="page-link px-0" tabindex="-1" aria-disabled="true">
                <i class="ki-duotone ki-left fs-2"></i>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?> m-1">
            <a href="<?= $link['uri'] ?>" class="page-link">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li class="page-item next m-1">
            <a href="<?= $pager->getNext() ?>" class="page-link px-0" aria-label="<?= lang('Pager.next') ?>">
                <i class="ki-duotone ki-right fs-2"></i>
            </a>
        </li>
        <li class="page-item last m-1">
            <a href="<?= $pager->getLast() ?>" class="page-link px-0" aria-label="<?= lang('Pager.last') ?>">
                <i class="ki-duotone ki-double-right fs-2"><span class="path1"></span><span class="path2"></span></i>
            </a>
        </li>
    <?php else: ?>
        <li class="page-item next disabled m-1">
            <a href="#" class="page-link px-0" tabindex="-1" aria-disabled="true">
                <i class="ki-duotone ki-right fs-2"></i>
            </a>
        </li>
        <li class="page-item last disabled m-1">
            <a href="#" class="page-link px-0" tabindex="-1" aria-disabled="true">
                <i class="ki-duotone ki-double-right fs-2"><span class="path1"></span><span class="path2"></span></i>
            </a>
        </li>
    <?php endif ?>
</ul>
