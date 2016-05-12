<?php

// src/Model/Table/UsersTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsToMany('Resources');
    }
    
    /*
     * Se encarga de validar los campos del formulario de registro
     * @param Validator $validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('username', 'Ingrese su correo institucional')
            ->add('username', [
                            'isUnique' => [
                                            'rule' => 'validateUnique',
                                            'provider' => 'table',
                                            'message' => 'Este correo ya está registrado.'
                                          ],
                            'validFormat' => [
                                            'rule' => array('custom', '/^[a-zA-Z0-9._\-]+@ucr.ac.cr$/'),
                                            'message' => 'Debe usar el correo institucional.'
                                            ]
            ])
            
            ->notEmpty('password', 'Ingrese su contraseña')
            ->add('password', [
                           
                            'lengthBetween' => ['rule' => ['lengthBetween', 8, 50],
                                        'message' => 'Debe contener mínimo 8 y máximo 50 caracteres.',
                                        ],
                            'validFormat' => [
                                            'rule' => array('custom', '/^[a-zA-Z0-9._]+$/'),
                                            'message' => 'Solo puede usar: números, letras, puntos y guiones bajos'
                                            ]
            ])
            
            ->notEmpty('repass', 'Ingrese su contraseña de nuevo.')
            ->add('repass', [
                    'compare' => [
                                'rule' => ['compareWith','password'],
                                'message' => 'Las contraseñas no coinciden.'
                                ]
            ])
            ->requirePresence('repass')
            
            ->notEmpty('first_name', 'Ingrese su nombre')
            ->add('first_name', [
                                'maxLength' =>  ['rule' => ['maxLength', 20],
                                                'message' => 'Solo se permiten 20 caracteres.'],
                                'validFormat' =>  ['rule' => array('custom', '/^[a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\' \-]+$/'),
                                                'message' => 'Debe contener solamente letras.']
            ])
            
            ->notEmpty('last_name', 'Ingrese su apellido')
            ->add('last_name', [
                                'maxLength' =>  ['rule' => ['maxLength', 20],
                                                'message' => 'Solo se permiten 30 caracteres.'],
                                'validFormat' =>  ['rule' => array('custom', '/^[a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\' \-]+$/'),
                                                'message' => 'Debe contener solamente letras.']
            ])
            
            ->notEmpty('telephone_number', 'Ingrese su telefono')
            ->add('telephone_number', [
                                    'lengthBetween' => ['rule' => ['lengthBetween', 8, 16],
                                                        'message' => ('Digite un número de teléfono válido.')],
                                    'validFormat' =>   ['rule' => array('custom', '/^[0-9 \-\+]+$/'),
                                                        'message' => ('Debe contener solamente números.')]   
            ])
            
            ->notEmpty('department', 'Ingrese a la facultad o institución a la que pertenece')
            ->add('department', [
                                'validFormat'=> [
                                'rule' => array('custom', '/^[a-zA-ZÁÉÍÓÚÑáéíóúñ\' \-]+$/'),
                                'message' => ('Debe contener solamente letras.')
                                ],

                                'lengthBetween' => ['rule' => ['lengthBetween', 1, 100],
                                        'message' => 'Debe contener mínimo 1 y máximo 100 caracteres.',
                                        ]

            ])
            
            ->notEmpty('position', 'Seleccione una opción.');
    }    
    
    /*
     * Se encarga de verificar si una solicitud de registro fue confirmada o no
     * @param $userId
     * @return bool
     */
    public function registrationConfirmed($userId)
    {
        $user = $this->get($userId);
        
        // Si el usuario tiene estado 0 es porque todavía está pendiente su solicitud de registro
        if($user['state'] == 0)
            return false;
        
        return true;
    }
    
    public function equaltofield($check,$otherfield)
    {
        //get name of field
        $fname = '';
        foreach ($check as $key => $value){
            $fname = $key;
            break;
        }
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
    }


    public function findAuth(Query $query, array $options)
    {
        $query
            ->select(['id', 'username', 'password','role_id'])
            ->where(['Users.state' => 1]);

        return $query;
    }

}

?>