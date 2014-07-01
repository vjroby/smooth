<?php

use Shared\Controller as Controller;
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;

class Users extends Controller{

    public function register(){

        $view = $this->getActionView();


        if (RequestMethods::post('register')){

            $user = new User(array(
                "first" => RequestMethods::post("first"),
                "last" => RequestMethods::post("last"),
                "email" => RequestMethods::post("email"),
                "password" => RequestMethods::post("password")
            ));

            if ($user->validate())
            {
                $user->save();
                $view->set("success", true);
            }

            $view->set("errors", $user->getErrors());
        }
    }

    /**
     *
     */
    public function login()
    {
        if (RequestMethods::post("login"))
        {
            $email = RequestMethods::post("email");
            $password = RequestMethods::post("password");

            $view = $this->getActionView();
            $error = false;

            if (empty($email))
            {
                $view->set("email_error", "Email not provided");
                $error = true;
            }

            if (empty($password))
            {
                $view->set("password_error", "Password not provided");
                $error = true;
            }

            if (!$error)
            {
                $user = User::first(array(
                    "email = ?" => $email,
                    "password = ?" => $password,
                    "live = ?" => true,
                    "deleted = ?" => false
                ));

                if (!empty($user))
                {
                    $session = Registry::get("session");
                    $session->set("user", $user);
                    $this->redirect('/users/profile');
                }
                else
                {
                    $view->set("password_error", "Email address and/or password are incorrect");
                }
            }
        }
    }

    /**
     * view profile of a user
     */
    public function profile()
    {
        $user = $this->user;

        if (empty($user))
        {
            $this->redirect('/users/login');
        }
        $view = $this->getActionView();
        $view->set("user", $user);
    }

    public function sync(){
        //$user = new User();
        //$this->connector->sync($user);
    }

    public function index(){

        $user = $this->user;
        if (empty($user)){
            $this->redirect('/users/register');
        }else{
            $this->redirect('/users/profile');
        }
    }

    public function logout(){
        $session = Registry::get("session");
        $session->erase("user");

        $this->redirect('/users');
    }

    public function search()
    {

        $view = $this->getActionView();

        $query = RequestMethods::post("query");
        $order = RequestMethods::post("order", "modified");
        $direction = RequestMethods::post("direction", "desc");
        $page = RequestMethods::post("page", 1);
        $limit = RequestMethods::post("limit", 10);

        $count = 0;
        $users = false;

        if (RequestMethods::post("search"))
        {
            $where = array(
                "first LIKE ?" => "%".$query."%",
                "live = ?" => true,
                "deleted = ?" => false
            );

            $fields = array(
                "id", "first", "last"
            );

            $count = User::count($where);
            $users = User::all($where, $fields, $order, $direction, $limit, $page);
        }

        $view
            ->set("query", $query)
            ->set("order", $order)
            ->set("direction", $direction)
            ->set("page", $page)
            ->set("limit", $limit)
            ->set("count", $count)
            ->set("users", $users);
    }

    /**
     *
     */
    public function settings()
    {
        $view = $this->getActionView();
        $user = $this->getUser();

        if (RequestMethods::post("update"))
        {
            $user_update = new User(array(
                "id" => $user['id'],
                "first" => RequestMethods::post("first", $user['first']),
                "last" => RequestMethods::post("last", $user['last']),
                "email" => RequestMethods::post("email", $user['email']),
                "password" => RequestMethods::post("password", $user['password']),
            ));

            if ($user_update->validate())
            {
                $user_update->save();

                $user = User::first(array(
                    "id = ?" => $user['id'],
                ));
                $session = Registry::get("session");
                $this->setUser($user);
                $session->set("user", $user);
                $view->set("user", $user);
                $view->set("success", true);

            }

            $view->set("errors", $user_update->getErrors());
        }
    }

    /**
     * @before _secure
     */
    public function friend($id)
    {
        $user = $this->getUser();

        $friend = new Friend(array(
            "user" => $user['id'],
            "friend" => $id
        ));

        $friend->save();

        $this->redirect('/search');
    }

    /**
     * @before _secure
     */
    public function unfriend($id)
    {
        $user = $this->getUser();

        $friend = Friend::first(array(
            "user" => $user['id'],
            "friend" => $id
        ));

        if ($friend)
        {
            $friend = new Friend(array(
                "id" => $friend['id']
            ));
            $friend->delete();
        }

        $this->redirect('/search');
    }

    /**
     * @protected
     */
    public function _secure()
    {
        $user = $this->getUser();
        if (!$user)
        {
            $this->redirect('/login');
        }
    }

}
 