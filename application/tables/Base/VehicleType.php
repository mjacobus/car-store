<?php

/**
 * Base_VehicleType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $license
 * @property Doctrine_Collection $Vehicles
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_VehicleType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('vehicle_type');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('license', 'string', 5, array(
             'type' => 'string',
             'unique' => true,
             'length' => '5',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Vehicle as Vehicles', array(
             'local' => 'id',
             'foreign' => 'type_id',
             'onDelete' => 'RESTRICT'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}