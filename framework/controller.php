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
        protected $_defaultExtension = "php";

        /**
         * @readwrite
         */
        protected $_defaultContentType = "text/html";

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

//                    header("Content-type: {$defaultContentType}");
                    //echo $results;
                }
                else if ($doAction)
                {
//                    header("Content-type: {$defaultContentType}");
//                    echo $results;

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


                //$this->setLayoutView($view);
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
    }
}
