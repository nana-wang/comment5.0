<?php
namespace backend\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 *
 * @since 0.1
 */
class AppAsset extends AssetBundle
{

    public $sourcePath = '@backend/static';

    public $css = [
        'css/AdminLTE.min.css',
        'css/site.css',
        'css/datepicker_datepicker3.css',
		'css/bootstrap-treeview.css'
    ];

    public $js = [
//         'plugins/datepicker/moment.min.js',
//         'plugins/datepicker/bootstrap-datepicker.js',
//         'plugins/datepicker/daterangepicker.js',
//         'plugins/datepicker/bootstrap-colorpicker.min.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'js/app.min.js',
        'js/notify.js',
 		'js/bootstrap-treeview.js'
];

    public $depends = [
        'common\assets\FontAwesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    
    public static function addScript($view, $jsfile) {
    	$view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);
    }

    /**
     *
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = '_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }
            
            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }
        
        parent::init();
    }
}
