<?php

namespace Largezhou\WechatMenu;

trait Controller
{
    public function getMenus()
    {
        return WechatApp::getApp()->menu->list();
    }
}
