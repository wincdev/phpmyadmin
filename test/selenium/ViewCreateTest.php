<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Selenium TestCase for table related tests
 *
 * @package    PhpMyAdmin-test
 * @subpackage Selenium
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Selenium;

/**
 * TableCreateTest class
 *
 * @package    PhpMyAdmin-test
 * @subpackage Selenium
 * @group      selenium
 */
class ViewCreateTest extends TestBase
{
    /**
     * @return void
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->login();
    }

    /**
     * Creates a table
     *
     * @return void
     *
     * @group large
     */
    public function testCreateView()
    {

        $this->createDatabase();
        $this->createTable();

        $this->waitForElement('byPartialLinkText', 'SQL')->click();
        $this->waitAjax();
        $this->byId('clear')->click();
        $this->typeInTextArea("CREATE VIEW view_test AS SELECT id, test FROM test_table WHERE 1");
        $this->byId('button_submit_query')->click();
        $this->waitAjax();
        $this->waitForElement('byPartialLinkText', 'SQL')->click();
        $this->waitAjax();
        $this->byId('clear')->click();
        $this->typeInTextArea("SELECT * FROM view_test;");
        $this->byId('button_submit_query')->click();
        $this->waitAjax();
        $this->assertTrue((bool)$this->waitForElement('byClassName','success'));
    }

    private function createDatabase()
    {
        $this->dbQuery(
            'DROP DATABASE IF EXISTS ' . $this->database_name . ';'
        );

        $this->waitForElement('byPartialLinkText', 'Databases')->click();
        $this->waitAjax();

        $element = $this->waitForElement('byId', 'text_create_db');
        $element->clear();
        $element->value($this->database_name);

        $this->byId("buttonGo")->click();

    }

    private function createTable()
    {

        $this->waitAjax();
        $this->waitAjax();

        $this->waitForElement('byId', 'create_table_form_minimal');
        $this->byCssSelector(
            "form#create_table_form_minimal input[name=table]"
        )->value("test_table");
        $this->byName("num_fields")->clear();
        $this->byName("num_fields")->value("2");
        $this->byCssSelector('input[value=Go]')->click();

        $this->waitAjax();
        $this->waitForElement('byName', 'do_save_data');
        $this->byId('field_0_1')->value("id");
        $this->byId('field_1_1')->value("test");
        $this->byName('do_save_data')->click();
        $this->waitAjax();
        $this->waitAjax();

    }
}
