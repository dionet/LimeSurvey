<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* LimeSurvey
* Copyright (C) 2007-2011 The LimeSurvey Project Team / Carsten Schmitz
* All rights reserved.
* License: GNU/GPL License v2 or later, see LICENSE.php
* LimeSurvey is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
* Implements global  config
*/
class LSYii_Application extends CWebApplication
{
    protected $config = array();
    public $lang = null;

    /**
    * Initiates the application
    *
    * @access public
    * @param array $config
    * @return void
    */
    public function __construct($config = null)
    {
        if (!file_exists($config))
        {
            $config = APPPATH . 'config/config-sample' . EXT;
        }
        parent::__construct($config);
        // Load the default and environmental settings from different files into self.
        $ls_config = require(APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config-defaults.php');
        $email_config = require(APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'email.php');
        $version_config = require(APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'version.php');
        $settings = array_merge($ls_config, $version_config, $email_config);
        
        if(file_exists(APPPATH . DIRECTORY_SEPARATOR. 'config' . DIRECTORY_SEPARATOR . 'config.php'))
        {
            $ls_config = require(APPPATH . DIRECTORY_SEPARATOR. 'config' . DIRECTORY_SEPARATOR . 'config.php');
            if(is_array($ls_config['config']))
            {
                $settings = array_merge($settings, $ls_config['config']);
            }
        }

        foreach ($settings as $key => $value)
            $this->setConfig($key, $value);
    }

    /**
    * Loads a helper
    *
    * @access public
    * @param string $helper
    * @return void
    */
    public function loadHelper($helper)
    {
        Yii::import('application.helpers.' . $helper . '_helper', true);
    }

    /**
    * Loads a library
    *
    * @access public
    * @param string $helper
    * @return void
    */
    public function loadLibrary($library)
    {
        Yii::import('application.libraries.'.$library, true);
    }

    /**
    * Sets a configuration variable into the config
    *
    * @access public
    * @param string $name
    * @param mixed $value
    * @return void
    */
    public function setConfig($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
    * Loads a config from a file
    *
    * @access public
    * @param string $file
    * @return void
    */
    public function loadConfig($file)
    {
        $config = require_once(APPPATH . '/config/' . $file . '.php');
        if(is_array($config))
        {
            foreach ($config as $k => $v)
                $this->setConfig($k, $v);
        }
    }

    /**
    * Returns a config variable from the config
    *
    * @access public
    * @param string $name
    * @return mixed
    */
    public function getConfig($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : false;
    }


    /**
    * For future use, cache the language app wise as well.
    *
    * @access public
    * @param Limesurvey_lang
    * @return void
    */
    public function setLang(Limesurvey_lang $lang)
    {
        $this->lang = $lang;
    }

}
