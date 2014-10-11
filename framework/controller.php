<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\View as View;
    use Framework\Registry as Registry;
    //use Framework\Template as Template;
    use Framework\Events as Events;
    use Framework\Controller\Exception as Exception;

    class Controller extends Base
    {

        /**
         * @read
         */
        protected $_name;

        /**
         * @readwrite
         */
        protected $_parameters;

        /**
         * @readwrite
         */
        protected $_layoutView;

        /**
         * @readwrite
         */
        protected $_actionView;

        /**
         * @readwrite
         */
        protected $_willRenderLayoutView = true;

        /**
         * @readwrite
         */
        protected $_willRenderActionView = true;

        /**
         * @readwrite
         */
        protected $_defaultPath = "application/views";

        /**
         * @readwrite
         */
        protected $_defaultLayout = "layouts/standard";

        /**
         * @readwrite
         */
        protected $_defaultLayoutAjax = "layouts/ajax";

        /**
         * @readwrite
         */
        protected $_defaultLayoutApi = "layouts/api";

        /**
         * @readwrite
         */
        protected $_defaultExtension = "php";

        /**
         * @readwrite
         */
        protected $_defaultContentType = "text/html";

        /**
         * @readwrite
         */
        protected $_isAjaxNavigation = false;

        /**
         * @readwrite
         */
        protected $_isApiNavigation = false;

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        protected function _getExceptionForArgument()
        {
            return new Exception\Argument("Invalid argument");
        }

        public function render()
        {
            Events::fire("framework.controller.render.before", array($this->name));

            $defaultContentType = $this->getDefaultContentType();
            $results = null;

            if ($this->isApiNavigation ){
                $this->renderApiNavigation();
            }

            $doAction = $this->getWillRenderActionView() && $this->getActionView();
            $doLayout = $this->getWillRenderLayoutView() && $this->getLayoutView();

            try
            {
                $isAjaxRequest = Registry::get('httpRequest')->getIsAjaxRequest();

                // if there is an api request from ajax like in forms we must not load ajax navigation
                // the action file and layout for api (json) must be loaded
                if ($isAjaxRequest === true && !$this->isApiNavigation){
                    $this->renderAjaxNavigation();
                }
//                if ($isAjaxRequest === true || !$this->isApiNavigation){
//                    Smooth::$_cleanRequest = true;
//                }

                $view = $this->getLayoutView();
                if ($doAction)
                {
                    $view->set("data", $results);
                    $results = $view->renderAction();
                }

                if ($doLayout)
                {

                    // TODO action view content in template
                    $view->set("data", $results);
                    $results = $view->render();
                }
                else if ($doAction)
                {

                    $this->setWillRenderLayoutView(false);
                    $this->setWillRenderActionView(false);
                }
            }

            catch (\Exception $e)
            {
                throw new View\Exception\Renderer("Invalid layout/template syntax. From View Class: ".$e->getMessage());
            }

            Events::fire("framework.controller.render.after", array($this->name));
        }

        public function __construct($options = array())
        {
            parent::__construct($options);

            Events::fire("framework.controller.construct.before", array($this->name));

            $options_view = array();

            if ($this->getWillRenderLayoutView())
            {
                $defaultPath = $this->getDefaultPath();
                $defaultLayout = $this->getDefaultLayout();
                $defaultExtension = $this->getDefaultExtension();
                $options_view["file"]= APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}";


            }

            if ($this->getWillRenderActionView())
            {
                $router = Registry::get("router");
                $controller = $router->getController();
                $action = $router->getAction();

                $options_view["actionFile"] = APP_PATH."/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}";

                // $this->setActionView($view);
            }
            $view = new View($options_view);
            $this->setLayoutView($view);
            $this->setActionView($view);

            Events::fire("framework.controller.construct.after", array($this->name));

        }

        protected function getName()
        {
            if (empty($this->_name))
            {
                $this->_name = get_class($this);
            }
            return $this->_name;
        }

        /**
         * Creates a redirect location string
         * If $url is an array it presumes that is a controller / action / param
         * so, it will be imploded
         *
         * @param $url
         * @param null $statusCode
         * @param bool $exit
         * @throws Controller\Exception
         */
        public function redirect($url, $statusCode = null, $exit = true){
            if ( is_null($url)){
                throw new Exception('URL not provided.');
            }
            $host =  Registry::get('httpRequest')->getBaseUrl(true);

            if (is_array($url)){
                $url = implode('/', $url);
            }
            $stringLocation = $host.$url;


            if(!is_null($statusCode)){
                $stringLocation .=','.$statusCode;
            }

            header('Location:'.$stringLocation);

            $this->willRenderLayoutView = false;
            $this->willRenderActionView = false;

            if ($exit === true){
                exit();
            }
        }

        /**
         * If there is an Ajax request the layout must pe changed
         * to get only the info for ajax,
         * it uses the layout ajax.php which doesn't have styles
         */
        public function renderAjaxNavigation(){

            $view = $this->getLayoutView();
            $defaultPath = $this->getDefaultPath();
            $defaultLayout = $this->getDefaultLayoutAjax();
            $defaultExtension = $this->getDefaultExtension();
            $view->setFile(APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}");
        }


        public function renderApiNavigation(){

            Registry::get('httpRequest')->isApiRequest = true;

            $view = $this->getLayoutView();
            $defaultPath = $this->getDefaultPath();
            $defaultLayout = $this->getDefaultLayoutApi();
            $defaultExtension = $this->getDefaultExtension();
            $this->setWillRenderActionView(false);
            $view->setFile(APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}");
            $view->setIsForApi(true);
        }

        /**
         * method for loading custom layouts file from controller
         *
         * @param $layoutFileName
         */
        public function setCustomLayoutView($layoutFileName){
            //$this->_layoutView = $layoutFileName;

            $view = $this->getLayoutView();
            $layout =  explode('/',$this->getDefaultLayout());
            $defaultPath =$this->getDefaultPath();
            $defaultExtension = $this->getDefaultExtension();
            $view->setFile(APP_PATH."/{$defaultPath}/{$layout[0]}/{$layoutFileName}.{$defaultExtension}");
        }

        public function __destruct()
        {
            Events::fire("framework.controller.destruct.before", array($this->name));
            $this->render();
            Events::fire("framework.controller.destruct.after", array($this->name));
        }

    }
}
