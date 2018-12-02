<?php

class UtilTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    function uri_params()
    {
        $params = \TapItTest\Util::uriParams('');
        $this->assertEquals(['uri', 'action', 'args'], array_keys($params));
        $this->assertEquals('index', $params['action']);

        $params = \TapItTest\Util::uriParams('/foo/bar/1');
        $this->assertEquals('foo', $params['action']);
    }

    /** @test */
    function auth()
    {
        // init app
        $app = new \TapItTest\App();

        // just login is allowed
        \TapItTest\App::$container['auth'] = false;
        $this->assertTrue(\TapItTest\Util::hasCredentials('login'));
        $this->assertFalse(\TapItTest\Util::hasCredentials('foo'));

        // other actions are allowed
        \TapItTest\App::$container['auth'] = true;
        $this->assertTrue(\TapItTest\Util::hasCredentials('foo'));
    }

    /** @test */
    function execute_action()
    {
        $app = new \TapItTest\App();
        $this->assertFalse(isset(\TapItTest\App::$container['view']));

        \TapItTest\Util::executeAction('foo');

        $this->assertTrue(isset(\TapItTest\App::$container['view']));
        $this->assertTrue(\TapItTest\App::$container['view'] instanceof \TapItTest\View);

    }

    /** @test */
    function execute_unknown_action()
    {
        $app = new \TapItTest\App();
        $this->expectExceptionMessage("unknown action `unknown_action`");

        \TapItTest\Util::executeAction('unknown_action');
    }

}
