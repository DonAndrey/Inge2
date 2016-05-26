<?php

// src/Controller/UsersController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{   
    /** 
     * Permite comprobar si hay una sesión activa y verificar los permisos de usuario.
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        $this->set('user_username', $this->Auth->User('username'));
        $this->Auth->allow(['add', 'logout']);
    }
    
    public function initialize()
    {
        parent::initialize();

    }
    
    /** 
     * Carga todos los usuarios de la base de datos y los pagina en una tabla.
     */
    public function index()
	{        
        // Carga el modelo de 'Roles' para sacar el rol del usuario
        $this->loadModel('Roles');                             
        
        // Consulta Join de usuarios con roles
        $query = $this->Users->find('all');
        $query->innerJoinWith('Roles', function ($q){return $q->where(['Users.id' => 'Roles.id']);});
        
        // Pagina la consulta
        $this->set('users', $this->paginate($query));
	}

    /**
     * Para futuras vistas
     * @param  integer $id
     */
    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    /**
     * Se encarga del registro de usuarios, se pasa a la vista de REGISTRO.
     * Indica si se proceso la solicitud de registro o no.
     */
    public function add()
    {
        if(!$this->Auth->user() || ($this->Auth->user() && $this->Auth->User('role_id') == 3))
        {
            $user = $this->Users->newEntity();
            
            if ($this->request->is('post'))
            {
                $user = $this->Users->patchEntity($user, $this->request->data);
                
                try
                {
                    if ($this->Users->save($user))
                    {
                        $this->Flash->success('Su registro está siendo procesado, la confirmación será enviada a su correo', ['key' => 'addUserSuccess']);
                        return $this->redirect(['controller' => 'Pages','action' => 'home']);
                    }
                }
                catch(Exception $ex)
                {
                    $this->Flash->error(__('No se ha podido procesar su solicitud'), ['key' => 'addUserError']);
                }
            }
            $this->set('user', $user);            
        }
        else
        {
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }

    }    
    /**
    * Se encarga del inicio de sesión de un usuario y se pasa a la vista de LOGIN.
    * Verifica si el usuario esta debidamente registrado y que los datos ingresados sean correctos.
    */
    public function login()
    {
        if(!$this->Auth->user())
        {
            if ($this->request->is('post'))
            {
                $user = $this->Auth->identify();

                if($user)
                {

                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());

                }
                else
                {
                    //TODO: Mostar $this->Flash->error('Su registro aún no ha sido procesado, por favor espere', ['key' => 'loginPendiente']);

                    //Se puede hacer con un if que verifique los datos de $this->request->data['username'] contra la base

                    $this->Flash->error(__('Nombre de usuario o contraseña incorrectos, inténtelo otra vez'), ['key' => 'loginError']);
                    
                }
            }            
        }
        else
        {
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }

    }
    /**
    * Se encarga del cierre de sesión de un usuario.
    * Se pasa a la pagina principal. 
    */
    public function logout()
    {
        $logout = $this->Auth->logout();
        
        if($logout)
        {
            $this->Flash->success('Se ha cerrado su sesión exitosamente', ['key' => 'logoutSuccess']);
            return $this->redirect($logout);
        }
    }
    
    /*
     * Verifica si un usuario tiene autorización para poder loguearse o no 
     * @param $user
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'view') {
            return false;            
        }

        if ($this->request->action === 'index' && $user['role_id'] == 2) {
            return false;            
        }
        
        return parent::isAuthorized($user);
    }
    
    /**
    * Se rechaza la solicitud de registro de un usuario y se envía un correo, aparte se elimina el usuario de la BD.
    * Se pasa a la pagina principal. 
    */
    public function reject($id)
    {
        // Si el usuario tiene permisos
        if($this->Auth->user())
        {
            $this->request->allowMethod(['post', 'reject']);
            $resource = $this->Users->get($id);
            try
            {
                if ($this->Users->delete($user))
                {
                    $this->Flash->success('La solicitud ha sido rechazada correctamente, y el usuario eliminado del sistema.', ['key' => 'deleteResourceSuccess']);
                    return $this->redirect(['controller' => 'Users','action' => 'index']);
                } 
            }
            catch(Exception $ex)
            {
                $this->Flash->error('La solicitud no fue rechazada. Por favor inténtelo de nuevo', ['key' => 'deleteUserError']);
            }
        }
        else
        {  
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
    }
    
    /**
    * Se acepta la solicitud de registro de un usuario y se envía un correo.
    * Se pasa a la pagina principal. 
    */
    public function confirm($id)
    {
         if($this->Auth->user())
        {
            //Carga el usuario se desea editar
            $user = $this->Users->get($id);
             
            if($this->request->is(array('post', 'put')))
		    {
                // Se cambia el estado a 1 que e aceptado por el  administrador.
                $user->state = 1;
                
                //Guarda el usuario con la nueva informacion modificada
                if ($this->Users->save($resource))
                {
                    //Muestra el mensaje de que ha sido modificado correctamente y redirecciona a la pagina principal de editar
                    $this->Flash->success('Se ha aceptado la solicitud con éxito.', ['key' => 'addUserSuccess']);
                    return $this->redirect(['controller' => 'Users','action' => 'index']);
                }
                else
                {
                    //En caso de que no se haa podido actualizar la nformacion despliega un mensaje indicando que hubo error.
                    $this->Flash->error('No se ha podido aceptar la solicitud. Por favor inténtelo de nuevo', ['key' => 'addUserError']);
                }
    }
}

?>