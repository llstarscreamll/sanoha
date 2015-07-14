<?php namespace Roles;

use \FunctionalTester;
use \Users\_common\UserCommons;
use \sanoha\Models\Role;
use \sanoha\Http\Requests\RoleFormRequest;

class CreateRoleCest
{
    /**
     * The user commons actions
     *
     * @var \Users\_common\UserCommons
     */
    private $userCommons;
    
    /**
     * The validation error messages
     * 
     * @var array
     */
    private $messages = [];

    /**
     * Instanciate the UserCommons to create user roles and then login with
     * the $userCommons->adminUser data.
     * Instanciate the PermissionsCommons and RoleFormRequest for get
     * validation messages
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function _before(FunctionalTester $I)
    {
        $RoleFormRequest = new RoleFormRequest;
        $this->messages = $RoleFormRequest->messages();
        
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Test role creation
     * 
     * @return void
     */
    public function createRole(FunctionalTester $I)
    {   
        $I->am('admin user loged in');
        $I->wantTo('create role and asign permissions to that role');

        $I->seeAuthentication();
        $I->amOnPage('/roles');
        
        $I->see('Roles', 'h1');
        $I->click('Crear Rol', 'a');
        $I->seeCurrentUrlEquals('/roles/create');
        $I->see('Crear Rol', 'h1');
        
        sleep(1); // delaying 1 second, for the updated_at creating the role
        
        $role = [
            'name'          =>  'technical.assistant',
            'display_name'  =>  'Auxiliar Técnica',
            'description'   =>  'Auxiliar del área técnica',
            'permissions'   =>  [
                true,
                false, 
                true,
                false,
                true
                ]
            ];
        
        $I->submitForm('form', $role, 'Crear');
        $I->seeCurrentUrlEquals('/roles');
        
        $I->see('Roles', 'h1');
        $I->see('El rol de usuaro ha sido creado correctamente.', '.alert-success div');
        $I->see('Permisos añadidos al rol correctamente.', '.alert-success div');
        $I->see($role['display_name'], 'tbody tr:first-child td:nth-child(2)');
        $I->see($role['description'], 'tbody tr:first-child td:nth-child(3)');
        
    }
    
    /**
     * Test wrong creation role messages
     * 
     * @return void
     */
    public function testRoleFormValidationMessages(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('check for validation error messages on creating role with wrong data');

        $I->seeAuthentication();
        $I->amOnPage('/roles');
        
        $I->click('Crear Rol', 'a');
        $I->seeCurrentUrlEquals('/roles/create');
        $I->see('Crear Rol', 'h1');
        
        $role = [
            'name'          =>  'te',
            'display_name'  =>  'Au',
            'description'   =>  'Au',
            'permissions'   =>  [
                true,
                false, 
                true,
                false,
                true
                ]
            ];
        
        $I->submitForm('form', $role, 'Crear');
        
        // validation should redirect back the user
        $I->seeCurrentUrlEquals('/roles/create');
        
        // check form validation errors
        $I->seeFormHasErrors();
        $I->seeFormErrorMessages([
            'name'              =>  str_replace(':min', '3', $this->messages['name.min']),
            'display_name'      =>  str_replace(':min', '3', $this->messages['display_name.min']),
            'description'       =>  str_replace(':min', '5', $this->messages['description.min']),
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see(str_replace(':min', '3', $this->messages['name.min']), '.text-danger');
        $I->see(str_replace(':min', '3', $this->messages['display_name.min']), '.text-danger');
        $I->see(str_replace(':min', '5', $this->messages['description.min']), '.text-danger');
        
        /***********************
         * submit again the form
         ***********************/ 

        // test other messages
        $role = [
            'name'          =>  'tdfgsdfgsdfgsfgsgfsdfsfgsfgsfknlc_nalfjdfgsdfgsgfsfgbfdcnhiuio_iouwsdgdfggfgsdfgsdfgchuiohioweuhuiweh_iue',
            'display_name'  =>  'sdgfsdfgsdfdfhfghfgjdjkjkdf sdf sdfnsdfsdfndfndf sdfsdfkdfndf df df kdfk dfs ksdfakjsdfajkasdfjkdfjkjkf',
            'description'   =>  'dfgsdfgsdfgsdgdfgsdfgsgfsbym opñpñdvfgs  fgsfqwassxbcv bcbvghfghfgjghrthdrgthfghfgh opñpñdvfgs  fgsfqwassxbcv bcbvghfghfgjghrthdrgthfghfgh opñpñdvfgs  fgsfqwassxbcv bcbvghfghfgjghrthdrgthfghfgh opñpñdvfgs  fgsfqwassxbcv bcbvghfghfgjghrthdrgthfghfgh opñpñdvfgs  fgsfqwassxbcv bcbvghfghfgjghrthdrgthfghfghdfghdfghdfghdfh',
            'permissions'   =>  [
                true,
                false, 
                true,
                false,
                true
                ]
            ];
        
        $I->submitForm('form', $role, 'Crear');
        
        // validation should redirect back the user
        $I->seeCurrentUrlEquals('/roles/create');
        
        // check form validation errors
        $I->seeFormHasErrors();
        $I->seeFormErrorMessages([
            'name'              =>  str_replace(':max', '50', $this->messages['name.max']),
            'display_name'      =>  str_replace(':max', '50', $this->messages['display_name.max']),
            'description'       =>  str_replace(':max', '150', $this->messages['description.max']),
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see(str_replace(':max', '50', $this->messages['name.max']), '.text-danger');
        $I->see(str_replace(':max', '50', $this->messages['display_name.max']), '.text-danger');
        $I->see(str_replace(':max', '150', $this->messages['description.max']), '.text-danger');
        
        /***********************
         * submit again the form
         ***********************/ 

        // test other messages
        $role = [
            'name'          =>  '"#234%#$',
            'display_name'  =>  '$//D345GF',
            'description'   =>  '2356gfggdfg',
            'permissions'   =>  [
                true,
                false, 
                true,
                false,
                true
                ]
            ];
        
        $I->submitForm('form', $role, 'Crear');
        
        // validation should redirect back the user
        $I->seeCurrentUrlEquals('/roles/create');
        
        // check form validation errors
        $I->seeFormHasErrors();
        $I->seeFormErrorMessages([
            'name'              =>  $this->messages['name.alpha_dots'],
            'display_name'      =>  $this->messages['display_name.alpha_spaces'],
            'description'       =>  $this->messages['description.alpha_spaces'],
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see($this->messages['name.alpha_dots'], '.text-danger');
        $I->see($this->messages['display_name.alpha_spaces'], '.text-danger');
        $I->see($this->messages['description.alpha_spaces'], '.text-danger');
        
    }
}