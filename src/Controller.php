<?php namespace Zotyo\AjaxFileUploader;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{

    public function upload(Request $request)
    {
        $validator = $this->validator($request);
        if ($validator->fails()) {
            return response()->json([
                    'error' => $validator->errors()->first('file')
                    ], 400);
        }


        $file = File::store($request->file($this->getInputFileName()));

        return response()->json($file->toArray());
    }

    private function validator(Request $request)
    {
        return Validator::make([
                'file' => $request->file($this->getInputFileName())
                ], [
                'file' => PackageServiceProvider::config('file-validation-rule')
        ]);
    }

    private function getInputFileName()
    {
        return PackageServiceProvider::config('file-name');
    }
}
