<? $paginator = $data['paginator']; ?>
<? if ($paginator->num_pages() > 1): ?>
    <ul class="pagination"> 
        <? if ($paginator->first_page() != $paginator->current_page()): ?>
            <li class="pagination__item">
                <a class="pagination__link"
                   href="<?= $paginator->get_link($paginator->first_page()) ?>">
                    <<
                </a>
            </li>
        <? endif; ?>

        <? if ($paginator->prev_page() != $paginator->current_page()): ?>
            <li class="pagination__item">
                <a class="pagination__link"
                   href="<?= $paginator->get_link($paginator->prev_page()) ?>">
                    <
                </a>
            </li>
        <? endif; ?>

        <? for ($page_num = 1; $page_num <= $paginator->num_pages(); ++$page_num): ?>
            <li class="pagination__item">
                <a href="<?= $paginator->get_link($page_num) ?>"
                   class="pagination__link <? if ($paginator->current_page() == $page_num): ?> pagination__link-active <? endif; ?>">
                    <?= $page_num ?>
                </a>
            </li>
        <? endfor; ?>
        
        <? if ($paginator->next_page() != $paginator->current_page()): ?>
            <li class="pagination__item">
                <a class="pagination__link"
                   href="<?= $paginator->get_link($paginator->next_page()) ?>">
                    >
                </a>
            </li>
        <? endif; ?>
        
        <? if ($paginator->last_page() != $paginator->current_page()): ?>
            <li class="pagination__item">
                <a class="pagination__link"
                   href="<?= $paginator->get_link($paginator->last_page()) ?>">
                    >>
                </a>
            </li>
        <? endif; ?>
    </ul>
<? endif; ?>
