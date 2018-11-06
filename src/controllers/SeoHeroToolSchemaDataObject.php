<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;


/**
 *  Class SeoHeroToolSchemaDataObject extends the DataObject and gives the possiblity for pages
 *  to add a schema.org definition via the .yml configuration.
 *  It is possible to add normal text values and also to add Variables, including has_one connections and methods.
 */
class SeoHeroToolSchemaDataObject extends DataExtension
{
    public $emptyValues = false;

    public function recursive_search_and_replace(&$arr)
    {
        foreach ($arr as $idx => $val) {
            if (is_array($val)) {
                $this->recursive_search_and_replace($arr[$idx]);
            } else {
                if (substr($val, 0, 1) == '$') {
                    // valueForReturn removes the $ to get the variable name
                    $valueForReturn = substr($val, 1);
                    // If the variable contains a . then there is a has_one connection
                    if (strpos($valueForReturn, '.')) {
                        // Has One Connection
                        $HasOneArray = explode(".", $valueForReturn);
                        $object = $this->owner->{$HasOneArray[0]}();
                        if (isset($object->$HasOneArray[1]) && $object->ID != 0) {
                            $arr[$idx] = $object->$HasOneArray[1];
                        } else {
                            $this->emptyValues = true;
                        }
                    } elseif (strpos($valueForReturn, '()')) {

                        // Method
                        $value = substr($valueForReturn, 0, -2);

                        if (method_exists($this->owner->ClassName, $value)) {
                            $realValue = $this->owner->{$value}();
                            if (isset($realValue) && $realValue != '') {
                                $arr[$idx] = str_replace($val, $realValue, $val);
                            } else {
                                $this->emptyValues = true;
                            }
                        } else {
                            $this->emptyValues = true;
                        }
                    } else {
                        // Variable
                        if (isset($this->owner->{$valueForReturn}) && $this->owner->{$valueForReturn} != '') {
                            $arr[$idx] = str_replace($val, $this->owner->{$valueForReturn}, $val);
                        } else {
                            $this->emptyValues = true;
                        }
                    }
                }
            }
        }
    }


    public function getDisplayForBackend()
    {
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolSchemaDataObject', $classname);
        if ($yamlsettings) {
            $return = $this->convertSchemaSettings($yamlsettings, true);
            return $return;
        } else {
            return false;
        }
    }

    private function noReturnPossible()
    {
        return false;
    }

    public function convertSchemaSettings($settings, $ignoreEmptyValaue = false)
    {
        $replacedSettings = $this->recursive_search_and_replace($settings);
        if ($this->emptyValues && !$ignoreEmptyValaue) {
            return false;
        }
        return json_encode($settings, JSON_PRETTY_PRINT);
    }

    public function getSchemaData()
    {
        $classname = $this->owner->ClassName;
        $yamlsettings = config::inst()->get('SeoHeroToolSchemaDataObject', $classname);
        if ($yamlsettings) {
            $return = $this->convertSchemaSettings($yamlsettings);
            return $return;
        }
    }
}
