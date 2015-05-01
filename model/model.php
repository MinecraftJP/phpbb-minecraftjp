<?php
namespace minecraftjp\phpbb\model;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class model {
    protected $cache;
    protected $container;
    protected $db;
    protected $extension_manager;
    protected $table;

    public function __construct(\phpbb\cache\driver\driver_interface $cache, ContainerInterface $container, \phpbb\db\driver\driver_interface $db, \phpbb\extension\manager $extension_manager, $table) {
        $this->cache = $cache;
        $this->container = $container;
        $this->db = $db;
        $this->extension_manager = $extension_manager;
        $this->table = $table;
    }
}