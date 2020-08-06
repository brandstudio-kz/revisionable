<?php

return [
    'responsible_type' => 'App\User',
    'getResponsible' => function() {
        return backpack_user() ?? request()->user() ?? null;
    }
];
