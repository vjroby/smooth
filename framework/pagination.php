<?php

namespace Framework
{
    use Framework\Pagination\Exception;

    class Pagination extends Base{

        /**
         * @readwrite
         */
        protected $_pageName;

        /**
         * @readwrite
         */
        protected $_totalRecords;

        /**
         * @readwrite
         */
        protected $_totalPages;

        /**
         * @readwrite
         */
        protected $_recordsPerPage;

        /**
         * @readwrite
         */
        protected $_maxPagesShown;

        /**
         * @readwrite
         */
        protected $_currentStartPage;

        /**
         * @readwrite
         */
        protected $_currentEndPage;

        /**
         * @readwrite
         */
        protected $_currentPage;

        /**
         * @readwrite
         */
        protected $_spanNextInactive;

        /**
         * @readwrite
         */
        protected $_spanPreviousInactive;

        /**
         * @readwrite
         */
        protected $_firstInactiveSpan;

        /**
         * @readwrite
         */
        protected $_lastInactiveSpan;


        /**
         * @readwrite
         */
        protected $_recordOffset;

        /**
         * @readwrite
         */
        protected $_pageNr;

        /**
         * used for search to continue pagination
         * @readwrite
         */
        protected $_params;
        //css class names
        /**
         * @readwrite
         */
        protected $_inactiveSpanName = "disabled";
        /**
         * @readwrite
         */
        protected $_pageDisplayDivName = "totalpagesdisplay";
        /**
         * @readwrite
         */
        protected $_divWrapperName = "pagination pagination-sm";

        /**
         * @readwrite
         */
        protected $_currentName = "active";

        //text for navigation
        /**
         * @readwrite
         */
        protected $_strFirst = "&laquo;";
        /**
         * @readwrite
         */
        protected $_strNext = "Next";
        /**
         * @readwrite
         */
        protected $_strPrevious = "Prev";

        /**
         * @readwrite
         */
        protected $_strPage = "Page";

        /**
         * @readwrite
         */
        protected $_strOf = "of";
        /**
         * @readwrite
         */
        protected $_strLast = "&raquo;";


        public function __construct($options = array()){


            parent::__construct($options);

            $this->checkRecordOffset();

            $this->setTotalPages();
            $this->calculateCurrentPage();
            $this->createInactiveSpans();
            $this->calculateCurrentStartPage();
            $this->calculateCurrentEndPage();
        }

        /**
         * give css class name to inactive span
         *
         * @param $name
         */
        public function setInactiveSpanName($name){
            $this->inactivespanname=$name;
            //call function to rename span
            $this->createInactiveSpans();
        }

        /**
         * Here is all the logic in creating the paginator
         *
         * @return string
         */
        public function createNavigator(){
            $strnavigator = '';

            // only if the pages are more then 1 we display in page navigation
            if ($this->totalPages > 1){
                //wrap in div tag
                $strnavigator .= "<ul class=\"$this->divWrapperName\">\n";

                //output movefirst button
                if($this->currentPage == 1){
                    $strnavigator.= $this->firstInactiveSpan;
                }else{
                    $strnavigator .= $this->createLink(1, $this->strFirst);
                }

                //output moveprevious button
                if($this->currentPage == 1){
                    $strnavigator.= $this->spanPreviousInactive;
                }else{
                    $strnavigator.= $this->createLink($this->currentPage -1, $this->strPrevious);
                }

                //loop through displayed pages from $currentstart
                for($x=$this->currentStartPage;$x<=$this->currentEndPage;$x++){
                    //make current page inactive
                    if($x==$this->currentPage){
                        $strnavigator.= "<li class=\"$this->currentName\"><a href='#'>";
                        $strnavigator.= $x;
                        $strnavigator.= "</a></li>\n";
                    }else{
                        $strnavigator.= $this->createLink($x, $x);
                    }
                }

                //next button
                if($this->currentPage == $this->totalPages){
                    $strnavigator.=$this->spanNextInactive;
                }else{
                    $strnavigator.=$this->createLink($this->currentPage + 1, $this->strNext);
                }

                //move last button
                if($this->currentPage==$this->totalPages){
                    $strnavigator.= $this->lastInactiveSpan;
                }else{
                    $strnavigator.=$this->createLink($this->totalPages, $this->strLast);
                }
                $strnavigator.= "</ul>\n";
                $strnavigator.=$this->getPageNumberDisplay();
            }

            return $strnavigator;
        }

        private function createLink($offset, $strdisplay ){
            $strtemp= '<li><a href="'.\Framework\Smooth::baseUrl(true).'/'.$this->pageName;
            $strtemp.= 'page:'.$offset.'/';
            if (!is_null($this->_params)){
                $strtemp.= $this->transformParams();
            }
            $strtemp.= '">'.$strdisplay.'</a></li>'."\n";
            return $strtemp;
        }

        private function transformParams(){
            if (count($this->_params) != 0){
                $str ='';
                foreach ($this->_params as $k => $p) {
                    if (!is_null($p))
                    $str .= $k.':'.$p.'/';
                }
                return $str;

            }
            return '';
        }

        private function getPageNumberDisplay(){
            $str= "<div class=\"$this->pageDisplayDivName\">\n ".$this->strPage;
            $str.= $this->currentPage;
            $str.= " ".$this->strOf." $this->totalPages";
            $str.= "</div>\n";
            return $str;
        }

        private function setTotalPages(){
            $this->totalPages = ceil($this->totalRecords/$this->recordsPerPage);
        }

        private function calculateCurrentPage(){
            $this->currentPage = $this->pageNr;
        }

        private function createInactiveSpans(){
            $this->spanNextInactive="<li class=\"".
                "$this->inactiveSpanName\"><a href=\"#\">$this->strNext</a></li>\n";
            $this->lastInactiveSpan="<li class=\"".
                "$this->inactiveSpanName\"><a href=\"#\">$this->strLast</a></li>\n";
            $this->spanPreviousInactive="<li class=\"".
                "$this->inactiveSpanName\"><a href=\"#\">$this->strPrevious</a></li>\n";
            $this->firstInactiveSpan="<li class=\"".
                "$this->inactiveSpanName\"><a href=\"#\">$this->strFirst</a></li>\n";
        }

        private function calculateCurrentStartPage(){
            $temp = floor($this->currentPage/$this->maxPagesShown);
            $temp = $temp == 0 ? 1 : $temp;
            $this->currentStartPage = $temp;
        }

        private function calculateCurrentEndPage(){
            $this->currentEndPage = $this->currentStartPage+$this->maxPagesShown;
            if($this->currentEndPage > $this->totalPages)
                $this->currentEndPage = $this->totalPages;
        }
        private function checkRecordOffset(){
            $bln = true;
//            if($this->recordOffset %$this->recordsPerPage != 0){
//                throw new Exception('Wrong offset: '.$this->recordOffset);
//            }
            return $bln;
        }
    }
}
 