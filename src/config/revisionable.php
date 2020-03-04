<?php

return [
    'getResponsibleId' => function() {
        return backpack_user() ? backpack_user()->id : (
                    request()->user() ? request()->user()->id : (
                        request()->manager_id ? request()->manager_id : null
                    )
                );
    }
];
