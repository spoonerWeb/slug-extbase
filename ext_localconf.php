<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)
    ->connect(
        \TYPO3\CMS\Extbase\Persistence\Generic\Backend::class,
        'afterUpdateObject',
        \SpoonerWeb\SlugExtbase\Slot\UpdateSlugSlot::class,
        'updateSlugForObject'
    );

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)
    ->connect(
        \TYPO3\CMS\Extbase\Persistence\Generic\Backend::class,
        'afterInsertObject',
        \SpoonerWeb\SlugExtbase\Slot\UpdateSlugSlot::class,
        'updateSlugForObject'
    );
