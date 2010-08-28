<?php

/**
 * Base model for the application model
 *
 * @author marcelo
 */
abstract class Admin_Model_Abstract extends Model_Abstract
{

    /**
     * Admin_Form_Abstract
     */
    protected $_form;
    /**
     * @var String
     */
    protected $_tableName;
    /**
     * @var Admin_Form_Del
     */
    protected $_delForm;
    /**
     * @var Admin_Form_Search
     */
    protected $_searchForm;

    /**
     * Mapping of unique keys
     * @var array
     */
    protected $_ukMapping = array(
        'uk_sample' => array(
            'field' => 'field',
            'label' => 'field label',
            'message' => 'There is already a "{field label}" with value "{value}" '
        )
    );

    const SAVE_OK = 'SAVE_OK';
    const SAVE_ERROR = 'SAVE_ERROR';
    const REGISTER_NOT_FOUND = 'REGISTER_NOT_FOUND';
    const DELETED_ERROR = 'DELETED_ERROR';
    const DELETE_OK = 'DELETE_OK';
    const DELETE_CONFIRM = 'DELETE_CONFIRM';

    protected $_crudMessages = array(
        SAVE_OK => 'Registro salvo com sucesso.',
        SAVE_ERROR => '* Erro ao salvar registro:',
        REGISTER_DO_NOT_EXIST => 'Registro não encontrado.',
        DELETE_ERROR => 'O regististro não pode ser excluído. Certifique-se de que o mesmo exista e não possue vínculos.',
        DELETE_OK => 'Registro excluído com sucesso.',
        DELETE_CONFIRM => 'Tem certeza de que deseja excluir o seguinte registro?',
    );

    /**
     * Return the ordinariy del form
     * @return Admin_Form_Del
     */
    public function getDelForm($id)
    {
        if ($this->_delForm == null) {
            $this->_delForm = new Admin_Form_Del($id);
        }
        return $this->_delForm;
    }

    /**
     * Return the ordinariy del form
     * @param string $search
     * @return Admin_Form_Search
     */
    public function getSearchForm($search = null)
    {
        if ($this->_searchForm == null) {
            $this->_searchForm = new Admin_Form_Search($search);
        }
        return $this->_searchForm;
    }

    /**
     * Return a confirmation message for showing before deleting a rgister
     * @return string
     */
    public function getDelConfirmationMessage($id = null)
    {
        return $this->_crudMessages[self::DELETE_CONFIRM];
    }

    /**
     * Replaces a string
     * @param string $message
     * @param array $replacements where array key is the search part and the value the substitution
     * @return string
     */
    public function replace($message, array $replacements)
    {
        foreach ($replacements as $search => $replace) {
            $message = str_replace($search, $replace, $message);
        }
        return $message;
    }

    /**
     * Get a register by id
     * @param string $table Doctrine Table name
     * @param int $id
     * @return Doctrine_Record
     */
    public function getById($table, $id)
    {
        $register = Doctrine_Core::getTable($table)->find($id);
        if ($register == null) {
            throw new App_Exception_RegisterNotFound($this->_crudMessages[self::REGISTER_NOT_FOUND]);
        }
        return $register;
    }

    /**
     * Get a error by name
     * @var string $name
     */
    public function getError($name)
    {
        if (array_key_exists($name, $this->_errors)) {
            return $this->_errors[$name];
        }
        throw new Exception(sprintf('Error %s do not exist.', $name));
    }

    /**
     * Try to save a record
     * @param array $values
     * @return bool
     */
    public function save(array $values, $id = null)
    {
        try {
            if ($this->getForm()->isValid($values)) {
                $this->persist($values, $id);
                $this->addMessage($this->_crudMessages[self::SAVE_OK]);
                return true;
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->addMessage($this->_crudMessages[self::SAVE_ERROR]);
            $this->addMessage($e->getMessage());
        }
        return false;
    }

    /**
     * Persist a Record
     * @param array $values the values to persist
     * @param int $id the record id
     * @throws Exception
     * @return Admin_Model_Abstract
     */
    public function persist($values, $id = null)
    {
        if ($id !== null) {
            $record = $this->getById($this->getTablelName(), $id);
        } else {
            $record = Doctrine_Core::getTable($this->getTablelName())->create();
        }
        $record->fromArray($values);
        $record->save();
        return $this;
    }

    /**
     * Get all records
     * @param array $params for querying
     * @return DoctrineCollection
     */
    public function getCollection(array $params = array())
    {
        return $this->getListingDql($params)->execute();
    }

    /**
     * Replace underscore by dashes and removes non ascii chars
     *
     * @param string $string
     * @return string
     */
    public static function stringToUrl($string)
    {
        $replacements = array(
            '(a|á|à|â|ã|ä)' => 'a',
            '(e|é|è|ê|ë)' => 'e',
            '(i|í|ì|î|ï)' => 'i',
            '(o|ó|ò|ô|õ)' => 'o',
            '(u|ú|ù|û|ü)' => 'u',
            '(c|ç)' => 'c'
        );

        foreach ($replacements as $pattern => $replace) {
            $string = preg_replace('/' . $pattern . '/i', $replace, $string);
        }

        $string = preg_replace('/[_\s]/', '-', $string);
        $string = preg_replace('/[^\w-]/', '', $string);

        return $string;
    }

    /**
     * Get the table name where the persistence is made
     * @return string
     */
    public function getTablelName()
    {
        return $this->_tableName;
    }

    /**
     * Return a DQL for listing registers
     * @param array $params for querying
     * @return Doctrine_Query
     */
    public function getListingDql(array $params = array())
    {
        $dql = Doctrine_Core::getTable($this->getTablelName())
                ->createQuery()->orderBy('name ASC');

        if (array_key_exists('search', $params) && $params['search']) {
            $search = $params['search'];
            $dql->addWhere('name like ?', "%$search%");
        }
        return $dql;
    }

    /**
     * Populates a form
     * @param array $params
     * @throws App_Exception_RegisterNotFound case register wont exist
     * @return Admin_Model_Brand
     */
    public function populateForm($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        $this->getForm()->populate($record->toArray());
        return $this;
    }

    /**
     * Attempts to delete a record
     * @return bool
     */
    public function deleteRecord($id)
    {
        try {
            $record = $this->getById($this->getTablelName(), $id)->delete();
        } catch (Exception $e) {
            $this->addMessage($this->_crudMessages[self::DELETED_ERROR]);
            return false;
        }
        $this->addMessage($this->_crudMessages[self::DELETE_OK]);
        return true;
    }

    /**
     * Get DQL responsible for populating combo boxes
     * @param int $id
     * @return Doctrine_Query
     */
    public static function getSelectDql($id = null)
    {
        $dql = Doctrine_Query::create()->from($this->getTablelName());

        if ($id !== null) {
            $dql->where('id != ?', $id);
        }

        return $dql;
    }

}
