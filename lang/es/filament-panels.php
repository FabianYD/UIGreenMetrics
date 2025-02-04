<?php

return [
    'pages' => [
        'dashboard' => [
            'title' => 'Panel de Control',
        ],
    ],

    'resources' => [
        'label' => 'Recursos',
        'modal' => [
            'heading' => 'Crear :label',
        ],
    ],

    'actions' => [
        'create' => 'Crear',
        'edit' => 'Editar',
        'view' => 'Ver',
        'delete' => 'Eliminar',
        'restore' => 'Restaurar',
        'force_delete' => 'Eliminar permanentemente',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
    ],

    'table' => [
        'actions' => [
            'edit' => [
                'label' => 'Editar',
            ],
            'view' => [
                'label' => 'Ver detalles',
            ],
            'delete' => [
                'label' => 'Eliminar',
            ],
        ],

        'bulk_actions' => [
            'delete' => [
                'label' => 'Eliminar seleccionados',
            ],
        ],

        'fields' => [
            'id' => 'ID',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ],

        'filters' => [
            'label' => 'Filtros',
        ],

        'empty' => [
            'heading' => 'No se encontraron registros',
            'description' => 'Crea un registro para empezar.',
        ],
    ],

    'widgets' => [
        'account' => [
            'label' => 'Cuenta',
        ],
        'filament_info' => [
            'label' => 'Información de Filament',
        ],
    ],
];
