<?php

namespace Ibonly\PotatoORM;

/**
 * This class allows a user to upload and 
 * validate their files.
 *
 * @author John Ciacia <Sidewinder@extreme-hq.com>
 * @version 1.0
 * @copyright Copyright (c) 2007, John Ciacia
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
trait Upload {
    
    protected $file;
    /**
     *@var string contains an array of valid extensions which are allowed to be uploaded.
     */
    protected $ValidExtensions;
    /**
     *@var string contains a message which can be used for debugging.
     */
    protected $Message;
    /**
     *@var integer contains maximum size of fiels to be uploaded in bytes.
     */
    protected $MaximumFileSize;
    /**
     *@var bool contains whether or not the files being uploaded are images.
     */
    protected $IsImage;
    /**
     *@var string contains the email address of the recipient of upload logs.
     */
    protected $Email;
    /**
     *@var integer contains maximum width of images to be uploaded.
     */
    protected $MaximumWidth;
    /**
     *@var integer contains maximum height of images to be uploaded.
     */
    protected $MaximumHeight;

    // public function upload()
    // {

    // }

    public function file($file)
    {
        $this->output = $file;

        return $this;
    }

    // public function fileSize()
    // {
    //     return $this->output['size'];
    // }

    // public function fileTmp()
    // {
    //     return $this->output['tmp_name'];
    // }

    // public function fileName()
    // {
    //     return $this->output;
    // }

    // public function fileMove($destination, $fileName = NULL)
    // {
    //     $file_name = ($fileName == null) ? $this->fileName() : $fileName;
    //     if (move_uploaded_file($this->fileTmp(), $destination.'/'.$file_name)) {
    //         return 333;
    //     } else {
    //         return 000;
    //     }
    // }

    /**
     *@method bool ValidateExtension() returns whether the extension of file to be uploaded
     *    is allowable or not.
     *@return true the extension is valid.
     *@return false the extension is invalid.
     */
    public function ValidateExtension()
    {

        $FileName = trim($this->GetFileName());
        $FileParts = pathinfo($FileName);
        $Extension = strtolower($FileParts['extension']);
        $ValidExtensions = $this->ValidExtensions;

        if (!$FileName) {
            $this->SetMessage("ERROR: File name is empty.");
            return false;
        }

        if (!$ValidExtensions) {
            $this->SetMessage("WARNING: All extensions are valid.");
            return true;
        }

        if (in_array($Extension, $ValidExtensions)) {
            $this->SetMessage("MESSAGE: The extension '$Extension' appears to be valid.");
            return true;
        } else {
            $this->SetMessage("Error: The extension '$Extension' is invalid.");
            return false;  
        }
    }

    /**
     *@method bool ValidateSize() returns whether the file size is acceptable.
     *@return true the size is smaller than the alloted value.
     *@return false the size is larger than the alloted value.
     */
    public function ValidateSize()
    {
        $MaximumFileSize = $this->MaximumFileSize;
        $TempFileName = $this->GetTempName();
        $TempFileSize = filesize($TempFileName);

        if($MaximumFileSize == "") {
            $this->SetMessage("WARNING: There is no size restriction.");
            return true;
        }

        if ($MaximumFileSize >= $TempFileSize) {
            $this->SetMessage("ERROR: The file is too big. It must be less than $MaximumFileSize and it is $TempFileSize.");
            return false;
        }

        $this->SetMessage("Message: The file size is less than the MaximumFileSize.");
        return true;
    }

    /**
     *@method bool ValidateExistance() determins whether the file already exists. If so, rename $FileName.
     *@return true can never be returned as all file names must be unique.
     *@return false the file name does not exist.
     */
    public function ValidateExistance($uploadDirectory)
    {
        $FileName = $this->GetFileName();
        $File = $uploadDirectory . $FileName;

        if (file_exists($File)) {
            $this->SetMessage("Message: The file '$FileName' already exist.");
            $UniqueName = rand() . $FileName;
            $this->SetFileName($UniqueName);
            $this->ValidateExistance($uploadDirectory);
        } else {
            $this->SetMessage("Message: The file name '$FileName' does not exist.");
            return false;
        }
    }

    /**
     *@method bool ValidateDirectory()
     *@return true the UploadDirectory exists, writable, and has a traling slash.
     *@return false the directory was never set, does not exist, or is not writable.
     */
    public function ValidateDirectory($uploadDirectory)
    {
        if (! $uploadDirectory) {
            $this->SetMessage("ERROR: The directory variable is empty.");
            return false;
        }

        if (!is_dir($uploadDirectory)) {
            $this->SetMessage("ERROR: The directory '$uploadDirectory' does not exist.");
            return false;
        }

        if (!is_writable($uploadDirectory)) {
            $this->SetMessage("ERROR: The directory '$uploadDirectory' does not writable.");
            return false;
        }

        if (substr($uploadDirectory, -1) != "/") {
            $this->SetMessage("ERROR: The traling slash does not exist.");
            $NewDirectory = $uploadDirectory . "/";
            $this->SetUploadDirectory($NewDirectory);
            $this->ValidateDirectory($uploadDirectory);
        } else {
            $this->SetMessage("MESSAGE: The traling slash exist.");
            return true;
        }
    }

    /**
     *@method bool ValidateImage()
     *@return true the image is smaller than the alloted dimensions.
     *@return false the width and/or height is larger then the alloted dimensions.
     */
    public function ValidateImage() {
        $MaximumWidth = $this->MaximumWidth;
        $MaximumHeight = $this->MaximumHeight;
        $TempFileName = $this->GetTempName();

        if($Size = @getimagesize($TempFileName)) {
            $Width = $Size[0];   //$Width is the width in pixels of the image uploaded to the server.
            $Height = $Size[1];  //$Height is the height in pixels of the image uploaded to the server.
        }

        if ($Width > $MaximumWidth) {
            $this->SetMessage("The width of the image [$Width] exceeds the maximum amount [$MaximumWidth].");
            return false;
        }

        if ($Height > $MaximumHeight) {
            $this->SetMessage("The height of the image [$Height] exceeds the maximum amount [$MaximumHeight].");
            return false;
        }

        $this->SetMessage("The image height [$Height] and width [$Width] are within their limitations.");     
        return true;
    }

    /**
     *@method bool UploadFile() uploads the file to the server after passing all the validations.
     *@return true the file was uploaded.
     *@return false the upload failed.
     */
    public function uploadFile($uploadDirectory)
    {

        // if (!$this->ValidateExtension()) {
        //     die($this->GetMessage());
        // } 

        // else if (!$this->ValidateSize()) {
        //     die($this->GetMessage());
        // }

        // else if ($this->ValidateExistance($uploadDirectory)) {
        //     die($this->GetMessage());
        // }

        // elseif (!$this->ValidateDirectory($uploadDirectory)) {
        //     die($this->GetMessage());
        // }

        // else if ($this->IsImage && !$this->ValidateImage()) {
        //     die($this->GetMessage());
        // }

        // else {

            $FileName = time()."_".str_replace(' ', '_', $this->GetFileName());
            $TempFileName = $this->GetTempName();

            if (is_uploaded_file($TempFileName)) { 
                move_uploaded_file($TempFileName, $_SERVER['DOCUMENT_ROOT'].'/'.$uploadDirectory.'/'.$FileName);
                return $FileName;
            } else {
                return 4444;
            }

        // }

    }

    #Accessors and Mutators beyond this point.
    #Siginificant documentation is not needed.
    public function SetValidExtensions($argv)
    {
        $this->ValidExtensions = $argv;
    }

    public function SetMessage($argv)
    {
        $this->Message = $argv;
    }

    public function SetMaximumFileSize($argv)
    {
        $this->MaximumFileSize = $argv;
    }

    public function SetEmail($argv)
    {
        $this->Email = $argv;
    }
   
    public function SetIsImage($argv)
    {
        $this->IsImage = $argv;
    }

    public function SetMaximumWidth($argv)
    {
        $this->MaximumWidth = $argv;
    }

    public function SetMaximumHeight($argv)
    {
        $this->MaximumHeight = $argv;
    }   
    public function GetFileName()
    {
        return $this->output['name'];
    }

    public function GetUploadDirectory()
    {
        return $this->UploadDirectory;
    }

    public function GetTempName()
    {
        return $this->output['tmp_name'];
    }

    public function GetValidExtensions()
    {
        return $this->ValidExtensions;
    }

    public function GetMessage()
    {
        if (!isset($this->Message)) {
            $this->SetMessage("No Message");
        }

        return $this->Message;
    }

    public function GetMaximumFileSize()
    {
        return $this->MaximumFileSize;
    }

    public function GetIsImage()
    {
        return $this->IsImage;
    }

    public function GetMaximumWidth()
    {
        return $this->MaximumWidth;
    }

    public function GetMaximumHeight()
    {
        return $this->MaximumHeight;
    }
}