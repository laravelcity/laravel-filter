<?php

return [

    // set Filter Class and builder result
    'models' => [
        [
            /*
                'filter'=> \App\Library\Filters\User\UserFilterInAdmin::class,
                'query'=>[
                            'model'=> \App\Models\User::class,
                            'with'=>"roles"
                 ]
            */
        ]

    ] ,

    // set class for html tags
    'input-class' => 'form-control form-control-sm ',

    // set created date field for query
    'created_date_filed' => 'created_at'
];