<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'PommProfiler\\ServiceManager\\PommProfiler' => 'PommProfiler\\ServiceManager\\PommProfiler',
        ),
        'aliases' => array(
            'pommProfiler' => 'PommProfiler\\ServiceManager\\PommProfiler',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'zend-developer-tools/toolbar/pomm-profiler' =>
            __DIR__ . '/../view/zend-developer-tools/toolbar/pomm-profiler.phtml',
        ),
    ),

   'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'pomm_profiler' => 'PommProfiler\\ServiceManager\\PommProfiler',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'pomm_profiler' => 'zend-developer-tools/toolbar/pomm-profiler',
            ),
        ),
    ), 
);
