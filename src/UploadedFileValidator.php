<?php namespace Zotyo\AjaxFileUploader;

use Exception as AnyException;
use Illuminate\Validation\Validator;

class UploadedFileValidator
{

    public function fileUploadedInCurrentSession($attribute, $value, $parameters, $validator)
    {
        try {
            $file = File::findOrFail($value);
        } catch (AnyException $ex) {
            $this->addValidationMessage($validator);
            return false;
        }

        $wasUploadedInCurrentSession = $file->wasUploadedInCurrentSession();
        $isAcceptable                = $this->isException($file, $parameters);

        if (!$wasUploadedInCurrentSession && !$isAcceptable) {
            $this->addValidationMessage($validator);
            return false;
        }

        return true;
    }

    private function isException(File $file, $parameters)
    {
        return in_array($file->file(), $parameters);
    }

    private function addValidationMessage(Validator $validator)
    {
        $validator->setFallbackMessages([
            'file_uploaded_in_current_session' => 'Can not use the uploaded file! Please try to upload it again.',
        ]);
    }
}
