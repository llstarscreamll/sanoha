<?php

namespace Page\WorkOrders;

use \FunctionalTester;
use \common\BaseTest;

class WorkOrdersPage
{
    // include url of current page
    public static $URL = '/workOrder';
    public static $mainActor = 'jefe del área técnica';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    /**
     * index
     */
    public static $indexPageTitle = 'Ordenes de Trabajo';
    public static $indexPageTitleTag = 'h1';
    public static $indexPageTable = '.table-hover';
    public static $workOrderDetailsLink = 'a';
     
    /**
     * página de creación de orden
     */
    public static $createPageTitle = 'Crear Orden de Trabajo';
    public static $createPageTitleTag = 'h1';
    public static $createForm = 'form[name=create-work-order]';
    public static $createFormButtonCaption = 'Crear';
    public static $createFormButton = 'button[type=submit]';
    
    /**
     * Los empleados autorizados para manejar vehículos
     */
    public static $employeesAuthorizedToDriveVehicles = [
        'Trabajador 1'
        ];
    
    /**
     * Los empleados NO autorizados para manejar vehículos
     */
    public static $employeesUnauthorizedToDriveVehicles = [
        'Trabajador 2',
        'Trabajador 3',
        'Trabajador 4',
        ];
    
    public static $msgWorkOrderCreation = [
        'success'                       =>  'Orden de trabajo creada correctamente.',
        'error'                         =>  'Ocurrió un error creando la orden de trabajo.',
        'internal_accompanists_success' =>  'Acompañantes Internos asociados correctamente.',
        'exteral_accompanists_success'  =>  'Acompañantes Externos asociados correctamente.',
    ];
    
    public static $msgClass = [
        'success'       =>  '.alert-success',
        'error'         =>  '.alert-danger',
        'input_error'   =>  '.text-danger'
    ];
    
    /** 
     * página de edición de orden
     */
    public static $editFormAccessBtn = [
        'txt'  =>  'Editar Orden de Trabajo',
        'class' =>  'a.btn.btn-warning'
    ];
    public static $editFormName = 'form[name=edit-work-order]';
    public static $editPageTitleTag = 'h1';
    public static $editPageTitle = 'Actualizar Orden de Trabajo';
    public static $editFormButtonCaption = 'Actualizar';
    public static $editFormButton = 'button[type=submit]';
    public static $editFormSuccessMsg = [
        'txt'       =>  'La orden ha sido actualizada correctamente.',
        'class'     =>  '.alert.alert-success'
    ];
    
    /**
     * página de detalles de orden de trabajo
     */
    public static $showTitle = 'Detalles de Orden de Trabajo';
    public static $showTitleTag = 'h1';
    public static $workOrderDetailsLegend = [
        'tag'       =>  'ul li a',
        'attr'      =>  [],
        'text'      =>  'Detalles de Orden de Trabajo'
    ];
    public static $inputReading = [
        'tag'       =>  'input',
        'attr'      =>  ['disabled'],
        'selector'  =>  'input:disabled'
    ];
    public static $textareaReading = [
        'tag'       =>  'textarea',
        'attr'      =>  ['disabled'],
        'selector'  =>  'textarea:disabled'
    ];
    public static $workOrderReportBodyLocation = '#work-order-report .panel .panel-body';
    public static $workOrderReportFooterLocation = '#work-order-report .panel .panel-footer div.text-right';
    
    public static $workOrderReportEmpty = [
        'selector'  =>  '#work-order-report .alert.alert-warning',
        'txt'       =>  ' no ha reportado actividad...' // antes va el nombre del encargado de quien se le asignó la orden de trabajo
    ];

    public static $internalsAccompanistsLocation = '.panel-group .panel-default .panel-heading';
    public static $internalsAccompanistReportMsg = [
        'no_activity_reported_body'  => [
            'txt'       =>  'El trabajador no ha reportado actividad...',
            'selector'  =>  '.panel-body .alert.alert-warning'
            ],
        'no_activity_reported_footer'=> [
            'txt'       =>  '---',
            'selector'  =>  '.panel-footer.text-right'
            ]
    ];
    public static $externalsAccompanistsLocation = '#external-accompanists .panel .list-group';
    public static $mainReportLink = [
        'txt'       =>  'Crear Reporte',
        'selector'  =>  '#btn-create-main-report.btn-primary',
        ];
    
    /**
     * Los elementos de la página de creación de reporte principal
     */
    public static $mainReportTitle = [
        'txt'       =>  'Reportar Actividades de la Orden de Trabajo',
        'selector'  =>  'h1'
        ];
    public static $mainReportTextarea = [
        'selector'  =>  'textarea[name=work_order_report].form-control',
        'name'      =>  'work_order_report'
        ];
    public static $mainReportForm = 'form#report-work-order-activity';
    public static $mainReportFormButton = [
        'txt'       =>  'reportar',
        'selector'  =>  'button.btn-primary'
        ];
    public static $workReportFormData = ['work_order_report'   =>  'reporte de prueba'];
    
    /**
     * los elementos de formulario
     */
    public static $vehicleIdField = 'select[name=vehicle_id]';
    public static $destinationField = 'input[name=destination]';
    public static $authorizedByField = 'input[name=authorized_by]:disabled';
    public static $workDescriptionField = 'textarea[name=work_description]';
    public static $vehicleResponsableField = 'select[name=vehicle_responsable]';
    public static $internalAccompanistsField = 'select[name="internal_accompanists[]"]';
    public static $externalAccompanistsField = 'select[name="external_accompanists[]"]';
    
    /**
     * datos de prueba formulario de creación de orden de trabajo
     */
    public static $createFormData = [
        'authorized_by'             =>  'Orbin Travis', // campo informativo
        'vehicle_responsable'       =>  1,
        'vehicle_responsable_name'  =>  'B1 Trabajador 1',
        'vehicle_id'                =>  1,
        'vehicle_plate'             =>  'AAA111',
        'destination'               =>  'Beteitiva',
        'work_description'          =>  'Mantenimiento de motor malacate',
        'internal_accompanists'     =>  [1,2],
        'internal_accompanists_name'=>  [1 => 'B1 Trabajador 1', 2 => 'B2 Trabajador 2'],
        'external_accompanists'     =>  ['Mike Terrana', 'Thomas Lang']
    ];
    
    public static $editFormData = [
        'authorized_by'             =>  'Orbin Travis', // campo informativo
        'vehicle_responsable'       =>  2,
        'vehicle_responsable_name'  =>  'B2 Trabajador 2',
        'vehicle_id'                =>  2,
        'vehicle_plate'             =>  'BBB222',
        'destination'               =>  'Topaga',
        'work_description'          =>  'Mantenimiento de red electrica',
        'internal_accompanists'     =>  [3,4],
        'internal_accompanists_name'=>  [1 => 'B1 Trabajador 3', 2 => 'B1 Trabajador 4'],
        'external_accompanists'     =>  ['Alex Gonzales', 'Cindy Blackman']
    ];
    
    /**
     * Datos para validar form request
     */
    public static $formRequestValidation = [
        0   =>  [
            'data'  =>  [
                'vehicle_responsable'   =>  5659, // no existe
                'vehicle_id'            =>  2563, // no existe
                'destination'           =>  '"#SD1', // formato inválido
                'internal_accompanists' =>  [5699, 2652], // no existen
                'external_accompanists' =>  ['#$%', 'Alex Ubago'], // formato inválido en indice 0
                'work_description'      =>  'ASDf%&/(/&' // formato inválido
            ],
            'msg'   =>  [
                'vehicle_responsable'   =>  'No se encontró la información del responsable del vehículo.',
                'vehicle_id'            =>  'El vehículo no existe en la base de datos.',
                'destination'           =>  'El destino tiene un formato inválido, sólo se permiten letras y/o números.',
                'internal_accompanists' =>  'No se encontraró el empleado en la base de datos.',
                'external_accompanists' =>  'El acompañante externo tiene un formato inválido, sólo se permiten letras, espacios y/o números.',
                'work_description'      =>  'Sólo se permiten letras, números, espacios, puntos, guiones y/o arroba.'
            ]
        ],
        
        1   =>  [
            'data'  =>  [
                'vehicle_responsable'   =>  null, // requerido
                'vehicle_id'            =>  null, // requerido
                'destination'           =>  null, // requerido
                'internal_accompanists' =>  null,
                'external_accompanists' =>  null,
                'work_description'      =>  null // requerido
            ],
            'msg'   =>  [
                'vehicle_responsable'   =>  'Debes elegir al empleado responsable del vehículo.', // requerido
                'vehicle_id'            =>  'El vehículo es un campo obligatorio, elige uno.', // requerido
                'destination'           =>  'Digita donde se realizarán las actividades de la orden de trabajo.', // requerido
                'work_description'      =>  'Debes digitar una descripción para la orden de trabajo.' // requerido
            ]
        ]
    ];

    /**
     * Un por de ordenes de trabajo mas para crear cuando se requiera, por ejemplo búsqueda
     */
    public static $extraData = array();
    
    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var FunctionalTester;
     */
    protected $functionalTester;
    
    /**
     * Hago el setup inicial del módulo, creando datos de los que depende y creo
     * al usuario admin del sistema y lo logeo...
     */
    public function __construct(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->workOrders();

        $I->amLoggedAs($base_test->admin_user);
        
        $this->functionalTester = $I;
    }
    
    /**
     * @return WorkOrderPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
    
    /**
     * Crea una orden de trabajo
     * 
     * @return void
     */
    public static function createWorkOrder(FunctionalTester $I)
    {
        $I->amOnPage(WorkOrdersPage::route('/create'));
        $I->submitForm(self::$createForm, self::$createFormData, self::$createFormButtonCaption);
        
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['success'], WorkOrdersPage::$msgClass['success']);
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['internal_accompanists_success'], WorkOrdersPage::$msgClass['success']);
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['exteral_accompanists_success'], WorkOrdersPage::$msgClass['success']);
    }
    
    /**
     * Compruebo que la información sea la esperada en la vista de sólo lectura
     * de una orden de trabajo, los 
     * 
     * @param array $data
     */
    public static function checkDataOnShowRoute($data, FunctionalTester $I)
    {
        $I->see(self::$showTitle, self::$showTitleTag);
        
        // los elementos que tienen los detalles de la orden
        $I->see(self::$workOrderDetailsLegend['text'], self::$workOrderDetailsLegend['tag']);
        $I->seeElement(self::$inputReading['selector'], ['value' => $data['authorized_by']]);
        $I->seeElement(self::$inputReading['selector'], ['value' => $data['vehicle_plate']]);
        $I->seeElement(self::$inputReading['selector'], ['value' => $data['vehicle_responsable_name']]);
        $I->seeElement(self::$inputReading['selector'], ['value' => $data['destination']]);
        $I->see($data['work_description'], self::$textareaReading['selector']);
        
        // la sección de reporte de la orden de trabajo
        $I->see($data['vehicle_responsable_name'].self::$workOrderReportEmpty['txt'], self::$workOrderReportEmpty['selector']);
        
        // sección de acompañantes internos
        $I->see($data['internal_accompanists_name'][1], self::$internalsAccompanistsLocation);
        // zona del reporte del trabajador, el cuerpo del reporte
        $I->see(self::$internalsAccompanistReportMsg['no_activity_reported_body']['txt'], self::$internalsAccompanistReportMsg['no_activity_reported_body']['selector']);
        // el footer del reporte
        $I->see(self::$internalsAccompanistReportMsg['no_activity_reported_footer']['txt'], self::$internalsAccompanistReportMsg['no_activity_reported_footer']['selector']);
        // nadie ha reportado actividades de la orden de trabajo

        // sección donde se muestran los campoañantes externos
        $I->see($data['external_accompanists'][0], self::$externalsAccompanistsLocation);
        $I->see($data['external_accompanists'][1], self::$externalsAccompanistsLocation);
    }
    
    /**
     * Obtiene el footer del reporte principal de la orden de trabajo
     */
    public static function getWorkOrderReportFooter($user = null, $date = null)
    {
        $data = is_null($user) ? 'Reportado por '.\Auth::getUser()->fullname : 'Reportado por '.$user;
        
        $data .= is_null($date) ? ' el '.date('Y-m-d') : ' el '.$date;
        
        // Reportado por el {{$report->created_at}}

        return $data;
    }

    /**
     *  Crea algunas ordenes de trabajo extra para los test
     */
    public static function createExtraWorkOrdersData()
    {
        self::$extraData = array();
        
        self::$extraData[] = [
            'authorized_by'             =>  1,
            'vehicle_id'                =>  2,
            'vehicle_responsable'       =>  2,
            'destination'               =>  'Pinos',
            'work_description'          =>  'Mantenimiento de maquinaria pesada',
            'created_at'                =>  '2015-09-09 15:30:00',
            'updated_at'                =>  '2015-09-09 15:30:00',
            'deleted_at'                =>  null,
        ];

        self::$extraData[] = [
            'authorized_by'             =>  1,
            'vehicle_id'                =>  1,
            'vehicle_responsable'       =>  3,
            'destination'               =>  'La Escalera',
            'work_description'          =>  'Revisión de herramienta',
            'created_at'                =>  '2015-11-09 15:30:00',
            'updated_at'                =>  '2015-11-09 15:30:00',
            'deleted_at'                =>  null,
        ];

        \DB::table('work_orders')->insert(self::$extraData);
    }
    
    /**
     * Crea el reporte principal de la orden de trabajo
     */
    public static function createMainReport(FunctionalTester $I)
    {
        $I->amOnPage(WorkOrdersPage::route('/1'));
        $I->see(WorkOrdersPage::$showTitle, WorkOrdersPage::$showTitleTag);
        
        $I->click(WorkOrdersPage::$mainReportLink['txt'], WorkOrdersPage::$mainReportLink['selector']);
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1/mainReport'));
        
        $I->see(WorkOrdersPage::$mainReportTitle['txt'], WorkOrdersPage::$mainReportTitle['selector']);
        $I->seeElement(WorkOrdersPage::$mainReportTextarea['selector']);
        
        $I->submitForm(WorkOrdersPage::$mainReportForm, WorkOrdersPage::$workReportFormData, WorkOrdersPage::$mainReportFormButton['txt']);
        
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1'));
        
        $I->see(WorkOrdersPage::$workReportFormData['work_order_report'], WorkOrdersPage::$workOrderReportBodyLocation);
        $I->see(WorkOrdersPage::getWorkOrderReportFooter(), WorkOrdersPage::$workOrderReportFooterLocation);
    }
}
