<?php

class SeoHeroToolLatLngFieldTest extends FunctionalTest
{
    public static $use_draft_site = true;
    /**
     * Check the php validator for email addresses. We should be checking against RFC 5322 which defines email address
     * syntax.
     *
     * @TODO
     *   - double quotes around the local part (before @) is not supported
     *   - special chars ! # $ % & ' * + - / = ? ^ _ ` { | } ~ are all valid in local part
     *   - special chars ()[]\;:,<> are valid in the local part if the local part is in double quotes
     *   - "." is valid in the local part as long as its not first or last char
     * @return void
     */
    public function testLatLngSyntax()
    {
        $this->internalCheck("8.8085507", "Valid", true);
        $this->internalCheck("1238.8085507", "Invalid", false);
    }

    public function internalCheck($value, $checkText, $expectSuccess)
    {
        $field = new LatLngField("LatLng");
        $field->setValue($value);

        $val = new SeoHeroToolLatLngFieldTest_Validator();
        try {
            $field->validate($val);
            // If we expect failure and processing gets here without an exception, the test failed
            $this->assertTrue($expectSuccess, $checkText . " (/$value/ passed validation, but not expected to)");
        } catch (Exception $e) {
            if ($e instanceof PHPUnit_Framework_AssertionFailedError) {
                throw $e;
            } // re-throw assertion failure
            elseif ($expectSuccess) {
                $this->assertTrue(false,
                    $checkText . ": " . $e->GetMessage() . " (/$value/ did not pass validation, but was expected to)");
            }
        }
    }
}

class SeoHeroToolLatLngFieldTest_Validator extends Validator
{
    public function validationError($fieldName, $message, $messageType='')
    {
        throw new Exception($message);
    }

    public function javascript()
    {
    }

    public function php($data)
    {
    }
}

class SeoHeroToolLatLngFieldTest_Controller extends Controller implements TestOnly
{
    private static $allowed_actions = array('Form');

    private static $url_handlers = array(
        '$Action//$ID/$OtherID' => "handleAction",
    );

    protected $template = 'BlankPage';

    public function Link($action = null)
    {
        return Controller::join_links(
            'SeoHeroToolLatLngFieldTest_Controller',
            $this->getRequest()->latestParam('Action'),
            $this->getRequest()->latestParam('ID'),
            $action
        );
    }

    public function Form()
    {
        $form = new Form(
            $this,
            'Form',
            new FieldList(
                new LatLngField('LatLng')
            ),
            new FieldList(
                new FormAction('doSubmit')
            ),
            new RequiredFields(
                'LatLng'
            )
        );

        // Disable CSRF protection for easier form submission handling
        $form->disableSecurityToken();

        return $form;
    }

    public function doSubmit($data, $form, $request)
    {
        $form->sessionMessage('Test save was successful', 'good');
        return $this->redirectBack();
    }

    public function getViewer($action = null)
    {
        return new SSViewer('BlankPage');
    }
}
