<?php

namespace Zotyo\AjaxFileUploader\Exceptions;

use Exception;

class FileNotFoundException extends Exception {

        public $message = 'The referenced file is not uploaded';

}
