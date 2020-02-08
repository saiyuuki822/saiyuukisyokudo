<?php

return [
  'settings' => [
    'index' => [
      'select' => [
        'my_theme' => [
          "condition" => [],
          "is_list" => true,
          "key_name" => null,
          "order" => ["sort" => "asc"]
        ],
        'my_theme_color' => [
          "condition" => [],
          "is_list" => true,
          "key_name" => null,
          "order" => ["sort" => "asc"]
        ],
      ],
      'save' => [
        'my_user_theme' => [
          'condition' => ["uid" => "uid"],
          'data' => [
            'uid' => 'uid',
            'theme_id' => 'theme',
            'theme_color' => 'theme_color'
          ]
        ]
      ]
    ]
  ],
//  'api' => [
//    'user_post' => [
//      'save' => [
//        'my_pass_token' => [
//          'condition' => ["uid" => "uid"],
//          'data' => [
//            'uid' => 'uid',
//            'token' => 'token'
//          ]
//        ]
//      ]
//    ]
//  ]
];