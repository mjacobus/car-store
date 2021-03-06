<?php

/**
 * Base_VehicleFeature
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $vehicle_id
 * @property integer $priority
 * @property string $description
 * @property Vehicle $Vehicle
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_VehicleFeature extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('vehicle_feature');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('vehicle_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('priority', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));


        $this->index('vehicle_id_description_uk', array(
             'fields' => 
             array(
              'vehicle_id' => 
              array(
              ),
              'description' => 
              array(
              ),
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Vehicle', array(
             'local' => 'vehicle_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}