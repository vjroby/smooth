<?php
    use Shared\Controller as Controller;

    class Home extends Controller{

        public function index(){

            $user = $this->getUser();
            $view = $this->getActionView();

            if ($user)
            {
                $friends = Friend::all(array(
                    "user" => $user->id,
                    "live" => true,
                    "deleted" => false
                ), array("friend"));

                $ids = array();

                foreach($friends as $friend)
                {
                    $ids[] = $friend->friend;
                }
                if (count($ids) == 0) $ids[]=0;

                $messages = Message::all(array(
                    "user" =>array( $ids,'IN'),
                    "live" => true,
                    "deleted" => false
                ), array("*"), "created", "asc");

                $view->set("messages", $messages);
            }
        }

        public function test(){
            // DO nothing for now
        }
    }

 