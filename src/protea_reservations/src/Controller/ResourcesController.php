<?php

// src/Controller/UsersController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class ResourcesController extends AppController
{   
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
    
    /** 
     * Verifica algunos permisos de usuario y establece variables importantes de usuario.
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        // Establece el id y el username del usuario actualmente en sesión
        $this->set('user_id', $this->Auth->User('id'));
        $this->set('user_role', $this->Auth->User('role_id'));
        
        // Cualquier tipo de usuario puede acceder al método 'view' de recursos
        $this->Auth->allow(['view']);
    }
    
    /** 
     * Paginador de recursos.
     */
    public $paginate = array('limit' => 10,
                             'order' => array('Resource.resource_name' => 'asc')
                            );
    
    /** 
     * Carga todos los recursos de la base de datos y los pagina en una tabla.
     */
    public function index()
	{        
        // Carga el modelo de 'ResourceTypes' para sacar el nombre del tipo de recurso
        $this->loadModel('ResourceTypes');                             
        $this->set('resource_types', $this->ResourceTypes->find('all'));
        
        /*// Carga el modelo de 'ResourcesUsers' para mostrar sólo les recursos que puedo administrar
        $this->loadModel('ResourcesUsers');                             
        $this->set('relations', $this->ResourcesUsers->find('all'));*/
        
        // Consulta Join de recursos con usuarios, saca los recursos asociados al admin
        $query = $this->Resources->find('all');
        $query->innerJoinWith('Users', function ($q){return $q->where(['Users.id' => $this->Auth->User('id')]);});
        
        // Pagina la tabla de recursos
        $this->set('resources', $this->paginate($query));
	}

    /**
     * Muestra información más detallada sobre un recurso específico.
     * @param  integer $id
     */
    public function view($id)
    {
        // Saca el recurso específico
        $resource = $this->Resources->get($id);
        $this->set(compact('resource'));
        
        // Saca la descripción del tipo de ese recurso específico
        $connection = ConnectionManager::get('default');
        $result = $connection
                    ->execute('SELECT description FROM resource_types WHERE id = :id', ['id' => $resource->resource_type])
                    ->fetchAll('assoc');
        $this->set('r_type', $result);
        
        // Saca admins asociados
        $this->loadModel('Users');
        $query = $this->Users->find()
                                        ->select(['id', 'username', 'first_name', 'last_name'])
                                        ->where(['Users.role_id' => '1']);
        
        $query->innerJoinWith('Resources', function ($q) use ($id){
                                                return $q->where(['Resources.id' => $id]);
                                            });
        
        $this->set('admins_assoc', $query->toArray());
    }

    /**
     * Agrega un nuevo recurso a la base de datos.
     * Asocia al administrador actualmente en sesión como encargado default de ese nuevo recurso.
     */
    public function add()
    {
        // Carga todos los tipos de recursos para el DropDown
        $this->loadModel('ResourceTypes');
        $options = $this->ResourceTypes->find('list',['keyField' => 'id','valueField' => 'description'])->toArray();                              
        $this->set('resource_types_options', $options);
        
        // Si el usuario tiene permisos
        if($this->Auth->user())
        {
            // Nueva entidad 'Resource'
            $resource = $this->Resources->newEntity();
            
            if ($this->request->is('post'))
            {
                // Guarda en la entidad toda la información ingresada en el formulario
                $resource = $this->Resources->patchEntity($resource, $this->request->data);
                
                // Guarda en la entidad el id del tipo de recurso
                $tipoderecurso = $this->request->data['Resources']['resource_type'];
                $resource->resource_type = $tipoderecurso;
                
                try
                {
                    // Si pudo guardar en la tabla 'Resources'
                    if ($this->Resources->save($resource))
                    {
                        // Nueva entidad 'ResourceUser'
                        $this->loadModel('ResourcesUsers');
                        $resourcesUser = $this->ResourcesUsers->newEntity();
                        
                        // Guarda en la entidad los ids del recurso y del usuario actual
                        $resourcesUser->resource_id = $resource->id;
                        $resourcesUser->user_id = $this->Auth->User('id');
                        
                        // Si pudo guardar en la tabla 'ResourcesUsers'
                        if ($this->ResourcesUsers->save($resourcesUser))
                        {
                            $this->Flash->success('Se ha agregado el nuevo recurso', ['key' => 'addResourceSuccess']);
                            return $this->redirect(['controller' => 'Resources','action' => 'index']);
                        }
                    }
                }
                catch(Exception $ex)
                {
                    $this->Flash->error('No se ha podido agregar el recurso', ['key' => 'addResourceError']);
                }
            }
            $this->set('resource', $resource);            
        }
        else
        {
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
    }  
    
    /**
     * Actualiza la información de un recurso.
     * @param  integer $id
     */
    public function edit($id)
    {
    }
    
    /**
     * Elimina un recurso de la base de datos.
     * @param  integer $id
     */
    public function delete($id)
    {
        // Si el usuario tiene permisos
        if($this->Auth->user())
        {
            $this->request->allowMethod(['post', 'delete']);
            $resource = $this->Resources->get($id);
            try
            {
                if ($this->Resources->delete($resource))
                {
                    $this->Flash->success('El recurso ha sido eliminado éxitosamente', ['key' => 'deleteResourceSuccess']);
                    return $this->redirect(['controller' => 'Resources','action' => 'index']);
                } 
            }
            catch(Exception $ex)
            {
                $this->Flash->error('El recurso no pudo ser eliminado. Por favor inténtelo de nuevo', ['key' => 'deleteResourceError']);
            }
        }
        else
        {  
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
    }
    
    /**
     * Asocia a un administrador como encargado de un recurso.
     * @param  integer $id
     */
    public function associate($id)
    {
        // Admins asociados
        $this->loadModel('Users');
        $query = $this->Users->find()
                                        ->select(['id', 'username', 'first_name', 'last_name'])
                                        ->where(['Users.role_id' => '1']);
        
        $query->innerJoinWith('Resources', function ($q) use ($id){
                                                return $q->where(['Resources.id' => $id]);
                                            });
        
        $this->set('admins_assoc', $query);
        
        //-------------------------------------------------------------------------------
        
        // Admins no asociados
        $innerQuery = $this->Users->find()
                                ->select(['id'])
                                ->where(['Users.role_id' => '1']);
        
        $innerQuery->innerJoinWith('Resources', function ($q) use ($id){
                                                    return $q->where(['Resources.id' => $id]);
                                                });
        
        $query2 = $this->Users->find('list',['keyField' => 'id','valueField' => 'username'])
                                ->where(['Users.role_id' => '1'])
                                ->where(function ($q) use ($innerQuery){
                                        return $q->notIn('id', $innerQuery);
                                        });
        
        $this->set('no_admins_options', $query2);
        
        //-------------------------------------------------------------------------------
        
        $this->set('r_id', $id);
        
        //-------------------------------------------------------------------------------
        
        // Si el usuario tiene permisos
        if($this->Auth->user())
        {
            // Nueva entidad 'ResourceUser'
            $this->loadModel('ResourcesUsers');
            $resourcesUser = $this->ResourcesUsers->newEntity();
            
            if ($this->request->is('post'))
            {
                // Guarda en la entidad toda la información ingresada en el formulario
                $resourcesUser = $this->ResourcesUsers->patchEntity($resourcesUser, $this->request->data);
                
                // Guarda en la entidad el id del admin y del recurso
                $id_Admin = $this->request->data['ResourcesUsers']['user_id'];
                $resourcesUser->user_id = $id_Admin;
                $resourcesUser->resource_id = $id;
                
                try
                {
                    // Si pudo guardar en la tabla 'Resources'
                    if ($this->ResourcesUsers->save($resourcesUser))
                    {
                        $this->Flash->success('Se ha asociado el administrador con el recurso', ['key' => 'associateResourceAdminSuccess']);
                        return $this->redirect(['controller' => 'Resources','action' => 'associate', $id]);
                    }
                }
                catch(Exception $ex)
                {
                    $this->Flash->error('No se ha podido asociar el administrador con el recurso', ['key' => 'associateResourceAdminError']);
                }
            }
            $this->set('resourcesUser', $resourcesUser);            
        }
        else
        {
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
    }
    
    
    /**
     * Desasocia a un administrador como encargado de un recurso.
     * @param  integer $id
     */
    public function disassociate($admin_id, $resource_id)
    {
        // Encontrar el id de la asociación
        $this->loadModel('ResourcesUsers');
        $id = $this->ResourcesUsers->find('list')->select(['id'])
                                          ->where(['ResourcesUsers.user_id' => $admin_id,
                                                   'ResourcesUsers.resource_id' => $resource_id]);
        
        // Si el usuario tiene permisos
        if($this->Auth->user())
        {
            $this->request->allowMethod(['post', 'delete']);
            $resourceUser = $this->ResourcesUsers->get($id->toArray());
            try
            {
                if ($this->ResourcesUsers->delete($resourceUser))
                {
                    $this->Flash->success('Se ha desasociado el administrador del recurso', ['key' => 'disassociateResourceAdminSuccess']);
                    return $this->redirect(['controller' => 'Resources','action' => 'associate', $resource_id]);
                } 
            }
            catch(Exception $ex)
            {
                $this->Flash->error('No se ha podido desasociar el administrador del recurso', ['key' => 'disassociateResourceAdminError']);
            }
        }
        else
        {  
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }

    }
    
    /*
     * Revisa cuáles funciones puede hacer un usuario con cierto rol
     * @param $user
     */
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }
}

?>