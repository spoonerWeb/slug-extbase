<?php

$EM_CONF['slug_extbase'] = [
    'title' => 'Slug updater for Extbase',
    'description' => 'Utility to update slug fields after changing or creating Extbase objects in frontend',
    'version' => '1.0.2',
    'category' => 'misc',
    'state' => 'beta',
    'author' => 'Thomas Löffler',
    'author_email' => 'loeffler@spooner-web.de',
    'author_company' => 'Spooner Web',
    'clearCacheOnLoad' => true,
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0 - 10.4.99',
            'extbase' => '9.5.0 - 10.4.99',
        ]
    ]
];
