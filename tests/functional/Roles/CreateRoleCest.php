<?php
namespace Roles;

use \FunctionalTester;
use \common\BaseTest;

class CreateRoleCest
{
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
        $this->base_test = new BaseTest;
        $this->base_test->roles();

        $I->amLoggedAs($this->base_test->admin_user);
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
            'name'              =>  str_replace(':min', '3', $this->base_test->roleFormMessages['name.min']),
            'display_name'      =>  str_replace(':min', '3', $this->base_test->roleFormMessages['display_name.min']),
            'description'       =>  str_replace(':min', '5', $this->base_test->roleFormMessages['description.min']),
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see(str_replace(':min', '3', $this->base_test->roleFormMessages['name.min']), '.text-danger');
        $I->see(str_replace(':min', '3', $this->base_test->roleFormMessages['display_name.min']), '.text-danger');
        $I->see(str_replace(':min', '5', $this->base_test->roleFormMessages['description.min']), '.text-danger');
        
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
            'name'              =>  str_replace(':max', '50', $this->base_test->roleFormMessages['name.max']),
            'display_name'      =>  str_replace(':max', '50', $this->base_test->roleFormMessages['display_name.max']),
            'description'       =>  str_replace(':max', '150', $this->base_test->roleFormMessages['description.max']),
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see(str_replace(':max', '50', $this->base_test->roleFormMessages['name.max']), '.text-danger');
        $I->see(str_replace(':max', '50', $this->base_test->roleFormMessages['display_name.max']), '.text-danger');
        $I->see(str_replace(':max', '150', $this->base_test->roleFormMessages['description.max']), '.text-danger');
        
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
            'name'              =>  $this->base_test->roleFormMessages['name.alpha_dots'],
            'display_name'      =>  $this->base_test->roleFormMessages['display_name.alpha_spaces'],
            'description'       =>  $this->base_test->roleFormMessages['description.alpha_spaces'],
        ]);
        
        // check that messages are always displayed on red (text-danger) text
        $I->see($this->base_test->roleFormMessages['name.alpha_dots'], '.text-danger');
        $I->see($this->base_test->roleFormMessages['display_name.alpha_spaces'], '.text-danger');
        $I->see($this->base_test->roleFormMessages['description.alpha_spaces'], '.text-danger');
    }
}
