<?php
return [
    'relations' => [
        // 'hasMany' => [
        //     'Institutions' => [
        //        'foreignKey' => 'user_id',
        //         'table' => 'institutions',
        //         'className' => 'Institutions'
        //     ],
        //     'Resources' => [
        //         'foreignKey' => 'user_id',
        //         'table' => 'resources',
        //         'className' => 'Resources'
        //     ],
        //     'Students' => [
        //         'foreignKey' => 'user_id',
        //         'table' => 'students',
        //         'className' => 'Students'
        //     ],
        //     'Teachers' => [
        //         'foreignKey' => 'user_id',
        //         'table' => 'teachers',
        //         'className' => 'Teachers'
        //     ],
        //     'Tutors' => [
        //         'foreignKey' => 'user_id',
        //         'table' => 'tutors',
        //         'className' => 'Tutors'
        //     ],
        // ],
        'belongsTo' => [
            'Addresses' => [
               'foreignKey' => 'user_id',
               'table' => 'addresses',
               'className' => 'Addresses'
            ]
        ]
    ]
];
