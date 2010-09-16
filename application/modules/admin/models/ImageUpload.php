<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_ImageUpload extends Admin_Model_Abstract
{

    protected $_tableName = 'Image';
    protected $_ukMapping = array(
        'filename' => array(
            'field' => 'filename',
            'label' => 'Nome do Arquivo',
            'message' => 'Um registro já existe com "{label}" igual a "{value}" '
        ),
        'md5' => array(
            'field' => 'md5',
            'label' => 'Imagem',
            'message' => 'Esta imagem já está cadastrada. Para encontrá-la pesquise por "{value}"'
        )
    );

    /**
     * Get the form
     * @param array $params
     * @return Admin_Form_Brand
     */
    public function getForm(array $params = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_ImageUpload($params);
        }
        return $this->_form;
    }

    /**
     * Get request for a image
     * @return string
     */
    public function getRequestFormImage($filename, $width = 200, $height = 200)
    {
        $request = Model_Image::getInstance()
                ->setFile($filename . ".png")
                ->setWidth($width)
                ->setHeight($height)
                ->getRequest();

        $request = Zend_Controller_Front::getInstance()->getBaseUrl()
            . '/image' . $request;
        return $request;
    }

    /**
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the Brand record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        $this->getRequestFormImage($record->filename);
        $imgTag = '<div><img src="' . $request . '" alt="imagem não encontrada" /></div>';
        return sprintf('Tem certeza que deseja excluir a imagem "%s (%s)"? %s',
            $record->filename, $record->description, $imgTag);
    }

    /**
     * Return a DQL for listing registers
     * @param array $params for querying
     * @return Doctrine_Query
     */
    public function getListingDql(array $params = array())
    {
        $dql = Doctrine_Core::getTable($this->getTablelName())
                ->createQuery();

        if (array_key_exists('search', $params) && $params['search']) {
            $search = $params['search'];
            $dql->addWhere('filename like ?', "%$search%")
                ->orWhere('md5 like ?', "%$search%")
                ->orWhere('description like ?', "%$search%");
        }
        return $dql;
    }

    /**
     * Persist register and save image
     * @param array $values
     * @param int $id
     * @return bool
     */
    public function save(array $values, $id = null)
    {
        $values['filename'] = Util_String::toUrl($values['filename']);
        $form = $this->getForm();
        $fileRenamed = false;
        $savedOn = false;

        //on update image is not required
        if ($id !== null) {
            $form->setRequired($form->file,false);
            $register = $this->getById($this->getTablelName(), $id);
            $oldFilename = $register->filename;
            $fileRenamed = ($oldFilename !== $values['filename']);
        }

        //get image
        if ($form->isValid($values)) {
            if (strlen($form->file->getValue())) {
                try {
                    $savedOn = $this->saveFile($form);
                    $values['md5'] = md5_file($savedOn);
                } catch (Exception $e) {
                    $this->addMessage($e->getMessage());
                    return false;
                }
            }
        }



        if (parent::save($values, $id)) {

            $model = Model_Image::getInstance();
            $path = $model->getOriginalPath();

            if ($savedOn) {
                $this->deleteImage($oldFilename);
                $saveTo = $path . '/' . $form->getValue('filename') . '.png';
                rename($savedOn, $saveTo);
            } else if ($fileRenamed) {
                $this->deleteResized($oldFilename);
                $oldName = "$path/$oldFilename.png";
                $newName = "$path/" . $form->getValue('filename') . ".png";
                rename($oldName,$newName);
            }
            return true;
        }

        return false;
    }

    /**
     *
     * @param Admin_Form_ImageUpload $form
     * @return string md5
     */
    public function saveFile(Admin_Form_ImageUpload $form)
    {
        $file = $form->file;

        if (!$file->receive()) {
            throw new Exception("Não foi possível fazer o upload do arquivo");
        }

        $savedOn = $file->getFileName();
        $tmpFile = $savedOn . ".png";

        WideImage::load($savedOn)->saveToFile($tmpFile);
        unlink($savedOn);
        return $tmpFile;
    }

    /**
     * Attempts to delete a record
     * @return bool
     */
    public function deleteRecord($id)
    {
        try {
            $record = $this->getById($this->getTablelName(), $id);
            if (parent::deleteRecord($id)) {
                $this->deleteImage($record->filename);
                return true;
            }
        } catch (Exception $e) {

        }
        return false;
    }

    /**
     * Delete image from the filesystem
     * @param string $name
     */
    public function deleteImage($name)
    {
        $this->deleteResized($name);
        $dir = Model_Image::getInstance()->getOriginalPath();
        unlink("$dir/$name.png");
    }

    /**
     * Delete resized versions of a image
     * @param string $name
     */
    public function deleteResized($name)
    {
        $pattern = "/^" . preg_quote($name) . "_(\d+)x(\d+)\.png$/i";
        $dir = Model_Image::getInstance()->getResizedPath();
        $dh = opendir($dir);

        while (false !== ($filename = readdir($dh))) {
            if (preg_match($pattern, $filename)) {
                unlink("$dir/$filename");
            }
        }
    }

    /**
     * Populates a form
     * @param int $id
     * @throws App_Exception_RegisterNotFound case register wont exist
     * @return Admin_Model_Brand
     */
    public function populateForm($id)
    {
        parent::populateForm($id);
        $form = $this->getForm();
        $filename = $form->getValue('filename');
        $image = $this->getRequestFormImage($filename, 400, 400);
        $form->image->setImage($image);
        return $this;
    }

    /**
     * Get DQL responsible for populating combo boxes
     * @param int $id
     * @return Doctrine_Query
     */
    public static function getSelectDql($id = null)
    {
        $dql = Doctrine_Query::create()->from('Image');

        if ($id !== null) {
            $dql->where('id != ?', $id);
        }

        return $dql;
    }

}