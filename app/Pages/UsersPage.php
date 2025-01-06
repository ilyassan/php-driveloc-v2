<?php
    class UsersPage extends BasePage
    {
        public function index()
        {
            $page = $_GET['page'] ?? 1;

            if (! is_numeric($page) || $page < 1) {
                redirect('users');
            }

            $usersTotalCount = User::count();
            $users = User::paginate($page);

            $this->render("/users/index", compact('usersTotalCount', 'users'));
        }
    }