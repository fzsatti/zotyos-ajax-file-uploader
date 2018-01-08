#Ajax File Uploader

This package is offering you a more convient way to manage file uploads.

If you are building a **normal site**(AJAX is used only in few cases), handling file uploads alongside other inputs in the form is a bit tricky.

##### problem 1.
If the validation fails, normally you would redirect back the user to the form so he can correct the data for submiting again. This means the user has to find that file on his filesystem each time.

##### problem 2.
The other tricky thing is when you want to edit a resource wich has file. If the user leaves the file input empty you have to implement some logic to decide whether the user want to remove the file or just leave it unchanged.

This package is offering a solution for such cases.

### How it works
Submitting your form would need an anterior request for actually uploading the file

1. First you upload the file with an AJAX request in a separate form. If the upload succeeded, the response will contain the __ID of the file__ and it's URL(ex. if you want preview in IE7)
2. Then you just have to attach the __ID of the file__ to your original form(probably in a hidden input)
3. The users submits your original form, and you process the form. The file is already located on the server so you just handle it's ID.

Yes, in your database you only need to save the file's ID (the column should be 40 char long string). 
The path, url and other descriptive informations of the file will be accessible once you instantiated the __Zotyo\AjaxFileUploader\File__ object. 
Details of the file from the client's machine are also accessible. Each time you upload a file, a .json file will be also generated for storing client side informations. 
By default these .json files won't be accessible trough your webserver.

### Pros
* you don't have to create your own file upload from scratch
* it can be reused as any resource's file upload


### Cons
* you will need a little JavaScript

Validation __file-uploaded-in-current-session__ ships with the package. Use this validation against hackers who would change the file's ID. 


## Installation

Add the following line to you composer.json
```
"fzsatti/zsotyos-ajax-file-uploader": "dev-master"
```

After downloading the package, add PackageServiceProvider to the providers array of your config/app.php
```
Fzsatti\ZotyosAjaxFileUploader\PackageServiceProvider::class
```

Finally you should publish the config & assets of the package.
```
php artisan vendor:publish --provider="Fzsatti\ZotyosAjaxFileUploader\PackageServiceProvider"
```
This will create a *file-uploader.php* in your config directory.

## TODO
* Multiple file upload
* Private files. These files would have some prefix and the .htaccess file would block these. Serving such files would happen trough the framework
* Ideas are welcome :)