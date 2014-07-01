<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\View as View;
    use Framework\Registry as Registry;
    //use Framework\Template as Template;
    use Framework\Controller\Exception as Exception;

    class Controller extends Base
    {
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
        protected $_defaultExtension = "php";

        /**
         * @readwrite
         */
        protected $_defaultContentType = "text/html";

        /**
         * @readwrite
         */
        protected $_isAjaxNavigation = false;

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
            $defaultContentType = $this->getDefaultContentType();
            $results = null;

            $doAction = $this->getWillRenderActionView() && $this->getActionView();
            $doLayout = $this->getWillRenderLayoutView() && $this->getLayoutView();

            try
            {
                $isAjaxRequest = Registry::get('httpRequest')->getIsAjaxRequest();

                if ($isAjaxRequest === true){
                    $this->renderAjaxNavigation();
                }

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
                throw new View\Exception\Renderer("Invalid layout/template syntax.".$e->getMessage());
            }
        }

        public function __destruct()
        {
            $this->render();
        }

        public function __construct($options = array())
        {
            parent::__construct($options);

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
    }
}
