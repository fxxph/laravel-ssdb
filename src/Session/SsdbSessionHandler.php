<?php

namespace Huangdijia\Ssdb\Session;

use Huangdijia\Ssdb\Simple;

class SsdbSessionHandler implements \SessionHandlerInterface
{
    private $ssdb;
    private $lifetime = 120;

    public function __construct($app)
    {
        $this->lifetime = $app['config']['session']['lifetime'] ?? 120;
        $this->ssdb     = new Simple(
            $app['config']['cache']['stores']['ssdb']['host'] ?? '127.0.0.1',
            $app['config']['cache']['stores']['ssdb']['port'] ?? 8888,
            $app['config']['cache']['stores']['ssdb']['timeout'] ?? 2000
        );
    }
    public function open($savePath, $sessionName)
    {
        return true;
    }
    public function close()
    {
        $this->ssdb->close();
    }
    public function read($sessionId)
    {
        return $this->ssdb->get($sessionId);
    }
    public function write($sessionId, $data)
    {
        return $this->ssdb->setx($sessionId, $data, $this->lifetime) ? true : false;
    }
    public function destroy($sessionId)
    {
        return $this->ssdb->del($sessionId);
    }
    public function gc($lifetime)
    {
        return true;
    }
}