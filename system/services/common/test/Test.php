<?php
namespace system\services\common\test;

use system\datalevels\DaoType;
use workerbase\classs\Config;
use workerbase\classs\datalevels\DaoFactory;
use workerbase\traits\Tools;

/**
 * 业务测试
 * @author fukaiyao
 */
class Test
{
    use Tools;

    /**
     * 测试数据接口
     * @var
     */
    private $_testDao;

    /**
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->_testDao = DaoFactory::getDao(DaoType::COMMON_TEST);
    }

    public function test($msg)
    {
        $data = $this->rsaEncrypt(Config::read("rsa_public_key"), $msg);
        $data = $this->rsaDecrypt(Config::read("rsa_private_key"), $data);
        return $data;
    }

    /**
     *  获取一条记录
     * @param mixed $id - 主键id
     * @param mixed $fields - 返回字段，多个字段逗号分隔, 为空返回全部 (支持以数组的形式传递字段)
     * @param boolean $isLock         - 是否对读取的数据强制加上for update
     * @return null | array            - 找到返回一条记录(详细字段请参考对应的表)，找不到返回null
     */
    public function getInfoById($id, $fields = null, $isLock = false)
    {
        return $this->_testDao->getInfoById($id, $fields, $isLock);
    }

    /**
     * 添加一条记录
     * @param array $info         - 详细字段参考对应表字段
     * @return boolean | int      - 添加成功返回自增ID(不存在自增id返回0，多条插入返回true)，失败返回false
     */
    public function add($info)
    {
        return $this->_testDao->add($info);
    }

    public function test2($test)
    {
        $num = 300;
        while ($num--) {
            var_dump('good');
            sleep(3600);
        }
    }

}