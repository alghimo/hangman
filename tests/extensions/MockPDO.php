<?php

namespace Framework\TestExtensions;

/**
 * We can not mock PDO via the usual "getMock" or "getMockBuilder" methods, because the constructor
 * cannot be serialized.
 *
 * @author Albert Giménez Morales
 * @package Framework\TestExtensions
 * @uses \PDO
 */
class MockPDO extends \PDO
{
    /**
     * Override the default constructor so we can mock this class.
     */
    public function __construct()
    {
    }
}