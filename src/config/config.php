<?php
return [
    /*
     * The relative path of the folder where the package stores the files
     */
    'relative_path'         => 'zotyo/ajax-file-uploader/uploads',
    /*
     * Validation rule for the file
     * There should be separate rule for each file upload
     */
    'file-validations' => [
        [
            'url'   => 'upload',
            'alias' => 'upload',
            'rule'  => 'required|min:0',
        ]
    ],
    /*
     * Name of the file input the package is waiting for
     */
    'file-name'             => 'file',
];
