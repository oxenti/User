<?php
return [
    'user_plugin' => [
        'relations' => [
            'belongsTo' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'class_id',
                //     'bindingKey' => 'id',
                //     'conditions' => ['Class.is_active' => true],
                //     'joinType' => 'INNER',
                //     'finder' => 'all'
                // ]
            ],
            'belongsToMany' => [
                'Addresses' => [
                    'className' => 'Address.Addresses',
                    'joinTable' => 'addresses_users',
                    'foreignKey' => 'user_id',
                    'targetForeignKey' => 'address_id',
                    'conditions' => ['AddressesUsers.is_active' => true],
                    'through' => 'AddressesUsers',
                    'saveStrategy' => 'replace',
                ],
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'joinTable' => 'addresses_classes',
                //     'foreignKey' => 'address_id',
                //     'targetForeignKey' => 'class_id',
                //     'conditions' => ['Class.is_active' => true],
                //     'through' => 'AddressesClasses',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'class',
                //     'strategy' => 'select',
                //     'saveStrategy' => 'replace',
                //     'finder' => 'all'
                // ]
            ],
            'hasOne' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'class_id',
                //     'bindingKey' => '',
                //     'conditions' => ['Class.is_active' => true],
                //     'joinType' => 'INNER',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'class',
                //     'finder' => 'all'
                // ]
            ],
            'hasMany' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'address_id',
                //     'bindingKey' => 'id',
                //     'conditions' => ['Class.is_active' => true],
                //     'sort' => ['Class.field' => 'ASC'],
                //     'joinType' => 'INNER',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'classes',
                //     'strategy' => 'select',
                //     'finder' => 'all'
                // ]
            ]
        ],
        'rules' => [
            // 'ruleName' => [
            // 	'keys' => [],
            // 	'tableName' => ''
            // ],
            
            // 'existsIn' => [
            // 	'keys' => [],
            // 	'tableName' => []
            // ]
        ]
    ]
];
