<?php

/**
 * Text input field with validation for latitude and lingitude values
 *
 * @package forms
 * @subpackage fields-formattedinput
 */
class LatLngField extends TextField
{


  /**
   * @config
   * @var array
   */
  private static $default_config = array(
    'lat' => false,
    'lng' => false
  );

  /**
   * @var array
   */
  protected $config;

  /**
     * {@inheritdoc}
     */
    public function Type()
    {
        return 'lat & lng text';
    }



    /**
     * Validates for RFC 2822 compliant email addresses.
     *
     * @see http://www.regular-expressions.info/email.html
     * @see http://www.ietf.org/rfc/rfc2822.txt
     *
     * @param Validator $validator
     *
     * @return string
     */
    public function validate($validator)
    {
        $this->value = trim($this->value);
        if (strlen($this->value) == 0) {
            return true;
        }
        if ($this->getConfig('lat') === true) {
            #lat
            if (preg_match("/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", $this->value)) {
                return true;
            } else {
                $validator->validationError(
                  $this->name,
                  _t('LatLngField.LatVal', 'Please enter an correct Latitude Value'),
                  'validation'
              );
                return false;
            }
        } else {
            #lng
          if (preg_match("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/", $this->value)) {
              return true;
          } else {
              $validator->validationError(
              $this->name,
              _t('LatLngField.LngVal', 'Please enter an correct Longitude Value'),
              'validation'
          );
              return false;
          }
        }
        /*$this->value = trim($this->value);

        $pattern = '^[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$';

        // Escape delimiter characters.
        $safePattern = str_replace('/', '\\/', $pattern);

        if ($this->value && !preg_match('/' . $safePattern . '/i', $this->value)) {
            $validator->validationError(
                $this->name,
                _t('EmailField.VALIDATION', 'Please enter an email address'),
                'validation'
            );

            return false;
        }

        return true;*/
    }

    /**
     * @param string $name
     * @param mixed $val
     */
    public function setConfig($name, $val)
    {
        $this->config[$name] = $val;
        return $this;
    }

    /**
     * @param String $name Optional, returns the whole configuration array if empty
     * @return mixed|array
     */
    public function getConfig($name = null)
    {
        if ($name) {
            return isset($this->config[$name]) ? $this->config[$name] : null;
        } else {
            return $this->config;
        }
    }
}
