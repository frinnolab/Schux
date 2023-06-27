<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cli extends CI_Controller {
    /*
    | -----------------------------------------------------
    | PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:			INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:			info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:		RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:			http://inilabs.net
    | -----------------------------------------------------
    */
    // For user php artisan cli crud controllerName tableName PrimaryID (optional - subfolder name in views )
    protected $DUMMY_CLASS_NAME;
    protected $DUMMY_MODEL_NAME;
    protected $DUMMY_CLASS_NAME_SORT;
    protected $DUMMY_CLASS_NAME_SORT_VIEW;
    protected $DUMMY_CLASS_NAME_SINGULAR;
    protected $primaryID;
    protected $databaseName;

    function __construct() {
        parent::__construct();
    }

    public function crud($controllerName, $databaseName = '', $primaryID = '', $prefixViewPath = '') {
        if(!is_null($prefixViewPath) && !is_dir($prefixViewPath)) {
            mkdir('mvc/views/'.$prefixViewPath);
        }
        $this->DUMMY_CLASS_NAME = ucfirst($controllerName);
        $this->DUMMY_MODEL_NAME = strtolower($controllerName).'_m';
        $this->DUMMY_CLASS_NAME_SORT = strtolower($controllerName);
        $this->DUMMY_CLASS_NAME_SORT_VIEW = $prefixViewPath.'/'.$this->DUMMY_CLASS_NAME_SORT ;
        $this->DUMMY_CLASS_NAME_SINGULAR = $this->DUMMY_CLASS_NAME_SORT;
        $this->primaryID = $primaryID;
        $this->databaseName = $databaseName;

        $this->controller();
        $this->lang();
        $this->model();
        $this->view();
        echo "Done\n";

    }

    public function model()
    {
        $model = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            file_get_contents('assets/stubs/model/Model.stub')
        );
        $model = str_replace(
            'DUMMY_CLASS_NAME',
            $this->DUMMY_CLASS_NAME,
            $model
        );
        $model = str_replace(
            'DUMMY_DATABASE_NAME',
            $this->databaseName,
            $model
        );
        $model = str_replace(
            'DUMMY_PRIMARY_ID',
            $this->primaryID,
            $model
        );
        file_put_contents('mvc/models/'.$this->DUMMY_CLASS_NAME.'_m.php', $model);
        echo "Created ".$this->DUMMY_CLASS_NAME."_m.php Model\n";
    }

    public function controller()
    {
        $controller = str_replace(
            'DUMMY_CLASS_NAME_SINGULAR',
            $this->DUMMY_CLASS_NAME_SINGULAR,
            file_get_contents('assets/stubs/controller/Controller.stub')
        );
        $controller = str_replace(
            'DUMMY_CLASS_NAME_SORT_VIEW',
            $this->DUMMY_CLASS_NAME_SORT_VIEW,
            $controller
        );
        $controller = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            $controller
        );
        $controller = str_replace(
            'DUMMY_CLASS_NAME',
            $this->DUMMY_CLASS_NAME,
            $controller
        );
        $controller = str_replace(
            'DUMMY_MODEL_NAME',
            $this->DUMMY_MODEL_NAME,
            $controller
        );
        $controller = str_replace(
            'DUMMY_PRIMARY_ID',
            $this->primaryID,
            $controller
        );

        file_put_contents('mvc/controllers/'.$this->DUMMY_CLASS_NAME.'.php', $controller);
        echo "Created ".$this->DUMMY_CLASS_NAME.".php Controller\n";
    }

    public function lang()
    {
        $lang = str_replace(
            'DUMMY_CLASS_NAME_TITLE_CASE',
            str_replace('_', ' ',$this->DUMMY_CLASS_NAME),
            file_get_contents('assets/stubs/lang/Lang.stub')
        );
        $lang = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            $lang
        );
        file_put_contents('mvc/language/english/'.$this->DUMMY_CLASS_NAME_SORT.'_lang.php', $lang);
        echo "Created ".$this->DUMMY_CLASS_NAME_SORT."_lang.php language file\n";
    }

    public function view()
    {
        $addView = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            file_get_contents('assets/stubs/view/add.stub')
        );

        $editView = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            file_get_contents('assets/stubs/view/edit.stub')
        );

        $viewView = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            file_get_contents('assets/stubs/view/view.stub')
        );

        $viewView = str_replace(
            'DUMMY_PRIMARY_ID',
            $this->primaryID,
            $viewView
        );

        $indexView = str_replace(
            'DUMMY_CLASS_NAME_SORT',
            $this->DUMMY_CLASS_NAME_SORT,
            file_get_contents('assets/stubs/view/index.stub')
        );

        $indexView = str_replace(
            'DUMMY_PRIMARY_ID',
            $this->primaryID,
            $indexView
        );

        if(!is_dir('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW)) {
            mkdir('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW);
        }
        file_put_contents('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW.'/add.php', $addView);
        echo "Created ".$this->DUMMY_CLASS_NAME_SORT_VIEW."/add.php view\n";
        file_put_contents('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW.'/edit.php', $editView);
        echo "Created ".$this->DUMMY_CLASS_NAME_SORT_VIEW."/edit.php view\n";
        file_put_contents('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW.'/view.php', $viewView);
        echo "Created ".$this->DUMMY_CLASS_NAME_SORT_VIEW."/view.php view\n";
        file_put_contents('mvc/views/'.$this->DUMMY_CLASS_NAME_SORT_VIEW.'/index.php', $indexView);
        echo "Created ".$this->DUMMY_CLASS_NAME_SORT_VIEW."/index.php view\n";
    }

    public function create($option, ...$name)
    {
        if($option == 'lang') {
            $this->DUMMY_CLASS_NAME = ucfirst($name[0]);
            $this->DUMMY_CLASS_NAME_SORT = strtolower($name[0]);
            $this->lang();
        } elseif ($option == 'model') {
            $this->DUMMY_CLASS_NAME = ucfirst($name[0]);
            $this->DUMMY_CLASS_NAME_SORT = strtolower($name[0]);
            $this->databaseName = $name[1];
            $this->primaryID = $name[2];
            $this->model();
        } elseif ($option == 'controller') {
            $prefixViewPath = isset($name[3]) ? $name[3].'/' : '';
            $this->DUMMY_CLASS_NAME = ucfirst($name[0]);
            $this->DUMMY_MODEL_NAME = strtolower($name[1]).'_m';
            $this->DUMMY_CLASS_NAME_SORT = strtolower($name[0]);
            $this->DUMMY_CLASS_NAME_SORT_VIEW = $prefixViewPath.$this->DUMMY_CLASS_NAME_SORT ;
            $this->DUMMY_CLASS_NAME_SINGULAR = $this->DUMMY_CLASS_NAME_SORT;
            $this->primaryID = $name[2];
            $this->controller();
        }
    }

}
