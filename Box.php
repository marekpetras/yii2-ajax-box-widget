<?php

/**
 * @author Marek Petras <mark@markpetras.eu>
 * @link https://github.com/marekpetras/yii2-ajax-box-widget/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.0
 */

namespace marekpetras\yii2ajaxboxwidget;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\helpers\Html;

/**
 * Two types of usages, remote or local
 *
 * Remote:
 *
 * echo marekpetras\ajaxbox\Box::widget($options);
 *
 * Local:
 *
 * $box = marekpetras\ajaxbox\Box::begin($options);
 * // write body content here
 * echo 'Body';
 * // add some tools
 * $box->tools();
 * echo 'Tools';
 * // add some footer
 * $box->footer();
 * echo 'Footer';
 * $box->end();
 *
 * Possible Options:
 *
 * $options = [
 *     'title' => 'My Box',
 *     'subtitle' => 'About us',
 *     'type' => 'info',
 *     'invisible' => false,
 *     'bodyLoad' => ['test/my', 'var' => 'value'],
 *
 *     'toolsTemplate' => '{tools} {reload} {collapse} {remove} {myButton}',
 *     'toolsButtons' => [
 *         'myButton' => function() {
 *             return \yii\helpers\Html::button('my button');
 *         },
 *     ],
 *     'toolsButtonsOptions' => [
 *         'class' => 'myButtons',
 *     ],
 *     'autoload' => true,
 *     'hidden' => false,
 *     'data' => [
 *         'postvar1' => 123,
 *         'postvar2' => 234,
 *     ],
 *     'clientOptions' => [
 *        'autoload' => true, // modify this with the general option not here though
 *        'onerror' => new \yii\web\JsExpression('function(response, box, xhr) {console.log(response,box,xhr)}'), // loads the error message in the box by default
 *        'onload' => new \yii\web\JsExpression('function(box, status) { console.log(box,status); }'), // nothing by default
 *     ],
 *     'classes' => ['box', 'box-flat', 'box-init'],
 *     'view' => '@path/to/your/view',
 * ];
 *
 * Js Usage
 * $('#boxId').box('reload', data); // reloads content from predefined source with, if you choose so, ammended data - good for filtering etc
 * $('#boxId').box('source', newSource); // sets new box source
 * $('#boxId').box('show|hide|toggle'); // show hide toggle using jquery
 * $('#boxId').box('addOverlay|removeOverlay'); // add/removes overlay indicating load
 *
 */
class Box extends Widget
{
    /**
     * @var string widget title
     */
    public $title;

    /**
     * @var string widget subtitle, redenred next to title, only if title is present
     */
    public $subtitle;

    /**
     * @var string box type, results in css class box-type
     */
    public $type = 'info';

    /**
     * @var bool invisible, if true, only the body is rendered, use this if you want only the functional placeholder
     */
    public $invisible = false;

    /**
     * @var mixed source of the box if you want to load content remotely
     * @see Url::to()
     */
    public $bodyLoad = false;

    /**
     * @var string url source, leave empty to populate via bodyload, otherwise this saved in the javascript object for later use
     */
    public $source;

    /**
     * @var string template for tools buttons, any content you put in the tools section is rendered via {tools} placeholder
     */
    public $toolsTemplate = '{tools} {reload} {collapse} {remove}';

    /**
     * @var array create any buttons here you want to use in the toolsTemplate, predefined are {reload}, {collapse}, {remove}
     */
    public $toolsButtons = [];

    /**
     * @var array htmlOptions appended to all the buttons
     */
    public $toolsButtonOptions = [];

    /**
     * @var bool autoload the content right away, or wait for use action to load via javascript
     */
    public $autoload = true;

    /**
     * @var bool hidden on load using bootstrap css class collapse
     */
    public $hidden = false;

    /**
     * @var array here you can specify any post data you would like to use for the widget if you have remote source
     * if you have data here (key value pairs), the remote content will be loaded using POST, GET is used otherwise
     */
    public $data = [];

    /**
     * @var array any options you would like to pass to the javscript object, data and autoload are added in runtime
     * predefined in javascript object are 'autoload', 'onerror', 'onload', use JsExpression for event callbacks
     *
     * $clientOptions = [
     *     'autoload' => true, // modify this with the general option not here though
     *     'onerror' => new JsExpression('function(response, box, xhr) {console.log(response,box,xhr)}'), // loads the error message in the box by default
     *     'onload' => new JsExpression('function(box, status) { console.log(box,status); }'), // nothing by default
     * ];
     */
    public $clientOptions = [];

    /**
     * @var array css classes added to the box, there are a few defaults, if you however specify the widget to be invisible, only box-init is added
     */
    public $classes = ['box', 'box-flat', 'box-init'];

    /**
     * @var string view path
     */
    public $view = '@vendor/marekpetras/yii2-ajax-box-widget/views/box.php';

    /**
     * @var bool if we have footer
     * @internal
     */
    protected $_hasFooter = false;

    /**
     * @var bool if we have tools
     * @internal
     */
    protected $_hasTools = false;

    /**
     * @var string footer content
     * @internal
     */
    protected $_footer;

    /**
     * @var string tools content, as rendered in {tools}
     * @internal
     */
    protected $_tools;

    /**
     * @var string body of the box
     * @internal
     */
    protected $_body;

    /**
     * initiation, if overriden dont forget to call it in the child with parent::init()
     * @return void
     */
    public function init()
    {
        parent::init();

        ob_start();

        $this->clientOptions['data'] = $this->data;

        if ( $this->type ) {
            $this->addCssClass('box-' . $this->type);
        }

        if ( !$this->isVisible() ) {
            $this->classes = ['box-init'];
        }

        if ( $this->hidden ) {
            $this->addCssClass('collapse');
        }

        if ( $this->bodyLoad ) {
            $this->clientOptions['autoload'] = $this->autoload;
            $this->source = Url::to($this->bodyLoad);
        }

        $this->initToolsButtons();
    }

    /**
     * Registers required assets and the executing code block with the view
     * @return void
     */
    protected function registerAssets()
    {
        BoxAsset::register($this->getView());

        $this->getView()->registerJs("
            $('#".$this->getId()."').box(".Json::encode($this->clientOptions).");
        ");
    }

    /**
     * init default tools buttons
     * @return void
     */
    protected function initToolsButtons()
    {
        if ( !isset($this->toolsButtons['reload']) && $this->hasSource() ) {
            $this->toolsButtons['reload'] = function () {
                $options = array_merge([
                    'class' => 'btn btn-box-tool',
                    'onclick' => new JsExpression('$("#'.$this->getId().'").box("reload");'),
                ], $this->toolsButtonOptions);
                return Html::button('<i class="fa fa-refresh"></i>', $options);
            };
        }

        if ( !isset($this->toolsButtons['collapse']) ) {
            $this->toolsButtons['collapse'] = function () {
                $options = array_merge([
                    'class' => 'btn btn-box-tool',
                    'data' => [
                        'widget' => 'collapse',
                    ],
                ], $this->toolsButtonOptions);
                return Html::button('<i class="fa fa-minus"></i>', $options);
            };
        }

        if ( !isset($this->toolsButtons['remove']) ) {
            $this->toolsButtons['remove'] = function () {
                $options = array_merge([
                    'class' => 'btn btn-box-tool',
                    'data' => [
                        'widget' => 'remove',
                    ],
                ], $this->toolsButtonOptions);
                return Html::button('<i class="fa fa-times"></i>', $options);
            };
        }

        if ( !isset($this->toolsButtons['tools']) ) {
            $this->toolsButtons['tools'] = function () {
                return $this->_tools;
            };
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ( $this->hasFooter() ) {
            $this->_footer = ob_get_clean();
        }
        elseif ( $this->hasTools() ) {
            $this->_tools = ob_get_clean();
        }
        else {
            $this->_body = ob_get_clean();
        }

        $this->registerAssets();

        return $this->getView()->renderFile($this->view, [
            'box'=>$this,
        ]);
    }

    /**
     * start recording tools content
     * @return void
     */
    public function tools()
    {
        if ( $this->hasFooter() ) {
            throw new BoxException('You have to call Box::tools() before Box::footer().');
        }

        $this->_hasTools = true;
        $this->_body = ob_get_clean();

        ob_start();
    }

    /**
     * start recording footer content
     * @return void
     */
    public function footer()
    {
        $this->_hasFooter = true;

        if ( $this->_hasTools ) {
            $this->_tools = ob_get_clean();
        }
        else {
            $this->_body = ob_get_clean();
        }

        ob_start();
    }

    /**
     * if we have tools
     * @return bool
     */
    public function hasTools()
    {
        return $this->_hasTools;
    }

    /**
     * if we have footer
     * @return bool
     */
    public function hasFooter()
    {
        return $this->_hasFooter;
    }

    /**
     * render the tools using toolsButtons and the toolsTemplate
     * @return string rendered tools
     */
    public function renderTools()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];

            if ( isset($this->toolsButtons[$name])) {
                return call_user_func($this->toolsButtons[$name]);
            } else {
                return '';
            }
        }, $this->toolsTemplate);
    }

    /**
     * render box body
     * @return string rendered body
     */
    public function renderBody()
    {
        return $this->_body;
    }

    /**
     * render box footer
     * @return string rendered footer
     */
    public function renderFooter()
    {
        return $this->_footer;
    }

    /**
     * if we have remote content
     * @return bool
     */
    public function hasSource()
    {
        return $this->source ? true : false;
    }

    /**
     * if the widget is visible box
     * @return bool
     */
    public function isVisible()
    {
        return !$this->invisible;
    }

    /**
     * get all the css classes for box container
     * @return string
     */
    public function getCssClasses()
    {
        return is_array($this->classes) ? implode(' ', $this->classes) : $this->classes;
    }

    /**
     * add new css class
     * @param string css class
     * @return void
     */
    protected function addCssClass($class)
    {
        if ( is_array($this->classes) ) {
            $this->classes[] = $class;
        }
        else {
            $this->classes .= ' ' . $class;
        }
    }
}


