<?php

class UserController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new UserDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
        $this->_dialog->setCloseButton(false);
    }
    
    function define(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) 
        { 
            $this->action("Contact");
            
        }              
        try
        {          
            $dao = new RoleDAO();
            $roles = $dao->list();
                       
            $viewModel = new UserViewModel();
            $user = $this->_dao->get($id);          
            
            if (!$user)
            {
                $this->action("Contact");
            }
            
            $viewModel->setLogin($user->getLogin());
            $viewModel->setRoleId($user->getRole()->getId());
            $viewModel->setNewRecord($user->getRole()->getId() == 0 ? true : false);
            if ($_POST)
            {
                $validator = UserViewModelValidator::instance($viewModel);
                $viewModel->setLogin($_POST["txtlogin"]);
                $viewModel->setRoleId($_POST["cboroleid"]);
                $viewModel->setPassword($_POST["txtpassword"]);
                $viewModel->setConfirm($_POST["txtconfirm"]);
                
                if ($validator->validate())
                {
                    $user->setLogin($viewModel->getLogin());
                    $user->getRole()->setId($viewModel->getRoleId());
                    $user->setPassword($viewModel->getPassword());
                    $this->save($user);
                }
                else
                {
                    $this->_dialog->setMessage($validator->get());
                }
            }              
        } 
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }     
        $model = (object) array("user" => $user, "roles" => $roles, "viewmodel" => $viewModel,  "message" => $this->_dialog->render());
        return $this->view($model);  
        
    }
    
    function login(): object
    {    
        try
        {          
            $viewModel = new LoginViewModel();
            $validator = LoginViewModelValidator::instance($viewModel);
            
            if ($_POST)
            {
                
                //getting values from form
                $viewModel->setLogin(trim($_POST["txtlogin"]));
                $viewModel->setPassword(trim($_POST["txtpassword"]));  

                if ($validator->validate())
                {
                    $user = $this->_dao->login($viewModel->getLogin(), $viewModel->getPassword());

                    if ($user)
                    {
                        $dao = new AccessDAO();
                        $dao->list($user->getRole());
                        Index::setCurrentUser($user);
                        if ($user->getRole()->getActions())
                        {
                            $this->action("Home");
                        }
                        else
                        {
                            $this->_dialog->setMessage("O perfil '{$user->getRole()->getName()}' não possui regras configuradas!");
                        }
                    }
                    else
                    {
                        $this->_dialog->setMessage("Usuário ou senha inválidos!");
                    }
                }
                if ($validator->get())
                {
                    $this->_dialog->setMessage($validator->get());
                }
            }
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object)array("viewmodel" => $viewModel , "message" => $this->_dialog->render());
        return $this->view($model);
    }
    
    public function logout()
    {
        Index::logout();
        $this->action("Login", "login");
    }


    private function save(User $newUserData): void
    {
        $oldUserData = $this->_dao->get($newUserData->getId());
        
        if ($oldUserData->getRole()->getId() == 0)
        {
            $this->_dao->insert($newUserData);
        }
        else
        {
            if ($oldUserData->getLogin() != $newUserData->getLogin())
            {          
                $this->_dao->updateLogin($newUserData->getId(), $newUserData->getLogin());
                $this->_dialog->setDialogType(1);
                $this->_dialog->setMessage("Login do usuário alterado.");
            }
            
            if ($oldUserData->getRole()->getId() != $newUserData->getRole()->getId())
            {
                $this->_dao->updateRole($newUserData->getId(), $newUserData->getRole()->getId());
                $this->_dialog->setDialogType(1);
                $this->_dialog->setMessage("Perfil do usuário alterado.");
            }
            
            if ($newUserData->getPassword() != "")
            {
                $this->_dao->updatePassword($newUserData->getId(), $newUserData->getPassword());
                $this->_dialog->setDialogType(1);
                $this->_dialog->setMessage("Senha do usuário alterado.");
            }
        }
    }  
}
