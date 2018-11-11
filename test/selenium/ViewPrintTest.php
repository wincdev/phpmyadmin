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
class ViewPrintTest extends TestBase
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
    public function testRenameView()
    {
        $this->createView();
        $this->waitAjax();
        $this->byCssSelector("a[href='db_structure.php?server=1&db=" . $this->database_name."']")->click();
        $this->waitAjax();
        $this->byCssSelector("a[href='db_structure.php?server=1&db=" . $this->database_name . "&tbl_type=view']")->click();
        $this->waitAjax();
        $this->waitForElement('byCssSelector',"a[href='sql.php?db=" . $this->database_name . "&table=view_test&pos=0']")->click();
        $this->waitAjax();
        $this->waitForElement('byPartialLinkText', 'Structure')->click();
        $this->waitAjax();
        $this->byId("structure-action-links")->byCssSelector("a:second-child ")->click();
        $this->waitAjax();
        $this->assertTrue((bool)$this->waitForElement('byId','print_button_print_view'));
    }

    private function createView()
    {

        $this->createDatabase();
        $this->createTable();

        $this->waitForElement('byPartialLinkText', 'SQL')->click();
        $this->waitAjax();
        $this->byId('clear')->click();
        $this->typeInTextArea("CREATE VIEW view_test AS SELECT id, test FROM test_table WHERE 1");
        $this->byId('button_submit_query')->click();
        $this->waitAjax();
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
