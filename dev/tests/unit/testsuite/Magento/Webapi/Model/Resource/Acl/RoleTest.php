<?php
/**
 * Test class for \Magento\Webapi\Model\Resource\Acl\Role
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Webapi\Model\Resource\Acl;

class RoleTest extends \Magento\Webapi\Model\Resource\Acl\AbstractTest
{
    /**
     * Create resource model.
     *
     * @param \Magento\DB\Select $selectMock
     * @return \Magento\Webapi\Model\Resource\Acl\Role
     */
    protected function _createModel($selectMock = null)
    {
        $this->_resource = $this->getMockBuilder('Magento\App\Resource')
            ->disableOriginalConstructor()
            ->setMethods(array('getConnection', 'getTableName'))
            ->getMock();

        $this->_resource->expects($this->any())
            ->method('getTableName')
            ->withAnyParameters()
            ->will($this->returnArgument(0));

        $this->_adapter = $this->getMockBuilder('Magento\DB\Adapter\Pdo\Mysql')
            ->disableOriginalConstructor()
            ->setMethods(array('select', 'fetchCol', 'fetchPairs'))
            ->getMock();

        $this->_adapter->expects($this->any())
            ->method('fetchCol')
            ->withAnyParameters()
            ->will($this->returnValue(array(1)));

        $this->_adapter->expects($this->any())
            ->method('fetchPairs')
            ->withAnyParameters()
            ->will($this->returnValue(array('key' => 'value')));

        if (!$selectMock) {
            $selectMock = new \Magento\DB\Select(
                $this->getMock('Magento\DB\Adapter\Pdo\Mysql', array(), array(), '', false));
        }

        $this->_adapter->expects($this->any())
            ->method('select')
            ->withAnyParameters()
            ->will($this->returnValue($selectMock));

        $this->_resource->expects($this->any())
            ->method('getConnection')
            ->withAnyParameters()
            ->will($this->returnValue($this->_adapter));

        return $this->_helper->getObject('Magento\Webapi\Model\Resource\Acl\Role', array(
            'resource' => $this->_resource,
        ));
    }

    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        $model = $this->_createModel();

        $this->assertAttributeEquals('webapi_role', '_mainTable', $model);
        $this->assertAttributeEquals('role_id', '_idFieldName', $model);
    }

    /**
     * Test _initUniqueFields().
     */
    public function testGetUniqueFields()
    {
        $model = $this->_createModel();
        $fields = $model->getUniqueFields();

        $this->assertEquals(array(array('field' => 'role_name', 'title' => 'Role Name')), $fields);
    }

    /**
     * Test getRolesList().
     */
    public function testGetRolesList()
    {
        $selectMock = $this->getMockBuilder('Magento\DB\Select')
            ->setConstructorArgs(array($this->getMock('Magento\DB\Adapter\Pdo\Mysql', array(), array(), '', false)))
            ->setMethods(array('from', 'order'))
            ->getMock();

        $selectMock->expects($this->once())
            ->method('from')
            ->with('webapi_role', array('role_id', 'role_name'))
            ->will($this->returnSelf());

        $selectMock->expects($this->once())
            ->method('order')
            ->with('role_name')
            ->will($this->returnSelf());

        $model = $this->_createModel($selectMock);
        $result = $model->getRolesList();
        $this->assertEquals(array('key' => 'value'), $result);
    }

    /**
     * Test getRolesIds().
     */
    public function testGetRolesIds()
    {
        $selectMock = $this->getMockBuilder('Magento\DB\Select')
            ->setConstructorArgs(array($this->getMock('Magento\DB\Adapter\Pdo\Mysql', array(), array(), '', false)))
            ->setMethods(array('from', 'order'))
            ->getMock();

        $selectMock->expects($this->once())
            ->method('from')
            ->with('webapi_role', array('role_id'))
            ->will($this->returnSelf());

        $model = $this->_createModel($selectMock);

        $result = $model->getRolesIds();
        $this->assertEquals(array(1), $result);
    }
}
